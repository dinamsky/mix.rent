<?php

namespace UserBundle\Controller;

use AppBundle\Controller\MailGunController;
use AppBundle\Menu\ServiceStat;
use MarkBundle\Entity\CarModel;
use MarkBundle\Entity\CarMark;
use UserBundle\Entity\UserInfo;
use UserBundle\Entity\UserOrder;
use UserBundle\Entity\User;
use AppBundle\Entity\CardFeature;
use AppBundle\Entity\CardPrice;
use AppBundle\Entity\Feature;
use AppBundle\Entity\Foto;
use AppBundle\Entity\Price;
use AppBundle\Entity\Tariff;
use AppBundle\Entity\Country;
use AdminBundle\Entity\Admin;
use AppBundle\Foto\FotoUtils;

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
use UserBundle\Security\Password;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NewCardController extends Controller
{
    /**
     * @Route("/card/new/{gt_url}", name="card_new")
     */
    public function indexAction($gt_url = '', MenuMarkModel $markmenu, MenuGeneralType $mgt, MenuCity $mc, Request $request, FotoUtils $fu, EntityManagerInterface $em, \Swift_Mailer $mailer, MailGunController $mgc, ServiceStat $stat, Password $pass)
    {

        $admin = false;
        if ($this->get('session')->has('admin')){
            $admin = $this->getDoctrine()
                ->getRepository(Admin::class)
                ->find($this->get('session')->get('admin')->getId());
        }

        $user = false;
        if($this->get('session')->has('logged_user')) {
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['id' => $this->get('session')->get('logged_user')->getId()]);
        }


        //if($this->get('session')->get('logged_user') === null and !$this->get('session')->has('admin')) return new Response("",404);

        if(!$admin and $user) {
            if ($user->getIsBanned()) return new Response("", 404);
//            if ($user->getAccountTypeId() == 0 and count($user->getCards()) > 1){
//                $this->addFlash(
//                    'notice',
//                    'You may pay for PRO account to unlimited cards'
//                );
//                $stat_arr = [
//                    'url' => '/card/new',
//                    'event_type' => 'need_PRO',
//                    'page_type' => 'form',
//                ];
//                if(isset($user)) $stat_arr['user_id'] = $user->getId();
//                $stat->setStat($stat_arr);
//                return new RedirectResponse('/user/cards');
//            }
        }

        $card = new Card();

        $conditions = $this->getDoctrine()
            ->getRepository(State::class)
            ->findAll();

        $tariffs = $this->getDoctrine()
            ->getRepository(Tariff::class)
            ->findAll();

        $colors = $this->getDoctrine()
            ->getRepository(Color::class)
            ->findAll();

        $features = $this->getDoctrine()
            ->getRepository(Feature::class)
            ->findBy(['parent'=>null]);

        $prices = $this->getDoctrine()
            ->getRepository(Price::class)
            ->findAll();

        if($request->isMethod('GET')) {


            $city = $this->get('session')->get('city');

            $countries = $this->getDoctrine()
                ->getRepository(Country::class)
                ->findBy([],['header'=>'ASC']);

            $gt = $this->getDoctrine()
                ->getRepository(GeneralType::class)
                ->find(2);

            $random = '';
            if($gt_url != ''){
                $gt_url = $this->getDoctrine()
                ->getRepository(GeneralType::class)
                ->findOneBy(['url'=>$gt_url]);


                $query = $em->createQuery('SELECT c.id FROM AppBundle:Card c WHERE c.generalTypeId = ?1 AND c.cityId = ?2 ORDER BY RAND()');
                $query->setParameter(1, $gt_url->getId());
                $query->setParameter(2, $city->getId());
                $query->setMaxResults(12);
                if(count($query->getScalarResult())<1) {
                    $query = $em->createQuery('SELECT c.id FROM AppBundle:Card c WHERE c.generalTypeId = ?1 ORDER BY RAND()');
                    $query->setParameter(1, $gt_url->getId());
                    $query->setMaxResults(12);
                    if(count($query->getScalarResult())<1) {
                        $query = $em->createQuery('SELECT c.id FROM AppBundle:Card c ORDER BY RAND()');
                        $query->setMaxResults(12);
                    }
                }
                foreach ($query->getScalarResult() as $cars_id) $cars_ids[] = $cars_id['id'];
                $query = $em->createQuery('SELECT c,p,f FROM AppBundle:Card c LEFT JOIN c.cardPrices p LEFT JOIN c.fotos f WHERE c.id IN ('.implode(",",$cars_ids).') ');
                $random = $query->getResult();
            }


            $query = $em->createQuery('SELECT c FROM AppBundle:City c WHERE c.total > 0 ORDER BY c.total DESC, c.header ASC');
            $popular_city = $query->getResult();

            $stat_arr = [
                'url' => '/card/new',
                'event_type' => 'set_form',
                'page_type' => 'form',
            ];

            if($user) $stat_arr['user_id'] = $user->getId();

            $stat->setStat($stat_arr);

            $phone = true;

            if($user){
                $phone = false;
                foreach ($user->getInformation() as $inf)
                    if($inf->getUiKey() == 'phone') {
                    $phone = $inf->getUiValue();
                    break;
                }
                if(isset($phone) and $phone != '') $phone = true;
            }

            if ($this->get('session')->has('admin')) $phone = true;

            $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g ORDER BY g.total DESC');
            $generalTypes = $query->getResult();

            $response = $this->render('card/card_new.html.twig', [
                'generalTopLevel' => $mgt->getTopLevel(),
                'generalSecondLevel' => $mgt->getSecondLevel(1),
                'gt' => $gt,
                'custom_fields' => '',
                'mark_groups' => $markmenu->getGroups(),
                'marks' => $markmenu->getMarks(1),
                'conditions' => $conditions,
                'colors' => $colors,
                'features' => $features,
                'prices' => $prices,
                'tariffs' =>$tariffs,

                //'countries' => $mc->getCountry(),
                'countryCode' => $city->getCountry(),
                'regionId' => $city->getParentId(),
                'regions' => $mc->getRegion($city->getCountry()),
                'cities' => $mc->getCities($city->getParentId()),
                'cityId' => $city->getId(),
                'city' => $city,
                'in_city' => $city->getUrl(),

                'admin' => $admin,
                'user' => $user,

                'generalTypes' => $generalTypes,

                'popular_city' => $popular_city,
                'phone' => $phone,
                'gt_url' => $gt_url,
                'random' => $random,
                'lang' => $_SERVER['LANG'],
                'countries' => $countries,
                'btc_address' => json_decode(file_get_contents('https://develop.smartcontract.ru/api/acq.create/1BxmNSkA9Mhd4EHv1mCrsRFfBdmgb7HPeN/1'),true)
            ]);

            return $response;
        }

        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $post = $request->request;
            $card->setHeader(strip_tags($post->get('header')));
            $card->setContent(strip_tags($post->get('content')));
            $card->setAddress(strip_tags($post->get('address')));
            $card->setCoords($post->get('coords'));
            $card->setVideo($post->get('video'));
            $card->setStreetView($post->get('streetView'));
            $card->setDeliveryStatus($post->get('deliveryStatus'));
            $card->setCurrency($post->get('currency'));


            if ($post->get('generalTypeId') == 0) $gt = $post->get('generalTypeTopLevelId');
            else $gt = $post->get('generalTypeId');

            $generalType = $this->getDoctrine()
                ->getRepository(GeneralType::class)
                ->find($gt);
            $card->setGeneralType($generalType);


            if($post->has('noMark')){
                $mark_header = strip_tags(trim(mb_strtoupper(mb_substr($post->get('ownMark'), 0, 1)).mb_substr($post->get('ownMark'),1)));
                $check_mark = $this->getDoctrine()
                    ->getRepository(CarMark::class)
                    ->findOneBy(['header'=>$mark_header,'carTypeId'=>$generalType->getCarTypeIds()]);
                if ($check_mark === null) {
                    $newmark = new CarMark();
                    $newmark->setCarTypeId($generalType->getCarTypeIds());
                    $newmark->setHeader($mark_header);
                    $em->persist($newmark);
                    $em->flush();
                }
            }

            if($post->has('noModel')){

                if(!isset($newmark)) {
                    $mark = $this->getDoctrine()
                        ->getRepository(CarMark::class)
                        ->find($post->get('mark'));
                } else $mark = $newmark;

                $model_header = strip_tags(trim(mb_strtoupper(mb_substr($post->get('ownModel'), 0, 1)).mb_substr($post->get('ownModel'),1)));

                $check_model = $this->getDoctrine()
                    ->getRepository(CarModel::class)
                    ->findOneBy(['header'=>$model_header,'carTypeId'=>$generalType->getCarTypeIds(),'carMarkId'=>$mark->getId()]);
                if (!$check_model) {
                    $model = new CarModel();
                    $model->setCarTypeId($generalType->getCarTypeIds());
                    $model->setHeader($model_header);
                    $model->setMark($mark);
                    $model->setTotal(1);
                    $em->persist($model);
                    $em->flush();
                }

            } else {
                $model = $this->getDoctrine()
                    ->getRepository(CarModel::class)
                    ->find($post->get('modelId'));
            }

            if($model) $card->setMarkModel($model);
            else {
                $this->addFlash(
                                'notice',
                                'Select model!'
                            );
                return new RedirectResponse('/card/new');
            }


            $country = $this->getDoctrine()
                ->getRepository(Country::class)
                ->findOneBy(['iso3'=>$post->get('countryCode')]);

            if($post->get('cityId') != 0) {
                $city = $this->getDoctrine()
                    ->getRepository(City::class)
                    ->find($post->get('cityId'));
            }

            if($post->get('regionId') != 0){
                $region = $this->getDoctrine()
                ->getRepository(City::class)
                ->find($post->get('regionId'));
            }


            if($post->has('is_region')
                and $post->get('new_region') != ''
                and $post->get('countryCode') != '0'
                and $post->has('is_city')
                and $post->get('new_city') != ''){

                $region = new City();
                $region->setCountry($post->get('countryCode'));
                $region->setHeader($post->get('new_region'));
                $region->setUrl($fu->translit($post->get('new_region')));
                $region->setGde(' ');
                $region->setTotal(1);
                $region->setModels(' ');
                $region->setCoords($country->getCoords());
                $region->setIso($country->getIso2());
                $em->persist($region);
                $em->flush();
            }



            if($post->has('is_city')
                and $post->get('new_city') != ''
                and $post->get('countryCode') != '0'){

                $city = new City();
                $city->setParent($region);
                $city->setCountry($post->get('countryCode'));
                $city->setHeader($post->get('new_city'));
                $city->setUrl($fu->translit($post->get('new_city')).'_'.$post->get('countryCode'));
                $city->setGde(' ');
                $city->setTotal(1);
                $city->setModels(' ');
                $city->setCoords($country->getCoords());
                $city->setIso($country->getIso2());
                $em->persist($city);
                $em->flush();

            }

            $card->setCity($city);


            $card->setProdYear($post->get('prodYear'));

            $condition = $this->getDoctrine()
                ->getRepository(State::class)
                ->find($post->get('conditionId'));
            $card->setCondition($condition);

            $card->setServiceTypeId($post->get('serviceTypeId'));


            $is_new_user = false;


            if ($post->has('user_email') and $this->get('session')->has('admin')){
                $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(array(
                        'email' => $post->get('user_email')
                    ));
            } else {
                if($this->get('session')->has('logged_user')) {
                    $user = $this->getDoctrine()
                        ->getRepository(User::class)
                        ->find($this->get('session')->get('logged_user')->getId());
                } else {

                    if($post->get('l_email') != '' and $post->get('l_password') != ''){ // if sign in
                        $user = $this->getDoctrine()
                            ->getRepository(User::class)
                            ->findOneBy(array(
                                'email' => $post->get('l_email')
                            ));
                        if ($pass->CheckPassword($post->get('l_password'), $user->getPassword())){

                            $this->get('session')->set('logged_user', $user);
                            $this->get('session')->set('user_pic', false);
                            foreach($user->getInformation() as $info){
                                if($info->getUiKey() == 'foto') $this->get('session')->set('user_pic', $info->getUiValue());
                            }

//                            if(count($user->getCards()) > 1) {
//                                $this->addFlash(
//                                    'notice',
//                                    'You may pay for PRO account to unlimited cards'
//                                );
//                                return new RedirectResponse('/card/new');
//                            }

                        } else {
                            $this->addFlash(
                                'notice',
                                'Wrong password!'
                            );
                            return new RedirectResponse('/card/new');
                        }
                    }



                    if($post->get('r_email') != '' and $post->get('r_password') != '' and $post->get('r_phone') != ''){
                        $card->setIsActive(false);

                        $user = $this->getDoctrine()
                            ->getRepository(User::class)
                            ->findOneBy(array(
                                'email' => $post->get('r_email')
                            ));

                        $_t = $this->get('translator');

                        if(!$user){

                            $code = md5(rand(0,99999999));
                            $user = new User();
                            $user->setEmail($request->request->get('r_email'));
                            $user->setLogin('');
                            $user->setPassword($pass->HashPassword($request->request->get('r_password')));
                            $user->setHeader($request->request->get('r_header'));
                            $user->setActivateString($code);
                            $user->setTempPassword('');

                            if($post->has('subscriber')) $user->setIsSubscriber(true);
                            else $user->setIsSubscriber(false);

                            $em = $this->getDoctrine()->getManager();
                            $em->persist($user);
                            $em->flush();

                            $ui = new UserInfo();
                            $ui->setUser($user);
                            $ui->setUiKey('phone');
                            $ui->setUiValue($post->get('r_phone'));
                            $em->persist($ui);
                            $em->flush();

                            $message = (new \Swift_Message('Mix.Rent registration notification'))
                                ->setFrom('mail@mix.rent')
                                ->setTo($user->getEmail())
                                ->setBody(
                                    $this->renderView(
                                        'email/registration_en.html.twig',
                                        array(
                                            'header' => $user->getHeader(),
                                            'code' => $code
                                        )
                                    ),
                                    'text/html'
                                );
                            $mailer->send($message);

                            $this->addFlash(
                                'notice',
                                $_t->trans('На вашу почту было отправлено письмо для активации аккаунта!')
                            );

                            $is_new_user = true;

                        } else {
                            $this->addFlash(
                                'notice',
                                $_t->trans('Пользователь уже зарегистрирован!')
                            );
                            return new RedirectResponse('/card/new');
                        }
                    }
                }
            }

            $card->setUser($user);


            if($user->getCards()->count() === 0){
                $new_card = true;
            }


            if($post->get('colorId') != 0) {
                $color = $this->getDoctrine()
                    ->getRepository(Color::class)
                    ->find($post->get('colorId'));
                $card->setColor($color);
            }



            $tariff = $this->getDoctrine()
                ->getRepository(Tariff::class)
                ->find(1);

            $card->setTariff($tariff);

            $admin = $this->getDoctrine()
                ->getRepository(Admin::class)
                ->find(1);

            $card->setAdmin($admin);

            if ($this->get('session')->has('admin')){
                $admin = $this->getDoctrine()
                    ->getRepository(Admin::class)
                    ->find($this->get('session')->get('admin')->getId());
                $card->setAdmin($admin);
            }

            $card->setIsActive(false);

            $em->persist($card);

            $em->flush();



            if($user){
                $phone = false;
                foreach ($user->getInformation() as $inf)
                    if($inf->getUiKey() == 'phone') {
                        $phone = $inf->getUiValue();
                        $ui_id = $inf->getId();
                        break;
                    }
                if(isset($phone) and $phone != '') $phone = true;
                if($phone == '' and isset($ui_id)){
                    $ui = $this->getDoctrine()
                        ->getRepository(UserInfo::class)
                        ->find($ui_id);
                    $em->remove($ui);
                    $em->flush();
                    $phone = false;
                }

                if(!$phone and $post->has('phone')){
                    $ui = new UserInfo();
                    $ui->setUser($user);
                    $ui->setUiKey('phone');
                    $ui->setUiValue($post->get('phone'));
                    $em->persist($ui);
                    $em->flush();
                }
            }


            $stat_arr = [
                'url' => '/card/new',
                'event_type' => 'new_card',
                'page_type' => 'form',
                'card_id' => $card->getId(),
            ];


            // ------- start of the POSTING

            // $consumerKey = 'p5YG14DfS9VIA0rvZMOhe6IpI';
            // $consumerSecret = '6JqpIYZtcq5VOvyvNUzElGQkBiCtFSAoqpDXlLnAY0V8J65yR3';
            // Owner ID	997130977372790784
            // $accessToken = '997130977372790784-i7RN2vNqTnTFm1wwwW9ZJvRjktGXBmd';
            // $accessTokenSecret = 'fYh5NqzcyhAOis7iRnzFXELRIssFVOb6bJiLfRIeOb60o';
            // $twitter = new \Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
            // $twitter->send('https://mix.rent/card/'.$card->getId());

            // ------- end of the POSTING

            if(isset($user)) $stat_arr['user_id'] = $user->getId();

            $stat->setStat($stat_arr);


            if($post->has('noMark')){
                $message = (new \Swift_Message('Пользователь не нашел свою марку'))
                    ->setFrom('mail@multiprokat.com')
                    ->setTo('mail@multiprokat.com')
                    ->setBody(
                        $this->renderView(
                            'email/newmark.html.twig',
                            array(
                                'mark' => $post->get('ownMark'),
                                'card' => $card
                            )
                        ),
                        'text/html'
                    );
                $mailer->send($message);
            }


            foreach($post->get('subField') as $fieldId=>$value) if($value!=0 and $value!=''){
                $subfield = $this->getDoctrine()
                    ->getRepository(FieldType::class)
                    ->find($fieldId);
                $storageTypeName = "\AppBundle\Entity\\".$subfield->getStorageType();
                $storage = new $storageTypeName();
                $storage->setCard($card);
                $storage->setCardFieldId($fieldId);
                $storage->setValue($value);

                $em->persist($storage);
            }

            if ($post->has('feature')) foreach ($post->get('feature') as $featureId=>$featureValue){
                $feature = $this->getDoctrine()
                    ->getRepository(Feature::class)
                    ->find($featureId);
                $cardFeature = new CardFeature();
                $cardFeature->setCard($card);
                $cardFeature->setFeature($feature);
                $em->persist($cardFeature);
            }

            if ($post->has('price')) foreach ($post->get('price') as $priceId=>$priceValue) if ($priceValue!="") {
                $price = $this->getDoctrine()
                    ->getRepository(Price::class)
                    ->find($priceId);
                $cardPrice = new CardPrice();
                $cardPrice->setCard($card);
                $cardPrice->setPrice($price);
                $cardPrice->setValue($priceValue);
                $em->persist($cardPrice);
            }

            $em->flush();

            $fu->uploadImages($card);


            //if($this->get('session')->has('admin') and isset($new_card) and $card->getCity()->getCountry() == 'RUS'){
            if($this->get('session')->has('admin') and isset($new_card)){


                $main_foto = $this->getDoctrine()
                    ->getRepository(Foto::class)
                    ->findOneBy(['cardId'=>$card->getId(), 'isMain'=>1]);


//                $dql = 'SELECT c,f,p FROM AppBundle:Card c LEFT JOIN c.fotos f LEFT JOIN c.cardPrices p WHERE c.id='.$card->getId();
//                $query = $em->createQuery($dql);
//                $this_card = $query->getResult()[0];


                //dump($main_foto);

//                $main_foto = $this_card->getFotos()[0];
//                foreach($this_card->getFotos() as $f){
//                    if($f->getIsMain()) $main_foto = $f;
//                }


                $prices = $this->getDoctrine()
                    ->getRepository(CardPrice::class)
                    ->findBy(['cardId'=>$card->getId()]);


                //dump($prices);

                $c_price = '';
                $c_ed = '';
                foreach ($prices as $p){
                    if($p->getPrice()->getId() == 2) {
                        $c_price = $p->getValue();
                        $c_ed = '/день';
                    }
                    if($p->getPrice()->getId() == 1) {
                        $c_price = $p->getValue();
                        $c_ed = '/час';
                    }
                    if($p->getPrice()->getId() == 6 and $c_price == '') {
                        $c_price = $p->getValue();
                        $c_ed = '';
                    }
                }

                //

//                $message = (new \Swift_Message('Ваша компания теперь на сайте multiprokat.com. Мы разместили ваше объявление: '.$card->getMarkModel()->getMark()->getHeader().' '.$card->getMarkModel()->getHeader()))
//                    ->setFrom('mail@multiprokat.com','Multiprokat.com - прокат и аренда транспорта')
//                    ->setTo($user->getEmail())
//                    ->setBcc('mail@multiprokat.com')
//                    ->setBody(
//                        $this->renderView(
//                            'email/admin_registration.html.twig',
//                            array(
//                                'header' => $user->getHeader(),
//                                'password' => $user->getTempPassword(),
//                                'email' => $user->getEmail(),
//                                'card' => $card,
//                                'main_foto' => 'http://multiprokat.com/assets/images/cards/'.$main_foto->getFolder().'/t/'.$main_foto->getId().'.jpg',
//                                'c_price' => $c_price,
//                                'c_ed' => $c_ed
//                            )
//                        ),
//                        'text/html'
//                    );
//                $mailer->send($message);


                $mgc->sendMG($user->getEmail(),'Your company in mix.rent now. We place this listing for you: '.$card->getMarkModel()->getMark()->getHeader().' '.$card->getMarkModel()->getHeader(),$this->renderView(
                        $_SERVER['LANG'] == 'ru' ? 'email/admin_registration.html.twig' : 'email/admin_registration_'.$_SERVER['LANG'].'.html.twig',
                    array(
                        'header' => $user->getHeader(),
                        'password' => $user->getTempPassword(),
                        'email' => $user->getEmail(),
                        'card' => $card,
                        'main_foto' => 'http://mix.rent/assets/images/cards/'.$main_foto->getFolder().'/t/'.$main_foto->getId().'.jpg',
                        'c_price' => $c_price,
                        'c_ed' => $c_ed
                    )
                    ));



                $user->setTempPassword('');
                $em->persist($user);
                $em->flush();
            }



            $mc->updateCityTotal($card->getCityId(),$card->getModelId());

            $markmenu->updateModelTotal($card->getModelId());


            if ($this->get('session')->has('admin')) {
                $card->setIsActive(true);
                $em->persist($card);
                $em->flush();

                $user->setCardCounter($user->getCardCounter() + 1);
                $em->persist($user);
                $em->flush();

                $response = $this->redirectToRoute('admin_main');

            } elseif($user->getAccountTypeId() == 1 or $user->getCardCounter() == 0 or $is_new_user) {
                $card->setIsActive(true);
                $em->persist($card);
                $em->flush();

                $user->setCardCounter($user->getCardCounter() + 1);
                $em->persist($user);
                $em->flush();

                $response = $this->redirect('/user/cards');

            } else {

//                // PayPal settings
//                $paypal_email = 'multiprokat.msk@gmail.com';
//                $return_url = 'https://mix.rent/paypalSuccess';
//                $cancel_url = 'https://mix.rent/paypalCancel';
//                $notify_url = 'https://mix.rent/paypalPayment';

                if($post->get('payment_system') == 'paypal') {

                    if ($post->has('one_card')) {
                        $cost = '7.00';
                        $custom = 'card_' . $card->getId();
                        $item_name = 'One card payment';
                    }
                    if ($post->has('pay_pro')) {
                        $cost = '99.99';
                        $custom = 'cardpro_' . $card->getId() . ',' . $user->getId();
                        $item_name = 'PRO Account';
                    }

                    $url = "https://api.paypal.com/v1/oauth2/token";
                    $headers = array(
                        'Accept' => 'application/json',
                        'Accept-Language' => 'en_US',
                    );

                    //$clientID = 'AVtyX4DQ_AxvLHzGbdGk3meMLtJD6vNPEcR1Ffqq23AKfZAqOWyUSb_QXES9_l25nPdITbiNJVQLenOz';
                    //$clientSecret = 'EAWY5q29JVzJbcfX4oM0GmsEy987zoD-_fyps0yRTg__pSa1SFwR1uOMdwFSjJtPDwbtIEwmm9dfSXv_';

                    $clientID = 'AdY3c4Ha4pY1nE3m2DUdSUZH651XUDz_iMp2sGY2ba7_BdWQfPDMNgWxV7IgE1Fy5V23GCot3GI3Popy';
                    $clientSecret = 'EJ-dt00hpLDAj73WnM6oylCK-_TfaDIQlof3dpVlvA9LSMAIDsMcIqcqKEa1duDhq6Du9gaNEIF4E-cu';

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_USERPWD, $clientID . ':' . $clientSecret);
                    $curl = curl_exec($ch);
                    //curl_close($ch);

                    //dump($curl);

                    $x = json_decode($curl, TRUE);
                    $accesstoken = $x['access_token'];

                    //$cost = '99.99';
                    $data = '{"intent":"sale","redirect_urls":{"return_url":"https://mix.rent/paypalResult","cancel_url":"https://mix.rent/paypalCancel"},"payer":{"payment_method":"paypal"},"transactions":[{"amount":{"total":"' . $cost . '","currency":"USD"},"item_list":{"items":[{"quantity":"1","name":"' . $item_name . '","price":"' . $cost . '","currency":"USD"}]},"custom":"' . $custom . '"}]}';

                    //$data = '{"intent":"sale","redirect_urls":{"return_url":"https://mix.rent/paypalResult","cancel_url":"https://mix.rent/paypalCancel"},"payer":{"payment_method":"paypal"},"transactions":[{"amount":{"total":"'.$item_amount.'","currency":"RUB"},"custom":"'.$custom.'"}]}';

                    $saleurl = "https://api.paypal.com/v1/payments/payment";

                    $sale = curl_init();
                    curl_setopt($sale, CURLOPT_URL, $saleurl);
                    curl_setopt($sale, CURLOPT_VERBOSE, TRUE);
                    curl_setopt($sale, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($sale, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($sale, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($sale, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($sale, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $accesstoken));

                    $finalsale = curl_exec($sale);
                    curl_close($sale);

                    //dump($finalsale);

                    $url = json_decode($finalsale, TRUE);

                    //dump($finalsale);

                    $response = new RedirectResponse($url['links'][1]['href']);
                }

            }

            if (!$this->get('session')->has('admin')) {
                $this->addFlash(
                    'notice',
                    'Do not forget to share your listing!'
                );
            }

        }

        if(!isset($response) or $response == '') $response = $this->redirect('/');
        return $response;
    }

}


// https://chart.googleapis.com/chart?chs=225x225&chld=L|2&cht=qr&chl=bitcoin:1MoLoCh1srp6jjQgPmwSf5Be5PU98NJHgx?amount=.01%26label=Moloch.net%26message=Donation

// https://blockchain.info/tobtc?currency=USD&value=7

// https://develop.smartcontract.ru/api/acq.create/1BxmNSkA9Mhd4EHv1mCrsRFfBdmgb7HPeN/2

// 1P2B7bkiHYrbm1ZAdEgqhdkWzbJqeKNSBM

// 1P2B7bkiHYrbm1ZAdEgqhdkWzbJqeKNSBM 1P2B7bkiHYrbm1ZAdEgqhdkWzbJqeKNSBM 1P2B7bkiHYrbm1ZAdEgqhdkWzbJqeKNSBM 1P2B7bkiHYrbm1ZAdEgqhdkWzbJqeKNSBM 0.00087597 1P2B7bkiHYrbm1ZAd  0.00087597  EgqhdkWzbJqeKNSBM