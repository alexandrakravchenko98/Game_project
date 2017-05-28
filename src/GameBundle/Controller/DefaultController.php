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
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller {

    /**
     * @Route("/", name="home")
     */
    public function indexAction() {
        return $this->render('GameBundle:Default:index.html.twig');
    }

    /**
     * @Route("/rules", name="rules")
     */
    public function rulesPageAction() {
        return $this->render('GameBundle:Default:rules.html.twig');
    }

    /**
     * @Route("/level/0", name="level0")
     * @Security("has_role('ROLE_USER')")
     */
    public function teachLevelAction() {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $result = $em->createQueryBuilder();
        $level_one = $result->select('p')
                ->from('GameBundle:Records', 'p')
                ->where('p.username= :username')
                ->andWhere('p.currentLevel= :level')
                ->setParameter('username', $user->getUsername())
                ->setParameter('level', '1')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $this->render('GameBundle:Default:Levels/level0.html.twig', array(''
                    . 'user' => $user, 'scores' => $level_one));
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
     * @Route("/game/set/new/fullscore", name="setGameScore", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newFullScoreIfNotCompletedLevelAction(Request $request) {
        if ($request->isXMLHttpRequest()) {
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $content = $request->getContent();
            $params = json_decode($content, true);
            $records = new Records;
            $records->setUsername($user->getUsername());
            $records->setGameId($params['gameId']);
            $em->persist($records);
            $em->flush();
        }
        return new Response('ok');
    }

    /**
     * @Route("/record/new/create", name="record_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newRecordAction(Request $request) {
        if ($request->isXMLHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $content = $request->getContent();
            $params = json_decode($content, true);
            $records = new Records;

            /*
             * Исправление ошибки с Division By Zero 
             * Если получаем кол-во кликов 0 - то логичнее всего просто 
             * Присвоить кликам единицу во избежании 
             */
            if ($params['clicks'] == 0) {
                $params['clicks'] = 1;
            }

            $score = ((600 / $params['gametime']) + (30 / $params['clicks'])) * (2 ** $params['level']);
            $records->setUsername($this->getUser());
            $records->setClicksCount($params['clicks']);
            $records->setgametime($params['gametime']);
            $records->setCurrentLevel($params['level']);
            $records->setScore(floor($score));
            $records->setGameId($request->cookies->get('PHPSESSID'));
            $em->persist($records);
            $em->flush();

            if (isset($params['win'])) {
                
                /**
                 * Если все уровни пройдены
                 */
                if (isset($params['allLevelsCompleted'])) {
                    $user = $this->getUser();
                    $resultQBO = $em->createQueryBuilder();
                    $level_one = $resultQBO->select('p')
                            ->from('GameBundle:Records', 'p')
                            ->where('p.username= :username')
                            ->andWhere('p.currentLevel= :level')
                            ->andWhere('p.gameId= :gameId')
                            ->setParameter('username', $user->getUsername())
                            ->setParameter('level', '1')
                            ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                            ->getQuery()
                            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                    $level_two = $resultQBO->select('p')
                            ->from('GameBundle:Records', 'p')
                            ->where('p.username= :username')
                            ->andWhere('p.currentLevel= :level')
                            ->andWhere('p.gameId= :gameId')
                            ->setParameter('username', $user->getUsername())
                            ->setParameter('level', '2')
                            ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                            ->getQuery()
                            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                    $level_three = $resultQBO->select('p')
                            ->from('GameBundle:Records', 'p')
                            ->where('p.username= :username')
                            ->andWhere('p.currentLevel= :level')
                            ->andWhere('p.gameId= :gameId')
                            ->setParameter('username', $user->getUsername())
                            ->setParameter('level', '3')
                            ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                            ->getQuery()
                            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                    $level_four = $resultQBO->select('p')
                            ->from('GameBundle:Records', 'p')
                            ->where('p.username= :username')
                            ->andWhere('p.currentLevel= :level')
                            ->andWhere('p.gameId= :gameId')
                            ->setParameter('username', $user->getUsername())
                            ->setParameter('level', '4')
                            ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                            ->getQuery()
                            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                    $level_five = $resultQBO->select('p')
                            ->from('GameBundle:Records', 'p')
                            ->where('p.username= :username')
                            ->andWhere('p.currentLevel= :level')
                            ->andWhere('p.gameId= :gameId')
                            ->setParameter('username', $user->getUsername())
                            ->setParameter('level', '5')
                            ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                            ->getQuery()
                            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                    $score_level_one = $level_one['0']['score'];
                    $score_level_two = $level_two['0']['score'];
                    $score_level_three = $level_three['0']['score'];
                    $score_level_four = $level_four['0']['score'];
                    $score_level_five = $level_five['0']['score'];

                    $fullScore = $score_level_one + $score_level_two + $score_level_three +
                            $score_level_four + $score_level_five + $score;
                    $records->setFullScore($fullScore);
                    $em->persist($records);
                    $em->flush();
                }

                /* Уровень не пройден. Время закончилось
                 * Здесь необходимо вставить полный рекорд за пройденные уровни 
                 * 
                 */
                if ($params['win'] == false) {
                    if ($params['level'] == "2") {
                        $user = $this->getUser();
                        $resultQBO = $em->createQueryBuilder();
                        $level_one = $resultQBO->select('p')
                                ->from('GameBundle:Records', 'p')
                                ->where('p.username= :username')
                                ->andWhere('p.currentLevel= :level')
                                ->andWhere('p.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '1')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $score_level_one = $level_one['0']['score'];
                        $fullScore = $score_level_one;
                        $records->setScore('0');
                        $records->setFullScore($fullScore);
                        $em->persist($records);
                        $em->flush();
                    }
                    if ($params['level'] == "3") {
                        $user = $this->getUser();
                        $resultQBO = $em->createQueryBuilder();
                        $level_one = $resultQBO->select('p')
                                ->from('GameBundle:Records', 'p')
                                ->where('p.username= :username')
                                ->andWhere('p.currentLevel= :level')
                                ->andWhere('p.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '1')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_two = $resultQBO->select('z')
                                ->from('GameBundle:Records', 'z')
                                ->where('z.username= :username')
                                ->andWhere('z.currentLevel= :level')
                                ->andWhere('z.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '2')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $score_level_one = $level_one['0']['score'];
                        $score_level_two = $level_two['0']['score'];
                        $fullScore = $score_level_one + $score_level_two;
                        $records->setScore('0');
                        $records->setFullScore($fullScore);
                        $em->persist($records);
                        $em->flush();
                    }
                    if ($params['level'] == "4") {
                        $user = $this->getUser();
                        $resultQBO = $em->createQueryBuilder();
                        $level_one = $resultQBO->select('b')
                                ->from('GameBundle:Records', 'b')
                                ->where('b.username= :username')
                                ->andWhere('b.currentLevel= :level')
                                ->andWhere('b.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '1')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_two = $resultQBO->select('q')
                                ->from('GameBundle:Records', 'q')
                                ->where('q.username= :username')
                                ->andWhere('q.currentLevel= :level')
                                ->andWhere('q.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '2')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_three = $resultQBO->select('x')
                                ->from('GameBundle:Records', 'x')
                                ->where('x.username= :username')
                                ->andWhere('x.currentLevel= :level')
                                ->andWhere('x.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '3')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $score_level_one = $level_one['0']['score'];
                        $score_level_two = $level_two['0']['score'];
                        $score_level_three = $level_three['0']['score'];
                        $fullScore = $score_level_one + $score_level_two + $score_level_three;
                        $records->setScore('0');
                        $records->setFullScore($fullScore);
                        $em->persist($records);
                        $em->flush();
                    }
                    if ($params['level'] == "5") {
                        $user = $this->getUser();
                        $resultQBO = $em->createQueryBuilder();
                        $level_one = $resultQBO->select('w')
                                ->from('GameBundle:Records', 'w')
                                ->where('w.username= :username')
                                ->andWhere('w.currentLevel= :level')
                                ->andWhere('w.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '1')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_two = $resultQBO->select('g')
                                ->from('GameBundle:Records', 'g')
                                ->where('g.username= :username')
                                ->andWhere('g.currentLevel= :level')
                                ->andWhere('g.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '2')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_three = $resultQBO->select('i')
                                ->from('GameBundle:Records', 'i')
                                ->where('i.username= :username')
                                ->andWhere('i.currentLevel= :level')
                                ->andWhere('i.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '3')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_four = $resultQBO->select('a')
                                ->from('GameBundle:Records', 'a')
                                ->where('a.username= :username')
                                ->andWhere('a.currentLevel= :level')
                                ->andWhere('a.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '4')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $score_level_one = $level_one['0']['score'];
                        $score_level_two = $level_two['0']['score'];
                        $score_level_three = $level_three['0']['score'];
                        $score_level_four = $level_four['0']['score'];
                        $fullScore = $score_level_one + $score_level_two + $score_level_three + $score_level_four;
                        $records->setScore('0');
                        $records->setFullScore($fullScore);
                        $em->persist($records);
                        $em->flush();
                    }
                    if ($params['level'] == "6") {
                        $user = $this->getUser();
                        $resultQBO = $em->createQueryBuilder();
                        $level_one = $resultQBO->select('e')
                                ->from('GameBundle:Records', 'e')
                                ->where('e.username= :username')
                                ->andWhere('e.currentLevel= :level')
                                ->andWhere('e.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '1')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_two = $resultQBO->select('y')
                                ->from('GameBundle:Records', 'y')
                                ->where('y.username= :username')
                                ->andWhere('y.currentLevel= :level')
                                ->andWhere('y.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '2')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_three = $resultQBO->select('s')
                                ->from('GameBundle:Records', 's')
                                ->where('s.username= :username')
                                ->andWhere('s.currentLevel= :level')
                                ->andWhere('s.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '3')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_four = $resultQBO->select('l')
                                ->from('GameBundle:Records', 'l')
                                ->where('l.username= :username')
                                ->andWhere('l.currentLevel= :level')
                                ->andWhere('l.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '4')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $level_five = $resultQBO->select('m')
                                ->from('GameBundle:Records', 'm')
                                ->where('m.username= :username')
                                ->andWhere('m.currentLevel= :level')
                                ->andWhere('m.gameId= :gameId')
                                ->setParameter('username', $user->getUsername())
                                ->setParameter('level', '5')
                                ->setParameter('gameId', $request->cookies->get('PHPSESSID'))
                                ->getQuery()
                                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                        $score_level_one = $level_one['0']['score'];
                        $score_level_two = $level_two['0']['score'];
                        $score_level_three = $level_three['0']['score'];
                        $score_level_four = $level_four['0']['score'];
                        $score_level_five = $level_five['0']['score'];
                        $fullScore = $score_level_one + $score_level_two + $score_level_three + $score_level_four+$score_level_five;
                        $records->setScore('0');
                        $records->setFullScore($fullScore);
                        $em->persist($records);
                        $em->flush();
                    }
                }
            }
        }
        return new Response('ok');
    }

}
