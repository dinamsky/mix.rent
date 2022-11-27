<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="temp_password", type="string", length=255, nullable=true)
     */
    private $tempPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="string", length=255)
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_create", type="datetime", nullable=true)
     */
    private $dateCreate = null;

    /**
     * @var string
     *
     * @ORM\Column(name="activate_string", type="string", length=32)
     */
    private $activateString;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_activated", type="boolean")
     */
    private $isActivated = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_subscriber", type="boolean")
     */
    private $isSubscriber;

    /**
     * @var bool
     *
     * @ORM\Column(name="account_type_id", type="smallint")
     */
    private $accountTypeId = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_banned", type="boolean")
     */
    private $isBanned = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=true, options={"default" : 1})
     */
    private $adminId = null;

    /**
     * @var int
     *
     * @ORM\Column(name="card_counter", type="integer", options={"default" : 0})
     */
    private $cardCounter = 0;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Admin", inversedBy="users")
     * @ORM\JoinColumn(name="admin_id", referencedColumnName="id")
     */
    private $admin;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UserInfo", mappedBy="user")
     */
    private $information;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UserOrder", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    private $userOrders;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Card", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    private $cards;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Opinion", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    private $opinions;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_new", type="boolean")
     */
    private $isNew = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="whois", type="string", length=255)
     */
    private $whois = '';

    public function __construct() {
        $this->information = new ArrayCollection();
        $this->userOrders = new ArrayCollection();
        $this->cards = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->opinions = new ArrayCollection();
    }

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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return User
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return User
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }


    /**
     * Set isActivated
     *
     * @param boolean $isActivated
     *
     * @return User
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * Get isActivated
     *
     * @return boolean
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * Set activateString
     *
     * @param string $activateString
     *
     * @return User
     */
    public function setActivateString($activateString)
    {
        $this->activateString = $activateString;

        return $this;
    }

    /**
     * Get activateString
     *
     * @return string
     */
    public function getActivateString()
    {
        return $this->activateString;
    }

    /**
     * Add information
     *
     * @param \UserBundle\Entity\UserInfo $information
     *
     * @return User
     */
    public function addInformation(\UserBundle\Entity\UserInfo $information)
    {
        $this->information[] = $information;

        return $this;
    }

    /**
     * Remove information
     *
     * @param \UserBundle\Entity\UserInfo $information
     */
    public function removeInformation(\UserBundle\Entity\UserInfo $information)
    {
        $this->information->removeElement($information);
    }

    /**
     * Get information
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Add userOrder
     *
     * @param \UserBundle\Entity\UserOrder $userOrder
     *
     * @return User
     */
    public function addUserOrder(\UserBundle\Entity\UserOrder $userOrder)
    {
        $this->userOrders[] = $userOrder;

        return $this;
    }

    /**
     * Remove userOrder
     *
     * @param \UserBundle\Entity\UserOrder $userOrder
     */
    public function removeUserOrder(\UserBundle\Entity\UserOrder $userOrder)
    {
        $this->userOrders->removeElement($userOrder);
    }

    /**
     * Get userOrders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserOrders()
    {
        return $this->userOrders;
    }

    /**
     * Add card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return User
     */
    public function addCard(\AppBundle\Entity\Card $card)
    {
        $this->cards[] = $card;

        return $this;
    }

    /**
     * Remove card
     *
     * @param \AppBundle\Entity\Card $card
     */
    public function removeCard(\AppBundle\Entity\Card $card)
    {
        $this->cards->removeElement($card);
    }

    /**
     * Get cards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Set tempPassword
     *
     * @param string $tempPassword
     *
     * @return User
     */
    public function setTempPassword($tempPassword)
    {
        $this->tempPassword = $tempPassword;

        return $this;
    }

    /**
     * Get tempPassword
     *
     * @return string
     */
    public function getTempPassword()
    {
        return $this->tempPassword;
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return User
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set adminId
     *
     * @param integer $adminId
     *
     * @return User
     */
    public function setAdminId($adminId)
    {
        $this->adminId = $adminId;

        return $this;
    }

    /**
     * Get adminId
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set admin
     *
     * @param \AdminBundle\Entity\Admin $admin
     *
     * @return User
     */
    public function setAdmin(\AdminBundle\Entity\Admin $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return \AdminBundle\Entity\Admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Add opinion
     *
     * @param \AppBundle\Entity\Opinion $opinion
     *
     * @return User
     */
    public function addOpinion(\AppBundle\Entity\Opinion $opinion)
    {
        $this->opinions[] = $opinion;

        return $this;
    }

    /**
     * Remove opinion
     *
     * @param \AppBundle\Entity\Opinion $opinion
     */
    public function removeOpinion(\AppBundle\Entity\Opinion $opinion)
    {
        $this->opinions->removeElement($opinion);
    }

    /**
     * Get opinions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpinions()
    {
        return $this->opinions;
    }

    /**
     * Set isBanned
     *
     * @param boolean $isBanned
     *
     * @return User
     */
    public function setIsBanned($isBanned)
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    /**
     * Get isBanned
     *
     * @return boolean
     */
    public function getIsBanned()
    {
        return $this->isBanned;
    }

    /**
     * Set accountTypeId
     *
     * @param integer $accountTypeId
     *
     * @return User
     */
    public function setAccountTypeId($accountTypeId)
    {
        $this->accountTypeId = $accountTypeId;

        return $this;
    }

    /**
     * Get accountTypeId
     *
     * @return integer
     */
    public function getAccountTypeId()
    {
        return $this->accountTypeId;
    }

    /**
     * Set isSubscriber
     *
     * @param boolean $isSubscriber
     *
     * @return User
     */
    public function setIsSubscriber($isSubscriber)
    {
        $this->isSubscriber = $isSubscriber;

        return $this;
    }

    /**
     * Get isSubscriber
     *
     * @return boolean
     */
    public function getIsSubscriber()
    {
        return $this->isSubscriber;
    }

    /**
     * Set cardCounter
     *
     * @param integer $cardCounter
     *
     * @return User
     */
    public function setCardCounter($cardCounter)
    {
        $this->cardCounter = $cardCounter;

        return $this;
    }

    /**
     * Get cardCounter
     *
     * @return integer
     */
    public function getCardCounter()
    {
        return $this->cardCounter;
    }

    /**
     * Set isNew
     *
     * @param boolean $isNew
     *
     * @return User
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew
     *
     * @return boolean
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set whois
     *
     * @param string $whois
     *
     * @return User
     */
    public function setWhois($whois)
    {
        $this->whois = $whois;

        return $this;
    }

    /**
     * Get whois
     *
     * @return string
     */
    public function getWhois()
    {
        return $this->whois;
    }
}
