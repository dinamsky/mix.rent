<?php

namespace AppBundle\Menu;


use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

use Memcached;

class MyCacheService extends Controller
{
    private  $driver;
    private  $exp;
    private  $cacheid;
    private  $selfkey;
    private  $data;
    private  $env;

    function __construct($env) {
        $this->env = $env;
        if($this->env == 'test'){
            $this->selfkey = 'GLOBAL-KEY';
            $this->data = [];
            return TRUE;
        } else {
            $GLOBAL_ID = 'GLOBAL-KEY';
            $DSN = 'localhost:11211';
            $GLOBAL_EXPIRE = 3600;
            $this->cacheid = $GLOBAL_ID;
            $this->exp = $GLOBAL_EXPIRE;
            $this->driver = new \Memcached();
            $DSNdata = [];
            preg_match('/(?<host>.*):(?<port>\d+)/', $DSN, $DSNdata);
            $this->driver->addServer($DSNdata['host'], (float)$DSNdata['port']);

            return TRUE;
        }
    }

    private function _key($key) {
        if ($this->env == 'test') return $this->selfkey . sha1($key);
        else return $this->cacheid . sha1($key);
    }

    public function cset($name, $value, $ttl = FALSE) {
        if ($this->env == 'test'){
            $this->data[$this->_key($name)] = json_encode($value, JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
            return TRUE;
        } else {
            $result = $this->driver->replace($this->_key($name), json_encode($value, JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE), ($ttl) ? $ttl : $this->exp);
            if ($result == FALSE) {
                $result = $this->driver->set($this->_key($name), json_encode($value, JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE), ($ttl) ? $ttl : $this->exp);
            }

            return $result;
        }

    }

    public function cget($name) {
        if ($this->env == 'test'){
            if (isset($this->data[$this->_key($name)])) {
                return json_decode($this->data[$this->_key($name)], TRUE);
            }
            return FALSE;
        } else {
            $result = $this->driver->get($this->_key($name));
            if ($result) {
                return json_decode($result, TRUE);
            }
            return FALSE;
        }

    }

    public function check($name) {
        if ($this->env == 'test'){
            if (isset($this->data[$this->_key($name)])) {
                return TRUE;
            }
            return FALSE;
        } else {
            $result = $this->driver->get($this->_key($name));
            if ($result) {
                return TRUE;
            }
            return FALSE;
        }

    }

    public function delete($name) {
        if ($this->env == 'test') {
            unset($this->data[$this->_key($name)]);
            return TRUE;
        } else return $this->driver->delete($this->_key($name));
    }
}