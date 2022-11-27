<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Seo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\UserInfo;
use UserBundle\Security\Password;
use UserBundle\Entity\User;
use AdminBundle\Entity\Admin;

class StatController extends Controller
{

    var $monthes_names = [
        '01' => 'январь',
        '02' => 'февраль',
        '03' => 'март',
        '04' => 'апрель',
        '05' => 'май',
        '06' => 'июнь',
        '07' => 'июль',
        '08' => 'август',
        '09' => 'сентябрь',
        '10' => 'октябрь',
        '11' => 'ноябрь',
        '12' => 'декабрь',
    ];

    /**
     * @Route("/adminStat", name="adminStat")
     */
    public function indexStatAction()
    {

        if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_enter_form.html.twig');
        else {
            return $this->render('AdminBundle:stat:admin_stat_main.html.twig');
        }
    }


    /**
     * @Route("/adminStatCityQuantity", name="adminStatCityQuantity")
     */
    public function cityStatAction(EntityManagerInterface $em)
    {

        if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_enter_form.html.twig');
        else {

            $query = $em->createQuery('SELECT s,c FROM AppBundle:City s LEFT JOIN s.cards c WHERE s.parentId IS NOT NULL');
            $cities = $query->getResult();
            foreach($cities as $c){
                $res[$c->getId()] = count($c->getCards());
                $cit[$c->getId()] = $c;
            }
            arsort($res);

            return $this->render('AdminBundle:stat:admin_stat_city.html.twig',[
                'cities' => $cit,
                'sort' => $res,
            ]);
        }
    }

    /**
     * @Route("/adminStatUserReg", name="adminStatUserReg")
     */
    public function userStatRegAction(EntityManagerInterface $em)
    {

        if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_enter_form.html.twig');
        else {

            $query = $em->createQuery('SELECT u.dateCreate FROM UserBundle:User u');
            $dates = $query->getScalarResult();
            foreach($dates as $dt){
                $xdt = explode(" ",$dt['dateCreate']);
                $xd = explode("-",$xdt[0]);
                if(!isset($res[$xd[0]][$xd[1]][$xd[2]])) $res[$xd[0]][$xd[1]][$xd[2]] = 0;
                $res[$xd[0]][$xd[1]][$xd[2]] = $res[$xd[0]][$xd[1]][$xd[2]] + 1;
            }

            $query = $em->createQuery('SELECT c.dateCreate FROM AppBundle:Card c');
            $dates = $query->getScalarResult();
            foreach($dates as $dt){
                $xdt = explode(" ",$dt['dateCreate']);
                $xd = explode("-",$xdt[0]);
                if(!isset($res2[$xd[0]][$xd[1]][$xd[2]])) $res2[$xd[0]][$xd[1]][$xd[2]] = 0;
                $res2[$xd[0]][$xd[1]][$xd[2]] = $res2[$xd[0]][$xd[1]][$xd[2]] + 1;
            }

            return $this->render('AdminBundle:stat:admin_stat_user_reg.html.twig',[
                'dates' => $res,
                'cards' => $res2,
                'monthes_nums' => array_keys($this->monthes_names),
                'monthes_names' => $this->monthes_names
            ]);
        }
    }
}
