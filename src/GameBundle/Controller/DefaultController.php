<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GameBundle\Entity\Records;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        return $this->render('GameBundle:Default:index.html.twig',array(''
            . 'user' => $user));
    }
    public function addRecord()
    {
        $records = new Records();
    }
}
