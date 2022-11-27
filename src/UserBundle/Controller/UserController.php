<?php

namespace UserBundle\Controller;

use AppBundle\Controller\MailGunController;
use AppBundle\Menu\ServiceStat;
use Facebook\Facebook;
use MarkBundle\Entity\CarModel;
use MarkBundle\Entity\CarMark;
use UserBundle\Entity\Message;
use UserBundle\Entity\UserOrder;
use AppBundle\Entity\CardFeature;
use AppBundle\Entity\CardPrice;
use AppBundle\Entity\Feature;
use AppBundle\Entity\Foto;
use AppBundle\Entity\Price;
use AppBundle\Entity\Tariff;
use AppBundle\Foto\FotoUtils;
use UserBundle\Entity\User;
use UserBundle\Entity\UserInfo;
use AppBundle\Entity\Card;
use AppBundle\Entity\City;
use AppBundle\Entity\Color;
use AppBundle\Entity\FieldInteger;
use AppBundle\Entity\FieldType;
use AppBundle\Entity\GeneralType;
use AppBundle\Entity\Mark;
use AppBundle\Entity\State;
use AppBundle\Entity\CardField;
use AppBundle\Menu\MenuCity;
use AppBundle\Menu\MenuGeneralType;
use AppBundle\Menu\MenuMarkModel;
use AppBundle\Menu\MenuSubFieldAjax;
use AppBundle\SubFields\SubFieldUtils;
use UserBundle\Security\CookieMaster;
use UserBundle\Security\Password;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use UserBundle\UserBundle;

class UserController extends Controller
{

    protected $mailer;
    protected $cookieMaster;

    public function __construct(\Swift_Mailer $mailer, CookieMaster $cookieMaster)
    {
        $this->mailer = $mailer;
        $this->cookieMaster = $cookieMaster;
    }

    /**
     * @Route("/ajax/getAllSubFields")
     */
    public function getAllSubField(Request $request, SubFieldUtils $sf)
    {
        $generalTypeId = $request->request->get('generalTypeId');

        $result = $sf->getSubFields($generalTypeId);

        return $this->render('all_subfields.html.twig', [
            'result' => $result,
            'lang' => $_SERVER['LANG']
        ]);
    }


    /**
     * @Route("/ajax/getSubField")
     */
    public function getSubLevel(Request $request, MenuSubFieldAjax $menu)
    {
        $subId = $request->request->get('subId');
        $fieldId = $request->request->get('fieldId');


        $result = $menu->getSubField($fieldId, $subId);

        if(!empty($result)) {
            return $this->render('common/ajax_select.html.twig', [
                'options' => $result,
                'lang' => $_SERVER['LANG']
            ]);
        }
        else{
            $response = new Response();
            $response->setStatusCode(204);
            return $response;
        }
    }

    private function setAuthCookie(User $user)
    {
        $response = new Response();
        $hash = $this->cookieMaster->setHash($user->getId());
        $cookie = new Cookie('the_hash', $hash.base64_encode($user->getId()), strtotime('now +1 year'));
        $response->headers->setCookie($cookie);
        $response->sendHeaders();
    }

    /**
     * @Route("/userSignIn")
     */
    public function signInAction(Request $request, Password $password)
    {
        $_t = $this->get('translator');

        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT u FROM UserBundle:User u WHERE u.isBanned = 0 AND (u.email = ?1 OR u.login = ?1)';
        $query = $em->createQuery($dql);
        $query->setParameter(1, $request->request->get('email'));
        $users = $query->getResult();



        foreach($users as $user){

            if ($password->CheckPassword($request->request->get('password'), $user->getPassword())){

                $this->get('session')->set('logged_user', $user);

                $this->setAuthCookie($user);

                $this->addFlash(
                    'notice',
                    $_t->trans('Вы успешно вошли в аккаунт!')
                );

                $this->get('session')->set('user_pic', false);
                foreach($user->getInformation() as $info){
                    if($info->getUiKey() == 'foto') $this->get('session')->set('user_pic', $info->getUiValue());
                }

                return $this->redirect($request->request->get('return'));
                break;
            }
        }

        $this->addFlash(
            'notice',
            $_t->trans('Неправильная пара логин/пароль!')
        );

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/fromMail")
     */
    public function signInFromMailAction(Request $request, Password $password)
    {
        if(!$this->get('session')->has('logged_user')) {

            $_t = $this->get('translator');

            $em = $this->getDoctrine()->getManager();
            $dql = 'SELECT u FROM UserBundle:User u WHERE u.isBanned = 0 AND (u.email = ?1 OR u.login = ?1)';
            $query = $em->createQuery($dql);
            $query->setParameter(1, urldecode($request->query->get('email')));
            $users = $query->getResult();

            foreach ($users as $user) {

                if ($password->CheckPassword(urldecode($request->query->get('password')), $user->getPassword())) {

                    $this->get('session')->set('logged_user', $user);

                    $this->setAuthCookie($user);

                    $this->addFlash(
                        'notice',
                        $_t->trans('Вы успешно вошли в аккаунт!')
                    );

                    $this->get('session')->set('user_pic', false);
                    foreach ($user->getInformation() as $info) {
                        if ($info->getUiKey() == 'foto') $this->get('session')->set('user_pic', $info->getUiValue());
                    }

                    $this->get('session')->set('first_jump', true);

                    $route = 'user_cards';
                    if ($request->query->has('rentout')) $route = 'card_new';

                    return $this->redirectToRoute($route);
                    break;
                }
            }

            $this->addFlash(
                'notice',
                $_t->trans('Неправильная пара логин/пароль!')
            );
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/userLogout")
     */
    public function logoutAction(Request $request)
    {
        $_t = $this->get('translator');

        $response = new Response();
        $response->headers->clearCookie('the_hash');
        $response->sendHeaders();
        $this->get('session')->remove('logged_user');
        $this->addFlash(
            'notice',
            $_t->trans('Вы успешно вышли из аккаунта')
        );
        return $this->redirectToRoute('homepage');
    }


    /**
     * @Route("/userSignUp")
     */
    public function signUpAction(Request $request, Password $password, MailGunController $mgc)
    {
        $_t = $this->get('translator');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(array(
                'email' => $request->request->get('email')
            ));

        if ($user) {
            $this->addFlash(
                'notice',
                $_t->trans('Пользователь уже зарегистрирован!')
            );
            return $this->redirectToRoute('homepage');
        }


        $code = md5(rand(0,99999999));
        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setLogin('');
        $user->setPassword($password->HashPassword($request->request->get('password')));
        $user->setHeader($request->request->get('header'));
        $user->setActivateString($code);
        $user->setTempPassword('');
        $user->setIsSubscriber(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

//        $message = (new \Swift_Message($_t->trans('Регистрация на сайте multiprokat.com')))
//            ->setFrom('mail@multiprokat.com')
//            ->setTo($user->getEmail())
//            ->setBody(
//                $this->renderView(
//                    $_SERVER['LANG'] == 'ru' ? 'email/registration.html.twig' : 'email/registration_'.$_SERVER['LANG'].'.html.twig',
//                    array(
//                        'header' => $user->getHeader(),
//                        'code' => $code
//                    )
//                ),
//                'text/html'
//            );
//        $mailer->send($message);


        $mgc->sendMG($request->request->get('email'),$_t->trans('Регистрация на сайте multiprokat.com'),$this->renderView(
                        $_SERVER['LANG'] == 'ru' ? 'email/registration.html.twig' : 'email/registration_'.$_SERVER['LANG'].'.html.twig',
                    array(
                        'header' => $request->request->get('header'),
                        'code' => $code
                    )
                    ));


        $this->addFlash(
            'notice',
            $_t->trans('На вашу почту было отправлено письмо для активации аккаунта!')
        );
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/activate_account/{code}", name="activate_account")
     */
    public function activateAccountAction($code)
    {
        $_t = $this->get('translator');

        $em = $this->getDoctrine()->getManager();

        $mgc = new MailGunController($em);


        $return_url = 'homepage';

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(array(
                'activateString' => $code
            ));

        if($user){
            $message = $_t->trans('Ваш аккаунт успешно активирован!');
            if ($user->getTempPassword() != '') {
                $user->setPassword($user->getTempPassword());
                $message = $_t->trans('Ваш новый пароль успешно активирован!');
            } else {
//                $msg = (new \Swift_Message('Регистрация на сайте multiprokat.com'))
//                ->setFrom('mail@multiprokat.com')
//                ->setTo('mail@multiprokat.com')
//                ->setBody('Только что был успешно зарегистрирован <a href="https://multiprokat.com/user/'.$user->getId().'">пользователь</a>','text/html');
//
//                $this->mailer->send($msg);

                $m = 'Только что был успешно зарегистрирован <a href="https://multiprokat.com/user/'.$user->getId().'">пользователь</a>';

                $mgc->sendMG('mail@mix.rent','New registration',$m);

            }
            $user->setTempPassword('');
            $user->setIsActivated(true);
            $user->setActivateString('');
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('session')->set('logged_user', $user);

            $this->setAuthCookie($user);

            $this->addFlash(
                'notice',
                $message
            );

            foreach($user->getCards() as $card){
                $card->setIsActive(true);
                $em->persist($card);
                $em->flush();
            }

            $return_url = 'user_cards';
        } else {
            $this->addFlash(
                'notice',
                $_t->trans('Произошла ошибка, попробуйте еще раз.')
            );
        }

        return $this->redirectToRoute($return_url);
        //
    }

    /**
     * @Route("/userRecover")
     */
    public function recoverAction(Request $request, MailGunController $mgc, Password $password)
    {
        $_t = $this->get('translator');

        if($request->request->get('password1') == $request->request->get('password2')) {
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(array(
                    'email' => $request->request->get('email'),
                ));

            if($user) {
                $code = md5(rand(0, 99999999));
                $user->setActivateString($code);
                $user->setTempPassword($password->HashPassword($request->request->get('password1')));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

//                $message = (new \Swift_Message($_t->trans('Восстановление пароля на сайте multiprokat.com')))
//                    ->setFrom('mail@multiprokat.com')
//                    ->setTo($user->getEmail())
//                    ->setBody(
//                        $this->renderView(
//                            $_SERVER['LANG'] == 'ru' ? 'email/recover.html.twig' : 'email/recover_' . $_SERVER['LANG'] . '.html.twig',
//                            array(
//                                'header' => $user->getHeader(),
//                                'code' => $code
//                            )
//                        ),
//                        'text/html'
//                    );
//                $mailer->send($message);


                $mgc->sendMG($user->getEmail(),$_t->trans('Восстановление пароля на сайте multiprokat.com'),$this->renderView(
                        $_SERVER['LANG'] == 'ru' ? 'email/recover.html.twig' : 'email/recover_' . $_SERVER['LANG'] . '.html.twig',
                            array(
                                'header' => $user->getHeader(),
                                'code' => $code
                            )
                    ));


                $this->addFlash(
                    'notice',
                    $_t->trans('Вам отправлено письмо с активацией нового пароля')
                );
            } else {
                $this->addFlash(
                    'notice',
                    'Email is not exist!'
                );
            }
            return $this->redirect($request->request->get('return'));
        } else {
            $this->addFlash(
                'notice',
                $_t->trans('Пароли не совпадают!')
            );
            return $this->redirect($request->request->get('return'));
        }
    }

    /**
     * @Route("/robokassa/resultUrl")
     */
    public function robokassaResultUrlAction(Request $request)
    {
        $mrh_pass2 = "sMTO0qtfg6fhxV009rZT"; //pass2

        $out_summ = $_REQUEST["OutSum"];
        $inv_id = $_REQUEST["InvId"];
        $crc = strtoupper($_REQUEST["SignatureValue"]);

        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2"));
        $text = "OK$inv_id\n";
        if ($my_crc != $crc) $text = "bad sign\n";
        else{
            $em = $this->getDoctrine()->getManager();

            $order = $this->getDoctrine()
                ->getRepository(UserOrder::class)
                ->find($inv_id);

            $order->setStatus('paid');

            if($order->getOrderType() == 'accountPRO'){
                $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($order->getUserId());
                $user->setAccountTypeId(1);
                $em->persist($user);
                $em->flush();
                $this->get('session')->set('logged_user', $user);
            } else {
                $tariff = $this->getDoctrine()
                    ->getRepository(Tariff::class)
                    ->find($order->getTariffId());
                $card = $this->getDoctrine()
                    ->getRepository(Card::class)
                    ->find($order->getCardId());
                $card->setDateTariffStart(new \DateTime());
                $card->setTariff($tariff);
                $em->persist($order);
                $em->persist($card);
                $em->flush();
            }
        }
        return new Response($text, 200);
    }

    /**
 * @Route("/robokassa/successUrl")
 */
    public function robokassaSuccessUrlAction(Request $request)
    {
        $_t = $this->get('translator');

        $mrh_pass1 = "Wf1bYXSd5V8pKS3ULwb3";  // merchant pass1 here
        $out_summ = $_REQUEST["OutSum"];
        $inv_id = $_REQUEST["InvId"];
        $crc = $_REQUEST["SignatureValue"];
        $crc = strtoupper($crc);  // force uppercase
        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1"));

        if ($my_crc != $crc)
        {
            $text = "bad sign\n";
            return new Response($text, 200);
        }

        $message = $_t->trans('Ваш новый тариф успешно оплачен!');
        $url = '/user/cards';

        $order = $this->getDoctrine()
            ->getRepository(UserOrder::class)
            ->find($inv_id);

        if ($order->getOrderType() == 'accountPRO') {
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($order->getUserId());
            $this->get('session')->set('logged_user', $user);

            $message = $_t->trans('Ваш PRO аккаунт успешно оплачен!');
            $url = '/user/cards';
        }

        $this->addFlash(
            'notice',
            $message
        );

        //return $this->redirectToRoute('homepage');
        return new RedirectResponse($url);
    }

    /**
     * @Route("/robokassa/failUrl")
     */
    public function robokassaFailUrlAction(Request $request)
    {
        $_t = $this->get('translator');

        $this->addFlash(
            'notice',
            $_t->trans('К сожалению оплата не прошла!')
        );

        return $this->redirectToRoute('user_cards');
    }

    /**
     * @Route("/ajax/getUser")
     */
    public function getUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $emails = array();

        $users = $em->getRepository("UserBundle:User")->createQueryBuilder('u')
            ->where('u.email LIKE :eml')
            ->setParameter('eml', '%'.$request->request->get('q').'%')
            ->getQuery()
            ->getResult();

        foreach($users as $user){
            $emails[] = $user->getEmail();
        }

        return new Response(json_encode($emails));
    }

    /**
     * @Route("/userPayPro/{user_id}")
     */
    public function payProAction($user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($user_id);

        if ($user) {

            $tariff = $this->getDoctrine()
                ->getRepository(Tariff::class)
                ->find(1);
            $card = $this->getDoctrine()
                ->getRepository(Card::class)
                ->findOneBy(['isActive' => true]);

            $order = new UserOrder();
            $order->setUser($user);
            $order->setCard($card);
            $order->setTariff($tariff);
            $order->setPrice(ceil(450*100/110));
            $order->setOrderType('accountPRO');
            $order->setStatus('new');
            $em->persist($order);
            $em->flush();

            $mrh_login = "multiprokat";
            $mrh_pass1 = "Wf1bYXSd5V8pKS3ULwb3";
            $inv_id = $order->getId();
            $inv_desc = "set_account_PRO";
            $out_summ = ceil(450*100/110);

            $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

            $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&" .
                "OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";

            return new RedirectResponse($url);
        } else throw $this->createNotFoundException(); //404
    }

    /**
     * @Route("/user_checkmail")
     */
    public function checkMailAction(Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['email'=>$request->request->get('email')]);
        if ($user) return new Response('ok');
        else return new Response('new');
    }

    /**
     * @Route("/promote_card/{id}")
     */
    public function promoteCardAction($id, ServiceStat $stat)
    {
        $this->get('session')->set('promote', true);

        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($id);

        $stat_arr = [
                'url' => '/user/edit/card/'.$id,
                'event_type' => 'promote_card',
                'page_type' => 'form',
                'user_id' => $card->getUserId(),
                'card_id' => $id,
            ];

        $stat->setStat($stat_arr);

        return new RedirectResponse('/user/edit/card/'.$id);
    }



    /**
     * @Route("/user_controller_ajax_tariff_cancel/")
     */
    public function cancelPromoteCardAction(ServiceStat $stat)
    {
        $user = $this->get('session')->get('logged_user');

        $stat_arr = [
            'url' => '',
            'event_type' => 'cancel_promote_card',
            'page_type' => 'form',
        ];

        if($user) $stat_arr['user_id'] = $user->getId();

        $stat->setStat($stat_arr);

        return new Response();
    }

    /**
     * @Route("/ajax/get_chat_messages")
     */
    public function getChatMsg(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $res = array();

        $query = $em->createQuery('SELECT m FROM UserBundle:Message m WHERE m.cardId=?3 AND ((m.fromUserId = ?1 AND m.toUserId = ?2) OR (m.fromUserId = ?2 AND m.toUserId = ?1)) ORDER BY m.dateCreate ASC');
        if($request->request->has('last_id')){
            $query = $em->createQuery('SELECT m FROM UserBundle:Message m WHERE m.id>'.$request->request->get('last_id').' AND m.cardId=?3 AND ((m.fromUserId = ?1 AND m.toUserId = ?2) OR (m.fromUserId = ?2 AND m.toUserId = ?1)) ORDER BY m.dateCreate ASC');
        }

        $query->setParameter(1, $request->request->get('user_id'));
        $query->setParameter(2, $request->request->get('visitor_id'));
        $query->setParameter(3, $request->request->get('card_id'));
        $msgs = $query->getResult();




        $query = $em->createQuery('UPDATE UserBundle:Message m SET m.isReadVisitor = 1 WHERE m.cardId=?3 AND m.fromUserId = ?2 AND m.toUserId = ?1');
        if($request->request->has('last_id')){
            $query = $em->createQuery('UPDATE UserBundle:Message m SET m.isReadVisitor = 1 WHERE m.id>'.$request->request->get('last_id').' AND m.cardId=?3 AND m.fromUserId = ?2 AND m.toUserId = ?1');
        }
        $query->setParameter(1, $request->request->get('user_id'));
        $query->setParameter(2, $request->request->get('visitor_id'));
        $query->setParameter(3, $request->request->get('card_id'));
        $query->execute();


        $query = $em->createQuery('UPDATE UserBundle:Message m SET m.isRead = 1 WHERE m.cardId=?3 AND m.fromUserId = ?1 AND m.toUserId = ?2');
        if($request->request->has('last_id')){
            $query = $em->createQuery('UPDATE UserBundle:Message m SET m.isRead = 1 WHERE m.id>'.$request->request->get('last_id').' AND m.cardId=?3 AND m.fromUserId = ?1 AND m.toUserId = ?2');
        }
        $query->setParameter(1, $request->request->get('user_id'));
        $query->setParameter(2, $request->request->get('visitor_id'));
        $query->setParameter(3, $request->request->get('card_id'));
        $query->execute();



        $last_id = $request->request->get('last_id');
        foreach($msgs as $m){
            $msg[$m->getDateCreate()->format('d-m-Y')][] = $m;
            $last_id = $m->getId();
        }


        if (isset($msg)) foreach($msg as $date=>$msgs){
            if(!$request->request->has('last_id')) $message[] = '<div class="messages_date_delimiter"><span>'.$date.'</span></div>';
            foreach($msgs as $m) {
                $css_class = 'user_message';
                if ($m->getFromUserId() == $request->request->get('visitor_id')) $css_class = 'visitor_message';

                $msg = '<div class="uk-clearfix"><div class="' . $css_class . '">';
                if ($m->getIsAttachment()) $msg .= '<div><img src="/assets/images/chat/' . $m->getId() . '.jpg" class="message_image"></div>';
                $msg .= $m->getMessage() . '<div class="message_time">' . $m->getDateCreate()->format('H:i') . '</div></div></div>';
                $message[] = $msg;
            }
        } else $message = [];

        $res['messages'] = implode("",$message);
        $res['last_id'] = $last_id;
        return new Response(json_encode($res));
    }

    /**
     * @Route("/ajax/send_chat_message")
     */
    public function sendChatMsg(Request $request, MailGunController $mgc)
    {
        $em = $this->getDoctrine()->getManager();

        $msg = new Message();
        $msg->setFromUserId($request->request->get('visitor_id'));
        $msg->setToUserId($request->request->get('user_id'));
        $msg->setCardId($request->request->get('card_id'));
        $msg->setMessage($request->request->get('message'));
        $msg->setIsAttachment($request->request->get('is_file'));
        $em->persist($msg);
        $em->flush();

        $card = $this->getDoctrine()
        ->getRepository(Card::class)
        ->find($request->request->get('card_id'));

        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($request->request->get('user_id'));

        $visitor = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($request->request->get('visitor_id'));

//        $message = (new \Swift_Message('#'.$msg->getId().' Вам пришло новое сообщение'))
//                ->setFrom(['mail@multiprokat.com' => 'Робот Мультипрокат'])
//                ->setTo($user->getEmail())
//                ->setBody(
//                    $this->renderView(
//                     $_SERVER['LANG'] == 'ru' ? 'email/chat.html.twig' : 'email/chat_'.$_SERVER['LANG'].'.html.twig',
//                        array(
//                            'user' => $user,
//                            'message' => $request->request->get('message'),
//                            'card' => $card,
//                            'visitor' => $visitor,
//                        )
//                    ),
//                    'text/html'
//                );
//            $mailer->send($message);

        $mgc->sendMG($user->getEmail(),'New message',$this->renderView(
                        $_SERVER['LANG'] == 'ru' ? 'email/chat.html.twig' : 'email/chat_'.$_SERVER['LANG'].'.html.twig',
                        array(
                            'user' => $user,
                            'message' => $request->request->get('message'),
                            'card' => $card,
                            'visitor' => $visitor,
                        )
                    ));

        return new Response($msg->getId());
    }

    /**
     * @Route("/ajax_chat_upload")
     */
    public function uploadChatMsg(Request $request, FotoUtils $fu)
    {
        if($request->request->get('filename')!=''){
            $fu->uploadImage(
            'chat_foto',
            $request->request->get('filename'),
            $_SERVER['DOCUMENT_ROOT'].'/assets/images/chat',
            '');
        }


        return new Response('<script>parent.refresh_chat();</script>');
    }

    /**
     * @Route("/fb_test")
     */
    public function fb_test()
    {

        $app_id = '626564254385013';
        $secret = 'e6d9531ea6a08e0a657c65621b128200';

        //$json = json_decode(file_get_contents("https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=$app_id&client_secret=$secret"),true);

        $access_token = 'EAATxmg247nwBAPw85jiKJmluPMZCMF4BQGVBe6SkLQl61LkfgE9KwWZC85ZAWOZAn3hZCl9eLlWSzSbju2usBn2RQ1WyJZBRxQZAH0J6g2w6zdWO0SZCYPdT82oNDysmFNL6EjS5qb3O8vkZCZAfFvmLYRbhM6uzyrG4y7ZCmX0QfJwmgZDZD';


        $fb = new Facebook(array(
            'app_id' => $app_id,
            'app_secret' => $secret
        ));


        $linkData = [
         'link' => 'https://mix.rent/card/41261',
         'message' => 'Rent truck Hino 268A in Marcy NY USA'
        ];
        $pageAccessToken = $access_token;

        try {
         $response = $fb->post('/166649987446297/feed', $linkData, $pageAccessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
         echo 'Graph returned an error: '.$e->getMessage();
         exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
         echo 'Facebook SDK returned an error: '.$e->getMessage();
         exit;
        }

        $graphNode = $response->getGraphNode();

        var_dump($response);

        return new Response();
    }

    /**
     * @Route("/tw_test")
     */

    public function tw_test()
    {
        $consumerKey = 'p5YG14DfS9VIA0rvZMOhe6IpI';
        $consumerSecret = '6JqpIYZtcq5VOvyvNUzElGQkBiCtFSAoqpDXlLnAY0V8J65yR3';
        // Owner ID	997130977372790784
        $accessToken = '997130977372790784-i7RN2vNqTnTFm1wwwW9ZJvRjktGXBmd';
        $accessTokenSecret = 'fYh5NqzcyhAOis7iRnzFXELRIssFVOb6bJiLfRIeOb60o';

        $twitter = new \Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);


        $twitter->send('https://mix.rent/card/38268');
        var_dump($twitter);

        return new Response();
    }

    /**
     * @Route("/crypto_check")
     */
    public function crypto_check(Request $request)
    {
        $post = $request->request->all();

        file_put_contents('from_crypto.json',time().' --- '.json_encode($post));
        file_put_contents('from_crypto2.json',file_get_contents("php://input"));

        return new Response();
    }

        /**
     * @Route("/qreg_ajax_1")
     */
    public function qreg_ajax_1_Action(Request $request, Password $password)
    {
        $ok = true;

        $r = '';

        $user_info = $this->getDoctrine()
            ->getRepository(UserInfo::class)
            ->findOneBy(array(
                'uiKey' => 'phone',
                'uiValue' => $request->request->get('phone')
            ));

        if ($user_info) {
            $this->addFlash(
                'notice',
                'User is already registered! Please log in'
            );
            $ok = false;
        }

        //$xn = explode("@",$request->request->get('email'));


        $bu = $request->request->get('back_url');

        $code = rand(111111,999999);
        $user = new User();
        $user->setEmail('');
        $user->setLogin('');
        $user->setPassword($password->HashPassword($code));
        $user->setHeader($request->request->get('phone'));
        //$user->setHeader('');
        $user->setActivateString($code);
        $user->setTempPassword($bu);
        $user->setIsSubscriber(true);
        $user->setIsNew(true);
        $user->setWhois('new_renter');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        if($ok) {
            $number = preg_replace('~[^0-9]+~','',$request->request->get('phone'));
            //if(strlen($number)==11) $number = substr($number, 1);
            //$message = urlencode('Ваш код регистрации: '.$code);
            //$url = 'https://mainsms.ru/api/mainsms/message/send?apikey=72f5f151303b2&project=multiprokat&sender=MULTIPROKAT&recipients=' . $number . '&message=' . $message;
            //$sms_result = file_get_contents($url);
            $r = 'ok';


            $rq = array(
                "user-id"=>"mixrent",
                "api-key"=>"xXFkynY1f4OmLHwNZGhWQ4rnn1mPSFGWF8e5Gg86qg1Loy05",
                "number"=>$number,
                "security-code"=>$code
            );

            $rq = json_encode($rq);

            $url = "https://neutrinoapi.com/sms-verify";
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
            //$data = json_decode($response);

        }

        return new Response($r);
    }

    /**
     * @Route("/qreg_ajax_2")
     */
    public function qreg_ajax_2_Action(Request $request, Password $password)
    {
        $code = $request->request->get('regcode');
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(array(
                'activateString' => $code
            ));

        if($user) {

            $phone = $user->getHeader();

            //$user->setTempPassword('');
            $user->setHeader('');
            $user->setIsActivated(true);
            $user->setActivateString('');
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $user_info = new UserInfo();
            $user_info->setUser($user);
            $user_info->setUiKey('phone');
            $user_info->setUiValue($phone);
            $em->persist($user_info);
            $em->flush();

            $this->get('session')->set('logged_user', $user);
            $this->setAuthCookie($user);

            $r = $phone;
        } else {
            $r = 'bad';
        }

        return new Response($r);
    }


}

// https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=626564254385013&client_secret=6d93ddafffc5a2cfaee82bc8499539d2

// 166649987446297

// FB.api('/me/accounts', 'get', {}, function(response) {
//        console.log(response);
//    });
//}, {perms:'publish_stream,offline_access,manage_pages'});


//e6d9531ea6a08e0a657c65621b128200


// 267645130676079
// 33dd80a3ec19d6afe44942e61c9c9b9c