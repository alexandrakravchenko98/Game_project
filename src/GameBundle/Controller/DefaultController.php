<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GameBundle\Entity\Records;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller {

    /**
     * @Route("/", name="home")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction() {
        $user = $this->getUser();
        return $this->render('GameBundle:Default:index.html.twig', array(''
                    . 'user' => $user));
    }

    /**
     * @Route("/calendar/new", name="record_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newRecordAction(Request $request) {


        if ($request->isXMLHttpRequest()) {
            $data = $request->request->get('clicks');
            $records = new Records;
            $records->setUsername($this->getUser());
            $records->setClicksCount($data);

            $em = $this->getDoctrine()->getManager();

            $em->persist($records);
            $em->flush();
        }
        return new Response('ok');
    }

}
