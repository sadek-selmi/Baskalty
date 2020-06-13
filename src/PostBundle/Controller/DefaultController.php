<?php

namespace PostBundle\Controller;

use Doctrine\DBAL\Types\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use PostBundle\Entity\Commentaire;
use PostBundle\Entity\Nblike;
use PostBundle\Entity\Publication;
use PostBundle\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class DefaultController extends Controller
{
    public function addpostAction(Request $request)
    {

        $publication = new Publication();
        $form = $this->createFormBuilder($publication)
            ->add('contenu', CKEditorType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('image', FileType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->getForm();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $publication->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored

            $file->move(
                $this->getParameter('images_shop'),
                $fileName
            );


            $publication->setImage($fileName);

            $contenu = $form['contenu']->getData();
            $chain = $contenu;
            $mot = "bleu";
            $mot1 = "rouge";
            $mot2 = "vert";
            if (strpos($chain, $mot)) {
                $chain = str_replace($mot, "*****", $chain);
                $contenu = $chain;
            }
            if (strpos($chain, $mot1)) {
                $chain = str_replace($mot1, "*****", $chain);
                $contenu = $chain;
            }
            if (strpos($chain, $mot2)) {
                $chain = str_replace($mot2, "*****", $chain);
                $contenu = $chain;
            }
            $datecreation = new\DateTime('now');
            $publication->setContenu($contenu);
            $publication->setUser($user);
            $publication->setAccept(0);
            $publication->setDatecreation($datecreation);

            $sn = $this->getDoctrine()->getManager();
            $sn->persist($publication);
            $sn->flush();



            return $this->redirectToRoute('afficherpost', array('id' => $publication->getId()));
        }
        return $this->render('PostBundle:Default:addpost.html.twig', array(
            'form' => $form->createView()

        ));

    }

    public function nbrComment(int $id)
    {
        $em = $this->getDoctrine()->getManager();

        $todo = $this->getDoctrine()
            ->getRepository('PostBundle:Publication')
            ->find($id);
        $comments = $em->getRepository('PostBundle:Commentaire')->findByPublication($todo);
        $numberofcomments = count($comments);

        return $numberofcomments;
    }

    public function afficheAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $this->getDoctrine()
            ->getRepository('PostBundle:Publication')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todo, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );
        $comments = $em->getRepository('PostBundle:Commentaire')->findByPublication($todo);
        $numlike = $em->getRepository('PostBundle:Nblike')->findByPublication($todo);
        $numlikes = count($numlike);

        $numberofcomments = count($comments);

        return $this->render('@Post/Default/affiche.html.twig', array(
            'cyrine' => $pagination,
            'cc' => $todo,
            'comments' => $comments,
            'numberofcomments' => $numberofcomments,
            'numlikes' => $numlikes
        ));

    }
    public function affichermobileAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $todo = $this->getDoctrine()
            ->getRepository('PostBundle:Publication')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todo, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );
        $comments = $em->getRepository('PostBundle:Commentaire')->findByPublication($todo);
        $numlike = $em->getRepository('PostBundle:Nblike')->findByPublication($todo);
        $numlikes = count($numlike);

        $numberofcomments = count($comments);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($todo);
        return new JsonResponse($formatted);
    }
    public function detailsAction(Publication $publication, Request $request)
    {

//        $em = $this->getDoctrine()
//            ->getRepository('PostBundle:Publication')
//            ->find($id);
//        return $this->render('@Post/Default/detaiLs.html.twig', array(
//            'cc'=>$em
//        ));

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $post = $em->getRepository('PostBundle:Publication')->find($publication);
        $arr = array();
        if ($request->isMethod('post')) {
            $comment = new Commentaire();
            $comment->setUser($user);
            $comment->setPublication($publication);
            $comment->setContenu($request->get('commentcyrine'));
            $em->persist($post);
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('details', array('id' => $publication->getId()));
        }
        $comments = $em->getRepository('PostBundle:Commentaire')->findByPublication($publication);
        $numlike = $em->getRepository('PostBundle:Nblike')->findByPublication($publication);
        $numlikes = count($numlike);

        $numberofcomments = count($comments);

        return $this->render('PostBundle:Default:details.html.twig', array(

            'cc' => $publication,
            'comments' => $comments,
            'numberofcomments' => $numberofcomments,
            'numlikes' => $numlikes,
            'arr' => $arr,
        ));


    }

    public function editAction($id, Request $request)
    {
        $produit = $this->getDoctrine()
            ->getRepository('PostBundle:Publication')
            ->find($id);

        $produit->setContenu($produit->getContenu());


        $form = $this->createFormBuilder($produit)
            ->add('contenu', CKEditorType::class, array(
                'config' => array(
                    'required' => true,
                ),))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $Contenu = $form['contenu']->getData();
            $now = new\DateTime('now');

            $produit->setDateCreation($now);
            $produit->setContenu($Contenu);

            $sn = $this->getDoctrine()->getManager();
            $sn->persist($produit);
            $sn->flush();
            return $this->redirectToRoute('affiche');

        }
        return $this->render('PostBundle:Default:edit.html.twig', array(
            'form' => $form->createView()
        ));

    }


    public function deleteAction($id, Request $request)
    {

        $sn = $this->getDoctrine()->getManager();
        $todo = $sn->getRepository('PostBundle:Publication')->find($id);
        $sn->remove($todo);
        $sn->flush();

        return $this->redirectToRoute('affiche');
    }

    public function likeBlogAction($id)
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('PostBundle:Publication')->find($id);

        $like = $em->getRepository('PostBundle:Nblike')->findby(array('publication'=>$post,'user'=>$user));
        if(!$like){
            $love = new Nblike();
            $love->setUser($user);
            $post->setLikesnumber($post->getLikesnumber() + 1);
            $love->setPublication($post);
            $em->persist($love);
            $em->flush();
        }
        else{
            $em->remove($like[0]);
            $em->flush();
        }
        return $this->redirectToRoute('details', array('id' => $post->getId()));
    }
    public function addpostMAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $pub = new Publication();
        $form = $this->createForm('PostBundle\Form\PublicationType', $pub);
        $form->handleRequest($request);

        $pub->setContenu($request->get('contenu'));
        $pub->setImage($request->get('image'));
        $pub->setDatecreation(new \DateTime(null));
        $pub->setAccept(1);


        $em->persist($pub);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($pub);
        return new JsonResponse($formatted);



    }

}