<?php

namespace MaintenanceBundle\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Psr\Log\LoggerInterface;
use Twilio\Exceptions\TwilioException;
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

class DefaultController extends Controller
{


    public function SMSAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $account_sid = 'AC17206e77ae551637b207dba93abedf87';
        $auth_token = '42d2f21e26649f216ec6848a68cba79c';
        $twili_number = "+18588779208";
        $client = new Client($account_sid, $auth_token);

            $client->messages->create(
            // Where to send a text message (your cell phone?)
                '+21655410596',
                array(
                    'from' => $twili_number,
                    'body' => 'Confirmed')
            );

        return  $this->redirectToRoute("maintenance_homepage");






    }





}




