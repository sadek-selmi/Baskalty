<?php

namespace EventsBundle\Controller;

use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use EventsBundle\Entity\Association;
use EventsBundle\Entity\Events;
use EventsBundle\Entity\Interested;
use EventsBundle\Entity\Participer;
use EventsBundle\EventsBundle;
use EventsBundle\Form\AssociationType;
use EventsBundle\Form\EventsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use EventsBundle\Entity\Comment;
use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twilio\Rest\Client;
use Psr\Log\LoggerInterface;
use Twilio\Exceptions\TwilioException;
use Twilio\Jwt\TaskRouter\WorkerCapability;
use Twilio\Rest\Taskrouter\V1\Workspace\TaskInstance;
use Twilio\Rest\Taskrouter\V1\Workspace\WorkerInstance;
class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventsBundle:Events')->findAll();
        ///////////// Pagination
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $event, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $check = $em->getRepository('EventsBundle:Participer')->findby(array('user' => $user, 'eventtP' => $event));

        return $this->render('@Events/Default/index.html.twig', array('events' => $pagination,'check'=>$check));

    }

    public function DetailsAction($id, Request $request)
    {
        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('EventsBundle:Events')->find($id);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $events = $sn->getRepository('EventsBundle:Participer')->findby(array('user' => $user, 'eventtP' => $event));


        $user = $this->getUser();

        if ($request->isMethod('post')) {
            $comment = new Comment();
            $comment->setUser($user);
            $comment->setEvents($event);
            $comment->setContent($request->get('comment-content'));
            $comment->setPublishdate(new \DateTime('now'));
            $event->setCommenternumber($event->getCommenternumber() + 1);
            $sn->persist($event);
            $sn->persist($comment);
            $sn->flush();
            return $this->redirectToRoute('event_details', array('id' => $event->getId()));
        }
        $comments = $sn->getRepository('EventsBundle:Comment')->findByEvents($event);
        return $this->render('@Events/Default/DetailsEvent.html.twig', array('events' => $event, 'comm' => $comments,'check'=>$events));

    }

    public function ParticiperAction($id)
    {
        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('EventsBundle:Events')->find($id);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $check = $sn->getRepository('EventsBundle:Participer')->findby(array('user' => $user, 'eventtP' => $event));
        $ps = $event->getPrix();
        if (!$check) {
            $p = new Participer();
            $p->setEventtP($event);
            $p->setUser($user);
            $p ->setConfirmation(0);

            $ps = $event->getPrix();
            if ($ps == 0) {
                $event->setParticipernumber($event->getParticipernumber() + 1);
                $random =''.$this->generateRandomString();

                $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587,'tls'))
                    ->setUsername('baskaltitn@gmail.com')
                    ->setPassword('21885045');
                $mailer = new \Swift_Mailer($transport);
                $message = (new \Swift_Message('Confirmation du participation'))
                    ->setFrom('baskaltitn@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        'Vore code de confirmation  : '.$random )

                ;
                /* @var $mailer \Swift_Mailer */
                $mailer->send($message);
                $p->setChampsConfirmation($random) ;
                $sn->persist($p);
                $sn->persist($event);
                $sn->flush();
                return $this->redirectToRoute('events_homepage');

            } else {
                $random =''.$this->generateRandomString();

                $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587,'tls'))
                    ->setUsername('baskaltii@gmail.com')
                    ->setPassword('21885045');
                $mailer = new \Swift_Mailer($transport);
                $message = (new \Swift_Message('Confirmation du participation'))
                    ->setFrom('baskaltii@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        'Vore code de confirmation  : '.$random )

                ;
                /* @var $mailer \Swift_Mailer */
                $mailer->send($message);
                $p->setChampsConfirmation($random) ;
                $event->setParticipernumber($event->getParticipernumber() + 1);
                $event->setQuantity($event->getQuantity() - 1);
                $sn->persist($p);
                $sn->persist($event);
                $sn->flush();
                return $this->redirectToRoute('events_homepage');
            }
        } elseif ( $check and $ps == 0){

            $event->setParticipernumber($event->getParticipernumber() - 1);

            $sn->persist($event);
            $sn->remove($check[0]);
            $sn->flush();
        }else{
                $event->setParticipernumber($event->getParticipernumber() - 1);
                $event->setQuantity($event->getQuantity() + 1);
                $sn->persist($event);
                $sn->remove($check[0]);
                $sn->flush();
            }

        return $this->redirectToRoute('events_homepage');

    }

    public function InterstedAction($id)
    {
        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('EventsBundle:Events')->find($id);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $check = $sn->getRepository('EventsBundle:Interested')->findby(array('user' => $user, 'eventtP' => $event));
        if (!$check) {

            $p = new Interested();
            $p->setEventtP($event);
            $p->setUser($user);
            $event->setInterstednumber($event->getInterstednumber() + 1);
            $sn->persist($p);
            $sn->persist($event);
            $sn->flush();
            return $this->redirectToRoute('events_homepage');
        }else{
            $event->setInterstednumber($event->getInterstednumber() - 1);

            $sn->persist($event);
            $sn->remove($check[0]);
            $sn->flush();
        }
        return $this->redirectToRoute('events_homepage');

    }

    public function AjouterAction(Request $request)
    {
        $events = new Events();
        $form = $this->createForm('EventsBundle\Form\EventsType', $events);
        $form->handleRequest($request);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $events->setUserid($user);
            $events->setParticipernumber(0);
            $events->setInterstednumber(0);
            if ($events->getEnd() > $events->getStart()) {
                $diff = date_diff($events->getEnd(), $events->getStart());
                if ($diff->m < 6) {
                    $em->persist($events);
                    $em->flush();
                    return $this->redirectToRoute('events_show', array('id' => $events->getId()));
                } else {
                    // Retrieve flashbag from the controller
                    $flashbag = $this->get('session')->getFlashBag();

                    // Set a flash message
                    $flashbag->add("success", ['message1' => 'alaalalalalalalalal']);

                    return $this->redirectToRoute('events_add');
                }
            }

        }

        return $this->render('@Events/Default/Publier.html.twig', array(
            'events' => $events,
            'form' => $form->createView(),
        ));
    }

    public function FormulaireAction(Request $request)
    {


        $FOR = new Association();
        $form = $this->createForm('EventsBundle\Form\AssociationType', $FOR);
        $form->handleRequest($request);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $FOR->setUser($user);
            $em->persist($FOR);

            $em->flush();

            return $this->redirectToRoute('events_show', array('id' => $FOR->getId()));
        }
        return $this->render('@Events/Default/Formulaire.html.twig', array(
            'formulaire' => $FOR,
            'form' => $form->createView(),
        ));

    }

    public function envoyerMailAction(){

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $random =''.$this->generateRandomString();

        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 456,'tls'))
            ->setUsername('baskaltitn@gmail.com')
            ->setPassword('21885045');
        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Confirmation du participation'))
            ->setFrom('baskaltitn@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                'Vore code de confirmation  : '.$random )

        ;
        /* @var $mailer \Swift_Mailer */
        $mailer->send($message);


        return $this->redirectToRoute('events_show');

    }
    public function envoyerConfirmationAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $code = $request->request->get('code');
            $exists = $this->getDoctrine()->getRepository('EventsBundle:Participer')->findOneBy([

                'champsConfirmation' => $code,

            ]);


            if (!empty($exists)){
                $event = $this->getDoctrine()->getRepository('EventsBundle:Events')->findOneBy(
                    ['id'=>$exists->getEventtP()->getId()]);

                $exists->setConfirmation(True);

                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->persist($exists);
                $em->flush();
                $serializer = new Serializer([new ObjectNormalizer()]);

                $data = $serializer->normalize([
                    'id' => 'id',
                ]);
                return new JsonResponse($data);
            } else
                return new JsonResponse(null);
        }
        return new JsonResponse();
    }

//----------------------------------------FONCTION DE MODIFICATION DANS LA CALENDRIER-----------------------------------------------------
    public function modifyAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $name = $request->get('event');
        $start = $request->get('datedebut');
        $end = $request->get('datefin');
        $user = $request->get('user');
        $evenement = $em->getRepository(Events::class)->findOneBy(array("name" => $name));
        if ($user != $evenement->getUserid()->getId()) {
            return new Response("no");
        }
        $evenement->setStart(new \DateTime($start));
        $evenement->setEnd(new \DateTime($end));
        $em->merge($evenement);
        $em->flush();
        return new Response("yes");
    }

    public function downloadPdfAction($id)
    {
        $user = $this->getUser();
        $am = $this->getDoctrine()->getManager();
        $res = $am->getRepository("EventsBundle:Events")->find($id);
//BarCode
        $options = array(
            'code' => $user->getUsername() . $res->getName(),
            'type' => 'qrcode',
            'color' => 'black',
            'format' => 'html',
        );

        $barcode = $this->get('skies_barcode.generator')->generate($options);


        $snappy = $this->get("knp_snappy.pdf");
        $filename = " reservation_num_$id";
        $webSiteUrl = $this->render('EventsBundle:Default:pdf.html.twig', array('reservation' => $res, 'barcode' => $barcode));
        return new  Response(
            $snappy->getOutputFromHtml($webSiteUrl),
            200,
            array(
                'content-Type' => 'application/pdf',
                'content-Disposition' => 'attachment; filename="' . $filename . '.pdf"'
            )
        );
    }










    public function generateRandomString($length = 16, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }













    public function SMSAction(Request $request)
    {
        $account_sid = 'AC0e1b645b4bd67c3ffb33cdb373fe42e5';
        $auth_token = '9c8f3b3b8a1989e1fa7e686e13e11a3a';
        $twili_number = "+12038942382";
        $client = new Client($account_sid, $auth_token);
        try {
            $client->messages->create(
            // Where to send a text message (your cell phone?)
                '+21621885045',
                array(
                    'from' => $twili_number,
                    'body' => 'Confirmed')
            );
        } catch (TwilioException $e) {
            echo ('error');
        }
        return  $this->redirectToRoute("events_show");
    }
    public function MyAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventsBundle:Events')->findAll();
        return $this->render('EventsBundle:Default:Myevent.html.twig', array(
            'parti' => $event, 'idpar' => $id
        ));




    }

    public function editAction($id, Request $request)
    {


        $p = $this->getDoctrine()->getRepository(Events::class)->find($id);
        $form = $this->createForm(EventsType::class, $p);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $ef = $this->getDoctrine()->getManager();
            $ef->persist($p);
            $ef->flush();
            return $this->redirectToRoute("events_show");
        }


        return $this->render('@Events/Default/editEvent.html.twig', array(

            "form" => $form->createView()));

    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Events::class)->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('events_show');
    }

    public function deleteforAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Association::class)->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('homepage');
    }

    public function editforAction($id, Request $request)
    {


        $p = $this->getDoctrine()->getRepository(Association::class)->find($id);
        $form = $this->createForm(AssociationType::class, $p);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $ef = $this->getDoctrine()->getManager();
            $ef->persist($p);
            $ef->flush();
            return $this->redirectToRoute("events_show");
        }


        return $this->render('@Events/Default/Formulairedit.html.twig', array(

            "form" => $form->createView()));

    }

    public function formulAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventsBundle:Participer')->findAll();
        return $this->render('EventsBundle:Default:participer.html.twig', array(
            'parti' => $event, 'idpar' => $id
        ));


    }

    public function ErreurAction()
    {
        return $this->render("@Events/Default/hh.html.twig");
    }



}
