<?php

namespace ContacusBundle\Controller;

use ContacusBundle\Entity\Contact;
use ContacusBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $spy = new Contact();
        $form = $this->createForm(ContactType::class, $spy);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $spy->setDate(new \DateTime('now'));

            $em->persist($spy);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        return $this->render("@Contacus/Default/index.html.twig", array('form' => $form->createView()));
    }
}