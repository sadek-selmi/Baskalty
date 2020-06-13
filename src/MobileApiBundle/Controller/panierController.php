<?php

namespace MobileApiBundle\Controller;




use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use SellProductsBundle\Entity\Order;
use SellProductsBundle\Entity\panier;
use SellProductsBundle\Entity\product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class panierController extends Controller
{

    public function AddPanierAction($id_prod,Request $request)
    {
        //$user = $this->container->get('security.token_storage')->getToken()->getUser();
        $quantity = $request->get('quantity');
        //$id_pro = $request->get('id_prod');
       // $product = new product();
          // $panierlist = $this->getDoctrine()->getRepository(panier::class);
        $panierlist = new panier();
            $em = $this->getDoctrine()->getManager();
            $product = $this->getDoctrine()
                ->getRepository(product::class)->find($id_prod);


                if ($panierlist->getProduitP() == $product)
                {
                    $panierlist->setQuantity($panierlist->getQuantity() +$quantity );
                    $panierlist->setPrix($panierlist->getPrix() + ($product->getPrix() * $quantity));
                    $product->setQuantity($product->getQuantity() - $quantity);
                    $em->persist($product);
                    $em->persist($panierlist);
                    $em->flush();
                }
                else{
                    $panier = new panier();
                   // $panier->setUser($user);
                    $panier->setProduitP($product);
                    $panier->setDateP(new \DateTime('now'));
                    $panier->setQuantity($quantity);
                    $panier->setPrix($product->getPrice() * $quantity);
                    $product->setQuantite($product->getQuantite() - $quantity);
                    $em->persist($product);
                    $em->persist($panier);
                    $em->flush();
                }


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($product);
        return new JsonResponse($formatted);

    }


    public function deleteProductFromPanierAction(Request $request)
    {

        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(panier::class)->findOneBy(["id"=>$id]);

        $em->remove($product);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(new panier());
        return new JsonResponse($formatted);
    }




    public function getPanierAction()
    {
        $panier = $this->getDoctrine()->getRepository(panier::class)->getInfoPanier();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($panier);
        return new JsonResponse($formatted);

    }


    public function orderAction(Request $request, $total)
    {
        $id = $request->get('id');
        $productOrdred = $this->getDoctrine()->getRepository(panier::class)->findOneBy(["id"=>$id]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($productOrdred);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($productOrdred);
        return new JsonResponse($formatted);

    }


    public function OrderAllAction ($total)
    {
       $em = $this->getDoctrine()->getManager();
       $allproducts = $em->getRepository(panier::class)->truncateTtablePanier();

       $panier =$em->getRepository(panier::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($panier);
        return new JsonResponse($formatted);


    }


}
