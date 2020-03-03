<?php

namespace MaintenanceBundle\Controller;

use MaintenanceBundle\Entity\mechanicien;
use MaintenanceBundle\Form\mechanicienType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Mechanicien controller.
 *
 * @Route("mechanicien")
 */
class mechanicienController extends Controller
{
    /**
     * Lists all mechanicien entities.
     *
     * @Route("/", name="mechanicien_index")
     * @Method("GET")
     */
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
    public function DetailsMechAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mechanicien = $em->getRepository('MaintenanceBundle:mechanicien')->find($id);

        return $this->render('@Maintenance/Default/DetailsMech.html.twig', array(
            'mechaniciens' => $mechanicien,
        ));

    }

    /**
     * Creates a new mechanicien entity.
     *
     * @Route("/new", name="mechanicien_new")
     * @Method({"GET", "POST"})
     */
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

            $mailer= $this->get('mailer');
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

}
