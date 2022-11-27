<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Mark;
use AppBundle\Entity\SubField;
use AppBundle\Entity\Seo;
use AppBundle\Menu\MenuGeneralType;
use AppBundle\Menu\MenuCity;
use AppBundle\Menu\MenuMarkModel;
use AppBundle\Menu\ServiceStat;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as em;
use AppBundle\Entity\Card;
use AppBundle\Entity\CardField;
use AppBundle\Entity\City;
use AppBundle\Entity\GeneralType;
use AppBundle\SubFields\SubFieldUtils;
use Symfony\Component\HttpFoundation\Cookie;

class DefaultController extends Controller
{

    /**
     * @Route("/ajax/showPhone")
     */
    public function showPhoneAction(Request $request, ServiceStat $stat)
    {
        $post = $request->request;
        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($post->get('card_id'));

        foreach($card->getUser()->getInformation() as $info){
            if( $info->getUiKey() == 'phone') $phone = $info->getUiValue();
        }

        if($this->get('session')->has('phone')){
            $array = $this->get('session')->get('phone');
            $array[$card->getUserId()] = 1;
            $this->get('session')->set('phone', $array);
        } else {
            $this->get('session')->set('phone', [$card->getUserId() => 1]);
        }

        $stat_arr = [
            'url' => $request->getPathInfo(),
            'event_type' => 'phone',
            'page_type' => $post->get('type'),
            'card_id' => $card->getId(),
            'user_id' => $card->getUserId(),
        ];

        if($post->get('type') == 'card') $stat_arr['url'] = '/card/'.$card->getId();
        if($post->get('type') == 'profile') $stat_arr['url'] = '/user/'.$card->getUserId();

        $stat->setStat($stat_arr);


        return new Response($phone, 200);
    }

    /**
     * @Route("/listing/{slug}/")
     */
    public function wpStubAction(MenuGeneralType $mgt, MenuCity $mc, EntityManagerInterface $em, MenuMarkModel $mm)
    {
        $city = $this->get('session')->get('city');

        $in_city = $city->getUrl();

        $query = $em->createQuery('SELECT g,c FROM AppBundle:GeneralType g LEFT JOIN g.cards c');
        $generalTypes = $query->getResult();

        return $this->render('common/wp_stub.html.twig', [
            'city' => $city,
            'cityId' => $city->getId(),
            'generalTypes' => $generalTypes,
            'in_city' => $in_city
        ]);
    }

    /**
     * @Route("/regions")
     */
    public function showRegionsAction(EntityManagerInterface $em)
    {
        $query = $em->createQuery("SELECT c.id FROM AppBundle:City c WHERE c.country = 'RUS'");
        foreach($query->getScalarResult() as $row){
            $city_ids[] = $row['id'];
        }
        $query = $em->createQuery('SELECT c.parentId FROM AppBundle:City c WHERE c.parentId IS NOT NULL AND c.id IN ('.implode(",",$city_ids).')');
        foreach($query->getScalarResult() as $row){
            $region_ids[] = $row['parentId'];
        }
        $region_ids = array_unique($region_ids);
        $query = $em->createQuery('SELECT r FROM AppBundle:City r WHERE r.id IN ('.implode(",",$region_ids).')');
        $regions = $query->getResult();

        $city = $this->get('session')->get('city');

        return $this->render('main_page/regions.html.twig', [
            'regions' => $regions,
            'city' => $city,
            'lang' => $_SERVER['LANG']
        ]);
    }

    /**
     * @Route("/region/{id}")
     */
    public function showRegionAction($id, EntityManagerInterface $em)
    {
        $region = $this->getDoctrine()
            ->getRepository(City::class)
            ->find($id);
        $query = $em->createQuery('SELECT c.cityId FROM AppBundle:Card c ');
        foreach($query->getScalarResult() as $row){
            $city_ids[] = $row['cityId'];
        }
        $query = $em->createQuery('SELECT c FROM AppBundle:City c WHERE c.parentId ='.$id.' AND c.id IN ('.implode(",",$city_ids).')');
        $cities = $query->getResult();
        return $this->render('main_page/cities.html.twig', [
            'cities' => $cities,
            'region' => $region
        ]);
    }

    /**
     * @Route("/ajax/plusLike")
     */
    public function plusLikeAction(Request $request)
    {
        $res = '';
        $post = $request->request;
        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($post->get('card_id'));

        if($this->get('session')->has('likes')){
            $array = $this->get('session')->get('likes');
            if(!isset($array[$card->getId()])) {
                $array[$card->getId()] = 1;
                $card->setLikes($card->getLikes() + 1);
                $res = 'ok';
            }
            $this->get('session')->set('likes', $array);
        } else {
            $this->get('session')->set('likes', [$card->getId() => 1]);
            $card->setLikes($card->getLikes() + 1);
            $res = 'ok';
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($card);
        $em->flush();

        return new Response($res, 200);
    }
}
