<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Deleted;
use AppBundle\Entity\Mark;
use AppBundle\Entity\SubField;
use AppBundle\Menu\MenuGeneralType;
use AppBundle\Menu\MenuCity;
use AppBundle\Menu\MenuMarkModel;
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
use UserBundle\Entity\Blocking;

class ShowCardController extends Controller
{

    /**
     * @Route("/card/{id}", requirements={"id": "\d+"}, name="showCard")
     */
    public function showCardAction($id, MenuGeneralType $mgt, SubFieldUtils $sf, MenuCity $mc, MenuMarkModel $mm, Request $request, ServiceStat $stat)
    {
        $_t = $this->get('translator');

        $topSlider = $this->getDoctrine()
                    ->getRepository(Card::class)
                    ->getTopSlider($this->get('session')->get('city')->getId());

        $em = $this->get('doctrine')->getManager();

        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($id);

        $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g ORDER BY g.total DESC');
        $generalTypes = $query->getResult();

        if (!$card) {
            $deleted = $this->getDoctrine()
                ->getRepository(Deleted::class)
                ->findOneBy(['cardId' => $id]);
            if ($deleted) {
                $city = $this->getDoctrine()
                    ->getRepository(City::class)
                    ->find($deleted->getCityId());
                $general = $this->getDoctrine()
                    ->getRepository(GeneralType::class)
                    ->find($deleted->getGeneralTypeId());
                $model = $this->getDoctrine()
                    ->getRepository(CarModel::class)
                    ->find($deleted->getModelId());

                $dql = 'SELECT c.id FROM AppBundle:Card c WHERE c.generalTypeId = ' . $general->getId() . ' AND c.cityId = ' . $city->getId() . ' ORDER BY c.dateUpdate DESC';
                $query = $em->createQuery($dql);
                $query->setMaxResults(10);
                foreach ($query->getScalarResult() as $row) $sim_ids[] = $row['id'];
                if (isset($sim_ids)) {
                    $dql = 'SELECT c,p,f FROM AppBundle:Card c LEFT JOIN c.cardPrices p LEFT JOIN c.fotos f WHERE c.id IN (' . implode(",", $sim_ids) . ') ORDER BY c.dateUpdate DESC';
                    $query = $em->createQuery($dql);
                    $similar = $query->getResult();
                } else $similar = false;

                $dql = 'SELECT c.id FROM AppBundle:Card c WHERE c.cityId = ' . $city->getId() . ' ORDER BY c.dateUpdate DESC';
                $query = $em->createQuery($dql);
                $query->setMaxResults(10);
                foreach ($query->getScalarResult() as $row) $sim_ids[] = $row['id'];
                if (isset($sim_ids)) {
                    $dql = 'SELECT c,p,f FROM AppBundle:Card c LEFT JOIN c.cardPrices p LEFT JOIN c.fotos f WHERE c.id IN (' . implode(",", $sim_ids) . ') ORDER BY c.dateUpdate DESC';
                    $query = $em->createQuery($dql);
                    $allincity = $query->getResult();
                } else $allincity = false;

                return $this->render('card/card_deleted.html.twig', [
                    'city' => $city,
                    'cityId' => $city->getId(),
                    'similar' => $similar,
                    'allincity' => $allincity,
                    'general' => $general,
                    'model' => $model,
'lang' => $_SERVER['LANG'],
                    'topSlider' => $topSlider,
                    'generalTypes' => $generalTypes,
                    'car_type_id' => $general->getCarTypeIds(),
                    'in_city' => $city->getUrl(),

                ]);
            } else throw $this->createNotFoundException(); //404
        };

        if ($card->getIsActive() == 0) {
            $this->addFlash(
                'notice',
                'Listing №' . $card->getId() . ' temporarily unavailable!<br>If it is yours -<br>please activate it through link in registration email,<br>then this listing will be available.'
            );
            return $this->redirectToRoute('homepage');
        }

        if ($card->getUser()->getIsBanned()) return new Response("", 404);

        $views = $this->get('session')->get('views');
        if (!isset($views[$card->getId()])) {
            $views[$card->getId()] = 1;
            $this->get('session')->set('views', $views);
            $card->setViews($card->getViews() + 1);
            $em->persist($card);
            $em->flush();
        }

        $subFields = $sf->getCardSubFields($card);

        $city = $card->getCity();

        $country = $city->getCountry();

        $google_api_key = 'AIzaSyCsocnEg6RI_lrli1u59KQsYlkoX12lXgE';
        $target_locale = 'en';
        $source_locale = 'ru';

        if ($_SERVER['LANG'] == 'ru') {
            $target_locale = 'ru';
            $source_locale = 'en';
        }

        $need_to_translate = urlencode(str_replace('<br>','^',$card->getContent()));

        $to_google = "https://translation.googleapis.com/language/translate/v2?q=$need_to_translate&target=$target_locale&source=$source_locale&key=$google_api_key";

        // https://translation.googleapis.com/language/translate/v2?q=Текст для перевода&target=en&source=ru&key=AIzaSyCsocnEg6RI_lrli1u59KQsYlkoX12lXgE

        $em = $this->getDoctrine()->getManager();

        //dump($card->getContentEn());

//        if($_SERVER['LANG'] == 'en' and is_null($card->getContentEn()) and $need_to_translate != ''){
//            $translate_result = json_decode(file_get_contents($to_google),true);
//            $t_en = $translate_result['data']['translations'][0]['translatedText'];
//            $card->setContentEn($t_en);
//            $em->persist($card);
//            $em->flush();
//            $card_content = $t_en;
//        }
//
//        if($_SERVER['LANG'] == 'ru' and is_null($card->getContentRu()) and $need_to_translate != ''){
//            $translate_result = json_decode(file_get_contents($to_google),true);
//            $t_ru = $translate_result['data']['translations'][0]['translatedText'];
//            $card->setContentRu($t_ru);
//            $em->persist($card);
//            $em->flush();
//            $card_content = $t_ru;
//        }

//        if ($_SERVER['LANG'] == 'ru' and $card->getContentRu() != '') $card_content = $card->getContentRu();
//        if ($_SERVER['LANG'] == 'en' and $card->getContentEn() != '') $card_content = $card->getContentEn();

        $card_content = $card->getContent();


        if ($card->getVideo() != '') $video = explode("=", $card->getVideo())[1];
        else $video = false;

        if ($card->getStreetView() != '') $streetView = unserialize($card->getStreetView());
        else $streetView = false;


        // ---------------------------- start of similar ----------------------------------

        $dql = 'SELECT c.id FROM AppBundle:Card c WHERE c.cityId=?1 AND c.id != ?2 AND c.modelId=?3 ORDER BY c.dateUpdate DESC'; // -- get by model
        $query = $em->createQuery($dql);
        $query->setParameter(1, $card->getCityId());
        $query->setParameter(2, $card->getId());
        $query->setParameter(3, $card->getModelId());
        $query->setMaxResults(6);

        if (count($query->getScalarResult()) < 1) { // -- get by mark
            $dql = 'SELECT m.id FROM MarkBundle:CarModel m WHERE m.carMarkId=?1';
            $query = $em->createQuery($dql);
            $query->setParameter(1, $card->getMarkModel()->getCarMarkId());
            foreach ($query->getScalarResult() as $row) {
                $model_ids[] = $row['id'];
            }
            $dql = 'SELECT c.id FROM AppBundle:Card c WHERE c.cityId=?1 AND c.id != ?2 AND c.modelId IN ('.implode(",",$model_ids).') ORDER BY c.dateUpdate DESC';
            $query = $em->createQuery($dql);
            $query->setParameter(1, $card->getCityId());
            $query->setParameter(2, $card->getId());
            $query->setMaxResults(6);

            if (count($query->getScalarResult()) < 1) {
                $dql = 'SELECT c.id FROM AppBundle:Card c JOIN c.tariff t WHERE c.cityId=?1 AND c.id != ?2 AND c.generalTypeId = ' . $card->getGeneralTypeId() . ' ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';
                $query = $em->createQuery($dql);
                $query->setParameter(1, $card->getCityId());
                $query->setParameter(2, $card->getId());
                $query->setMaxResults(6);

                if (count($query->getScalarResult()) < 1) {
                    $dql = 'SELECT c.id FROM AppBundle:Card c JOIN c.tariff t WHERE c.id != ?2 AND c.generalTypeId = ' . $card->getGeneralTypeId() . ' ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';
                    $query = $em->createQuery($dql);
                    $query->setParameter(2, $card->getId());
                    $query->setMaxResults(6);

                    if (count($query->getScalarResult()) < 1) {
                        $dql = 'SELECT c.id FROM AppBundle:Card c JOIN c.tariff t WHERE c.id != ?2 ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';
                        $query = $em->createQuery($dql);
                        $query->setParameter(2, $card->getId());
                        $query->setMaxResults(6);
                    }
                }

            }
        }

        foreach ($query->getScalarResult() as $row) {
            $sim_ids[] = $row['id'];
        }
        $sim_ids = implode(",", $sim_ids);

        $dql = 'SELECT c,p,f FROM AppBundle:Card c JOIN c.tariff t LEFT JOIN c.cardPrices p LEFT JOIN c.fotos f WHERE c.id IN (' . $sim_ids . ') ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';
        $query = $em->createQuery($dql);

        $similar = $query->getResult();

        // ---------------------------- end of similar ----------------------------------

        $model = $mm->getModel($card->getModelId());
        $mark = $mm->getMark($model->getCarMarkId());
        $models = $mm->getModels($model->getCarMarkId());
        $marks = $mm->getMarks($model->getCarTypeId());

        $user_foto = false;
        foreach ($card->getUser()->getInformation() as $info){
           if($info->getUiKey() == 'foto' and $info->getUiValue()!='') $user_foto =  '/assets/images/users/t/'.$info->getUiValue().'.jpg';
        }


        $general = $this->getDoctrine()
            ->getRepository(GeneralType::class)
            ->find($card->getGeneralTypeId());


        $pgtid = $card->getGeneralType()->getParentId();
        if($pgtid == null) $pgtid = $card->getGeneralTypeId();

        $mainFoto = '';
        foreach($card->getFotos() as $foto){
            if ($foto->getIsMain()) $mainFoto = '/assets/images/cards/'.$foto->getFolder().'/'.$foto->getId().'.jpg';
        }


        $seo = [];
        if ($card->getServiceTypeId() == 1) $seo['service'] = $_t->trans('Прокат');
        if ($card->getServiceTypeId() == 2) $seo['service'] = $_t->trans('Аренда');
        if ($card->getServiceTypeId() == 3) $seo['service'] = $_t->trans('Аренда с правом выкупа');
//        $seo['type']['singular'] = $general->getChegoSingular();
//        $seo['type']['plural'] = $general->getChegoPlural();
        if ($_SERVER['LANG'] == 'ru') $seo['type']['singular'] = $general->getChegoSingular(); else $seo['type']['singular'] = $general->getSingularEn();
        if ($_SERVER['LANG'] == 'ru') $seo['type']['plural'] = $general->getChegoPlural(); else $seo['type']['plural'] = $general->getUrl();
        $seo['mark'] = $mark->getHeader();
        $seo['model'] = $model->getHeader();
        $seo['city']['chto'] = $city->getHeader();
        $seo['city']['gde'] = $city->getGde();
        $seo['city']['url'] = $city->getUrl();
        $seo['city']['iso'] = $city->getIso();
        $seo['city']['country'] = $city->getCountry();
        $seo['city']['id'] = $city->getId();
        $seo['city']['header'] = $city->getHeader();

        $mark_arr = $mm->getExistMarks('',$mark->getCarTypeId());
        $mark_arr_sorted = $mark_arr['sorted_marks'];
        $mark_arr_typed = $mark_arr['typed_marks'];
        $models_in_mark = $mark_arr['models_in_mark'];

        //dump($city);
        //dump($mark_arr);



        $star = 0;
        $total_opinions = 0;
        foreach($card->getOpinions() as $op){
            $star = $star + $op->getStars();
            $total_opinions++;
        }
        if ($total_opinions > 0) $opinions = round($star/$total_opinions, 1);
        else $opinions = 0;


        if ($this->get('session')->has('city')){
            $in_city = $this->get('session')->get('city');
            if(is_array($in_city)) $in_city = $in_city[0]->getUrl();
            else $in_city = $city->getUrl();
        }
        else $in_city = $city->getUrl();


        $stat->setStat([
            'url' => $request->getPathInfo(),
            'event_type' => 'visit',
            'page_type' => 'card',
            'card_id' => $card->getId(),
            'user_id' => $card->getUserId(),
        ]);


        $bodyType = false;
        foreach($subFields as $sf){
            if($sf['value'] instanceof SubField){
                if($sf['value']->getFieldId() == 3) $bodyType = $sf['value'];
            }
        }

        $is_admin_card = false;
        foreach ($card->getUser()->getInformation() as $ui){
            if($ui->getUiKey() == 'phone'){
                $ph = substr(preg_replace('/[^0-9]/', '', $ui->getUiValue()),1);
                $emz = explode("@",$card->getUser()->getEmail());


                if ($ph == $emz[0]) $is_admin_card = true;
            }
        }

        $blk = [];
        $blockings = $this->getDoctrine()
                    ->getRepository(Blocking::class)
                    ->findBy([
                        'userId' => $card->getUser()->getId(),
                    ]);
        foreach ($blockings as $b){
            $blk[$b->getVisitorId()] = 1;
        }




        $prs = [];
        //dump($card->getCardPrices());
        foreach($card->getCardPrices() as $pr){
            $prs[$pr->getPriceId()] = $pr;
        }
        //dump($prs);
        if(isset($prs[6])) $price = $prs[6];
        if(isset($prs[1])) $price = $prs[1];
        if(isset($prs[2])) $price = $prs[2];

        return $this->render('card/card_show.html.twig', [

            'card' => $card,
            'streetView' => $streetView,
            'video' => $video,

            'sub_fields' =>$subFields,

//            'general_type' => $card->getGeneralTypeId(),
            'city' => $city,

//            'countries' => $mc->getCountry(),
//            'countryCode' => $city->getCountry(),
//            'regionId' => $city->getParentId(),
//            'regions' => $mc->getRegion($city->getCountry()),
//            'cities' => $mc->getCities($city->getParentId()),
            'cityId' => $card->getCityId(),

//            'generalTopLevel' => $mgt->getTopLevel(),
//            'generalSecondLevel' => $mgt->getSecondLevel($card->getGeneralType()->getParentId()),
//            'pgtid' => $pgtid,
//            'gtid' => $card->getGeneralTypeId(),
            'similar' => $similar,

            'mark_groups' => $mm->getGroups(),
            'mark' => $mark,
            'model' => $model,
            'marks' => $marks,
            'models' => $models,
            'general' => $general,

            'user_foto' => $user_foto,
            'mainFoto' => $mainFoto,
            'seo' => $seo,

            'card_content' => $card_content,

            'mark_arr_sorted' => $mark_arr_sorted,
            'models_in_mark' => $models_in_mark,

            'generalTypes' => $generalTypes,
            'car_type_id' => $mark->getCarTypeId(),
            'opinions' => $opinions,
            'total_opinions' => $total_opinions,
            'in_city' => $in_city,
            'bodyType' => $bodyType,
            'page_type' => 'card',
            'lang' => $_SERVER['LANG'],
            'reserved' => $card->getDateRentFinish() > new \DateTime() ? true : false,
            'is_admin_card' => $is_admin_card,

            'blockings' => $blk,

            'topSlider' => $topSlider,

            'price' => $price,

            'phone' => false

        ]);
    }

    /**
     * @Route("/card_share/{id}", requirements={"id": "\d+"}, name="shareCard")
     */
    public function shareCardAction($id)
    {
        $this->get('session')->set('share', true);

        return $this->redirect('/card/'.$id);
    }

}
