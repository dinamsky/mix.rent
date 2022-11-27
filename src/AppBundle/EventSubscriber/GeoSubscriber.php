<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\City;
use AppBundle\Menu\MyCacheService;
use UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Cookie;
use UserBundle\Security\CookieMaster;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;

class GeoSubscriber implements EventSubscriberInterface
{

    private $cookieMaster;
    private $em;
    private $mcs;

    public function __construct(em $em, CookieMaster $cookieMaster, MyCacheService $mcs)
    {
        $this->cookieMaster = $cookieMaster;
        $this->em = $em;
        $this->mcs = $mcs;
    }

    public function onKernelController(FilterControllerEvent $event)
    {

        $cookie = $event->getRequest()->cookies->has('geo_city_id');



        if($cookie){
            $city = $this->em
                    ->getRepository(City::class)
                    ->find($event->getRequest()->cookies->get('geo_city_id'));
                $event->getRequest()->getSession()->set('city', $city);
        } else {

            $userAgent = isset($_SERVER['HTTP_USER_AGENT'])
                ? strtolower($_SERVER['HTTP_USER_AGENT'])
                : '';

            $is_bot = preg_match(
                "~(google|yahoo|rambler|bot|yandex|spider|snoopy|crawler|finder|mail|curl)~i",
                $userAgent
            );

            $default = true;

            if (!$is_bot) {
                if (!$event->getRequest()->getSession()->has('city')) {
                    $ip = $event->getRequest()->getClientIp();
                    if ($ip == 'unknown') {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    }
                    if ($ip != '127.0.0.1') {


//                    if(!$this->mcs->check('ip_'.$ip)) $this->mcs->cset('ip_'.$ip,$ip,3600);
//                    else dump($this->mcs->cget('ip_'.$ip));




                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'http://ip-api.com/json/' . $ip . '?lang=ru&fields=city');
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
                            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $get = curl_exec($ch);

                            //$data = json_decode($response);
                            //$get = file_get_contents();
                            if ($get) {
                                $geo = json_decode($get, true);
                                if (isset($geo['city'])) {
                                    $city = $this->em->getRepository("AppBundle:City")->createQueryBuilder('c')
                                        ->where('c.header LIKE :geoname')
                                        ->andWhere('c.parentId IS NOT NULL')
                                        ->setParameter('geoname', '%' . $geo['city'] . '%')
                                        ->getQuery()
                                        ->getResult();
                                    if ($city) {
                                        $event->getRequest()->getSession()->set('city', $city[0]);
                                        $default = false;
                                    }
                                } else {
                                    $city = $this->em->getRepository(City::class)->find(77);
                                }
                            }





                    }
                } else $default = false;
            }

            if ($default) {
                $city = $this->em->getRepository(City::class)->find(77);
                $event->getRequest()->getSession()->set('city', $city);
            }

            if (isset($city)) {
                if (is_array($city)) $cookie_data = $city[0]->getId();
                else $cookie_data = $city->getId();
            } else $cookie_data = $event->getRequest()->getSession()->get('city')->getId();
            $event->getRequest()->attributes->set('cookie_data', $cookie_data);
        }


        if($event->getRequest()->cookies->has('the_hash')){
            $hash = $event->getRequest()->cookies->get('the_hash');
            $sh = substr($hash,0,40);
            $user_id = base64_decode(substr_replace($hash, null, 0, 40));

            if($this->cookieMaster->setHash($user_id) === $sh){
                $user = $this->em
                    ->getRepository(User::class)
                    ->find($user_id);

                $event->getRequest()->getSession()->set('logged_user', $user);
                foreach ($user->getInformation() as $info) {
                    if ($info->getUiKey() == 'foto') $event->getRequest()->getSession()->set('user_pic', $info->getUiValue());
                }
            } else {
                $event->getRequest()->attributes->set('remove_cookie', true);
            }
        }

        if($event->getRequest()->cookies->has('not_first_time')){
            $event->getRequest()->getSession()->set('not_first_time', true);
        } else {
            $event->getRequest()->attributes->set('first_cookie', true);
        }

    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if($request->attributes->has('cookie_data')) {
            $cookie_data = $request->attributes->get('cookie_data');
            $cookie = new Cookie('geo_city_id', $cookie_data, strtotime('now +1 year'));
            $response->headers->setCookie($cookie);
        }

        if($request->attributes->has('remove_cookie')) {
            $response->headers->clearCookie('the_hash');
        }

        if($request->attributes->has('first_cookie')) {
            $cookie = new Cookie('not_first_time', '1', strtotime('now +1 year'));
            $response->headers->setCookie($cookie);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE => 'onKernelResponse'
        );
    }

}