<?php

namespace SellProductsBundle\Controller;

use SellProductsBundle\Entity\Seller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function formulaireVAction(Request $request)
    {


        $FOR = new Seller();
        $form = $this->createForm('SellProductsBundle\Form\SellerType', $FOR);
        $form->handleRequest($request);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $FOR->setUser($user);
            $em->persist($FOR);

            $em->flush();

            return $this->redirectToRoute('All_productsR', array('id' => $FOR->getId()));
        }
        return $this->render('@SellProducts/Default/Formulaire.html.twig', array(
            'formulaireL' => $FOR,
            'form' => $form->createView(),
        ));

    }
}
