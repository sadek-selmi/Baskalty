<?php

namespace ForumBundle\Controller;

use ForumBundle\Form\BlogType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ForumBundle:Default:index.html.twig');
    }
    public function afficheAction(Request  $request){

        $todo = $this->getDoctrine()
            ->getRepository('ForumBundle:blog')
            ->findAll();
        return $this->render('@Forum/Default/affiche.html.twig', array(
            'cyrine'=>$todo
        ));

    }

    public function DetaisAction($id, Request  $request){

        $todo = $this->getDoctrine()
            ->getRepository('ForumBundle:blog')
            ->find($id);
        return $this->render('@Forum/Default/detais.html.twig', array(
            'cyrine'=>$todo
        ));

    }

    public function editAction($id, Request $request){

        $todo = $this->getDoctrine()
            ->getRepository('ForumBundle:blog')
            ->find($id);

        $todo->setTitle($todo->getTitle());
        $todo->setContent($todo->getContent());

        $form = $this->createForm(BlogType::class, $todo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $sn = $this->getDoctrine()->getManager();
            $todo = $sn->getRepository('ForumBundle:blog')->find($id);
            $sn->persist($todo);
            $sn->flush();

            return $this->redirectToRoute('forum_homepage');
        }

        return $this->render('@Forum/Default/edit.html.twig', array(

            'todo'=>$todo,
            'form'=>$form->createView()
        ));

    }

    public function deleteAction($id, Request $request){

        $sn = $this->getDoctrine()->getManager();
        $todo = $sn->getRepository('ForumBundle:blog')->find($id);
        $sn->remove($todo);
        $sn->flush();

        return $this->redirectToRoute('affiche');
    }


}
