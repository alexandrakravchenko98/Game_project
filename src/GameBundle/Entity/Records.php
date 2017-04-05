<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Records
 *
 * @ORM\Table(name="records")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\RecordsRepository")
 */
class Records
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;
    
    /**
     * @ORM\Column(name="current_level", type="integer")
     */
    private $currentLevel;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var int
     *
     * @ORM\Column(name="clicks_count", type="integer")
     */
    private $clicksCount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gametime", type="integer")
     */
    private $gametime;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Records
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Records
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }
    
    public function getCurrentLevel() {
        return $this->currentLevel;
    }

    public function setCurrentLevel($currentLevel) {
        $this->currentLevel = $currentLevel;
    }

        /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set clicksCount
     *
     * @param integer $clicksCount
     *
     * @return Records
     */
    public function setClicksCount($clicksCount)
    {
        $this->clicksCount = $clicksCount;

        return $this;
    }

    /**
     * Get clicksCount
     *
     * @return int
     */
    public function getClicksCount()
    {
        return $this->clicksCount;
    }

    /**
     * Set gametime
     *
     * @param \DateTime $gametime
     *
     * @return Records
     */
    public function setGametime($gametime)
    {
        $this->gametime = $gametime;

        return $this;
    }

    /**
     * Get gametime
     *
     * @return \DateTime
     */
    public function getGametime()
    {
        return $this->gametime;
    }
}

