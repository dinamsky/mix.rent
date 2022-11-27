<?php

namespace UserBundle\Security;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CookieMaster extends Controller
{

    protected $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    public function setHash($user_id)
    {
        $array = [
            $_SERVER['HTTP_USER_AGENT'],
            $this->secret,
            $user_id
        ];
        $hash = sha1(implode("",$array));
        return $hash;
    }
}