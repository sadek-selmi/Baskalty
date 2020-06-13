<?php

namespace MobileApiBundle\Controller;

use EventsBundle\Entity\Comment;
use EventsBundle\Entity\Events;
use EventsBundle\Entity\Interested;
use EventsBundle\Entity\Participer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MobileApiBundle:Default:index.html.twig');
    }
    public function DetailsMAction($id, Request $request)
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
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);
    }
    public function AjouterMAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $country = $em->getRepository('AppBundle:User')->find($request->get('idevent'));
        $theme = $em->getRepository('EventsBundle:Comment')->find(1);

        $events = new Events();
        $form = $this->createForm('EventsBundle\Form\EventsType', $events);
        $form->handleRequest($request);
        $events->setName($request->get('name'));
        $events->setUserid($country);

        $events->setTheme($theme);

        $events->setLocation($request->get('location'));
        $events->setDescription($request->get('description'));
        $events->setStart(new \DateTime($request->get('start') ) );
        $events->setEnd(new \DateTime($request->get('end')) );
        $events->setPrix($request->get('prix'));
        $events->setQuantity($request->get('quantity'));
        $events->setCommenternumber(0);
        $events->setImage($request->get('image'));
        $events->setTheme($request->get('theme'));

        $events->setParticipernumber(0);
        $events->setInterstednumber(0);
        $em->persist($events);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($events);
        return new JsonResponse($formatted);




    }
    public function afficheMAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventsBundle:Events')->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);

    }
    public function afficheParticiAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventsBundle:Participer')->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);

    }
    public function AjouterCommenterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $country = $em->getRepository('EventsBundle:Events')->find($request->get('idevent'));
        $user = $em->getRepository('AppBundle:User')->find($request->get('user'));

        $comment = new Comment();

       $comment->setUser($user);
        $comment->setEvents($country);
        $comment->setContent($request->get('content'));
        $comment->setPublishdate(new \DateTime('now'));

        $em->persist($comment);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($comment);
        return new JsonResponse($formatted);




    }
    public function modifiercommenterAction(Request $request ,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventsBundle:Comment')->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);

    }
    public function affichercommenterAction(Request $request  )
    {
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery('SELECT m.content,e.id , p.username,p.imageFile FROM EventsBundle:Comment m ,EventsBundle:Events  e ,AppBundle:User p where m.events = e.id and p.id = m.user');
        $col = $query->getArrayResult();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($col);
        return new JsonResponse($formatted);

    }

    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $product = $em->getRepository('EventsBundle:Comment')->findOneBy(["id"=>$id]);
        $product->setContent($request->get('description'));


        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($product);
        return new JsonResponse($formatted);
    }
    public function deleteAction (Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('EventsBundle:Events')->findOneBy(["id"=>$id]);

        $em->remove($product);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(new Events());
        return new JsonResponse($formatted);
    }
    public function editevAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $events = $em->getRepository('EventsBundle:Events')->findOneBy(["id"=>$id]);
        $events->setName($request->get('name'));

        $events->setLocation($request->get('location'));
        $events->setDescription($request->get('description'));
        $events->setStart(new \DateTime($request->get('start') ) );
        $events->setEnd(new \DateTime($request->get('end')) );
        $events->setPrix($request->get('prix'));
        $events->setQuantity($request->get('quantity'));
       // $events->setCommenternumber(0);
        $events->setImage($request->get('image'));
       // $events->setTheme($request->get('theme'));

     //   $events->setParticipernumber(0);
     //   $events->setInterstednumber(0);

        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($events);
        return new JsonResponse($formatted);
    }
    public function findeventsByUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $productsByUser = $em->getRepository('EventsBundle:Events')->findByUser($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($productsByUser);
        return new JsonResponse($formatted);
    }
    public function InterstedmAction(Request $request)
    {
        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('EventsBundle:Events')->find($request->get('event'));
        $user = $sn->getRepository('AppBundle:User')->find($request->get('user'));
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
    public function ParticipermAction(Request $request)
    {$vide="ghalet";
        $vides="shih";

        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('EventsBundle:Events')->find($request->get('event'));
        $user = $sn->getRepository('AppBundle:User')->find($request->get('user'));
        $check = $sn->getRepository('EventsBundle:Participer')->findby(array('user' => $user, 'eventtP' => $event));
        if (!$check) {
            $p = new Participer();
            $p->setEventtP($event);
            $p->setUser($user);
            $p ->setConfirmation(0);

                $random =''.$this->generateRandomString();

                $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587,'tls'))
                    ->setUsername('baskaltii@gmail.com')
                    ->setPassword('21885045');
                $mailer = new \Swift_Mailer($transport);
                $message = (new \Swift_Message('Confirmation du participation'))
                    ->setFrom('baskaltii@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        'Vore code de confirmation  : '.$random );


                /* @var $mailer \Swift_Mailer */
                $mailer->send($message);

                $p->setChampsConfirmation($random) ;
                $sn->persist($p);
                $sn->flush();

            $serializer = new Serializer([new ObjectNormalizer()]);

            $data = $serializer->normalize($check);
            return new JsonResponse($data);
        }

        $serializer = new Serializer([new ObjectNormalizer()]);

        $data = $serializer->normalize($check);
        return new JsonResponse($data);

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
    public function downloadPdfsAction(Request $request)
    {           $am = $this->getDoctrine()->getManager();

        $user = $am->getRepository('AppBundle:User')->find($request->get('user'));

        $res = $am->getRepository("EventsBundle:Events")->find($request->get('event'));
//BarCode
        $options = array(
            'code' => $user->getUsername() . $res->getName(),
            'type' => 'qrcode',
            'color' => 'black',
            'format' => 'html',
        );

        $barcode = $this->get('skies_barcode.generator')->generate($options);


        $snappy = $this->get("knp_snappy.pdf");
        $filename = " reservation_num_";
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
    public function envoyerConfirmationsAction(Request $request)
    {$vide="ghalet";

            $exists = $this->getDoctrine()->getRepository('EventsBundle:Participer')->findOneBy([

                'champsConfirmation' => $request->get('code'),

            ]);
        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('EventsBundle:Events')->find($request->get('event'));
        $user = $sn->getRepository('AppBundle:User')->find($request->get('user'));

        $check = $sn->getRepository('EventsBundle:Participer')->findby(array('user' => $user, 'eventtP' => $event ));
        if ($check) {


            if (!empty($exists)){

                $exists->setConfirmation(True);

                $event->setParticipernumber($event->getParticipernumber() + 1);
                $event->setQuantity($event->getQuantity() - 1);

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
                return new JsonResponse($vide);

    }else {
            return new JsonResponse($vide);



        }
    }
    public function chamsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventsBundle:Participer')->findBychamps($request->get('id'),$request->get('event'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);

    }
    public function chamssAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($request->get('id'));
$v="ldld";
        $event = $em->getRepository('EventsBundle:Participer')->findByPari($request->get('id'),$request->get('event'));
        if (!empty($event)){
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($v);
        return new JsonResponse($formatted);

    }else{

            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($user);
            return new JsonResponse($formatted);



        }}


}
