<?php

namespace MobileApiBundle\Controller;
use FOS\UserBundle\Model\User;
use SellProductsBundle\Entity\product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class productController extends Controller
{
    public function allAction()

    {
        $product = $this->getDoctrine()->getManager()
            ->getRepository(product::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($product);
        return new JsonResponse($formatted);
    }

    public function findAction($id)
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository(product::class)->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }


    public function findProductsByUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $productsByUser = $em->getRepository('SellProductsBundle:product')->findByUser($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($productsByUser);
        return new JsonResponse($formatted);
    }



    public function addAction(Request $request)
    {

 //       $id_user = $request->get('iduser');

        $em = $this->getDoctrine()->getManager();



        $product = new product();
        $product->setName($request->get('name'));
        $product->setPrice($request->get('price'));
        $product->setImage($request->get('image'));
        $product->setDescription($request->get('description'));
        $product->setDate(new \DateTime('now'));
        $product->setUpdatedAt(new \DateTime(null));
        $product->setReference($request->get('reference'));
        $product->setQuantite($request->get('quantite'));
     //   $product->setUser($user);
        $r = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $r->findOneById($request->get('iduser'));
        $product->setUser($user);

        $em->persist($product);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($product);
        return new JsonResponse($formatted);
    }

      public function deleteAction (Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(product::class)->findOneBy(["id"=>$id]);

        $em->remove($product);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(new product());
        return new JsonResponse($formatted);
    }

    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $product = $em->getRepository(product::class)->findOneBy(["id"=>$id]);

        $product->setName($request->get('name'));
        $product->setPrice($request->get('price'));
       // $product->setImage($request->get('image'));
        $product->setDescription($request->get('description'));
        $product->setUpdatedAt(new \DateTime('now'));
        $product->setReference($request->get('reference'));
        $product->setQuantite($request->get('quantite'));

        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($product);
        return new JsonResponse($formatted);
    }

    public function showRecentAddedAction()
    {
        $product = new product();

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('SellProductsBundle:product')->showRecentProductsAdded();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($product);
        return new JsonResponse($formatted);
    }





}
