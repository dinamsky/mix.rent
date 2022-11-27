<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Card
 *
 * @ORM\Table(name="card")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardRepository")
 */
class Card
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
     * @ORM\Column(name="header", type="string", length=255, nullable=true)
     */
    private $header = null;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255, nullable=true)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="coords", type="string", length=255, nullable=true)
     */
    private $coords = null;

    /**
     * @var string
     *
     * @ORM\Column(name="street_view", type="string", length=255, nullable=true)
     */
    private $streetView = null;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255, nullable=true)
     */
    private $video = null;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content = null;

    /**
     * @var string
     *
     * @ORM\Column(name="content_en", type="text", nullable=true)
     */
    private $contentEn = null;

    /**
     * @var string
     *
     * @ORM\Column(name="content_ru", type="text", nullable=true)
     */
    private $contentRu = null;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address = null;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="cards")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @var int
     *
     * @ORM\Column(name="city_id", type="bigint", nullable=true)
     */
    private $cityId = null;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_year", type="integer", nullable=true)
     */
    private $prodYear = null;

    /**
     * @var int
     *
     * @ORM\Column(name="general_type_id", type="integer", nullable=true)
     */
    private $generalTypeId = null;


    /**
     * @ORM\ManyToOne(targetEntity="GeneralType", inversedBy="cards")
     * @ORM\JoinColumn(name="general_type_id", referencedColumnName="id")
     */
    private $generalType;

    /**
     * @var int
     *
     * @ORM\Column(name="condition_id", type="integer", nullable=true)
     */
    private $conditionId = null;

    /**
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumn(name="condition_id", referencedColumnName="id")
     */
    private $condition;

    /**
     * @var int
     *
     * @ORM\Column(name="service_type_id", type="integer", nullable=true)
     */
    private $serviceTypeId = null;


    /**
     * @ORM\ManyToOne(targetEntity="MarkBundle\Entity\CarModel")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     */
    private $markModel;

    /**
     * @var int
     *
     * @ORM\Column(name="model_id", type="integer", nullable=true)
     */
    private $modelId = null;

    /**
     * @var int
     *
     * @ORM\Column(name="color_id", type="integer", nullable=true)
     */
    private $colorId = null;

    /**
     * @ORM\ManyToOne(targetEntity="Color")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     */
    private $color;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="bigint", nullable=true)
     */
    private $userId = null;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="cards")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="admin_id", type="integer", nullable=true, options={"default" : 1})
     */
    private $adminId = 1;


     /**
     * @var int
     *
     * @ORM\Column(name="delivery_status", type="integer", nullable=true, options={"default" : 2})
     */
    private $deliveryStatus = 2;

    /**
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\Admin", inversedBy="cards")
     * @ORM\JoinColumn(name="admin_id", referencedColumnName="id")
     */
    private $admin;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive = 1;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_top", type="boolean", nullable=true)
     */
    private $isTop = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_best", type="boolean", nullable=true)
     */
    private $isBest = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_messages", type="boolean", nullable=true)
     */
    private $isMessages = 1;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_create", type="datetime", nullable=true)
     */
    private $dateCreate = null;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_expiry", type="datetime", nullable=true)
     */
    private $dateExpiry = null;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_rent_start", type="datetime", nullable=true)
     */
    private $dateRentStart = null;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_rent_finish", type="datetime", nullable=true)
     */
    private $dateRentFinish = null;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_tariff_start", type="datetime", nullable=true)
     */
    private $dateTariffStart = null;

    /**
     * @var int
     *
     * @ORM\Column(name="views", type="integer", nullable=true)
     */
    private $views = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="likes", type="integer")
     */
    private $likes = 0;

    /**
     * @ORM\OneToMany(targetEntity="FieldInteger", mappedBy="card", cascade={"remove"}, orphanRemoval=true)
     */
    private $fieldIntegers;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CardFeature", mappedBy="card", cascade={"remove"}, orphanRemoval=true)
     */
    private $cardFeatures;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Foto", mappedBy="card", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"isMain" = "DESC"})
     */
    private $fotos;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CardPrice", mappedBy="card", cascade={"remove"}, orphanRemoval=true)
     */
    private $cardPrices;


    /**
     * @var int
     *
     * @ORM\Column(name="tariff_id", type="integer", nullable=true, options={"default" : 1})
     */
    private $tariffId = 1;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tariff")
     * @ORM\JoinColumn(name="tariff_id", referencedColumnName="id")
     */
    private $tariff;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UserOrder", mappedBy="card", cascade={"remove"}, orphanRemoval=true)
     */
    private $userOrders;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="card", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"dateCreate" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Opinion", mappedBy="card", cascade={"remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"dateCreate" = "DESC"})
     */
    private $opinions;

    public function __construct() {
        $this->fieldIntegers = new ArrayCollection();
        $this->cardFeatures = new ArrayCollection();
        $this->fotos = new ArrayCollection();
        $this->cardPrices = new ArrayCollection();
        $this->userOrders = new ArrayCollection();
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
     * Set header
     *
     * @param string $header
     *
     * @return Card
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
     * Set content
     *
     * @param string $content
     *
     * @return Card
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
     * Set prodYear
     *
     * @param integer $prodYear
     *
     * @return Card
     */
    public function setProdYear($prodYear)
    {
        $this->prodYear = $prodYear;

        return $this;
    }

    /**
     * Get prodYear
     *
     * @return int
     */
    public function getProdYear()
    {
        return $this->prodYear;
    }



    /**
     * Set conditionId
     *
     * @param integer $conditionId
     *
     * @return Card
     */
    public function setConditionId($conditionId)
    {
        $this->conditionId = $conditionId;

        return $this;
    }

    /**
     * Get conditionId
     *
     * @return int
     */
    public function getConditionId()
    {
        return $this->conditionId;
    }

    /**
     * Set serviceTypeId
     *
     * @param integer $serviceTypeId
     *
     * @return Card
     */
    public function setServiceTypeId($serviceTypeId)
    {
        $this->serviceTypeId = $serviceTypeId;

        return $this;
    }

    /**
     * Get serviceTypeId
     *
     * @return int
     */
    public function getServiceTypeId()
    {
        return $this->serviceTypeId;
    }

    /**
     * Set modelId
     *
     * @param integer $modelId
     *
     * @return Card
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * Get modelId
     *
     * @return int
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * Set colorId
     *
     * @param integer $colorId
     *
     * @return Card
     */
    public function setColorId($colorId)
    {
        $this->colorId = $colorId;

        return $this;
    }

    /**
     * Get colorId
     *
     * @return int
     */
    public function getColorId()
    {
        return $this->colorId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Card
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Card
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Card
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
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Card
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set dateExpiry
     *
     * @param \DateTime $dateExpiry
     *
     * @return Card
     */
    public function setDateExpiry($dateExpiry)
    {
        $this->dateExpiry = $dateExpiry;

        return $this;
    }

    /**
     * Get dateExpiry
     *
     * @return \DateTime
     */
    public function getDateExpiry()
    {
        return $this->dateExpiry;
    }

    /**
     * Set views
     *
     * @param integer $views
     *
     * @return Card
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set generalTypeId
     *
     * @param integer $generalTypeId
     *
     * @return Card
     */
    public function setGeneralTypeId($generalTypeId)
    {
        $this->generalTypeId = $generalTypeId;

        return $this;
    }

    /**
     * Get generalTypeId
     *
     * @return integer
     */
    public function getGeneralTypeId()
    {
        return $this->generalTypeId;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return Card
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set condition
     *
     * @param \AppBundle\Entity\State $condition
     *
     * @return Card
     */
    public function setCondition(\AppBundle\Entity\State $condition = null)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return \AppBundle\Entity\State
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Set color
     *
     * @param \AppBundle\Entity\Color $color
     *
     * @return Card
     */
    public function setColor(\AppBundle\Entity\Color $color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return \AppBundle\Entity\Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set generalType
     *
     * @param \AppBundle\Entity\GeneralType $generalType
     *
     * @return Card
     */
    public function setGeneralType(\AppBundle\Entity\GeneralType $generalType = null)
    {
        $this->generalType = $generalType;

        return $this;
    }

    /**
     * Get generalType
     *
     * @return \AppBundle\Entity\GeneralType
     */
    public function getGeneralType()
    {
        return $this->generalType;
    }

    /**
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     *
     * @return Card
     */
    public function setCity(\AppBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set markModel
     *
     * @param \MarkBundle\Entity\CarModel $markModel
     *
     * @return Card
     */
    public function setMarkModel(\MarkBundle\Entity\CarModel $markModel = null)
    {
        $this->markModel = $markModel;

        return $this;
    }

    /**
     * Get markModel
     *
     * @return \MarkBundle\Entity\CarModel
     */
    public function getMarkModel()
    {
        return $this->markModel;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Card
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

    /**
     * Add fieldInteger
     *
     * @param \AppBundle\Entity\FieldInteger $fieldInteger
     *
     * @return Card
     */
    public function addFieldInteger(\AppBundle\Entity\FieldInteger $fieldInteger)
    {
        $this->fieldIntegers[] = $fieldInteger;

        return $this;
    }

    /**
     * Remove fieldInteger
     *
     * @param \AppBundle\Entity\FieldInteger $fieldInteger
     */
    public function removeFieldInteger(\AppBundle\Entity\FieldInteger $fieldInteger)
    {
        $this->fieldIntegers->removeElement($fieldInteger);
    }

    /**
     * Get fieldIntegers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFieldIntegers()
    {
        return $this->fieldIntegers;
    }







    /**
     * Add cardFeature
     *
     * @param \AppBundle\Entity\CardFeature $cardFeature
     *
     * @return Card
     */
    public function addCardFeature(\AppBundle\Entity\CardFeature $cardFeature)
    {
        $this->cardFeatures[] = $cardFeature;

        return $this;
    }

    /**
     * Remove cardFeature
     *
     * @param \AppBundle\Entity\CardFeature $cardFeature
     */
    public function removeCardFeature(\AppBundle\Entity\CardFeature $cardFeature)
    {
        $this->cardFeatures->removeElement($cardFeature);
    }

    /**
     * Get cardFeatures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCardFeatures()
    {
        return $this->cardFeatures;
    }

    /**
     * Add foto
     *
     * @param \AppBundle\Entity\Foto $foto
     *
     * @return Card
     */
    public function addFoto(\AppBundle\Entity\Foto $foto)
    {
        $this->fotos[] = $foto;

        return $this;
    }

    /**
     * Remove foto
     *
     * @param \AppBundle\Entity\Foto $foto
     */
    public function removeFoto(\AppBundle\Entity\Foto $foto)
    {
        $this->fotos->removeElement($foto);
    }

    /**
     * Get fotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFotos()
    {
        return $this->fotos;
    }

    /**
     * Add cardPrice
     *
     * @param \AppBundle\Entity\CardPrice $cardPrice
     *
     * @return Card
     */
    public function addCardPrice(\AppBundle\Entity\CardPrice $cardPrice)
    {
        $this->cardPrices[] = $cardPrice;

        return $this;
    }

    /**
     * Remove cardPrice
     *
     * @param \AppBundle\Entity\CardPrice $cardPrice
     */
    public function removeCardPrice(\AppBundle\Entity\CardPrice $cardPrice)
    {
        $this->cardPrices->removeElement($cardPrice);
    }

    /**
     * Get cardPrices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCardPrices()
    {
        return $this->cardPrices;
    }

    /**
     * Set coords
     *
     * @param string $coords
     *
     * @return Card
     */
    public function setCoords($coords)
    {
        $this->coords = $coords;

        return $this;
    }

    /**
     * Get coords
     *
     * @return string
     */
    public function getCoords()
    {
        return $this->coords;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Card
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set isTop
     *
     * @param boolean $isTop
     *
     * @return Card
     */
    public function setIsTop($isTop)
    {
        $this->isTop = $isTop;

        return $this;
    }

    /**
     * Get isTop
     *
     * @return boolean
     */
    public function getIsTop()
    {
        return $this->isTop;
    }

    /**
     * Set streetView
     *
     * @param string $streetView
     *
     * @return Card
     */
    public function setStreetView($streetView)
    {
        $this->streetView = $streetView;

        return $this;
    }

    /**
     * Get streetView
     *
     * @return string
     */
    public function getStreetView()
    {
        return $this->streetView;
    }

    /**
     * Set video
     *
     * @param string $video
     *
     * @return Card
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set tariffId
     *
     * @param integer $tariffId
     *
     * @return Card
     */
    public function setTariffId($tariffId)
    {
        $this->tariffId = $tariffId;

        return $this;
    }

    /**
     * Get tariffId
     *
     * @return integer
     */
    public function getTariffId()
    {
        return $this->tariffId;
    }

    /**
     * Set tariff
     *
     * @param \AppBundle\Entity\Tariff $tariff
     *
     * @return Card
     */
    public function setTariff(\AppBundle\Entity\Tariff $tariff = null)
    {
        $this->tariff = $tariff;

        return $this;
    }

    /**
     * Get tariff
     *
     * @return \AppBundle\Entity\Tariff
     */
    public function getTariff()
    {
        return $this->tariff;
    }

    /**
     * Set dateTariffStart
     *
     * @param \DateTime $dateTariffStart
     *
     * @return Card
     */
    public function setDateTariffStart($dateTariffStart)
    {
        $this->dateTariffStart = $dateTariffStart;

        return $this;
    }

    /**
     * Get dateTariffStart
     *
     * @return \DateTime
     */
    public function getDateTariffStart()
    {
        return $this->dateTariffStart;
    }

    /**
     * Add userOrder
     *
     * @param \UserBundle\Entity\UserOrder $userOrder
     *
     * @return Card
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
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Card
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
     * @return Card
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
     * @return Card
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
     * Set likes
     *
     * @param integer $likes
     *
     * @return Card
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes
     *
     * @return integer
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Add opinion
     *
     * @param \AppBundle\Entity\Opinion $opinion
     *
     * @return Card
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
     * Set deliveryStatus
     *
     * @param integer $deliveryStatus
     *
     * @return Card
     */
    public function setDeliveryStatus($deliveryStatus)
    {
        $this->deliveryStatus = $deliveryStatus;

        return $this;
    }

    /**
     * Get deliveryStatus
     *
     * @return integer
     */
    public function getDeliveryStatus()
    {
        return $this->deliveryStatus;
    }

    /**
     * Set dateRentStart
     *
     * @param \DateTime $dateRentStart
     *
     * @return Card
     */
    public function setDateRentStart($dateRentStart)
    {
        $this->dateRentStart = $dateRentStart;

        return $this;
    }

    /**
     * Get dateRentStart
     *
     * @return \DateTime
     */
    public function getDateRentStart()
    {
        return $this->dateRentStart;
    }

    /**
     * Set dateRentFinish
     *
     * @param \DateTime $dateRentFinish
     *
     * @return Card
     */
    public function setDateRentFinish($dateRentFinish)
    {
        $this->dateRentFinish = $dateRentFinish;

        return $this;
    }

    /**
     * Get dateRentFinish
     *
     * @return \DateTime
     */
    public function getDateRentFinish()
    {
        return $this->dateRentFinish;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Card
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set isMessages
     *
     * @param boolean $isMessages
     *
     * @return Card
     */
    public function setIsMessages($isMessages)
    {
        $this->isMessages = $isMessages;

        return $this;
    }

    /**
     * Get isMessages
     *
     * @return boolean
     */
    public function getIsMessages()
    {
        return $this->isMessages;
    }

    /**
     * Set isBest
     *
     * @param boolean $isBest
     *
     * @return Card
     */
    public function setIsBest($isBest)
    {
        $this->isBest = $isBest;

        return $this;
    }

    /**
     * Get isBest
     *
     * @return boolean
     */
    public function getIsBest()
    {
        return $this->isBest;
    }

    /**
     * Set contentEn
     *
     * @param string $contentEn
     *
     * @return Card
     */
    public function setContentEn($contentEn)
    {
        $this->contentEn = $contentEn;

        return $this;
    }

    /**
     * Get contentEn
     *
     * @return string
     */
    public function getContentEn()
    {
        return $this->contentEn;
    }

    /**
     * Set contentRu
     *
     * @param string $contentRu
     *
     * @return Card
     */
    public function setContentRu($contentRu)
    {
        $this->contentRu = $contentRu;

        return $this;
    }

    /**
     * Get contentRu
     *
     * @return string
     */
    public function getContentRu()
    {
        return $this->contentRu;
    }
}
