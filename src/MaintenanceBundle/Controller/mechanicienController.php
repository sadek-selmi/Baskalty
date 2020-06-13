<?php

namespace MaintenanceBundle\Controller;

use MaintenanceBundle\Entity\mechanicien;
use MaintenanceBundle\Form\mechanicienType;
use SellProductsBundle\Entity\product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/**
 * Mechanicien controller.
 *
 * @Route("mechanicien")
 */
class mechanicienController extends Controller
{

    public function afficheeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mechaniciens = $em->getRepository('MaintenanceBundle:mechanicien')->getMechanicienbyPrix();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $mechaniciens,
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );
        return $this->render('@Maintenance/Default/affiche.html.twig', array(
            'mechaniciens' => $pagination,

        ));
            }
        Public function allAction()
    {
        $tasks = $this ->getDoctrine()->getManager()
            ->getRepository('MaintenanceBundle:mechanicien')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }


    public function DetailsMechAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mechanicien = $em->getRepository('MaintenanceBundle:mechanicien')->find($id);

        return $this->render('@Maintenance/Default/DetailsMech.html.twig', array(
            'mechaniciens' => $mechanicien,
        ));

    }


    public function addAction(Request $request)
    {
        $mechanicien = new Mechanicien();
        $form = $this->createForm('MaintenanceBundle\Form\mechanicienType', $mechanicien);
        $form->handleRequest($request);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $mechanicien->setActif(0);
            $mechanicien->setUserid($user);
            $em->persist($mechanicien);
            $em->flush();


            $mailer = $this->get('mailer');
            $msg = (new \Swift_Message('demande de mecanicien  '))
                ->setFrom('noreply@twasalni.tn')
                ->setTo('myahyafdhila@gmail.com')
                ->setBody('Merci pour votre demande');

            $mailer->send($msg);

            return $this->redirectToRoute('affiche_mecanicien', array('id' => $mechanicien->getId()));
        }

        return $this->render('@Maintenance/Default/add.html.twig', array(
            'mechanicien' => $mechanicien,
            'form' => $form->createView(),
        ));

    }
        public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task = new  mechanicien();
        $task->setNom($request->get('nom'));
        $task->setPrenom($request->get('prenom'));
        $task->setMail($request->get('mail'));
        $task->setImage($request->get('image'));
        $task->setPrix($request->get('prix'));
        $task->setNumtel($request->get('num_tel'));
        $task->setDescription($request->get('description'));
        $task->setExperience($request->get('exprience'));
        $task->setRegion($request->get('region'));
        $task->setCity($request->get('city'));
        $task->setAdomicile($request->get('adomicile'));
        $task->setActif(0);
        $task->setUserid($em->getRepository("AppBundle:User")->find($request->get("iduser")));

        $em->persist($task);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);


    }
    public function findMecanicienbByUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $productsByUser = $em->getRepository('MaintenanceBundle:mechanicien')->findByUser($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($productsByUser);
        return new JsonResponse($formatted);
    }

    /**
     * Finds and displays a mechanicien entity.
     *
     * @Route("/{id}", name="mechanicien_show")
     * @Method("GET")
     */
    public function showAction(mechanicien $mechanicien)
    {
        $deleteForm = $this->createDeleteForm($mechanicien);

        return $this->render('mechanicien/show.html.twig', array(
            'mechanicien' => $mechanicien,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing mechanicien entity.
     *
     * @Route("/{id}/edit", name="mechanicien_edit")
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request,$id)
    { $mechanicien= $this->getDoctrine()->getRepository(mechanicien::class) ->find($id);
        $form= $this->createForm(mechanicienType::class,$mechanicien);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $ef= $this->getDoctrine()->getManager();
        $ef->persist($mechanicien); $ef->flush();
        return $this->redirectToRoute("affiche_mecanicien");
        }
        return $this->render("@Maintenance/Default/update.html.twig", array("form"=>$form->createView()));
        }
    public function deleteAction($id)
    {
        //the manager is the responsible for saving objects, deleting and updating object
        $em=$this->getDoctrine()->getManager();
        $mechanicien=$em->getRepository(mechanicien::class)->find($id);
        //the remove() method notifies Doctrine that you'd like to remove the given object from the database
        $em->remove($mechanicien);
        //The flush() method execute the DELETE query.
        $em->flush();
        //redirect our function to the read page to show our table
        return $this->redirectToRoute('affiche_mecanicien');
    }

    /**
     * Creates a form to delete a mechanicien entity.
     *
     * @param mechanicien $mechanicien The mechanicien entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(mechanicien $mechanicien)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mechanicien_delete', array('id' => $mechanicien->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    public function DetailsMobAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mechanicien = $em->getRepository('MaintenanceBundle:mechanicien')->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mechanicien);
        return new JsonResponse($formatted);


    }

    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $mechanicien= $em->getRepository(mechanicien::class)->findOneBy(["id"=>$id]);

        $mechanicien->setNom($request->get('nom'));
        $mechanicien->setPrenom($request->get('prenom'));
        $mechanicien->setMail($request->get('mail'));
        $mechanicien->setPrix($request->get('prix'));
        $mechanicien->setDescription($request->get('description'));
        $mechanicien->setExperience($request->get('exprience'));



        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($mechanicien);
        return new JsonResponse($formatted);
    }


    public function deleteMecAction (Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $mechanicien = $em->getRepository(mechanicien::class)->find($id);

        $em->remove($mechanicien);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(new mechanicien());
        return new JsonResponse($formatted);
    }
}
