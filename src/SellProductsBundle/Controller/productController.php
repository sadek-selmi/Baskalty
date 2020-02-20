<?php

namespace SellProductsBundle\Controller;

use SellProductsBundle\Entity\product;
use SellProductsBundle\Form\productType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Product controller.
 *
 * @Route("product")
 */
class productController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function ReadAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('SellProductsBundle:product')->findAll();

        return $this->render('@SellProducts/product/readProducts.html.twig', array(
            'products' => $products
        ));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('SellProductsBundle\Form\productType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('All_product', array('id' => $product->getId()));
        }

        return $this->render('@SellProducts/product/addProduct.html.twig', array(
            'product' => $product,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('@SellProducts/product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction($id)
    {
        //the manager is the responsible for saving objects, deleting and updating object
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository(product::class)->find($id);
        //the remove() method notifies Doctrine that you'd like to remove the given object from the database
        $em->remove($product);
        //The flush() method execute the DELETE query.
        $em->flush();
        //redirect our function to the read page to show our table
        return $this->redirectToRoute('All_product');
    }

    public function updateAction(Request $request,$id)
    { $product= $this->getDoctrine()->getRepository(product::class) ->find($id);
        $form= $this->createForm(productType::class,$product);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $ef= $this->getDoctrine()->getManager(); $ef->persist($product);
        $ef->flush();
        return $this->redirectToRoute("All_product");
        }
        return $this->render("@SellProducts/product/update.html.twig", array("form"=>$form->createView()));
        }

    public function detailsAction($id)

        {
            $details = $this->getDoctrine()->getRepository(product::class) ->find($id);
            return $this->render('@SellProducts/product/product-details.html.twig',array('details'=>$details));

        }
        public function shoppingcardAction()
        {
            return $this->render('@SellProducts/product/shopping-card.html.twig');
        }

    public function searchAction(Request $request)
    {
        $product = new product();
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $product =  $em->getRepository('SellProductsBundle:product')->findEntitiesByString($requestString);
        if(!$product) {
            $result['product']['error'] = "Post Not found :( ";
        } else {
            $result['product'] = $this->getRealEntities($product);
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($product){
        foreach ($product as $product){
            $realEntities[$product->getId()] = [$product->getImage(),$product->getName()];

        }
        return $realEntities;
    }
}
