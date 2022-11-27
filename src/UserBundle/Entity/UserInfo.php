<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserInfo
 *
 * @ORM\Table(name="user_info")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserInfoRepository")
 */
class UserInfo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="bigint")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="ui_key", type="string", length=255)
     */
    private $uiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="ui_value", type="text")
     */
    private $uiValue;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="information")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserInfo
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set uiKey
     *
     * @param string $uiKey
     *
     * @return UserInfo
     */
    public function setUiKey($uiKey)
    {
        $this->uiKey = $uiKey;

        return $this;
    }

    /**
     * Get uiKey
     *
     * @return string
     */
    public function getUiKey()
    {
        return $this->uiKey;
    }

    /**
     * Set uiValue
     *
     * @param string $uiValue
     *
     * @return UserInfo
     */
    public function setUiValue($uiValue)
    {
        $this->uiValue = $uiValue;

        return $this;
    }

    /**
     * Get uiValue
     *
     * @return string
     */
    public function getUiValue()
    {
        return $this->uiValue;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return UserInfo
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
