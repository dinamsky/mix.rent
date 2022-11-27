<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SettingsRepository")
 */
class Settings
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
     * @ORM\Column(name="s_key", type="string", length=255)
     */
    private $sKey;

    /**
     * @var string
     *
     * @ORM\Column(name="s_value", type="text")
     */
    private $sValue;


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
     * Set sKey
     *
     * @param string $sKey
     *
     * @return Settings
     */
    public function setSKey($sKey)
    {
        $this->sKey = $sKey;

        return $this;
    }

    /**
     * Get sKey
     *
     * @return string
     */
    public function getSKey()
    {
        return $this->sKey;
    }

    /**
     * Set sValue
     *
     * @param string $sValue
     *
     * @return Settings
     */
    public function setSValue($sValue)
    {
        $this->sValue = $sValue;

        return $this;
    }

    /**
     * Get sValue
     *
     * @return string
     */
    public function getSValue()
    {
        return $this->sValue;
    }
}

