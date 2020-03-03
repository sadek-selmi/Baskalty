<?php

namespace SellProductsBundle\Controller;

use SellProductsBundle\Entity\Order;
use SellProductsBundle\Entity\panier;
use SellProductsBundle\Entity\product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class panierController extends Controller
{
    public function addToPanierAction($id, Request $request)
    {

        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $panierlist = $this->getDoctrine()->getRepository(panier::class);
            $em = $this->getDoctrine()->getManager();
            $product = $this->getDoctrine()
                ->getRepository(product::class)
                ->find($id);
            $c = 0;


            foreach ($panierlist as $p) {
                if ($p->getProduitP() == $product) {
                    $p - setQuantite($p->getQuantite() + ($request->get('quantity')));
                    $p->setPrix($p->getPrix() + ($product->getPrix() * ($request->get('quantity'))));
                    $product->setQuantity($product->getQuantity() - ($request->get('quantity')));
                    $em->persist($product);
                    $em->persist($p);
                    $em->flush();
                    $c = 1;
                    return $this->redirectToRoute('shop_addtopanier');
                }

            }
            if ($c != 0) {
                return $this->redirectToRoute('shop_addtopanier');
            } else {
                if ($request->isMethod('POST')) {
                    $panier = new panier();
                    $panier->setUser($user);
                    $panier->setProduitP($product);
                    $panier->setDateP($product->getDate());
                    $panier->setQuantite(($request->get('quantite')));
                    $panier->setPrix($product->getPrice() * ($request->get('quantite')));
                    $product->setQuantite($product->getQuantite() - ($request->get('quantite')));
                    $em->persist($product);
                    $em->persist($panier);
                    $em->flush();
                    return $this->redirectToRoute('checkout');
                }
            }
        }
        //   return $this->render('@SellProducts/panier/shopping_card.html.twig',array('panier'=>$panier));
        //  return $this->redirectToRoute('shop_addtopanier');

    }

    public function checkoutAction()
    {
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $panierlist = $this->getDoctrine()->getRepository(panier::class)->findByUser($user);
            $count = count($panierlist);
            $total = 0;
            foreach ($panierlist as $prix) {

                $p = $prix->getPrix();
                $total = $total + $p;
            }

            return $this->render('@SellProducts/panier/checkout.html.twig', array('nbrp' => $count, 'total' => $total, 'panier' => $panierlist));
        }
    }

    public function deletechAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $produit = $sn->getRepository(panier::class)->find($id);
        $sn->remove($produit);
        $sn->flush();
        return $this->redirectToRoute('checkout');

    }

    public function proceedCheckoutAction($total)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $panierlist = $this->getDoctrine()->getRepository(panier::class)->findByUser($user);
        $count = count($panierlist);
        return $this->render('@SellProducts\panier\order.html.twig', array(
            'total' => $total,
            'panier' => $panierlist,
            'items' => $count
        ));
    }


    public function orderAction(Request $request, $total)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $panierlist = $this->getDoctrine()->getRepository(panier::class)->findByUser($user);
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $order = new Order();
            $order->setUser($user);
            $order->setPrice($total);
            $order->setCity(($request->get('city')));
            $order->setAdresse($request->get('address'));
            $order->setPhonenumber(($request->get('phonenumber')));
            $order->setAlernativePhoneNumber(($request->get('alphn')));
            $order->setDeliveryInstructions(($request->get('instruction')));
            $em->persist($order);
            $em->flush();

            foreach ($panierlist as $p) {

                $em->remove($p);
                $em->flush();
            }
        }
        return $this->redirectToRoute('All_products');
    }
}


