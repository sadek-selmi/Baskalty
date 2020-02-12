<?php

namespace EventsBundle\Controller;

use EventsBundle\Entity\Events;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    { $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventsBundle:Events')->findAll();

        return $this->render('@Events/Default/index.html.twig',array('events'=>$event));

    }
    public function AjouterAction(Request $request)
    {
        $events = new Events();
        $form = $this->createForm('EventsBundle\Form\EventsType', $events);
        $form->handleRequest($request);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $events->setAssocation($user);
            $events->setParticipernumber(0);
            $events->setInterstednumber(0);



            $em->persist($events);

            $em->flush();

            return $this->redirectToRoute('events_show', array('id' => $events->getId()));
        }

        return $this->render('@Events/Default/Publier.html.twig', array(
            'events' => $events,
            'form' => $form->createView(),
        ));
    }
}
