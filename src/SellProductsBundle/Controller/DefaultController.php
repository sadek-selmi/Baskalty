<?php

namespace SellProductsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SellProductsBundle:Default:readProducts.html.twig');
    }
}
