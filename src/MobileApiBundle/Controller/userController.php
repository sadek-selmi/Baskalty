<?php

namespace MobileApiBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class userController extends Controller
{



    public function  loginAction( $email,$password )
    {

        $em1 = $this->getDoctrine()->getManager();
        $user=$em1->getRepository('AppBundle:User')->findOneBy(["email"=>$email]);

        $test=array();
        $u=new User();


        if ($user==null)
        {
            $u->setId(0);
            $u->setEmail('null');
            $u->setPassword('null');
            $us=[$u];

          //  $msg=["message"=>"email non valid"];
            //array_push($test);
           // array_push($test,$us);

        }

        else if($user->getPassword() != $password)
        {
            $u->setId(0);
            $u->setEmail('null');
            $u->setPassword('null');
            $u->setUsername('null');
            $us=[$u];
          //  $msg=["message"=>"password non valid"];
          //  array_push($test);
            array_push($test,$us);
        }
        else
        {
           // $u->getRoles()
           // $u->setRoles($user->getRoles());
            $u=$user;
            $us=[$u];

            //array_push($test,$msg);
            array_push($test,$us);
        }

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($us);
        return new JsonResponse($formatted);

    }


    public function findUserByidAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);

    }


    public function editAction($id,$username,$email,$password,$repeatedpassword)
    {
       $u = new User();

        $em = $this->getDoctrine()->getManager();
        $userToEdit = $em->getRepository(User::class)->findOneBy(["id"=>$id]);

        if ($password == $repeatedpassword)
        {
            $u = $userToEdit;
            $u->setUsername($username);
            $u->setUsernameCanonical($username);
            $u->setEmail($email);
            $u->setEmailCanonical($email);
            $u->setPassword($password);

            $em->flush();
        }
        else
        {
            $u = [$userToEdit];
        }

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($u);
        return new JsonResponse($formatted);

    }


    public function registerAction($username,$email,$password,$repeatedpassword,$roles,$image)
    {
        $user=new User();


        if ( $password == $repeatedpassword)
        {

            $user->setUsername($username);
            $user->setUsernameCanonical($username);
            $user->setEmail($email);
            $user->setEmailCanonical($email);
            $user->setMobile(1);
            $user->setSalt(null);
            $user->setImageFile($image);
            $user->setLastLogin(new \DateTime('now'));


            $user->setPassword($password);
            $user->setRoles( array('ROLE_'.$roles));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        else
        {
            $user->setUsername(null);
            $user->setUsernameCanonical(null);
            $user->setEmail(null);
            $user->setEmailCanonical(null);
            $user->setMobile(0);
            $user->setSalt(null);
            $user->setImageFile(null);


            $user->setPassword(null);

        }

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);

    }


}
