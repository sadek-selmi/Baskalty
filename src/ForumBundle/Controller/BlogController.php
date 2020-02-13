<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\blog;
use ForumBundle\Form\blogType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function newblogAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $spy = new blog();
        $form = $this->createForm(blogType::class, $spy);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($spy);
            $em->flush();

            return $this->redirectToRoute('affiche',array('id'=>$spy->getId()));
        }
        return $this->render("@Forum/Default/newblog.html.twig", array('form' => $form->createView()));
    }






}
