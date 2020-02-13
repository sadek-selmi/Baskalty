<?php

namespace RentProductsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RentProductsBundle:Default:index.html.twig');
    }
}
