<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\User;
use EventsBundle\Entity\Association;
use MaintenanceBundle\Entity\mechanicien;
use SellProductsBundle\Entity\Seller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }
    public function userAction()
    {
        return $this->render('AdminBundle:Default:users.html.twig');
    }

    public function makesimpleAction($id){
        $sn = $this->getDoctrine()->getManager();
        $user = $sn->getRepository('AppBundle:User')->find($id);
        $user->setRoles(['0']);
        $sn->persist($user);
        $sn->flush();
        return $this->redirectToRoute('user_homepage');
    }
    public function makeASSOCIATIONAction($id){
    $sn = $this->getDoctrine()->getManager();
    $user = $sn->getRepository('AppBundle:User')->find($id);
    $user->setRoles(['ROLE_ASSOCIATION']);
    $sn->persist($user);
    $sn->flush();
    return $this->redirectToRoute('user_homepage');
}
    public function makeVendreAction($id){
        $sn = $this->getDoctrine()->getManager();
        $user = $sn->getRepository('AppBundle:User')->find($id);
        $user->setRoles(['ROLE_vender']);
        $sn->persist($user);
        $sn->flush();
        return $this->redirectToRoute('user_homepage');
    }
    public function makeLAction($id){
        $sn = $this->getDoctrine()->getManager();
        $user = $sn->getRepository('AppBundle:User')->find($id);
        $user->setRoles(['ROLE_locateur']);
        $sn->persist($user);
        $sn->flush();
        return $this->redirectToRoute('user_homepage');
    }
    public function AfficherUserAction(Request $request){

        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todos,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('AdminBundle:Default:users.html.twig', array(

            'users'=>$pagination,

        ));

    }
    public function contactAction(Request $request){

        $todos = $this->getDoctrine()
            ->getRepository('ContacusBundle:Contact')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todos,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('AdminBundle:Default:contact.html.twig', array(

            'contact'=>$pagination,

        ));

    }
    public function EventUserAction(Request $request){

        $todos = $this->getDoctrine()
            ->getRepository('EventsBundle:Association')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todos,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('AdminBundle:Default:events.html.twig', array(

            'events'=>$pagination,

        ));

    }
    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository(User::class)->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('user_homepage');
    }
    public function detailAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $event = $sn->getRepository('ContacusBundle:Contact')->find($id);


        return $this->render('@Admin/Default/contactdetails.html.twig', array('contact' => $event));

    }
    public function deletAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository('ContacusBundle:Contact')->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('contact_homepage');
    }
    public function deleteforAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository(Association::class)->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('user_homepage');

    }
    public function deleteforLAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository(self::class)->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('user_homepage');
    }
    public function deleteforVAction($id)
{
    $em=$this->getDoctrine()->getManager();
    $product=$em->getRepository(Seller::class)->find($id);
    $em->remove($product);
    $em->flush();
    return $this->redirectToRoute('user_homepage');
}

    public function deletemechAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository(mechanicien::class)->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('mechuser_homepage');
    }
    public function mechUserAction(Request $request){

        $todos = $this->getDoctrine()
            ->getRepository('MaintenanceBundle:mechanicien')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todos,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('AdminBundle:Default:mechanical.html.twig', array(

            'mech'=>$pagination,

        ));

    }
    public function mechUserVAction(Request $request){

        $todos = $this->getDoctrine()
            ->getRepository('SellProductsBundle:Seller')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todos,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('AdminBundle:Default:Seller.html.twig', array(

            'seller'=>$pagination,

        ));

    }
    public function mechUserLAction(Request $request){

        $todos = $this->getDoctrine()
            ->getRepository('RentProductsBundle:Rent')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todos,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('AdminBundle:Default:Locateur.html.twig', array(

            'locateur'=>$pagination,

        ));

    }
    public function actifAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository('MaintenanceBundle:mechanicien')->find($id);
       $product->setActif(1);
        $em->persist($product);
        $em->flush();
        return $this->redirectToRoute('mechuser_homepage');
    }


    public function validepostAction(Request $request){

        $todos = $this->getDoctrine()
            ->getRepository('PostBundle:Publication')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todos,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('AdminBundle:Default:post.html.twig', array(

            'post'=>$pagination,

        ));

    }
    public function acceptAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $sn = $em->getRepository('PostBundle:Publication')->find($id);
        $sn->setAccept(1);
        $em->persist($sn);
        $em->flush();
        return $this->redirectToRoute('Postapprov_homepage');
    }

    public function deleteblogAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $sn = $em->getRepository('PostBundle:Publication')->find($id);
        $em->remove($sn);
        $em->flush();
        return $this->redirectToRoute('Postapprov_homepage');
    }


}
