<?php

namespace Resi\GestionResiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller{
    public function indexAction(){
        return $this->render('GestionResiBundle:Default:index.html.twig',
                      array('userNotFound'=>null,
                            'sesionIniciado'=>null));
    }
}
