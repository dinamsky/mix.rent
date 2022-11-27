<?php

namespace UserBundle\Controller;

use AppBundle\Controller\MailGunController;
use AppBundle\Entity\Card;
use AppBundle\Entity\Notify;
use AppBundle\Foto\FotoUtils;
use AppBundle\Menu\ServiceStat;
use UserBundle\Entity\Blocking;
use UserBundle\Entity\FormOrder;
use UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface as em;
use AppBundle\Menu\MenuCity;
use AppBundle\Menu\MenuGeneralType;
use AppBundle\Menu\MenuMarkModel;
use AppBundle\Menu\MenuSubFieldAjax;
use AppBundle\SubFields\SubFieldUtils;
use UserBundle\Entity\UserInfo;
use UserBundle\Security\Password;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use UserBundle\UserBundle;

class ProfileController extends Controller
{

    /**
     * @Route("/user/cards", name="user_cards")
     */
    public function userCardsAction(EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->get('session')->get('logged_user')->getId());

        if(!$user->getIsBanned()) {
            $query = $em->createQuery('SELECT c FROM AppBundle:Card c WHERE c.userId = ?1');
            $query->setParameter(1, $this->get('session')->get('logged_user')->getId());
            $cards = $query->getResult();


            $city = $this->get('session')->get('city');
            $in_city = $city->getUrl();





            $stat_arr = [
                'url' => $request->getPathInfo(),
                'event_type' => 'visit',
                'page_type' => 'cabinet',
                'user_id' => $user->getId(),
            ];
            $stat->setStat($stat_arr);

            $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.total DESC');
            $generalTypes = $query->getResult();

            return $this->render('user/user_cards.html.twig', [
                'share' => true,
                'cards' => $cards,
                'city' => $city,

                'in_city' => $in_city,
                'cityId' => $city->getId(),
                'generalTypes' => $generalTypes,
                'lang' => $_SERVER['LANG'],

            ]);
        } else return new Response("",404);
    }

    /**
     * @Route("/user/messages", name="user_messages")
     */
    public function userMessagesAction(EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->get('session')->get('logged_user')->getId());

        if(!$user->getIsBanned()) {


            $city = $this->get('session')->get('city');
            $in_city = $city->getUrl();
            $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.total DESC');
            $generalTypes = $query->getResult();

            $m_users = $users = [];
            $query = $em->createQuery('SELECT m FROM UserBundle:Message m WHERE m.fromUserId = ?1 OR m.toUserId = ?1 ORDER BY m.dateCreate ASC');
            $query->setParameter(1, $user->getId());
            $msgs = $query->getResult();


            $res = [];$cards = [];$blockings = [];$blockme = [];
            foreach ($msgs as $m){
                $m_users[$m->getFromUserId()] = 1;
                $m_users[$m->getToUserId()] = 1;

                if($m->getFromUserId() != $user->getId()) $chat_visitor_id = $m->getFromUserId();
                else $chat_visitor_id = $m->getToUserId();
                $res[$chat_visitor_id][$m->getCardId()][] = $m;

                $cards[$m->getCardId()] = $this->getDoctrine()
                    ->getRepository(Card::class)
                    ->find($m->getCardId());

            }

            foreach($m_users as $u=>$v){

                $u_object = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($u);

                $u_object->user_foto = false;

                foreach ($u_object->getInformation() as $info) {
                    if ($info->getUiKey() == 'foto' and $info->getUiValue() != '') $u_object->user_foto = '/assets/images/users/t/' . $info->getUiValue() . '.jpg';
                }

                $users[$u] = $u_object;

                $blockings[$u] = $this->getDoctrine()
                    ->getRepository(Blocking::class)
                    ->findBy([
                        'userId' => $this->get('session')->get('logged_user')->getId(),
                        'visitorId' => $u
                    ]);

                $blockme[$u] = $this->getDoctrine()
                    ->getRepository(Blocking::class)
                    ->findBy([
                        'userId' => $u,
                        'visitorId' => $this->get('session')->get('logged_user')->getId()
                    ]);

            }




            return $this->render('user/user_messages.html.twig', [
                'city' => $city,
                'in_city' => $in_city,
                'cityId' => $city->getId(),
                'generalTypes' => $generalTypes,
                'lang' => $_SERVER['LANG'],


                'messages' => $res,
                'users' => $users,
                'cards' => $cards,
                'blockings' => $blockings,
                'blockme' => $blockme

            ]);
        } else return new Response("",404);
    }

    /**
     * @Route("/user/delete_blocking_user_messages", name="delete_blocking_user_messages")
     */
    public function duUserMessagesAction(EntityManagerInterface $em, Request $request)
    {
        $id = $request->request->get('id');
        $action = $request->request->get('user_action');

        if($action=='delete'){
            $query = $em->createQuery('DELETE UserBundle:Message m WHERE (m.fromUserId = ?1 AND m.toUserId = ?2) OR (m.fromUserId = ?2 AND m.toUserId = ?1)');
            $query->setParameter(1, $id);
            $query->setParameter(2, $this->get('session')->get('logged_user')->getId());
            $query->execute();
        }

        if($action=='block'){
            $blocking = new Blocking();
            $blocking->setUserId($this->get('session')->get('logged_user')->getId());
            $blocking->setVisitorId($id);
            $em->persist($blocking);
            $em->flush();
        }

        if($action=='unblock'){
            $blocking = $this->getDoctrine()
            ->getRepository(Blocking::class)
            ->findOneBy([
                'userId' => $this->get('session')->get('logged_user')->getId(),
                'visitorId' => $id,
            ]);
            $em->remove($blocking);
            $em->flush();
        }

        return $this->redirectToRoute('user_messages');
    }

    /**
     * @Route("/user", name="user_main")
     */
    public function indexAction(Password $password, EntityManagerInterface $em)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->get('session')->get('logged_user')->getId());

        $city = $this->get('session')->get('city');
        $in_city = $city->getUrl();



        $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.total DESC');
        $generalTypes = $query->getResult();




        if(!$user->getIsBanned()) return $this->render('user/profile_main.html.twig',[
            'user' => $user,
            'city' => $city,

            'in_city' => $in_city,
            'cityId' => $city->getId(),
            'generalTypes' => $generalTypes,
            'lang' => $_SERVER['LANG'],

        ]);
        else return new Response("",404);
    }



    /**
     * @Route("/profile", name="user_profile")
     */
    public function editProfileAction(EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->get('session')->get('logged_user')->getId());




        if(!$user->getIsBanned()) {


            $city = $this->get('session')->get('city');
            $in_city = $city->getUrl();



            $stat_arr = [
                'url' => $request->getPathInfo(),
                'event_type' => 'visit',
                'page_type' => 'cabinet',
                'user_id' => $user->getId(),
            ];
            $stat->setStat($stat_arr);

            $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.total DESC');
            $generalTypes = $query->getResult();




            return $this->render('user/user_profile.html.twig', [
                'user' => $user,
                'city' => $city,

                'in_city' => $in_city,
                'cityId' => $city->getId(),
                'generalTypes' => $generalTypes,
                'lang' => $_SERVER['LANG'],

            ]);
        }
        else return new Response("",404);
    }

    /**
     * @Route("/profile/save")
     */
    public function updateProfileAction(Request $request)
    {
        $post = $request->request;

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->get('session')->get('logged_user')->getId());

        $user->setHeader(trim(strip_tags($post->get('header'))));

        /**
         * @var $info UserInfo
         */

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('DELETE UserBundle\Entity\UserInfo u WHERE u.userId = ?1 AND u.uiKey != ?2');
        $query->setParameter(1, $user->getId());
        $query->setParameter(2, 'foto');
        $query->execute();

        foreach($post->get('info') as $uiKey =>$uiValue ){
            $info = new UserInfo();
            $info->setUiKey($uiKey);

//            if (in_array($uiKey,['website','about'])) $uiValue = strip_tags($uiValue);
            $uiValue = trim(strip_tags($uiValue));

            $info->setUiValue($uiValue);
            $info->setUser($user);
            $em->persist($info);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->get('session')->set('logged_user', $user);

        return $this->redirectToRoute('user_profile');
    }

    /**
     * @Route("/user/sendAbuse")
     */
    public function sendAbuseAction(Request $request, MailGunController $mgc)
    {
        $_t = $this->get('translator');

        $post = $request->request;

        $card_id = $post->get('card_id');

        if ($this->captchaVerify($post->get('g-recaptcha-response'))) {

            foreach($post->get('abuse') as $ms){
                $msg[] = $ms.'<br>';
            }
            $msg[] = 'Жалоба отправлена со <a href="https://mix.rent/card/'.$card_id.'">страницы</a>';
            $msg = implode("",$msg);

//            $message = (new \Swift_Message('Жалоба от пользователя'))
//                ->setFrom(['mail@multiprokat.com' => 'Робот Мультипрокат'])
//                ->setTo('mail@multiprokat.com')
//                ->setBody(
//                    $msg,
//                    'text/html'
//                );
//            $mailer->send($message);


            $mgc->sendMG('mail@mix.rent','Abuse',$msg);


            $this->addFlash(
                'notice',
                $_t->trans('Ваша жалоба успешно отправлена!')
            );
        } else {
            $this->addFlash(
                'notice',
                $_t->trans('Каптча не пройдена!')
            );
        }

        return $this->redirect('/card/'.$card_id);

    }


    /**
 * @Route("/user/sendMessage")
 */
    public function sendMessageAction(Request $request, MailGunController $mgc)
    {
        $_t = $this->get('translator');

        $post = $request->request;


        if($post->has('card_id')) {
            $card_id = $post->get('card_id');

            $card = $this->getDoctrine()
                ->getRepository(Card::class)
                ->find($card_id);
            $user = $card->getUser();
        }

        if($post->has('user_id')){
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($post->get('user_id'));
            $card = false;
        }


        if ($this->captchaVerify($post->get('g-recaptcha-response'))) {




//            $message = (new \Swift_Message($_t->trans('Сообщение от пользователя')))
//                ->setFrom(['mail@multiprokat.com' => 'Робот Мультипрокат'])
//                ->setTo($user->getEmail())
//                ->setCc('mail@multiprokat.com')
//                ->setBody(
//                    $this->renderView(
//                        $_SERVER['LANG'] == 'ru' ? 'email/request.html.twig' : 'email/request_'.$_SERVER['LANG'].'.html.twig',
//                        array(
//                            'header' => $user->getHeader(),
//                            'message' => $post->get('message'),
//                            'email' => $post->get('email'),
//                            'name' => $post->get('name'),
//                            'phone' => $post->get('phone'),
//                            'card' => $card,
//                            'user' => $user
//                        )
//                    ),
//                    'text/html'
//                );
//            $mailer->send($message);

            $mgc->sendMG($user->getEmail(),$_t->trans('Сообщение от пользователя'),$this->renderView(
                        $_SERVER['LANG'] == 'ru' ? 'email/request.html.twig' : 'email/request_'.$_SERVER['LANG'].'.html.twig',
                        array(
                            'header' => $user->getHeader(),
                            'message' => $post->get('message'),
                            'email' => $post->get('email'),
                            'name' => $post->get('name'),
                            'phone' => $post->get('phone'),
                            'card' => $card,
                            'user' => $user
                        )
                    ));

            $this->addFlash(
                'notice',
                $_t->trans('Ваше сообщение успешно отправлено!')
            );
        } else {
            $this->addFlash(
                'notice',
                $_t->trans('Каптча не пройдена!')
            );
        }

        if(isset($card)) return $this->redirect('/card/'.$card_id);
        if(isset($user)) return $this->redirect('/user/'.$user->getId());
    }




        /**
 * @Route("/user/bookMessage")
 */
    public function bookMessageAction(Request $request, MailGunController $mgc)
    {
        $_t = $this->get('translator');

        $post = $request->request;

        //var_dump($post);

        //var_dump($post->has('is_nonreged'));


        if ($this->captchaVerify($post->get('g-recaptcha-response')) or $post->has('is_nonreged')) {
        //if (1==1) {

            $card_id = $post->get('card_id');

            $card = $this->getDoctrine()
                ->getRepository(Card::class)
                ->find($card_id);
            $user = $card->getUser();

            $price = $deposit = $service = 0;
            foreach ($card->getCardPrices() as $cp){
                if($cp->getpriceId() == 2) $price = $cp->getValue();
                if($cp->getpriceId() == 10) $deposit = $cp->getValue();
            }

            $period = (strtotime(str_replace(".","-",$post->get('date_out'))) - strtotime(str_replace(".","-",$post->get('date_in'))))/60/60/24;

            if ($period < 1 ) $period = 1;

            $price = $period*$price;

            $service = ceil($price/100*15);

            if($service == 0) $service = 500;

            //$total = $price + $deposit + $service;
            $total = $price + $service;

            $is_admin_reged = false;
            foreach( $user->getInformation() as $info){
                if($info->getUiKey() == 'phone'){
                    $number = preg_replace('~[^0-9]+~','',$info->getUiValue());
                    if(strlen($number)==11) $number = substr($number, 1);

                    $ph = substr(preg_replace('/[^0-9]/', '', $info->getUiValue()),1);
                    $emz = explode("@",$user->getEmail());
                    if(strlen($emz[0])==11) $emz[0] = substr($emz[0], 1);
                    if ($ph == $emz[0]) $is_admin_reged = true;

                }
            }


            $message = urlencode('Добрый день! Поступила новая заявка на аренду вашего транспорта на сайте: https://multiprokat.com. Увидеть заявку вы можете в личном кабинете вашего аккаунта, а так же на почте '.$user->getEmail().'. Если у вас нет доступа к аккаунту - вы легко можете восстановить его по адресу: https://multiprokat.com/account_recovery');


            //dump($this->container->get('kernel')->getEnvironment());

            //$url = 'https://mainsms.ru/api/mainsms/message/send?apikey=72f5f151303b2&project=multiprokat&sender=MULTIPROKAT&recipients=' . $number . '&message=' . $message;
            //$sms_result = file_get_contents($url);


            $renter = $this->get('session')->get('logged_user');

//            if(!$is_admin_reged) {
//                $message = (new \Swift_Message($_t->trans('Запрос на бронирование')))
//                    ->setFrom(['mail@multiprokat.com' => 'Робот Мультипрокат'])
//                    ->setTo($user->getEmail())
//                    ->setBcc('mail@multiprokat.com')
//                    ->setBody(
//                        'Добрый день! Поступила новая заявка на аренду вашего транспорта на сайте: <a href="https://multiprokat.com">https://multiprokat.com</a>. Увидеть заявку вы можете в личном кабинете вашего аккаунта.',
//                        'text/html'
//                    );
//                $mailer->send($message);
//            }

            $form_order = new FormOrder();
            $form_order->setCardId($card->getId());
            $form_order->setUserId($user->getId());
            $form_order->setCityIn($post->get('city_in'));
            $form_order->setCityOut($post->get('city_out'));
            $form_order->setDateIn(\DateTime::createFromFormat('Y.m.d', $post->get('date_in')));
            $form_order->setDateOut(\DateTime::createFromFormat('Y.m.d', $post->get('date_out')));
            $form_order->setContent('');
            $form_order->setTransport($post->get('header'));
            $form_order->setEmail('');
            $form_order->setPhone('');
            $form_order->setName('');
            $form_order->setFormType('new_transport_order');

            if($post->get('content') != ''){
                $msg = $this->cut_num($post->get('content'));
            } else {
                $msg = 'Hi! Look to my request, please';
            }

            $messages[] = [
                'date' => date('d-m-Y'),
                'time' => date('H:i'),
                'from' => 'renter',
                'message' => $msg,
                'status' => 'send'
            ];

            $form_order->setMessages(json_encode($messages));
            $form_order->setRenterId($renter->getId());
            $form_order->setFioRenter($renter->getHeader());
            $form_order->setPassport4('');
            $form_order->setDrivingLicense4('');
            $form_order->setOwnerStatus('wait_for_accept');
            $form_order->setRenterStatus('wait_for_accept');
            $form_order->setIsNew(1);

            $form_order->setPrice($price);
            $form_order->setDeposit($deposit);
            $form_order->setService($service);
            $form_order->setTotal($total);

            $em = $this->getDoctrine()->getManager();
            $em->persist($form_order);
            $em->flush();


            $notify = new Notify();
            $notify->setUserId($user->getId());
            $notify->setObjectId($form_order->getId());
            $notify->setNotify('new_order');
            $em->persist($notify);
            $em->flush();


            $this->addFlash(
                'notice',
                'Your request successfully sent!'
            );

            // send SMS


        } else {
            $this->addFlash(
                'notice',
                $_t->trans('Wrong captcha!')
            );
        }

        if($post->has('is_nonreged')) {
            return $this->redirect('/profile');
        } else {
            return $this->redirect('/card/'.$card->getId());
        }

    }



     /**
 * @Route("/user/bookMessage2")
 */
    public function bookMessageAction2(Request $request, MailGunController $mgc)
    {
        $_t = $this->get('translator');

        $post = $request->request;

        if ($this->captchaVerify($post->get('g-recaptcha-response'))) {


            $card_id = $post->get('card_id');

            $card = $this->getDoctrine()
                ->getRepository(Card::class)
                ->find($card_id);
            $user = $card->getUser();




//            $message = (new \Swift_Message($_t->trans('Запрос на бронирование')))
//                ->setFrom(['mail@multiprokat.com' => 'Робот Мультипрокат'])
//                ->setTo($user->getEmail())
//                ->setBcc('mail@multiprokat.com')
//                ->setBody(
//                    $this->renderView(
//                        $_SERVER['LANG'] == 'ru' ? 'email/book.html.twig' : 'email/book_'.$_SERVER['LANG'].'.html.twig',
//                        array(
//                            'header' => $post->get('header'),
//                            'date_in' => $post->get('date_in'),
//                            'date_out' => $post->get('date_out'),
//                            'city_in' => $post->get('city_in'),
//                            'city_out' => $post->get('city_out'),
//                            'alternative' => $post->get('alternative'),
//                            'email' => $post->get('email'),
//                            'full_name' => $post->get('full_name'),
//                            'phone' => $post->get('phone'),
//                            'card' => $card,
//                            'user' => $user
//                        )
//                    ),
//                    'text/html'
//                );
//            $mailer->send($message);


            $mgc->sendMG($user->getEmail(),$_t->trans('Запрос на бронирование'),$this->renderView(
                        $_SERVER['LANG'] == 'ru' ? 'email/book.html.twig' : 'email/book_'.$_SERVER['LANG'].'.html.twig',
                        array(
                            'header' => $post->get('header'),
                            'date_in' => $post->get('date_in'),
                            'date_out' => $post->get('date_out'),
                            'city_in' => $post->get('city_in'),
                            'city_out' => $post->get('city_out'),
                            'alternative' => $post->get('alternative'),
                            'email' => $post->get('email'),
                            'full_name' => $post->get('full_name'),
                            'phone' => $post->get('phone'),
                            'card' => $card,
                            'user' => $user
                        )
                    ));



            $this->addFlash(
                'notice',
                $_t->trans('Ваше сообщение успешно отправлено!')
            );
        } else {
            $this->addFlash(
                'notice',
                $_t->trans('Каптча не пройдена!')
            );
        }

        return $this->redirect('/card/'.$card->getId());

    }



    private function cut_num($s)
    {
        $s = preg_replace('/\+?\([0-9]+\)[0-9]+/', '*', $s);
        $s = preg_replace('/[0-9]{7}/', '*', $s);
        $s = preg_replace('/[0-9]{2}\-[0-9]{2}\-[0-9]{3}/', '*', $s);
        $s = preg_replace('/[0-9]{3}\-[0-9]{2}\-[0-9]{2}/', '*', $s);
        return $s;
    }

//
    /**
     * @Route("/user/contactsMessage")
     */
    public function contactsMessageAction(Request $request, MailGunController $mgc)
    {
        $_t = $this->get('translator');

        $post = $request->request;

//        $card_id = $post->get('card_id');
//
//        $card = $this->getDoctrine()
//            ->getRepository(Card::class)
//            ->find($card_id);
//
//        $user = $card->getUser();

//        $message = (new \Swift_Message($_t->trans('Сообщение от пользователя')))
//            ->setFrom(['mail@multiprokat.com' => 'Робот Мультипрокат'])
//            ->setTo('mail@multiprokat.com')
//            ->setBody(
//                $this->renderView(
//                    $_SERVER['LANG'] == 'ru' ? 'email/request.html.twig' : 'email/request_'.$_SERVER['LANG'].'.html.twig',
//                    array(
//                        'header' => 'Админ',
//                        'message' => $post->get('message'),
//                        'email' => $post->get('email'),
//                        'name' => $post->get('name'),
//                        'phone' => $post->get('phone'),
//                    )
//                ),
//                'text/html'
//            );
//        $mailer->send($message);

        $mgc->sendMG('mail@mix.rent',$_t->trans('Сообщение от пользователя'),$this->renderView(
                        $_SERVER['LANG'] == 'ru' ? 'email/request.html.twig' : 'email/request_'.$_SERVER['LANG'].'.html.twig',
                        array(
                            'header' => 'Админ',
                            'message' => $post->get('message'),
                            'email' => $post->get('email'),
                            'name' => $post->get('name'),
                            'phone' => $post->get('phone'),
                        )
                    ));


        $this->addFlash(
            'notice',
            $_t->trans('Ваше сообщение успешно отправлено!')
        );

        return $this->redirect('/');
    }

    /**
     * @Route("/profile/saveFoto")
     */
    public function saveFotoAction(Request $request, FotoUtils $fu)
    {
        $post = $request->request;
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($post->get('user_id'));


        $query = $em->createQuery('DELETE UserBundle\Entity\UserInfo u WHERE u.userId = ?1 AND u.uiKey = ?2');
        $query->setParameter(1, $post->get('user_id'));
        $query->setParameter(2, 'foto');
        $query->execute();
        if (is_file($_SERVER['DOCUMENT_ROOT'].'/assets/images/users/'.$post->get('user_id')))
            unlink ($_SERVER['DOCUMENT_ROOT'].'/assets/images/users/'.$post->get('user_id'));

        if($post->has('delete')) return $this->redirectToRoute('user_profile');

        $userInfo = new UserInfo();
        $userInfo->setUser($user);
        $userInfo->setUiKey('foto');
        $userInfo->setUiValue('user_'.$post->get('user_id'));
        $em->persist($userInfo);
        $em->flush();



        $fu->uploadImage(
            'foto',
            'user_'.$post->get('user_id'),
            $_SERVER['DOCUMENT_ROOT'].'/assets/images/users',
            $_SERVER['DOCUMENT_ROOT'].'/assets/images/users/t');

        return $this->redirectToRoute('user_profile');
    }

    /**
     * @Route("/ajax/goPro")
     */
    public function goProAction(Request $request)
    {
        $post = $request->request;
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($post->get('user_id'));
        $user->setAccountTypeId(1);
        $em->persist($user);
        $em->flush();

        return new Response();
    }



    private function captchaverify($recaptcha){
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret"=>"6LcGCzUUAAAAAH0yAEPu8N5h9b5BB8THZtFDx3r2","response"=>$recaptcha));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data->success;
    }

       /**
     * @Route("/user/orders", name="user_transport_orders")
     */
    public function userTorderAction(EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->get('session')->get('logged_user')->getId());

        if(!$user->getIsBanned()) {
            $query = $em->createQuery('SELECT o FROM UserBundle:FormOrder o WHERE o.userId = ?1 OR o.renterId = ?1 ORDER BY o.dateCreate DESC');
            $query->setParameter(1, $this->get('session')->get('logged_user')->getId());
            $orders = $query->getResult();


            $city = $this->get('session')->get('city');
            $in_city = $city->getUrl();

            $stat_arr = [
                'url' => $request->getPathInfo(),
                'event_type' => 'orders_page',
                'page_type' => 'orders_list',
                'user_id' => $user->getId(),
            ];
            $stat->setStat($stat_arr);

            $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.total DESC');
            $generalTypes = $query->getResult();

            $totalSum = $this->countSum($user->getId());


            $ntf = array();
            $notifies = $this->getDoctrine()
            ->getRepository(Notify::class)
            ->findBy(['userId'=>$this->get('session')->get('logged_user')->getId()]);
            foreach ($notifies as $n){
                $ntf[$n->getObjectId()][] = $n;
            }


            $mobileDetector = $this->get('mobile_detect.mobile_detector');

            if ($mobileDetector->isMobile()) {
                return $this->render('user/mobile_user_orders_list.html.twig', [
                    'share' => true,
                    'orders' => $orders,
                    'city' => $city,
                    'full' => true,
                    'in_city' => $in_city,
                    'cityId' => $city->getId(),
                    'generalTypes' => $generalTypes,
                    'lang' => $_SERVER['LANG'],
                    'totalSum' => $totalSum,
                    'notifies' => $ntf,
                    'hide_notify' => true
                ]);
            } else {

                $query = $em->createQuery('DELETE AppBundle:Notify n WHERE n.userId = ?1');
                $query->setParameter(1, $this->get('session')->get('logged_user')->getId());
                $query->execute();


                return $this->render('user/user_orders_list.html.twig', [
                    'share' => true,
                    'orders' => $orders,
                    'city' => $city,
                    'full' => true,
                    'in_city' => $in_city,
                    'cityId' => $city->getId(),
                    'generalTypes' => $generalTypes,
                    'lang' => $_SERVER['LANG'],
                    'totalSum' => $totalSum,
                    'notifies' => $ntf
                ]);
            }


        } else return new Response("",404);
    }


    /**
     * @Route("/user/order_page/{id}", name="user_order_page")
     */
    public function user_order_pageAction($id, EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->get('session')->get('logged_user')->getId());

        if(!$user->getIsBanned()) {
            $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->find($id);


            $city = $this->get('session')->get('city');
            $in_city = $city->getUrl();

            $stat_arr = [
                'url' => $request->getPathInfo(),
                'event_type' => 'order_page',
                'page_type' => 'order_page',
                'user_id' => $user->getId(),
            ];
            $stat->setStat($stat_arr);

            $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.total DESC');
            $generalTypes = $query->getResult();

            $totalSum = $this->countSum($user->getId());

            $ntf = array();
            $notifies = $this->getDoctrine()
            ->getRepository(Notify::class)
            ->findBy(['userId'=>$this->get('session')->get('logged_user')->getId()]);
            foreach ($notifies as $n){
                $ntf[$n->getObjectId()][] = $n;
            }

            $query = $em->createQuery('DELETE AppBundle:Notify n WHERE n.userId = ?1 AND n.objectId = ?2');
            $query->setParameter(1, $this->get('session')->get('logged_user')->getId());
            $query->setParameter(2, $id);
            $query->execute();

            return $this->render('user/mobile_user_order_page.html.twig', [
                'share' => true,
                'o' => $order,
                'city' => $city,
                'full' => true,
                'in_city' => $in_city,
                'cityId' => $city->getId(),
                'generalTypes' => $generalTypes,
                'lang' => $_SERVER['LANG'],
                'totalSum' => $totalSum,
                'no_jivosite' => true,
                'no_header' => true,
                'notifies' => $ntf
            ]);



        } else return new Response("",404);
    }

    /**
     * @Route("/user/order_page_more/{id}", name="order_page_more")
     */
    public function order_page_moreAction($id, EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->get('session')->get('logged_user')->getId());

        if(!$user->getIsBanned()) {
            $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->find($id);


            $city = $this->get('session')->get('city');
            $in_city = $city->getUrl();

            $stat_arr = [
                'url' => $request->getPathInfo(),
                'event_type' => 'order_page',
                'page_type' => 'order_page',
                'user_id' => $user->getId(),
            ];
            $stat->setStat($stat_arr);

            $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.total DESC');
            $generalTypes = $query->getResult();

            $totalSum = $this->countSum($user->getId());


            return $this->render('user/mobile_order_page_more.html.twig', [
                'share' => true,
                'o' => $order,
                'city' => $city,
                'full' => true,
                'in_city' => $in_city,
                'cityId' => $city->getId(),
                'generalTypes' => $generalTypes,
                'lang' => $_SERVER['LANG'],
                'totalSum' => $totalSum,
                'no_jivosite' => true,
                'no_header' => true
            ]);



        } else return new Response("",404);
    }

    /**
     * @Route("/ajax_set_ord_active", name="ajax_set_ord_active")
     */
    public function ajaxSetOrderActiveAction(EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {
        $id = $request->request->get('id');
        $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->find($id);

        $user = 'renter';
        if($order->getUserId() == $request->request->get('user_id')) $user = 'owner';
//        else $order->setIsActiveRenter(true);

        if ($user == 'owner') {
//            $order->setIsActiveOwner(true);
            $query = $em->createQuery('UPDATE UserBundle:FormOrder f SET f.isActiveOwner = 0 WHERE f.userId = ?1');
            $query->setParameter(1, $request->request->get('user_id'));
            $query->execute();

            $query = $em->createQuery('UPDATE UserBundle:FormOrder f SET f.isActiveRenter = 0 WHERE f.renterId = ?1');
            $query->setParameter(1, $request->request->get('user_id'));
            $query->execute();

            $query = $em->createQuery('UPDATE UserBundle:FormOrder f SET f.isActiveOwner = 1 WHERE f.id = ?1');
            $query->setParameter(1, $request->request->get('id'));
            $query->execute();

        }
        else {
//            $order->setIsActiveRenter(true);
            $query = $em->createQuery('UPDATE UserBundle:FormOrder f SET f.isActiveRenter = 0 WHERE f.renterId = ?1');
            $query->setParameter(1, $request->request->get('user_id'));
            $query->execute();

             $query = $em->createQuery('UPDATE UserBundle:FormOrder f SET f.isActiveOwner = 0 WHERE f.userId = ?1');
            $query->setParameter(1, $request->request->get('user_id'));
            $query->execute();

            $query = $em->createQuery('UPDATE UserBundle:FormOrder f SET f.isActiveRenter = 1 WHERE f.id = ?1');
            $query->setParameter(1, $request->request->get('id'));
            $query->execute();
        }

//        $em->persist($order);
//        $em->flush();

        return new Response("");
    }

    /**
     * @Route("/ajax_owner_accept", name="ajax_owner_accept")
     */
    public function ownerAcceptAction(EntityManagerInterface $em, Request $request, ServiceStat $stat, MailGunController $mgc)
    {
        $id = $request->request->get('id');
        $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->find($id);


        $messages = json_decode($order->getMessages(),true);
        $messages[] = [
            'date' => date('d-m-Y'),
            'time' => date('H:i'),
            'from' => 'system',
            'message' => 'Order accepted. Wait for payment',
            'status' => 'send'
        ];

        $order->setMessages(json_encode($messages));
        $order->setOwnerStatus('accepted');
        $order->setRenterStatus('wait_for_pay');
        $em->persist($order);
        $em->flush();

        $this->addFlash(
                'notice',
                'Order accepted. Wait for payment'
            );

        // ------- send SMS -------

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($order->getRenterId());
        foreach( $user->getInformation() as $info){
            if($info->getUiKey() == 'phone'){
                $number = preg_replace('~[^0-9]+~','',$info->getUiValue());
                if(strlen($number)==11) $number = substr($number, 1);
            }
        }

        $message = urlencode('Hi! Owner just accepted your request for rent #'.$id.'. Wait for payment');
        if(isset($number)) $this->sendSMS($number,$message);



        //$url = 'https://mainsms.ru/api/mainsms/message/send?apikey=72f5f151303b2&project=multiprokat&sender=MULTIPROKAT&recipients=' . $number . '&message=' . $message;
        //$sms_result = @file_get_contents($url);

        // ---------------------------

        if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
//            $msg = (new \Swift_Message('Владелец одобрил вашу заявку №' . $id . '. Можно оплачивать'))
//                ->setFrom('mail@multiprokat.com')
//                ->setTo($user->getEmail())
//                ->setBody('Владелец одобрил вашу заявку №' . $id . '. Можно оплачивать', 'text/html');
//            $mailer->send($msg);


            $mgc->sendMG($user->getEmail(),$message,$message);
        }

        $notify = new Notify();
        $notify->setUserId($user->getId());
        $notify->setObjectId($id);
        $notify->setNotify('order_accept');
        $em->persist($notify);
        $em->flush();

        return new Response("");
    }

    /**
     * @Route("/ajax_owner_reject", name="ajax_owner_reject")
     */
    public function ownerRejectAction(EntityManagerInterface $em, Request $request, ServiceStat $stat, MailGunController $mgc)
    {
        $id = $request->request->get('id');
        $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->find($id);

        $messages = json_decode($order->getMessages(),true);
        $messages[] = [
            'date' => date('d-m-Y'),
            'time' => date('H:i'),
            'from' => 'system',
            'message' => 'Owner just rejected your request for rent #'.$id.'. We recommend you to keep looking for other vehicles',
            'status' => 'send'
        ];

        $order->setMessages(json_encode($messages));

        $order->setOwnerStatus('rejected');
        $order->setRenterStatus('rejected');
        $em->persist($order);
        $em->flush();

        $this->addFlash(
                'notice',
                'Owner just rejected your request for rent #'.$id.'. We recommend you to keep looking for other vehicles'
            );


        // ------- send SMS -------

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($order->getRenterId());
        foreach( $user->getInformation() as $info){
            if($info->getUiKey() == 'phone'){
                $number = preg_replace('~[^0-9]+~','',$info->getUiValue());
                if(strlen($number)==11) $number = substr($number, 1);
            }
        }

        $message = urlencode('Hi! Owner just rejected your request for rent #'.$id.'. We recommend you to keep looking for other vehicles.');
        if(isset($number)) $this->sendSMS($number,$message);

        //$url = 'https://mainsms.ru/api/mainsms/message/send?apikey=72f5f151303b2&project=multiprokat&sender=MULTIPROKAT&recipients=' . $number . '&message=' . $message;
        //$sms_result = @file_get_contents($url);

        // ---------------------------
        if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
//            $msg = (new \Swift_Message('Владелец отклонил вашу заявку №' . $id))
//                ->setFrom('mail@multiprokat.com')
//                ->setTo($user->getEmail())
//                ->setBody('Владелец отклонил вашу заявку №' . $id, 'text/html');
//            $mailer->send($msg);

            $mgc->sendMG($user->getEmail(),$message,$message);
        }

        $notify = new Notify();
        $notify->setUserId($user->getId());
        $notify->setObjectId($id);
        $notify->setNotify('order_reject');
        $em->persist($notify);
        $em->flush();

        return new Response("");
    }

    /**
     * @Route("/ajax_owner_answer", name="ajax_owner_answer")
     */
    public function ownerAnswerAction(EntityManagerInterface $em, Request $request, ServiceStat $stat, MailGunController $mgc)
    {
        $id = $request->request->get('id');
        $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->find($id);

        $messages = json_decode($order->getMessages(),true);
        $messages[] = [
            'date' => date('d-m-Y'),
            'time' => date('H:i'),
            'from' => 'owner',
            'message' => $this->cut_num($request->request->get('answer')),
            'status' => 'send'
        ];

        if($request->request->get('answer') != $this->cut_num($request->request->get('answer'))){
            $messages[] = [
                'date' => date('d-m-Y'),
                'time' => date('H:i'),
                'from' => 'system',
                'message' => '@mixrent_bot: phone numbers will be available after successful order payment',
                'status' => 'send'
            ];
        }

        $order->setMessages(json_encode($messages));
        //$order->setOwnerStatus('answered');
        //$order->setRenterStatus('wait_for_answer');
        $em->persist($order);
        $em->flush();

        // ------- send SMS -------

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($order->getRenterId());
        foreach( $user->getInformation() as $info){
            if($info->getUiKey() == 'phone'){
                $number = preg_replace('~[^0-9]+~','',$info->getUiValue());
                if(strlen($number)==11) $number = substr($number, 1);
            }
        }

        $message = urlencode("Hi! There is new message in order #".$id.". Please visit your dashboard's inbox for more information");
        if(isset($number)) {
//            $url = 'https://mainsms.ru/api/mainsms/message/send?apikey=72f5f151303b2&project=multiprokat&sender=MULTIPROKAT&recipients=' . $number . '&message=' . $message;
//            $sms_result = @file_get_contents($url);

            $this->sendSMS($number,$message);
        }

        // ---------------------------

        if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
//            $msg = (new \Swift_Message('Владелец ответил на ваше сообщение в заявке №' . $id))
//                ->setFrom('mail@multiprokat.com')
//                ->setTo($user->getEmail())
//                ->setBody('Владелец ответил на ваше сообщение в заявке №' . $id, 'text/html');
//            $mailer->send($msg);

            $mgc->sendMG($user->getEmail(),$message,$message);
        }

//        $msg = (new \Swift_Message('Мультипрокат. Владелец ответил на сообщение в заявке №'.$id))
//                ->setFrom('mail@multiprokat.com')
//                ->setTo('mail@multiprokat.com')
//                ->setBody($request->request->get('answer'),'text/html');
//        $mailer->send($msg);


        $notify = new Notify();
        $notify->setUserId($user->getId());
        $notify->setObjectId($id);
        $notify->setNotify('order_answer');
        $em->persist($notify);
        $em->flush();

        return new Response("");
    }

    /**
     * @Route("/ajax_renter_answer", name="ajax_renter_answer")
     */
    public function renterAnswerAction(EntityManagerInterface $em, Request $request, ServiceStat $stat, MailGunController $mgc)
    {
        $id = $request->request->get('id');
        $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->find($id);

        $messages = json_decode($order->getMessages(),true);
        $messages[] = [
            'date' => date('d-m-Y'),
            'time' => date('H:i'),
            'from' => 'renter',
            'message' => $this->cut_num($request->request->get('answer')),
            'status' => 'send'
        ];

        if($request->request->get('answer') != $this->cut_num($request->request->get('answer'))){
            $messages[] = [
                'date' => date('d-m-Y'),
                'time' => date('H:i'),
                'from' => 'system',
                'message' => '@mixrent_bot: phone numbers will be available after successful order payment',
                'status' => 'send'
            ];
        }

        $order->setMessages(json_encode($messages));
        //$order->setOwnerStatus('wait_for_answer');
        //$order->setRenterStatus('answered');
        $em->persist($order);
        $em->flush();

        // ------- send SMS -------

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($order->getUserId());
        foreach( $user->getInformation() as $info){
            if($info->getUiKey() == 'phone'){
                $number = preg_replace('~[^0-9]+~','',$info->getUiValue());
                if(strlen($number)==11) $number = substr($number, 1);
            }
        }

        $message = urlencode("Hi! There is new message in order #".$id.". Please visit your dashboard's inbox for more information");
        if(isset($number)) {
//            $url = 'https://mainsms.ru/api/mainsms/message/send?apikey=72f5f151303b2&project=multiprokat&sender=MULTIPROKAT&recipients=' . $number . '&message=' . $message;
//            $sms_result = @file_get_contents($url);

            $this->sendSMS($number,$message);
        }

        // ---------------------------

        if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
//            $msg = (new \Swift_Message('Арендатор ответил на ваше сообщение в заявке №' . $id))
//                ->setFrom('mail@multiprokat.com')
//                ->setTo($user->getEmail())
//                ->setBody('Арендатор ответил на ваше сообщение в заявке №' . $id, 'text/html');
//            $mailer->send($msg);

            $mgc->sendMG($user->getEmail(),$message,$message);
        }

//        $msg = (new \Swift_Message('Мультипрокат. Арендатор ответил на сообщение в заявке №'.$id))
//                ->setFrom('mail@multiprokat.com')
//                ->setTo('mail@multiprokat.com')
//                ->setBody($request->request->get('answer'),'text/html');
//        $mailer->send($msg);

        $notify = new Notify();
        $notify->setUserId($user->getId());
        $notify->setObjectId($id);
        $notify->setNotify('order_answer');
        $em->persist($notify);
        $em->flush();

        return new Response("");
    }

     /**
     * @Route("/user/{id}", name="user_page")
     */
    public function userPageAction($id, MenuMarkModel $mm, EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find((int)$id);
        if(!$user->getIsBanned()) {
            $user_foto = false;
            foreach ($user->getInformation() as $info) {
                if ($info->getUiKey() == 'foto' and $info->getUiValue() != '') $user_foto = '/assets/images/users/t/' . $info->getUiValue() . '.jpg';
            }

            $city = $this->get('session')->get('city');
            $in_city = $city->getUrl();



            $mark_arr = $mm->getExistMarks('',1);
            $mark_arr_sorted = $mark_arr['sorted_marks'];
            $models_in_mark = $mark_arr['models_in_mark'];

            $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.total DESC');
            $generalTypes = $query->getResult();


            $stat_arr = [
                'url' => $request->getPathInfo(),
                'event_type' => 'visit',
                'page_type' => 'profile',
                'user_id' => $user->getId(),
            ];
            $stat->setStat($stat_arr);

            return $this->render('user/user_page.html.twig', [
                'user' => $user,
                'user_foto' => $user_foto,
                'city' => $city,

                'mark_arr_sorted' => $mark_arr_sorted,
                'models_in_mark' => $models_in_mark,
                'in_city' => $in_city,
                'cityId' => $city->getId(),
                'mark' => $mark_arr_sorted[1][0]['mark'],
                'model' => $mark_arr_sorted[1][0]['models'][0],
                'generalTypes' => $generalTypes,
                'lang' => $_SERVER['LANG']
            ]);
        } else return new Response("",404);
    }

    private function countSum($user_id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find((int)$user_id);

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT o FROM UserBundle:FormOrder o WHERE o.userId = ?1 AND o.ownerStatus = ?2');
        $query->setParameter(1, $user_id);
        $query->setParameter(2, 'wait_for_rent');
        $orders = $query->getResult();

        $total = 0;
        foreach ($orders as $o){
            $total = $total + $o->getPrice();
        }
        return $total;
    }

    private function sendSMS($phone,$message)
    {
        $rq = array(
            "user-id"=>"mixrent",
            "api-key"=>"xXFkynY1f4OmLHwNZGhWQ4rnn1mPSFGWF8e5Gg86qg1Loy05",
            "number"=>$phone,
            "message"=>$message
        );
        $rq = json_encode($rq);
        $url = "https://neutrinoapi.com/sms-message";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($rq))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rq);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }

    /**
     * @Route("/pay_for_order/{id}", name="pay_for_order")
     */
    public function payForOrderAction($id, EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {
        $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->find($id);

        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($order->getCardId());


        $s = $this->get_secret();

        $renter = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($order->getRenterId());

        $phone = '+79870000000';
        foreach ($renter->getInformation() as $i){
            if ($i->getUiKey() == 'phone') $phone = '+'.str_replace(array("(",")","+"," "),"",trim($i->getUiValue()));
        }

        $merchantId = $s['id'];
        $secret = $s['secret'];

        $url = "https://secure.mandarinpay.com/api/transactions";

        //$reqid = time() ."_". microtime(true) ."_". rand();
        //$hash = hash("sha256", $merchantId ."-". $reqid ."-". $secret);
        //$xauth =  $merchantId ."-".$hash ."-". $reqid;


        $eml = $renter->getEmail();
        if($eml == '') $eml = "noemail@nodomain.za";

        $array_content = [
            "payment" => [
                "orderId" => $id,
                "action" => "pay",
                "price" => $order->getTotal().'.00',
            ],
            "customerInfo" => [
                "email" => $eml,
                "phone" => $phone
            ]
        ];

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey("test_9ncmKrgFNmz3HE5jACSTvvuqnq6HnV");


        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => $card->getCurrency(),
                "value" => $order->getTotal().'.00'
            ],
            "description" => "My first API payment",
            "redirectUrl" => "https://mix.rent/user_order_pay_success/".$id,
            "webhookUrl"  => "https://mix.rent/mollie_webhook",
            "metadata" => [
                  "order_id" => $id,
            ],
        ]);

        if ($payment) {

            $order->setPassport4($payment->id);

            return $this->redirect($payment->getCheckoutUrl());
        } else return new Response("");

    }

    /**
     * @Route("/mollie_webhook", name="mollie_webhook")
     */
    public function mollieWebhookAction(EntityManagerInterface $em, Request $request)
    {
        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey("test_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM");
        $payment = $mollie->payments->get($request->request->get('id'));

        if($payment->status == 'paid'){
            $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->findOneBy(['passport4'=>$payment->id]);
        }
    }

    /**
     * @Route("/user_order_pay_success/{id}", name="user_order_pay_success")
     */
    public function userOrderPaySuccessAction($id, EntityManagerInterface $em, Request $request, ServiceStat $stat, MailGunController $mgc)
    {

        $order = $this->getDoctrine()
            ->getRepository(FormOrder::class)
            ->find($id);

        if($order) {


            if($cb['status'] == 'success') {
                $order->setOwnerStatus('wait_for_rent');
                $order->setRenterStatus('wait_for_finish');


                $messages = json_decode($order->getMessages(),true);
                $messages[] = [
                    'date' => date('d-m-Y'),
                    'time' => date('H:i'),
                    'from' => 'system_ok',
                    'message' => 'Заявка оплачена. Деньги за заявку №'.$id.' получены',
                    'status' => 'send'
                ];

                $owner = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($order->getUserId());
                $owner_info = $this->getDoctrine()
                ->getRepository(UserInfo::class)
                ->findBy(['userId' => $order->getUserId()]);

                $owner_phone = '';
                foreach ($owner_info as $oi){
                    if ($oi->getUiKey() == 'phone') $owner_phone = $oi->getUiValue();
                }

                $messages[] = [
                    'date' => date('d-m-Y'),
                    'time' => date('H:i'),
                    'from' => 'system',
                    'message' => 'Пожалуйста обсудите детали аренды с владельцем: '.$owner->getHeader().' номер телефона: '.$owner_phone,
                    'status' => 'send'
                ];

                $messages[] = [
                    'date' => date('d-m-Y'),
                    'time' => date('H:i'),
                    'from' => 'system',
                    'message' => 'Если у вас возникли какие-либо затруднения обращайтесь в <a href="/contacts">поддержку</a>',
                    'status' => 'send'
                ];

                $order->setMessages(json_encode($messages));

                //$order->setPincodeForOwner($pincode);
                $em->persist($order);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Заявка #' . $id . ' успешно оплачена!<br> Владелец свяжется с вами для обсуждения нюансов.<br><a href="/assets/docs/rent_contract.docx">Скачайте</a> и распечатайте договор аренды.'
                );


                // ------- send SMS -------

                $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($order->getUserId());
                foreach( $user->getInformation() as $info){
                    if($info->getUiKey() == 'phone'){
                        $number = preg_replace('~[^0-9]+~','',$info->getUiValue());
                        if(strlen($number)==11) $number = substr($number, 1);
                    }
                }

                $message = urlencode('Арендатор оплатил заявку №'.$id);
                $url = 'https://mainsms.ru/api/mainsms/message/send?apikey=72f5f151303b2&project=multiprokat&sender=MULTIPROKAT&recipients=' . $number . '&message=' . $message;
                $sms_result = @file_get_contents($url);

                // ---------------------------

                if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
                    $msg = (new \Swift_Message('Арендатор оплатил заявку №' . $id))
                        ->setFrom('mail@multiprokat.com')
                        ->setTo($user->getEmail())
                        ->setBody('Арендатор оплатил заявку №' . $id, 'text/html');
                    $mailer->send($msg);
                }

                $msg = (new \Swift_Message('Арендатор оплатил заявку №'.$id))
                ->setFrom('mail@multiprokat.com')
                ->setTo('mail@multiprokat.com')
                ->setBody('Арендатор оплатил заявку №'.$id,'text/html');
                $mailer->send($msg);

                $notify = new Notify();
                $notify->setUserId($user->getId());
                $notify->setObjectId($id);
                $notify->setNotify('order_payed');
                $em->persist($notify);
                $em->flush();


                return new Response('OK', 200);
            } else {
                return new Response('BAD', 400);
            }


        } else {
            return new Response('BAD', 400);
        }
    }
}

// Hi! You have new order in Mix Rent platform: https://mix.rent Please visit your dashboard's inbox for more information.
// Hi! Owner just accepted your request for rent #123. Payment is waiting.
// Hi! Owner just rejected your request for rent #123. We recommend you to keep looking for other vehicles.
// Hi! There is new message in order #123. Please visit your dashboard's inbox for more information.
// Hi! Order #123 successfully paid! Please contact with renter from your dashboard's inbox order details.
// Hi! Order #123 successfully paid! Owner will contact you soon.