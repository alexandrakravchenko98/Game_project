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
     */
    public function indexAction() {
        return $this->redirectToRoute('level0');
    }

    /**
     * @Route("/level/0", name="level0")
     * @Security("has_role('ROLE_USER')")
     */
    public function teachLevelAction() {
        $user = $this->getUser();
        return $this->render('GameBundle:Default:Levels/level0.html.twig', array(''
                    . 'user' => $user));
    }

    /**
     * @Route("/level/1", name="level1")
     * @Security("has_role('ROLE_USER')")
     */
    public function firstLevelAction() {
        $user = $this->getUser();
        return $this->render('GameBundle:Default:Levels/level1.html.twig', array(''
                    . 'user' => $user));
    }

    /**
     * @Route("/level/2", name="level2")
     * @Security("has_role('ROLE_USER')")
     */
    public function secondLevelAction() {
        $user = $this->getUser();
        return $this->render('GameBundle:Default:Levels/level2.html.twig', array(''
                    . 'user' => $user));
    }

    /**
     * @Route("/level/3", name="level3")
     * @Security("has_role('ROLE_USER')")
     */
    public function thirdLevelAction() {
        $user = $this->getUser();
        return $this->render('GameBundle:Default:Levels/level3.html.twig', array(''
                    . 'user' => $user));
    }

    /**
     * @Route("/level/4", name="level4")
     * @Security("has_role('ROLE_USER')")
     */
    public function fourLevelAction() {
        $user = $this->getUser();
        return $this->render('GameBundle:Default:Levels/level4.html.twig', array(''
                    . 'user' => $user));
    }

    /**
     * @Route("/level/5", name="level5")
     * @Security("has_role('ROLE_USER')")
     */
    public function fiveLevelAction() {
        $user = $this->getUser();
        return $this->render('GameBundle:Default:Levels/level5.html.twig', array(''
                    . 'user' => $user));
    }

    /**
     * @Route("/level/6", name="level6")
     * @Security("has_role('ROLE_USER')")
     */
    public function sixLevelAction() {
        $user = $this->getUser();
        return $this->render('GameBundle:Default:Levels/level6.html.twig', array(''
                    . 'user' => $user));
    }

    /**
     * @Route("/record/new/create", name="record_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newRecordAction(Request $request) {
        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            $params = json_decode($content, true);
            $records = new Records;
            $score = (600 / ($params['gametime'])) + (30 / ($params['clicks']));
            $records->setUsername($this->getUser());
            $records->setClicksCount($params['clicks']);
            $records->setgametime($params['gametime']);
            $records->setCurrentLevel($params['level']);
            $records->setScore(floor($score));

            $em = $this->getDoctrine()->getManager();
            $em->persist($records);
            $em->flush();
        }
        return new Response('ok');
    }

}

