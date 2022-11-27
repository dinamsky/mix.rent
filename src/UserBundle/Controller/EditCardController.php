<?php

namespace UserBundle\Controller;

use AppBundle\Entity\Deleted;
use AppBundle\Menu\ServiceStat;
use MarkBundle\Entity\CarModel;
use MarkBundle\Entity\CarMark;
use UserBundle\Entity\UserOrder;
use AppBundle\Entity\CardFeature;
use AppBundle\Entity\CardPrice;
use AppBundle\Entity\Feature;
use AppBundle\Entity\Foto;
use AppBundle\Entity\Country;
use AppBundle\Entity\Price;
use AppBundle\Entity\Tariff;
use AppBundle\Foto\FotoUtils;
use UserBundle\Entity\User;
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

class EditCardController extends Controller
{
    /**
     * @Route("/user/edit/card/{cardId}")
     */
    public function editCardAction($cardId, MenuGeneralType $mgt, MenuMarkModel $markmenu, MenuCity $mc, SubFieldUtils $sf, EntityManagerInterface $em, Request $request, ServiceStat $stat)
    {
        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($cardId);

        if ( $this->get('session')->get('logged_user') !== null
            and $card->getUserId() != $this->get('session')->get('logged_user')->getId()
        ) return new Response("",404);

        if ( $this->get('session')->get('logged_user') === null
            and $this->get('session')->get('admin') === null
        ) return new Response("",404);

        if ( $this->get('session')->has('admin')
            and  $this->get('session')->get('admin')->getId() != $card->getAdminId()
            and  $this->get('session')->get('admin')->getRole() != 'superadmin'
        ) return new Response("",404);


        if(!$this->get('session')->has('admin')) {
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($this->get('session')->get('logged_user')->getId());

            if ($user->getIsBanned()) return new Response("", 404);
        }

        $conditions = $this->getDoctrine()
            ->getRepository(State::class)
            ->findAll();

        $colors = $this->getDoctrine()
            ->getRepository(Color::class)
            ->findAll();

        $generalType = $this->getDoctrine()
        ->getRepository(GeneralType::class)
        ->find($card->getGeneralTypeId());

        $model = $this->getDoctrine()
            ->getRepository(CarModel::class)
            ->find($card->getModelId());

        $marks = $markmenu->getMarks($model->getCarTypeId());

        $mark = $this->getDoctrine()
            ->getRepository(CarMark::class)
            ->find($model->getCarMarkId());

        $models = $this->getDoctrine()
            ->getRepository(CarModel::class)
            ->findBy(['carMarkId' => $model->getCarMarkId()]);

        $city = $this->getDoctrine()
            ->getRepository(City::class)
            ->find($card->getCityId());

        $features = $this->getDoctrine()
            ->getRepository(Feature::class)
            ->findBy(['parent'=>null]);

        $prices = $this->getDoctrine()
            ->getRepository(Price::class)
            ->findAll();

        $tariffs = $this->getDoctrine()
            ->getRepository(Tariff::class)
            ->findAll();

        $gt = $this->getDoctrine()
            ->getRepository(GeneralType::class)
            ->find($card->getGeneralTypeId());

        if ($card->getFieldIntegers()->isEmpty()) $subfields = false;
        else $subfields = $sf->getSubFieldsEdit($card);

        $gtid = $card->getGeneralTypeId();
        $pgtid = $card->getGeneralType()->getParentId();
        if($pgtid == null) $pgtid = $card->getGeneralTypeId();

        $query = $em->createQuery('SELECT c FROM AppBundle:City c WHERE c.total > 0 ORDER BY c.total DESC, c.header ASC');
        $popular_city = $query->getResult();


        $query = $em->createQuery('SELECT c.id FROM AppBundle:Card c ORDER BY c.views DESC');
        $query->setMaxResults(9);
        foreach ($query->getScalarResult() as $cars_id) $cars_ids[] = $cars_id['id'];
        $dql = 'SELECT c,f,p FROM AppBundle:Card c LEFT JOIN c.fotos f LEFT JOIN c.cardPrices p WHERE c.id IN ('.implode(",",$cars_ids).') ORDER BY c.views DESC';
        $query = $em->createQuery($dql);
        $promoted = $query->getResult();


        $stat_arr = [
            'url' => '/user/edit/card/'.$card->getId(),
            'event_type' => 'set_form',
            'page_type' => 'form',
            'user_id' => $card->getUserId(),
        ];
        $stat->setStat($stat_arr);

        $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.total !=0 ORDER BY g.total DESC');
        $generalTypes = $query->getResult();

        $countries = $this->getDoctrine()
                ->getRepository(Country::class)
                ->findBy([],['header'=>'ASC']);

        return $this->render('card/card_edit.html.twig',[
            'card' => $card,
            'conditions' => $conditions,
            'colors' => $colors,
            'generalTopLevel' => $mgt->getTopLevel(),
            'generalSecondLevel' => $mgt->getSecondLevel($generalType->getParentId()),
            'gt' => $gt,
            'gtid' => $gtid,
            'pgtid' => $pgtid,

            //'countries' => $mc->getCountry(),
            'mark' => $mark,
            'model' => $model,
            'marks' => $marks,
            'models' => $models,
            'mark_groups' => $markmenu->getGroups(),
            'countryCode' =>$city->getCountry(),
            'regionId' => $city->getParent()->getId(),
            'regions' => $mc->getRegion($city->getCountry()),
            'cities' => $city->getParent()->getChildren(),
            'city' => $city,
            'cityId' => $city->getId(),
            'subfields' => $subfields,
            'features' => $features,
            'prices' => $prices,
            'tariffs' => $tariffs,
            'popular_city' => $popular_city,
            'generalTypes' => $generalTypes,
            'lang' => $_SERVER['LANG'],
            'countries' => $countries,
            'promoted' => $promoted
        ]);
    }

    /**
     * @Route("/card/update")
     */
    public function saveCardAction(Request $request, FotoUtils $fu, ServiceStat $stat)
    {

        $post = $request->request;

        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($post->get('cardId'));

        $em = $this->getDoctrine()->getManager();

        if ($post->has('delete')){
            $del = new Deleted();
            $del->setUserId($card->getUserId());
            $del->setCardId($card->getId());
            $del->setCityId($card->getCityId());
            $del->setModelId($card->getModelId());
            $del->setGeneralTypeId($card->getGeneralTypeId());
            $em->persist($del);

            $fu->deleteAllFotos($card);
            $em->remove($card);
            $em->flush();
            if ($this->get('session')->get('admin') === null) return $this->redirectToRoute('user_cards');
            else return $this->redirectToRoute('admin_main');
        }

        $card->setHeader(strip_tags($post->get('header')));
        $card->setCurrency(strip_tags($post->get('currency')));
        $card->setContent(strip_tags($post->get('content')));
        $card->setAddress(strip_tags($post->get('address')));
        $card->setCoords($post->get('coords'));
        $card->setVideo($post->get('video'));
        $card->setStreetView($post->get('streetView'));
        $card->setDeliveryStatus($post->get('deliveryStatus'));
        $card->setDateRentStart(new \DateTime(implode("-",array_reverse(explode(".",$post->get('date_rent_start'))))));
        $card->setDateRentFinish(new \DateTime(implode("-",array_reverse(explode(".",$post->get('date_rent_finish'))))));

        $model = $this->getDoctrine()
            ->getRepository(CarModel::class)
            ->find($post->get('modelId'));
        $card->setMarkModel($model);

        if ($post->get('generalTypeId') == 0) $gt = $post->get('generalTypeTopLevelId');
        else $gt = $post->get('generalTypeId');

        $generalType = $this->getDoctrine()
            ->getRepository(GeneralType::class)
            ->find($gt);
        $card->setGeneralType($generalType);

        $city = $this->getDoctrine()
            ->getRepository(City::class)
            ->find($post->get('cityId'));
        $card->setCity($city);

        $card->setProdYear($post->get('prodYear'));

        $condition = $this->getDoctrine()
            ->getRepository(State::class)
            ->find($post->get('conditionId'));
        $card->setCondition($condition);

        $card->setServiceTypeId($post->get('serviceTypeId'));

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($card->getUserId());
        $card->setUser($user);

        $color = $this->getDoctrine()
            ->getRepository(Color::class)
            ->find($post->get('colorId'));
        $card->setColor($color);

        $tariff = $this->getDoctrine()
            ->getRepository(Tariff::class)
            ->find($post->get('tariffId'));

        //$card->setTariff($tariff);

        $em->persist($card);

        $stat_arr = [
            'url' => '/user/edit/card/'.$card->getId(),
            'event_type' => 'edit_card',
            'page_type' => 'form',
            'user_id' => $card->getUserId(),
        ];
        $stat->setStat($stat_arr);


        if ($post->get('subField') !== null) foreach($post->get('subField') as $fieldId=>$value) {

            $subfield = $this->getDoctrine()
                ->getRepository(FieldType::class)
                ->find($fieldId);
            //numberInput

            if($subfield->getFormElementType() == 'ajaxMenu' and ($value == 0 or $value == ''))
            {

            } else {

                if($subfield->getFormElementType() == 'numberInput' and $value == '') $value = 0;

                $dql = 'SELECT s FROM AppBundle:' . $subfield->getStorageType() . ' s WHERE s.cardId = ?1 AND s.cardFieldId = ?2';
                $query = $em->createQuery($dql);
                $query->setParameter(1, $card->getId());
                $query->setParameter(2, $fieldId);
                try {
                    $storage = $query->getSingleResult();
                } catch (\Doctrine\ORM\NoResultException $e) {
                    $storageTypeName = "\AppBundle\Entity\\" . $subfield->getStorageType();
                    $storage = new $storageTypeName();
                    $storage->setCard($card);
                    $storage->setCardFieldId($fieldId);
                }

                $storage->setValue($value);
                $em->persist($storage);
            }
        }

        $query = $em->createQuery('DELETE AppBundle\Entity\CardFeature c WHERE c.cardId = ?1');
        $query->setParameter(1, $card->getId());
        $query->execute();

        if ($post->has('feature')) foreach($post->get('feature') as $fid=>$f) {
            $feature = $this->getDoctrine()
                ->getRepository(Feature::class)
                ->find($fid);
            $cardFeature = new CardFeature();
            $cardFeature->setCard($card);
            $cardFeature->setFeature($feature);
            $em->persist($cardFeature);
        };

        $query = $em->createQuery('DELETE AppBundle\Entity\CardPrice c WHERE c.cardId = ?1');
        $query->setParameter(1, $card->getId());
        $query->execute();

        if ($post->has('price')) foreach($post->get('price') as $priceId=>$priceValue) {
            if ($priceValue != '' and $priceValue !=0) {
                $price = $this->getDoctrine()
                    ->getRepository(Price::class)
                    ->find($priceId);
                $cardPrice = new cardPrice();
                $cardPrice->setCard($card);
                $cardPrice->setPrice($price);
                $cardPrice->setValue($priceValue);
                $em->persist($cardPrice);
            }
        };

        $em->flush();

        $fu->uploadImages($card);

        if ($post->has('change_tariff') and $tariff->getId() > 1){

            if ($this->get('session')->has('admin')){ // admin able to change tariff without payment
                $card->setTariff($tariff);
                $card->setDateTariffStart(new \DateTime());
                $em->persist($card);
                $em->flush();
                return $this->redirectToRoute('search');
            } else {
                $order = new UserOrder();
                $order->setUser($user);
                $order->setCard($card);
                $order->setTariff($tariff);
                $order->setPrice(ceil($tariff->getPrice()*100/110));
                $order->setOrderType('tariff_' . $tariff->getId());
                $order->setStatus('new');
                $em->persist($order);
                $em->flush();

                $mrh_login = "multiprokat";
                $mrh_pass1 = "Wf1bYXSd5V8pKS3ULwb3";
                $inv_id = $order->getId();
                $inv_desc = "set_tariff";
                $out_summ  = ceil($tariff->getPrice()*100/110);

                $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

                $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&" .
                    "OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";

                $stat_arr = [
                    'url' => '/user/edit/card/'.$card->getId(),
                    'event_type' => 'go_pay_tariff',
                    'page_type' => $tariff->getId(),
                    'user_id' => $card->getUserId(),
                ];
                $stat->setStat($stat_arr);



                return new RedirectResponse($url);
            }

        } else {
            if($post->has('change_tariff') and $tariff->getId() == 1){
                $card->setTariff($tariff);
                $em->persist($card);
                $em->flush();
            }
            if ($this->get('session')->get('admin') === null) return $this->redirectToRoute('user_cards');
            else return $this->redirectToRoute('admin_main');
        }
    }

    /**
     * @Route("/ajaxMakeMainSlider", name="ajaxMakeMainSlider")
     */
    public function ajaxMakeMainSliderAction(EntityManagerInterface $em, Request $request)
    {
        $post = $request->request;

        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($post->get('card_id'));

        $card->setIsTop(true);
        $em->persist($card);
        $em->flush();
        return new Response();
    }

    /**
     * @Route("/ajaxMakeBestOffer", name="ajaxMakeBestOffer")
     */
    public function ajaxMakeBestOfferAction(EntityManagerInterface $em, Request $request)
    {
        $post = $request->request;

        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($post->get('card_id'));

        $card->setIsBest(true);
        $em->persist($card);
        $em->flush();
        return new Response();
    }
}
