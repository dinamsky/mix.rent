<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tokens
 *
 * @ORM\Table(name="tokens")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TokensRepository")
 */
class Tokens
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
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="token_name", type="string", length=255)
     */
    private $tokenName;

    /**
     * @var string
     *
     * @ORM\Column(name="server_hash", type="string", length=255)
     */
    private $serverHash;

    /**
     * @var string
     *
     * @ORM\Column(name="client_hash", type="string", length=255)
     */
    private $clientHash;


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
     * @return Tokens
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
     * Set tokenName
     *
     * @param string $tokenName
     *
     * @return Tokens
     */
    public function setTokenName($tokenName)
    {
        $this->tokenName = $tokenName;

        return $this;
    }

    /**
     * Get tokenName
     *
     * @return string
     */
    public function getTokenName()
    {
        return $this->tokenName;
    }

    /**
     * Set serverHash
     *
     * @param string $serverHash
     *
     * @return Tokens
     */
    public function setServerHash($serverHash)
    {
        $this->serverHash = $serverHash;

        return $this;
    }

    /**
     * Get serverHash
     *
     * @return string
     */
    public function getServerHash()
    {
        return $this->serverHash;
    }

    /**
     * Set clientHash
     *
     * @param string $clientHash
     *
     * @return Tokens
     */
    public function setClientHash($clientHash)
    {
        $this->clientHash = $clientHash;

        return $this;
    }

    /**
     * Get clientHash
     *
     * @return string
     */
    public function getClientHash()
    {
        return $this->clientHash;
    }
}

