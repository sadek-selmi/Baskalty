<?php

namespace MobileApiBundle\Controller;

use SellProductsBundle\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class orderController extends Controller
{
    public function AddOrderAction (Request $request)
    {
         $order = new Order();
         $em = $this->getDoctrine()->getManager();
         $order->setNom($request->get('name'));
         $order->setEmail($request->get('email'));
         $order->setPhonenumber(($request->get('phonenumber')));
         $order->setAdresse($request->get('adresse'));
         $order->setCity($request->get('city'));
         $order->setTotal($request->get('total'));
         //$order->setUser($this->getUser());
         $em->persist($order);
         $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($order);
        return new JsonResponse($formatted);

    }

    public function getOrderAction()
    {
        $order = $this->getDoctrine()->getRepository(Order::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($order);
        return new JsonResponse($formatted);
    }

}
