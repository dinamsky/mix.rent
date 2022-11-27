<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Promo;
use AppBundle\Menu\MenuGeneralType;
use AppBundle\Menu\MenuCity;
use AppBundle\Menu\MenuMarkModel;
use AppBundle\Menu\ServiceCaptcha;
use AppBundle\Menu\ServiceStat;
use Doctrine\ORM\EntityManagerInterface;
use MarkBundle\Entity\CarModel;
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

class PromoController extends Controller
{

    /**
     * @Route("/promo", name="promo")
     */
    public function showCardAction(MenuGeneralType $mgt, SubFieldUtils $sf, MenuCity $mc, MenuMarkModel $markmenu, Request $request, ServiceStat $stat)
    {


        $em = $this->get('doctrine')->getManager();


        $city = $this->get('session')->get('city');


        $in_city = $city->getUrl();


//        $stat->setStat([
//            'url' => $request->getPathInfo(),
//            'event_type' => 'visit',
//            'page_type' => 'card',
//            'card_id' => '',
//            'user_id' => '',
//        ]);


        $promo = [];
        $dql = "SELECT p FROM AppBundle:Promo p WHERE p.pId=1 AND p.pKey != 'opinion'";
        $query = $em->createQuery($dql);
        foreach($query->getResult() as $row){
            if ($_SERVER['LANG'] == 'ru') $promo[$row->getPKey()] = str_replace("{{ city.gde }}", $city->getGde(),$row->getPValue());
            if ($_SERVER['LANG'] == 'en') $promo[$row->getPKey()] = str_replace("{{ city.gde }}", $city->getGde(),$row->getPValueEn());
        }

        $dql = "SELECT p FROM AppBundle:Promo p WHERE p.pId=1 AND p.pKey = 'opinion'";
        $query = $em->createQuery($dql);
        foreach($query->getResult() as $row){

            $r = json_decode($row->getPValue(),true);
            $r['id'] = $row->getId();
            $opinions[$r['sort']] = $r;
        }
        ksort($opinions);

        $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.weight, g.total DESC');
        $generalTypes = $query->getResult();

        return $this->render('promo/promo.html.twig', [

            'city' => $city,

            'cityId' => $city->getId(),

            'in_city' => $in_city,

            'lang' => $_SERVER['LANG'],

            'promo' => $promo,
            'opinions' => $opinions,
            'generalTypes' => $generalTypes,

            'mark_groups' => $markmenu->getGroups(),
            'marks' => $markmenu->getMarks(1),

        ]);
    }

    /**
     * @Route("/promo_ajax_counter")
     */
    public function countAction(Request $request, ServiceStat $stat)
    {
        $error = false;
        $city = $this->get('session')->get('city');
        $modelId = $request->request->get('modelId');

        $em = $this->get('doctrine')->getManager();

        $dql = 'SELECT c,f,p FROM AppBundle:Card c LEFT JOIN c.fotos f LEFT JOIN c.cardPrices p WHERE c.generalTypeId = 2 AND c.modelId = '.(int)$modelId.' AND c.cityId = '.$city->getId();
        $query = $em->createQuery($dql);
        $result = $query->getResult();

        if(count($result)==0){
            $dql = 'SELECT c,f,p FROM AppBundle:Card c LEFT JOIN c.fotos f LEFT JOIN c.cardPrices p WHERE c.generalTypeId = 2 AND c.modelId = '.(int)$modelId;
            $query = $em->createQuery($dql);
            $result = $query->getResult();
        }

        $i = 0;
        $p = 0;
        foreach($result as $r){
            //$x = false;
            foreach ($r->getCardPrices() as $pr){
                if ($pr->getPriceId() == 2) $p = $p + $pr->getValue();
                //if ($pr->getPriceId() == 1 and $pr->getValue()!='') $x = true;
            }
            if($i<11) $for_slider[] = $r;
            $i++;
        }

        if(count($result) == 0) {
            $result = [1];
            $slider = '';
            $error = true;
        } else {
            $slider = $this->renderView('promo/promo_slider.html.twig',['slider'=>$for_slider,'lang' => $_SERVER['LANG']]);
        }

        $res = array(
            'price' => round($p/count($result),0),
            'slider' => $slider,
            'error' => $error,

        );

        $model = $em->getRepository(CarModel::class)
            ->find($request->request->get('modelId'));

        $mark = $model->getMark();

        $stat_arr = [
            'url' => $city->getHeader(),
            'page_type' => $mark->getHeader().' '.$model->getHeader(),
            'event_type' => 'promo_calc'
        ];

        $stat->setStat($stat_arr);


        return new Response(json_encode($res));
    }

     /**
     * @Route("/mail_test/{id}")
     */
    public function mtAction($id, Request $request, \Swift_Mailer $mailer)
    {
        $card = $this->getDoctrine()
                    ->getRepository(Card::class)
                    ->find($id);

        $main_foto = $card->getFotos()[0];
        foreach($card->getFotos() as $f){
            if($f->getIsMain()) $main_foto = $f;
        }

        $c_price = '';
        $c_ed = '';
        foreach ($card->getCardPrices() as $p){
            if($p->getPriceId() == 2) {
                $c_price = $p->getValue();
                $c_ed = '/день';
            }
            if($p->getPriceId() == 1) {
                $c_price = $p->getValue();
                $c_ed = '/час';
            }
            if($p->getPriceId() == 6 and $c_price == '') {
                $c_price = $p->getValue();
                $c_ed = '';
            }
        }

        $message = (new \Swift_Message('Ваша компания теперь на сайте multiprokat.com. Мы разместили ваше объявление: '.$card->getMarkModel()->getMark()->getHeader().' '.$card->getMarkModel()->getHeader()))
                    ->setFrom('mail@multiprokat.com','Multiprokat.com - прокат и аренда транспорта')
                    ->setTo('wqs-info@mail.ru')
                    //->setBcc('mail@multiprokat.com')
                    ->setBody(
                        $this->renderView(
                            'email/admin_registration.html.twig',
                            array(
                                'header' => '11',
                                'password' => '22',
                                'email' => '33',
                                'card' => $card,
                                'main_foto' => 'http://multiprokat.com/assets/images/cards/'.$main_foto->getFolder().'/t/'.$main_foto->getId().'.jpg',
                                'c_price' => $c_price,
                                'c_ed' => $c_ed
                            )
                        ),
                        'text/html'
                    );
                //$mailer->send($message);


        return $this->render('email/admin_registration.html.twig', [
            'header' => '111',
            'email' => '123123',
            'password' => 'sdfsdf',
            'card' => $card,
            'main_foto' => 'http://multiprokat.com/assets/images/cards/'.$main_foto->getFolder().'/t/'.$main_foto->getId().'.jpg',
            'c_price' => $c_price,
            'c_ed' => $c_ed


        ]);
    }



    /**
     * @Route("/promo_ajax_case")
     */
    public function caseAction(Request $request, ServiceStat $stat)
    {
        $stat_arr = [
            'url' => '/promo',
            'page_type' => $request->request->get('p_case'),
            'event_type' => 'promo_case'
        ];

        $stat->setStat($stat_arr);
        return new Response('ok');
    }

    /**
     * @Route("/promo_request")
     */
    public function preqAction(Request $request, \Swift_Mailer $mailer, ServiceCaptcha $captcha)
    {
        $post = $request->request;

        $content = 'Марка: '.$post->get('promo_mark').'<br>';
        $content.= 'Модель: '.$post->get('promo_model').'<br>';
        $content.= 'Год выпуска: '.$post->get('prod_year').'<br>';
        $content.= 'Город: '.$post->get('promo_city').'<br>';
        $content.= 'Email: '.$post->get('promo_email').'<br>';
        $content.= 'Телефон: '.$post->get('promo_phone').'<br>';
        $content.= 'Имя: '.$post->get('promo_name').'<br>';

        if ($captcha->captchaVerify($post->get('g-recaptcha-response'))) {
            $message = (new \Swift_Message('Заявка с ПРОМО'))
                ->setFrom('mail@multiprokat.com')
                ->setTo('mail@multiprokat.com')
                ->setBody(
                    $content,
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash(
                'notice',
                'Ваша заявка успешно отправлена!'
            );

        } else {
            $this->addFlash(
                'notice',
                'Каптча не пройдена!'
            );
        }
        return $this->redirectToRoute('promo');
    }

}
