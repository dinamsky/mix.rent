<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FormOrder
 *
 * @ORM\Table(name="form_order")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\FormOrderRepository")
 */
class FormOrder
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="messages", type="text")
     */
    private $messages='';

    /**
     * @var string
     *
     * @ORM\Column(name="transport", type="string", length=255)
     */
    private $transport;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_in", type="date", nullable=true)
     */
    private $dateIn = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_out", type="date", nullable=true)
     */
    private $dateOut = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_pincode_start", type="date", nullable=true)
     */
    private $datePincodeStart = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_owner_button_finish", type="date", nullable=true)
     */
    private $dateOwnerButtonFinish = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_renter_button_finish", type="date", nullable=true)
     */
    private $dateRenterButtonFinish = null;

    /**
     * @var string
     *
     * @ORM\Column(name="city_in", type="string", length=255)
     */
    private $cityIn;

    /**
     * @var string
     *
     * @ORM\Column(name="city_out", type="string", length=255)
     */
    private $cityOut;

    /**
     * @var string
     *
     * @ORM\Column(name="form_type", type="string", length=255)
     */
    private $formType;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_create", type="datetime", nullable=true)
     */
    private $dateCreate = null;

    /**
     * @var int
     *
     * @ORM\Column(name="card_id", type="integer", nullable=true)
     */
    private $cardId = null;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId = null;

    /**
     * @var int
     *
     * @ORM\Column(name="renter_id", type="integer", nullable=true)
     */
    private $renterId = null;

    /**
     * @var int
     *
     * @ORM\Column(name="renter_rating", type="integer")
     */
    private $renterRating = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="owner_rating", type="integer")
     */
    private $ownerRating = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="deposit", type="integer")
     */
    private $deposit = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="service", type="integer")
     */
    private $service = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="fio_renter", type="string", length=255)
     */
    private $fioRenter = '';

    /**
     * @var string
     *
     * @ORM\Column(name="passport4", type="string", length=255)
     */
    private $passport4 = '';

    /**
     * @var string
     *
     * @ORM\Column(name="driving_license4", type="string", length=255)
     */
    private $drivingLicense4 = '';

    /**
     * @var string
     *
     * @ORM\Column(name="pincode_for_owner", type="string", length=255)
     */
    private $pincodeForOwner = '';

    /**
     * @var string
     *
     * @ORM\Column(name="pincode_for_renter", type="string", length=255)
     */
    private $pincodeForRenter = '';

    /**
     * @var string
     *
     * @ORM\Column(name="renter_status", type="string", length=255)
     */
    private $renterStatus = 'ready';

    /**
     * @var string
     *
     * @ORM\Column(name="owner_status", type="string", length=255)
     */
    private $ownerStatus = 'ready';

    /**
     * @var string
     *
     * @ORM\Column(name="owner_result_details", type="string", length=255)
     */
    private $ownerResultDetails = '';

    /**
     * @var string
     *
     * @ORM\Column(name="renter_result_details", type="string", length=255)
     */
    private $renterResultDetails = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="is_new", type="boolean")
     */
    private $isNew = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active_owner", type="boolean")
     */
    private $isActiveOwner = 0;


    /**
     * @var bool
     *
     * @ORM\Column(name="is_active_renter", type="boolean")
     */
    private $isActiveRenter = 0;

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
     * Set name
     *
     * @param string $name
     *
     * @return FormOrder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return FormOrder
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
     * Set phone
     *
     * @param string $phone
     *
     * @return FormOrder
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return FormOrder
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set transport
     *
     * @param string $transport
     *
     * @return FormOrder
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set dateIn
     *

     *
     * @return FormOrder
     */
    public function setDateIn($dateIn)
    {
        $this->dateIn = $dateIn;

        return $this;
    }

    /**
     * Get dateIn
     *
     * @return \DateTime
     */
    public function getDateIn()
    {
        return $this->dateIn;
    }

    /**
     * Set dateOut
     *

     *
     * @return FormOrder
     */
    public function setDateOut($dateOut)
    {
        $this->dateOut = $dateOut;

        return $this;
    }

    /**
     * Get dateOut
     *
     * @return \DateTime
     */
    public function getDateOut()
    {
        return $this->dateOut;
    }

    /**
     * Set cityIn
     *
     * @param string $cityIn
     *
     * @return FormOrder
     */
    public function setCityIn($cityIn)
    {
        $this->cityIn = $cityIn;

        return $this;
    }

    /**
     * Get cityIn
     *
     * @return string
     */
    public function getCityIn()
    {
        return $this->cityIn;
    }

    /**
     * Set cityOut
     *
     * @param string $cityOut
     *
     * @return FormOrder
     */
    public function setCityOut($cityOut)
    {
        $this->cityOut = $cityOut;

        return $this;
    }

    /**
     * Get cityOut
     *
     * @return string
     */
    public function getCityOut()
    {
        return $this->cityOut;
    }

    /**
     * Set formType
     *
     * @param string $formType
     *
     * @return FormOrder
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;

        return $this;
    }

    /**
     * Get formType
     *
     * @return string
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * Set dateCreate
     *
     * @param string $dateCreate
     *
     * @return FormOrder
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return string
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set cardId
     *
     * @param integer $cardId
     *
     * @return FormOrder
     */
    public function setCardId($cardId)
    {
        $this->cardId = $cardId;

        return $this;
    }

    /**
     * Get cardId
     *
     * @return int
     */
    public function getCardId()
    {
        return $this->cardId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return FormOrder
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
     * Set price
     *
     * @param integer $price
     *
     * @return FormOrder
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set deposit
     *
     * @param integer $deposit
     *
     * @return FormOrder
     */
    public function setDeposit($deposit)
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * Get deposit
     *
     * @return integer
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Set fioRenter
     *
     * @param string $fioRenter
     *
     * @return FormOrder
     */
    public function setFioRenter($fioRenter)
    {
        $this->fioRenter = $fioRenter;

        return $this;
    }

    /**
     * Get fioRenter
     *
     * @return string
     */
    public function getFioRenter()
    {
        return $this->fioRenter;
    }

    /**
     * Set passport4
     *
     * @param string $passport4
     *
     * @return FormOrder
     */
    public function setPassport4($passport4)
    {
        $this->passport4 = $passport4;

        return $this;
    }

    /**
     * Get passport4
     *
     * @return string
     */
    public function getPassport4()
    {
        return $this->passport4;
    }

    /**
     * Set drivingLicense4
     *
     * @param string $drivingLicense4
     *
     * @return FormOrder
     */
    public function setDrivingLicense4($drivingLicense4)
    {
        $this->drivingLicense4 = $drivingLicense4;

        return $this;
    }

    /**
     * Get drivingLicense4
     *
     * @return string
     */
    public function getDrivingLicense4()
    {
        return $this->drivingLicense4;
    }

    /**
     * Set pincodeForOwner
     *
     * @param string $pincodeForOwner
     *
     * @return FormOrder
     */
    public function setPincodeForOwner($pincodeForOwner)
    {
        $this->pincodeForOwner = $pincodeForOwner;

        return $this;
    }

    /**
     * Get pincodeForOwner
     *
     * @return string
     */
    public function getPincodeForOwner()
    {
        return $this->pincodeForOwner;
    }

    /**
     * Set pincodeForRenter
     *
     * @param string $pincodeForRenter
     *
     * @return FormOrder
     */
    public function setPincodeForRenter($pincodeForRenter)
    {
        $this->pincodeForRenter = $pincodeForRenter;

        return $this;
    }

    /**
     * Get pincodeForRenter
     *
     * @return string
     */
    public function getPincodeForRenter()
    {
        return $this->pincodeForRenter;
    }

    /**
     * Set renterStatus
     *
     * @param string $renterStatus
     *
     * @return FormOrder
     */
    public function setRenterStatus($renterStatus)
    {
        $this->renterStatus = $renterStatus;

        return $this;
    }

    /**
     * Get renterStatus
     *
     * @return string
     */
    public function getRenterStatus()
    {
        return $this->renterStatus;
    }

    /**
     * Set ownerStatus
     *
     * @param string $ownerStatus
     *
     * @return FormOrder
     */
    public function setOwnerStatus($ownerStatus)
    {
        $this->ownerStatus = $ownerStatus;

        return $this;
    }

    /**
     * Get ownerStatus
     *
     * @return string
     */
    public function getOwnerStatus()
    {
        return $this->ownerStatus;
    }

    /**
     * Set isNew
     *
     * @param boolean $isNew
     *
     * @return FormOrder
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
     * Set renterId
     *
     * @param integer $renterId
     *
     * @return FormOrder
     */
    public function setRenterId($renterId)
    {
        $this->renterId = $renterId;

        return $this;
    }

    /**
     * Get renterId
     *
     * @return integer
     */
    public function getRenterId()
    {
        return $this->renterId;
    }

    /**
     * Set datePincodeStart
     *
     * @param \DateTime $datePincodeStart
     *
     * @return FormOrder
     */
    public function setDatePincodeStart($datePincodeStart)
    {
        $this->datePincodeStart = $datePincodeStart;

        return $this;
    }

    /**
     * Get datePincodeStart
     *
     * @return \DateTime
     */
    public function getDatePincodeStart()
    {
        return $this->datePincodeStart;
    }

    /**
     * Set dateOwnerButtonFinish
     *
     * @param \DateTime $dateOwnerButtonFinish
     *
     * @return FormOrder
     */
    public function setDateOwnerButtonFinish($dateOwnerButtonFinish)
    {
        $this->dateOwnerButtonFinish = $dateOwnerButtonFinish;

        return $this;
    }

    /**
     * Get dateOwnerButtonFinish
     *
     * @return \DateTime
     */
    public function getDateOwnerButtonFinish()
    {
        return $this->dateOwnerButtonFinish;
    }

    /**
     * Set dateRenterButtonFinish
     *
     * @param \DateTime $dateRenterButtonFinish
     *
     * @return FormOrder
     */
    public function setDateRenterButtonFinish($dateRenterButtonFinish)
    {
        $this->dateRenterButtonFinish = $dateRenterButtonFinish;

        return $this;
    }

    /**
     * Get dateRenterButtonFinish
     *
     * @return \DateTime
     */
    public function getDateRenterButtonFinish()
    {
        return $this->dateRenterButtonFinish;
    }

    /**
     * Set renterRating
     *
     * @param integer $renterRating
     *
     * @return FormOrder
     */
    public function setRenterRating($renterRating)
    {
        $this->renterRating = $renterRating;

        return $this;
    }

    /**
     * Get renterRating
     *
     * @return integer
     */
    public function getRenterRating()
    {
        return $this->renterRating;
    }

    /**
     * Set ownerRating
     *
     * @param integer $ownerRating
     *
     * @return FormOrder
     */
    public function setOwnerRating($ownerRating)
    {
        $this->ownerRating = $ownerRating;

        return $this;
    }

    /**
     * Get ownerRating
     *
     * @return integer
     */
    public function getOwnerRating()
    {
        return $this->ownerRating;
    }

    /**
     * Set ownerResultDetails
     *
     * @param string $ownerResultDetails
     *
     * @return FormOrder
     */
    public function setOwnerResultDetails($ownerResultDetails)
    {
        $this->ownerResultDetails = $ownerResultDetails;

        return $this;
    }

    /**
     * Get ownerResultDetails
     *
     * @return string
     */
    public function getOwnerResultDetails()
    {
        return $this->ownerResultDetails;
    }

    /**
     * Set renterResultDetails
     *
     * @param string $renterResultDetails
     *
     * @return FormOrder
     */
    public function setRenterResultDetails($renterResultDetails)
    {
        $this->renterResultDetails = $renterResultDetails;

        return $this;
    }

    /**
     * Get renterResultDetails
     *
     * @return string
     */
    public function getRenterResultDetails()
    {
        return $this->renterResultDetails;
    }

    /**
     * Set messages
     *
     * @param string $messages
     *
     * @return FormOrder
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get messages
     *
     * @return string
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set isActiveNow
     *
     * @param boolean $isActiveNow
     *
     * @return FormOrder
     */
    public function setIsActiveNow($isActiveNow)
    {
        $this->isActiveNow = $isActiveNow;

        return $this;
    }

    /**
     * Get isActiveNow
     *
     * @return boolean
     */
    public function getIsActiveNow()
    {
        return $this->isActiveNow;
    }

    /**
     * Set isActiveOwner
     *
     * @param boolean $isActiveOwner
     *
     * @return FormOrder
     */
    public function setIsActiveOwner($isActiveOwner)
    {
        $this->isActiveOwner = $isActiveOwner;

        return $this;
    }

    /**
     * Get isActiveOwner
     *
     * @return boolean
     */
    public function getIsActiveOwner()
    {
        return $this->isActiveOwner;
    }

    /**
     * Set isActiveRenter
     *
     * @param boolean $isActiveRenter
     *
     * @return FormOrder
     */
    public function setIsActiveRenter($isActiveRenter)
    {
        $this->isActiveRenter = $isActiveRenter;

        return $this;
    }

    /**
     * Get isActiveRenter
     *
     * @return boolean
     */
    public function getIsActiveRenter()
    {
        return $this->isActiveRenter;
    }

    /**
     * Set service
     *
     * @param integer $service
     *
     * @return FormOrder
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return integer
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return FormOrder
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }
}
