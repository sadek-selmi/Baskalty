<?php

namespace MobileApiBundle\Controller;

use RentProductsBundle\Entity\Rent;
use RentProductsBundle\Entity\RentProd;
use RentProductsBundle\Entity\Reservation;
use RentProductsBundle\Entity\Reviews;
use RentProductsBundle\Form\RentProdType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RentController extends Controller
{


    public function formulaireLAction(Request $request)
    {


        $FOR = new Rent();
        $form = $this->createForm('RentProductsBundle\Form\RentType', $FOR);
        $form->handleRequest($request);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $FOR->setUser($user);
            $em->persist($FOR);
            $em->flush();

            return $this->redirectToRoute('All_productsR', array('id' => $FOR->getId()));
        }
        return $this->render('@RentProducts/Default/Formulaire.html.twig', array(
            'formulaireL' => $FOR,
            'form' => $form->createView(),
        ));

    }
    public function addAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $events = new RentProd();
        $form = $this->createForm('RentProductsBundle\Form\RentProdType', $events);
        $country = $em->getRepository('AppBundle:User')->find($request->get('iduser'));

        $form->handleRequest($request);
        $events->setUserid($country);
        $events->setDispo(false);

        $events->setImage($request->get('image'));
        $events->setDescription($request->get('description'));

        $events->setPrice($request->get('price'));
        $events->setQuantity($request->get('quantity'));
        $events->setLocalisation($request->get('localisation'));
        $events->setMarke($request->get('marque'));
        $events->setReference($request->get('reference'));
        $events->setRentdays($request->get('days'));
        $events->setModel($request->get('model'));

        $em->persist($events);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($events);
        return new JsonResponse($formatted);
    }
    public function ReadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('RentProductsBundle:RentProd')->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($products);
        return new JsonResponse($formatted);

    }
    public function deleteAction($id)
    {
        //the manager is the responsible for saving objects, deleting and updating object
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository(RentProd::class)->find($id);
        //the remove() method notifies Doctrine that you'd like to remove the given object from the database
        $em->remove($product);
        //The flush() method execute the DELETE query.
        $em->flush();
        //redirect our function to the read page to show our table
        return $this->redirectToRoute('All_productsR');
    }
    public function updateAction(Request $request,$id)
    { $product= $this->getDoctrine()->getRepository(RentProd::class) ->find($id);
        $form= $this->createForm(RentProdType::class,$product);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        { $ef= $this->getDoctrine()->getManager(); $ef->persist($product);
            $ef->flush();
            return $this->redirectToRoute("All_productsR");
        }
        return $this->render("@RentProducts/Default/editProduct.html.twig", array("form"=>$form->createView()));
    }



    public function detailsAction(Request $request,$id)

    {

        $details = $this->getDoctrine()->getRepository(RentProd::class) ->find($id);
        $reviews = $this->getDoctrine()->getRepository('RentProductsBundle:Reviews')->findByProduitP($details);
        $rate = $this->getDoctrine()->getRepository('RentProductsBundle:Reviews')->findAll();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {

            $exist = false;
            foreach ($rate as $r){


                if ($r->getUser() == $user && $r->getProduitP() == $details){
                    $r->setTitle(($request->get('title')));
                    $r->setDescription(($request->get('description')));
                    $r->setStars(($request->get('stars')));
                    $em->flush();
                    $exist = true;

                }
                else{
                    $exist= false;
                }

            }
            if ($exist == false){

                $review = new Reviews();
                $review->setTitle(($request->get('title')));
                $review->setDescription(($request->get('description')));
                $review->setStars(($request->get('stars')));
                $review->setProduitP($details);
                $review->setUser($user);
                $em->persist($review);
                $em->flush();

                return $this->redirectToRoute('details_productsR', array('id' => $details->getId()));

            }


        }

        $nbrrev = count($reviews);
        $totlanbrR = 0;
        foreach ($reviews as $rating) {

            $totlanbrR = $totlanbrR + $rating->getStars();
        }
        if ($nbrrev == 0) {
            $res = 0;
        } else
            $res = $totlanbrR / $nbrrev;
        $details->setStars($res);
        $em = $this->getDoctrine()->getManager();
        $em->persist($details);
        $em->flush();

        return $this->render('@RentProducts/Default/detailsProduct.html.twig',array(

            'details'=>$details,
            'reviews' => $reviews,
            'rev' => $nbrrev,
            'rating' => $res,

        ));

    }

    public function RentProdAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $product=$em->getRepository(RentProd::class)->find($id);
        $res = new Reservation();
        $user = $this->getUser();
        $form = $this->createForm('RentProductsBundle\Form\ReservationType', $res);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $qnt = $form['quantity']->getData();


            if ($qnt>$product->getQuantity()){

                return $this->redirectToRoute('All_productsR', array('id' => $product->getId()));
            }
            else{
                /** @var UploadedFile $file */
                $file = $res->getDocument();

                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored

                $file->move(
                    $this->getParameter('images_folder'),
                    $fileName
                );

                $res->setDocument($fileName);
                $res->setPrice($qnt*$product->getPrice());
                $res->setUser($user);
                $res->setRentProd($product);
                $product->setQuantity($product->getQuantity()-$qnt);

                $em->persist($res);
                $em->flush();

                return $this->redirectToRoute('Reservation', array('id' => $res->getId()));
            }

        }

        return $this->render('@RentProducts/Default/createReservation.html.twig', array(
            'form' => $form->createView()
        ));

    }

    public function resAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $res=$em->getRepository(Reservation::class)->find($id);
        $res->setQuantity($res->getQuantity());
        $form = $this->createFormBuilder($res)
            ->add('Quantity', TextType::class, array('attr'=>array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
            ->add('document', FileType::class, array('data_class'=>null,'attr'=>array('class'=>'form-control', 'style'=>'margin-bottom:15px')))

            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $qnt = $form['Quantity']->getData();

            $res=$em->getRepository(Reservation::class)->find($id);
            if ($qnt> ($res->getRentProd()->getQuantity()+$res->getQuantity())){

                return $this->redirectToRoute('All_productsR', array('id' => $res->getRentProd()->getId()));
            }
            else{


                /** @var UploadedFile $file */
                $file = $res->getDocument();

                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored

                $file->move(
                    $this->getParameter('images_folder'),
                    $fileName
                );

                $res->setDocument($fileName);

                $res->setPrice($qnt*$res->getRentProd()->getPrice());

                $em->flush();

                return $this->redirectToRoute('Reservation', array('id' => $res->getId()));
            }

        }

        return $this->render('@RentProducts/Default/Reservation.html.twig', array(
            'res' => $res,
            'form'=>  $form->createView()
        ));
    }

    public function rejectAction($id){

        $sn = $this->getDoctrine()->getManager();
        $res = $sn->getRepository(Reservation::class) ->find($id);
        $res->getRentProd()->setQuantity($res->getRentProd()->getQuantity()+$res->getQuantity());
        $sn->remove($res);
        $sn->flush();
        return $this->redirectToRoute('All_productsR', array('id' => $res->getRentProd()->getId()));

    }

    public function confirmAction($id){

        $user = $this->getUser();
        $am = $this->getDoctrine()->getManager();
        $res = $am->getRepository(Reservation::class)->find($id);
        $res->getRentProd()->setRenter($user);
        $res->setConfirmation(true);
        $am->flush();
        return $this->redirectToRoute('myRents');



    }

    public function myRentsAction(){

        $user = $this->getUser();
        $am = $this->getDoctrine()->getManager();
        $res = $am->getRepository(RentProd::class)->findByRenter($user);
        return $this->render('@RentProducts/Default/myRents.html.twig', array(
            'res' => $res));
    }

    public function downloadPdfAction($id)
    {
         $em = $this->getDoctrine()->getManager();
         $participation = $em->getRepository('RentProductsBundle:Reservation')->find($id);
         $snappy=$this->get("knp_snappy.pdf");
         $html=$this->renderView("@RentProducts/Default/pdf.html.twig",array(
             "Title"=> "Reservation",'participation'=>$participation
         ));


         $filename="participation";

         return new Response(
             $snappy->getOutputFromHtml($html),

             200,
             array(
                 'Content-Type' => 'application/pdf',
                 'Content-Disposition' => 'attachment; filename="'.$filename.'.pdf"'
             )
         );
     }





}
