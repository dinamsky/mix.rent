<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 */
class Country
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
     * @ORM\Column(name="header", type="string", length=255)
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="header_en", type="string", length=255)
     */
    private $headerEn;

    /**
     * @var string
     *
     * @ORM\Column(name="coords", type="string", length=255)
     */
    private $coords;

    /**
     * @var string
     *
     * @ORM\Column(name="iso2", type="string", length=255)
     */
    private $iso2;

    /**
     * @var string
     *
     * @ORM\Column(name="iso3", type="string", length=255)
     */
    private $iso3;


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
     * @return Country
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
     * Set headerEn
     *
     * @param string $headerEn
     *
     * @return Country
     */
    public function setHeaderEn($headerEn)
    {
        $this->headerEn = $headerEn;

        return $this;
    }

    /**
     * Get headerEn
     *
     * @return string
     */
    public function getHeaderEn()
    {
        return $this->headerEn;
    }

    /**
     * Set coords
     *
     * @param string $coords
     *
     * @return Country
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
     * Set iso2
     *
     * @param string $iso2
     *
     * @return Country
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;

        return $this;
    }

    /**
     * Get iso2
     *
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * Set iso3
     *
     * @param string $iso3
     *
     * @return Country
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;

        return $this;
    }

    /**
     * Get iso3
     *
     * @return string
     */
    public function getIso3()
    {
        return $this->iso3;
    }
}

