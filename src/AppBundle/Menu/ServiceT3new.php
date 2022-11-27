<?php

namespace AppBundle\Menu;

use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Card;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface as si;

class ServiceT3new extends Controller
{
    private $em;

    public function __construct(em $em, si $session  )
    {
        $this->em = $em;
        $this->sess = $session;

    }

    public function getTop3()
    {

        $cityId = $this->sess->get('city')->getId();
        $query = $this->em->createQuery('SELECT c FROM AppBundle:Card c JOIN c.tariff t WHERE c.cityId = ?1 ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC');
        $query->setParameter(1, $cityId);
        $query->setMaxResults(3);
        if(count($query->getResult())<3) {

            $query = $this->em->createQuery('SELECT c FROM AppBundle:Card c JOIN c.tariff t WHERE c.cityId < 1260 ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC');
            $query->setMaxResults(3);
        }

        return $query->getResult();



    }

    public function getNew()
    {

        $cityId = $this->sess->get('city')->getId();
        $query = $this->em->createQuery('SELECT c.id FROM AppBundle:Card c WHERE c.cityId > 1260 ORDER BY c.dateCreate DESC');
        //$query->setParameter(1, $cityId);
        $query->setMaxResults(3);
        if(count($query->getResult())<3) {
            $query = $this->em->createQuery('SELECT c.id FROM AppBundle:Card c WHERE c.cityId > 1260 ORDER BY c.dateCreate DESC');
            $query->setMaxResults(3);
        }

        foreach ($query->getResult() as $cars_id) $cars_ids[] = $cars_id['id'];
        $dql = 'SELECT c,f,p,g,m FROM AppBundle:Card c LEFT JOIN c.fotos f LEFT JOIN c.cardPrices p LEFT JOIN c.city g LEFT JOIN c.markModel m WHERE c.id IN ('.implode(",",$cars_ids).')';
        $query = $this->em->createQuery($dql);
        return $query->getResult();
    }

    public function getNew4()
    {

        $cityId = $this->sess->get('city')->getId();
        $query = $this->em->createQuery('SELECT c.id FROM AppBundle:Card c WHERE c.cityId > 1260 ORDER BY c.dateCreate DESC');
        //$query->setParameter(1, $cityId);
        $query->setMaxResults(4);
        if(count($query->getResult())<4) {
            $query = $this->em->createQuery('SELECT c.id FROM AppBundle:Card c WHERE c.cityId > 1260 ORDER BY c.dateCreate DESC');
            $query->setMaxResults(3);
        }

        foreach ($query->getResult() as $cars_id) $cars_ids[] = $cars_id['id'];
        $dql = 'SELECT c,f,p,g,m FROM AppBundle:Card c LEFT JOIN c.fotos f LEFT JOIN c.cardPrices p LEFT JOIN c.city g LEFT JOIN c.markModel m WHERE c.id IN ('.implode(",",$cars_ids).')';
        $query = $this->em->createQuery($dql);
        return $query->getResult();
    }


    public function popularCities()
    {
        $country = $this->sess->get('city')->getCountry();
        $query = $this->em->createQuery('SELECT c FROM AppBundle:City c WHERE c.total > 0 AND c.country = ?1 AND c.parentId IS NOT NULL ORDER BY c.header ASC');
        $query->setParameter(1, $country);
        $query->setMaxResults(100);
        return $query->getResult();
    }

    public function getGt()
    {
        $query = $this->em->createQuery('SELECT g FROM AppBundle:GeneralType g ORDER BY g.weight ASC');
        return $query->getResult();
    }

    public function getUnread()
    {
        $query = $this->em->createQuery('SELECT COUNT(m) as total FROM UserBundle:Message m WHERE m.toUserId = ?1 AND m.isRead = 0');
        $query->setParameter(1, $this->sess->get('logged_user')->getId());
        $unread = $query->getResult();
        return $unread[0]['total'];
    }

    public function getNotify()
    {
        $query = $this->em->createQuery('SELECT n FROM AppBundle:Notify n WHERE n.userId = ?1');
        if($this->sess->get('logged_user') != NULL) {
            $query->setParameter(1, $this->sess->get('logged_user')->getId());
            return $query->getResult();
        } else return array();
    }
}