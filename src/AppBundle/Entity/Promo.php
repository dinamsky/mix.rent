<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promo
 *
 * @ORM\Table(name="promo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PromoRepository")
 */
class Promo
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
     * @var int
     *
     * @ORM\Column(name="p_id", type="integer")
     */
    private $pId;

    /**
     * @var string
     *
     * @ORM\Column(name="p_key", type="string", length=255)
     */
    private $pKey;

    /**
     * @var string
     *
     * @ORM\Column(name="p_value", type="text")
     */
    private $pValue;

    /**
     * @var string
     *
     * @ORM\Column(name="p_value_en", type="text")
     */
    private $pValueEn;

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
     * Set pId
     *
     * @param integer $pId
     *
     * @return Promo
     */
    public function setPId($pId)
    {
        $this->pId = $pId;

        return $this;
    }

    /**
     * Get pId
     *
     * @return int
     */
    public function getPId()
    {
        return $this->pId;
    }

    /**
     * Set pKey
     *
     * @param string $pKey
     *
     * @return Promo
     */
    public function setPKey($pKey)
    {
        $this->pKey = $pKey;

        return $this;
    }

    /**
     * Get pKey
     *
     * @return string
     */
    public function getPKey()
    {
        return $this->pKey;
    }

    /**
     * Set pValue
     *
     * @param string $pValue
     *
     * @return Promo
     */
    public function setPValue($pValue)
    {
        $this->pValue = $pValue;

        return $this;
    }

    /**
     * Get pValue
     *
     * @return string
     */
    public function getPValue()
    {
        return $this->pValue;
    }

    /**
     * Set pValueEn
     *
     * @param string $pValueEn
     *
     * @return Promo
     */
    public function setPValueEn($pValueEn)
    {
        $this->pValueEn = $pValueEn;

        return $this;
    }

    /**
     * Get pValueEn
     *
     * @return string
     */
    public function getPValueEn()
    {
        return $this->pValueEn;
    }
}
