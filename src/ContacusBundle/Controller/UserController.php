<?php

namespace ContacusBundle\Controller;

use AppBundle\Entity\User;
use Cassandra\Date;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserController extends Controller
{


    public function  loginAction( $email,$password )
    {

        $em1 = $this->getDoctrine()->getManager();
        $user=$em1->getRepository('AppBundle:User')->findOneBy(array("email"=>$email));

        $test=array();
        $u=new User();


       if ($user==null)
        {
            $u->setId(0);
            $us=["user"=>$u];

            $msg=["message"=>"email non valid"];
            array_push($test,$msg);
            array_push($test,$us);

        }

        else if($user->getPassword() != $password)
        {
            $u->setId(0);
            $us=["user"=>$u];
            $msg=["message"=>"password non valid"];
            array_push($test,$msg);
            array_push($test,$us);
        }
        else
            {
                $u=$user;

                $us=["user"=>$u,"message"=>"connection etablie"];

                //array_push($test,$msg);
                array_push($test,$us);
        }

         $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($test);
        return new JsonResponse($formatted);
        
        }


        public function registerAction(Request $request)
        {

            $user=new User();

            $user->setUsername($request->get('username'));
            $user->setUsernameCanonical($request->get('username'));
            $user->setEmail($request->get('email'));
            $user->setEmailCanonical($request->get('email'));
            $user->setEnabled(1);
            $user->setSalt(null);
            $user->setImageFile($request->get('image'));

            $user->setLastLogin(new \DateTime('now'));

            $user->setPassword($request->get('password'));
            $user->setRoles( array("ROLE_USER"));


            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($user);
            return new JsonResponse($formatted);



        }

}
