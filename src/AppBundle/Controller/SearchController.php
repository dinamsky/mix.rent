<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\City;
use AppBundle\Entity\Color;
use AppBundle\Entity\FieldInteger;
use AppBundle\Entity\FieldType;
use AppBundle\Entity\GeneralType;
use AppBundle\Entity\Mark;
use AppBundle\Entity\Seo;
use AppBundle\Entity\State;
use AppBundle\Entity\CardField;
use AppBundle\Menu\MenuCity;
use AppBundle\Menu\MenuGeneralType;
use AppBundle\Menu\MenuMarkModel;
use AppBundle\Menu\MenuSubFieldAjax;
use AppBundle\Menu\ServiceStat;
use AppBundle\SubFields\SubFieldUtils;
use MarkBundle\Entity\CarMark;
use MarkBundle\Entity\CarModel;
use UserBundle\Security\Password;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SearchController extends Controller
{
    /**
     * @Route("/rent/{city}/{service}/{general}/{mark}/{model}", name="search")
     */
    public function showCardsAction(
        $city = false, $service = false, $general = false, $mark = false, $model = false, $card = false,
        EntityManagerInterface $em, MenuGeneralType $mgt, MenuCity $mc, MenuMarkModel $mm, Request $request, ServiceStat $stat)
    {

        $mobileDetector = $this->get('mobile_detect.mobile_detector');

        $get = $request->query->all();

        $is_filter = false;
        $is_feature = false;

        if(isset($get['filter'])){
            foreach ($get['filter'] as $check_filter_id=>$check_value){
                if(array_filter($get['filter'][$check_filter_id])) $is_filter = true;
            }
        }

        if (isset($get['feature'])) $is_feature = true;

        $filter_ready = false;
        if($city and $service and $general) $filter_ready = true;

        if (strtolower($city) == 'rus') $city = false;
        if ($service == 'all') $service = false;
        if ($general == 'alltypes') $general = false;

        $view = 'grid_view';
        if (isset($get['view'])) $view = $get['view'];


        $_t = $this->get('translator');




        $is_body = false;
        $city_condition = '';
        $service_condition = '';
        $general_condition = '';
        $mark_condition = '';
        $body_condition = '';
        $order = ' ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';
        $sort = '0';
        if (isset($get['order'])){
            if($get['order'] == 'date_desc') $order = ' ORDER BY c.dateUpdate DESC';
            if($get['order'] == 'date_asc') $order = ' ORDER BY c.dateUpdate ASC';
            $sort = $get['order'];
        }

        $pgtId = 0;
        $gtId = 0;

        if($city){

            if($this->get('session')->get('city')->getUrl() != $city) {
                $city = $this->getDoctrine()
                    ->getRepository(City::class)
                    ->findOneBy(['url' => $city]);
                if(!$city) throw $this->createNotFoundException(); //404
                $this->get('session')->set('city', $city);
            } else $city = $this->get('session')->get('city');

            $countryCode = $city->getCountry();
            if($city->getChildren()->isEmpty()){
                $city_condition = 'AND c.cityId = '.$city->getId();
                $cityId = $city->getId();
                $regionId = $city->getParent()->getId();
                $cities = $city->getParent()->getChildren();
            } else {
                $cities = $city->getChildren();
                foreach($cities as $child){
                    $city_ids[] = $child->getId();
                }
                $city_condition = 'AND c.cityId IN ('.implode(',',$city_ids).')';
                $regionId = $city->getId();
                $cityId = 0;
            }
        } else {
            $countryCode = 'RUS';
            $regionId = 0;
            $cities = array();
            $cityId = 0;
            $city = new City();
            $city->setHeader('Россия');
            $city->setUrl('rus');
            $city->setGde('России');
        }

        if($service){
            if ($service == 'prokat') $service = 1;
            if ($service == 'arenda') $service = 2;
            if ($service == 'leasebuyout') $service = 3;
            $service_condition = ' AND c.serviceTypeId = '.$service;
        }



        if($general and $general != ''){
            $general = $this->getDoctrine()
                ->getRepository(GeneralType::class)
                ->findOneBy(['url' => $general]);

            if($general) {
                if ($general->getChildren()->isEmpty()) {
                    $gtId = $general->getId();
                    $general_condition = ' AND c.generalTypeId = ' . $gtId;
                    if (!$general->getParent()) $pgtId = $general->getId();
                    else $pgtId = $general->getParent()->getId();
                } else {
                    $generals = $general->getChildren();
                    foreach ($generals as $child) {
                        $general_ids[] = $child->getId();
                    }
                    $general_condition = ' AND c.generalTypeId IN (' . implode(',', $general_ids) . ',' . $general->getId() . ')';
                    $pgtId = $general->getId();
                    $gtId = 0;
                }
            } else throw $this->createNotFoundException();
        }


        if ($general and $general->getUrl() == 'cars') {
            $query = $em->createQuery('SELECT s FROM AppBundle:SubField s WHERE s.fieldId = 3');
            $bodyTypes = $query->getResult();
            foreach($bodyTypes as $bt){
                $bodyTypeArray[] = $bt->getUrl();
                $bodyTypeEntity[$bt->getUrl()] = $bt;
            }
        } else $bodyTypes = false;


        if(isset($bodyTypeArray) and (($mark and in_array($mark,$bodyTypeArray)) or ($model and in_array($model,$bodyTypeArray)))){
            if (in_array($mark,$bodyTypeArray)) $bt_value = $bodyTypeEntity[$mark]->getId();
            if (in_array($model,$bodyTypeArray)) $bt_value = $bodyTypeEntity[$model]->getId();
            $query = $em->createQuery('SELECT f.cardId FROM AppBundle:FieldInteger f WHERE f.value = '.$bt_value.' AND f.cardFieldId = 3');

            $result = $query->getScalarResult();
            $bt_ids = [0];
            foreach ($result as $row){
                $bt_ids[] = $row['cardId'];
            }
            $body_condition = 'AND c.id IN ('.implode(",",$bt_ids).')';



            if($body_condition != '')

            $query = $em->createQuery('SELECT s FROM AppBundle:SubField s WHERE s.url = ?1');
            if (in_array($mark,$bodyTypeArray)) {
                $query->setParameter(1, $mark);
                $is_body = $mark;
                $mark = false;
            }
            if (in_array($model,$bodyTypeArray)) {
                $query->setParameter(1, $model);
                $is_body = $model;
                $model = false;
            }
            $bodyType = $query->getResult()[0];
        }



        if($mark){
            if($general) {
                $mark = $this->getDoctrine()
                    ->getRepository(CarMark::class)
                    ->findOneBy(['header' => $mark, 'carTypeId' => explode(",", $general->getCarTypeIds())]);
            } else {
                $mark = $this->getDoctrine()
                    ->getRepository(CarMark::class)
                    ->findOneBy(['header' => $mark]);
            }

            if(!$mark) throw $this->createNotFoundException(); //404

            $models = $mm->getModels($mark->getId());
            foreach ($models as $child) {
                $mark_ids[] = $child->getId();
            }
            $mark_condition = ' AND c.modelId IN (' . implode(',', $mark_ids) . ')';
            $marks = $mm->getMarks($mark->getCarTypeId());
        } else {
            //$mark = array('id' => 0,'groupname'=>'', 'header'=>false, 'carTypeId'=>0);
            $mark = new CarMark();
            if ($general) {
                $mark->setCarTypeId($general->getCarTypeIds());
                $mark->setHeader('');
            }
            else {
                $mark->setCarTypeId(1);

            }
            $mark->setHeader('');

            $marks = array();
            $models = false;
        }
//
        if($model){

            $model = $this->getDoctrine()
                ->getRepository(CarModel::class)
                ->findOneBy(['header' => urldecode($model), 'carMarkId' => $mark->getId()]);

            if(!$model) throw $this->createNotFoundException(); //404



            $mark_condition = ' AND c.modelId = '.$model->getId();
        } else {
            $model = array('groupName' => 'cars','id' => 0,'header'=>$_t->trans('Любая модель'));
            if (!$models) $models = array();
        }






        $dql = 'SELECT c.id FROM AppBundle:Card c WHERE c.isActive = 1 '.$city_condition.$service_condition.$general_condition.$mark_condition.$body_condition;
        $query = $em->createQuery($dql);
        $first_result = $query->getScalarResult();

        foreach ($first_result as $fr_id) $fr_ids[] = $fr_id['id'];

        $total_cards = count($first_result);

        //$filter = '';

        $filter = false;

        $filter_type = [];

        if ($filter_ready and $general) {
            $dql = "SELECT cf FROM AppBundle:CardField cf WHERE cf.generalTypeId = ?1";
            $query = $em->createQuery($dql);
            $query->setParameter(1, $general->getId());
            foreach ($query->getResult() as $cf) {
                $dql = "SELECT ft FROM AppBundle:FieldType ft WHERE ft.id = ?1";
                $query_ft = $em->createQuery($dql);
                $query_ft->setParameter(1, $cf->getFieldId());
                foreach ($query_ft->getResult() as $ft) {
                    if ($ft->getFormElementType() == 'ajaxMenu'){
                        $dql_sf = "SELECT sf FROM AppBundle:SubField sf WHERE sf.fieldId = ?1";
                        $query_sf = $em->createQuery($dql_sf);
                        $query_sf->setParameter(1, $ft->getId());

                        $filter[] = [
                            'id' => $ft->getId(),
                            'label' => $ft->getHeader(),
                            'label_en' => $ft->getHeaderEn(),
                            'type' => 'checkbox',
                            'set' => $query_sf->getResult(),
                        ];

                        $filter_type[$ft->getId()] = 'checkbox';

                    }
                    if ($ft->getFormElementType() == 'numberInput'){
                        $filter[] = [
                            'id' => $ft->getId(),
                            'label' => $ft->getHeader(),
                            'label_en' => $ft->getHeaderEn(),
                            'type' => 'input',
                        ];

                        $filter_type[$ft->getId()] = 'range';
                    }
                }
            }
        }

        $features = false;
        if($general) {
            $dql = "SELECT f FROM AppBundle:Feature f";
            $query = $em->createQuery($dql);
            foreach ($query->getResult() as $f) {
                $fgts = explode(",", $f->getGts());
                foreach ($fgts as $fgt) if ($fgt == $general->getId()) {
                    $features[] = $f;
                }
            }
        }

        // -------------------------- start of filter -----------------------

        $filter_cond = '';
        $is_real_filter = false;

        if ($is_filter){

            $fids = false;
            foreach($get['filter'] as $filter_id => $filter_array){

                if ($filter_type[$filter_id] == 'checkbox'){
                    $dql_add = "SELECT fi.cardId FROM AppBundle:FieldInteger fi WHERE fi.cardFieldId = " . $filter_id." AND fi.value IN (".implode(",",array_keys($filter_array)).")";
                    $query = $em->createQuery($dql_add);
                    $add_ids = $query->getScalarResult();
                    foreach ($add_ids as $fid) $fids[$filter_id][] = $fid['cardId'];
                    $is_real_filter = true;
                }

                if ($filter_type[$filter_id] == 'range' and isset($get['filter'][$filter_id]['on'])){
                    $dql_add = "SELECT fi.cardId FROM AppBundle:FieldInteger fi WHERE fi.cardFieldId = " . $filter_id." AND fi.value >= ?1 AND fi.value <= ?2";
                    $query = $em->createQuery($dql_add);
                    $query->setParameter(1, $filter_array['from']);
                    $query->setParameter(2, $filter_array['to']);
                    $add_ids = $query->getScalarResult();
                    foreach ($add_ids as $fid) $fids[$filter_id][] = $fid['cardId'];
                    $is_real_filter = true;
                }


            }


            if($fids) {
                $result_keys = array_keys($fids);

                if (count($result_keys) > 1) {
                    $intersect = call_user_func_array('array_intersect', $fids);

                    $fid_arr = array_unique($intersect);
                } else $fid_arr = $fids[$result_keys[0]];


                $filter_cond = ' AND c.id IN (' . implode(",", $fid_arr) . ') ';

                $total_cards = count($fid_arr);

            }

            if($filter_cond == '' and $is_real_filter) {
                $filter_cond = ' AND c.id = 0 ';
                $total_cards = 0;

            }

        }

        // -------------------------- end of filter -----------------------




        // -------------------------- start of feature -----------------------


        if ($is_feature){
            $dql = "SELECT f.cardId FROM AppBundle:CardFeature f WHERE f.featureId IN (".implode(",",array_keys($get['feature'])).")";
            $query = $em->createQuery($dql);
            $add_ids = $query->getScalarResult();
            foreach ($add_ids as $fid) $feat_ids[] = $fid['cardId'];

            if(isset($feat_ids)) {
                if (isset($fid_arr)) {
                    $res_arr = array_intersect($fid_arr, $feat_ids);
                } else {
                    $res_arr = $feat_ids;
                }

                $res_arr = array_intersect($fr_ids, $res_arr);

                $filter_cond = ' AND c.id IN (' . implode(",", $res_arr) . ') ';
                $total_cards = count($res_arr);
            } else {
                $filter_cond = ' AND c.id = 0 ';
                $total_cards = 0;
            }
        }



        // -------------------------- end of feature -----------------------



        if ($request->query->has('page')) $page = $get['page']; else $page = 1;
        if ($request->query->has('onpage')) $cards_per_page = $get['onpage']; else $cards_per_page = 12;

        $pages_in_center = 5;
        if ($mobileDetector->isMobile()) $pages_in_center = 2;

        $pager_center_start = 2;

        $total_pages = ceil($total_cards/$cards_per_page);
        $start = ($page-1)*$cards_per_page;

        if ($total_pages>($pages_in_center+1)) {
            if(($total_pages-$page) > $pages_in_center) $pager_center_start = $page;
            else $pager_center_start = $total_pages - $pages_in_center;
            if ($pager_center_start == 1) $pager_center_start = 2;
        }


        if (isset($get['order'])){
            if($get['order'] == 'price_asc'){
                $order = ' ORDER BY p.value ASC';
            }
            if($get['order'] == 'price_desc'){
                $order = ' ORDER BY p.value DESC';
            }
        }

        $price_condition = '';
        if (isset($get['price_from'])) $price_condition = ' AND p.priceId = 2 AND p.value >= '.$get['price_from'].' AND p.value <= '.$get['price_to'];

        $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g ORDER BY g.total DESC');
        $generalTypes = $query->getResult();

        $dql = 'SELECT c.id FROM AppBundle:Card c JOIN c.tariff t LEFT JOIN c.cardPrices p WITH p.priceId = 2 WHERE c.isActive = 1 '.$price_condition.$city_condition.$service_condition.$general_condition.$mark_condition.$body_condition.$filter_cond.$order;
        $query = $em->createQuery($dql);


        // ------ new pager --------

        $total_cards = count($query->getResult());

        $total_pages = ceil($total_cards/$cards_per_page);
        $start = ($page-1)*$cards_per_page;

        if ($total_pages>($pages_in_center+1)) {
            if(($total_pages-$page) > $pages_in_center) $pager_center_start = $page;
            else $pager_center_start = $total_pages - $pages_in_center;
            if ($pager_center_start == 1) $pager_center_start = 2;
        }

        // ---------------------------

        $query->setMaxResults($cards_per_page);
        $query->setFirstResult($start);



        $card_ids = $query->getResult();
        if($card_ids) {
            foreach ($card_ids as $c_id) $sids[] = $c_id['id'];
            $ids = implode(",", $sids);
        } else {
            $ids = 1;
        }

        $dql = 'SELECT c,p,f FROM AppBundle:Card c JOIN c.tariff t LEFT JOIN c.cardPrices p WITH p.priceId > 0 LEFT JOIN c.fotos f WHERE c.id IN ('.$ids.')'.$order;
        $query = $em->createQuery($dql);
        $cards = $query->getResult();

        if (!$service) $p_service = 'all';
        else {
            if ($service == 1) $p_service = 'prokat';
            if ($service == 2) $p_service = 'arenda';
            if ($service == 3) $p_service = 'leasebuyout';
        }

        $seo = [];
        if ($p_service == 'all') $seo['service'] = $_t->trans('Прокат и аренда');
        if ($p_service == 'prokat') $seo['service'] = $_t->trans('Прокат');
        if ($p_service == 'arenda') $seo['service'] = $_t->trans('Аренда');
        if ($p_service == 'leasebuyout') $seo['service'] = $_t->trans('Аренда с правом выкупа');
        if (!$general) {
            $seo['type']['singular'] = $_t->trans('транспорта');
            $seo['type']['plural'] = $_t->trans('транспорта');
        } else {
            if ($_SERVER['LANG'] == 'ru') $seo['type']['singular'] = $general->getChegoSingular(); else $seo['type']['singular'] = $general->getUrl();
            if ($_SERVER['LANG'] == 'ru') $seo['type']['plural'] = $general->getChegoPlural(); else $seo['type']['plural'] = $general->getUrl();
        }
        if (!is_array($mark)) $seo['mark'] = $mark->getHeader();
        else $seo['mark'] = '';
        if (!is_array($model)) $seo['model'] = $model->getHeader();
        else $seo['model'] = '';
        if ($city) {
            $seo['city']['chto'] = $city->getHeader();
            $seo['city']['gde'] = $city->getGde();
            $seo['city']['url'] = $city->getUrl();
            $seo['city']['iso'] = $city->getIso();
            $seo['city']['country'] = $city->getCountry();
            $seo['city']['id'] = $city->getId();
            $seo['city']['header'] = $city->getHeader();
        } else {
            $seo['city']['chto'] = 'России';
            $seo['city']['gde'] = 'России';
        }
        if ($body_condition != ''){
            $seo['bodyType'] = $bodyType->getChego();
        } else {
            $seo['bodyType'] = '';
        }

        $custom_seo = $this->getDoctrine()
            ->getRepository(Seo::class)
            ->findOneBy(['url' => $request->getPathInfo()]);



        if($general) {
            $carType = $general->getCarTypeIds();
        } else $carType = '';

        $mark_arr = $mm->getExistMarks("",$carType);

        $mark_arr_sorted = $mark_arr['typed_marks'];
        $models_in_mark = $mark_arr['models_in_mark'];



        $gtm_ids = $mm->getExistMarkGtId($city->getId());
        $all_gts = $mm->getExistGt($gtm_ids['gts']);
        if ($general) $all_marks = $mm->getExistMark($gtm_ids['models'],$general);
        else $all_marks = '';

        if(!$general) $general = ['url'=>'alltypes','header'=>'Любой тип транспорта'];

        if ($this->get('session')->has('city')){
            $in_city = $this->get('session')->get('city');
            if(is_array($in_city)) $in_city = $in_city[0]->getUrl();
            else $in_city = $in_city->getUrl();
        }
        else $in_city = $city->getUrl();

        $stat_arr = [
            'url' => $request->getPathInfo(),
            'event_type' => 'visit',
            'page_type' => 'catalog',
        ];

        if($total_cards == 0) $stat_arr['is_empty'] = true;

        $stat->setStat($stat_arr);


        // ---------------------------- start of similar ----------------------------------

        $similar = false;

        if($total_cards == 0) {

            $dql = 'SELECT c.id FROM AppBundle:Card c WHERE c.cityId=?1  AND c.modelId=?3 ORDER BY c.dateUpdate DESC'; // -- get by model

            $query = $em->createQuery($dql);
            $query->setParameter(1, $this->get('session')->get('city')->getId());



            $query->setParameter(3, is_array($model) ? 0 : $model->getId());
            $query->setMaxResults(9);

            if (count($query->getScalarResult()) < 1) { // -- get by mark
                $dql = 'SELECT m.id FROM MarkBundle:CarModel m WHERE m.carMarkId=?1';
                $query = $em->createQuery($dql);
                $query->setParameter(1, $mark->getId());
                foreach ($query->getScalarResult() as $row) {
                    $model_ids[] = $row['id'];
                }
                if(isset($model_ids)) $dql = 'SELECT c.id FROM AppBundle:Card c WHERE c.cityId=?1 AND c.modelId IN (' . implode(",", $model_ids) . ') ORDER BY c.dateUpdate DESC';
                else $dql = 'SELECT c.id FROM AppBundle:Card c WHERE c.cityId=?1 ORDER BY c.dateUpdate DESC';

                $query = $em->createQuery($dql);
                $query->setParameter(1, $this->get('session')->get('city')->getId());

                $query->setMaxResults(9);



                if (count($query->getScalarResult()) < 1) {

                    if(!$general or is_array($general)) $dql = 'SELECT c.id FROM AppBundle:Card c JOIN c.tariff t WHERE c.cityId=?1 ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';
                    else $dql = 'SELECT c.id FROM AppBundle:Card c JOIN c.tariff t WHERE c.cityId=?1 AND c.generalTypeId = ' . $general->getId() . ' ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';

                    $query = $em->createQuery($dql);
                    $query->setParameter(1, $this->get('session')->get('city')->getId());

                    $query->setMaxResults(9);

                    if (count($query->getScalarResult()) < 1) {
                        if(!$general or is_array($general)) $dql = 'SELECT c.id FROM AppBundle:Card c JOIN c.tariff t WHERE c.generalTypeId = 2 WHERE c.cityId > 1251 ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';
                        else $dql = 'SELECT c.id FROM AppBundle:Card c JOIN c.tariff t WHERE c.generalTypeId = ' . $general->getId() . ' AND c.cityId > 1251 ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';

                        $query = $em->createQuery($dql);

                        $query->setMaxResults(9);

                        if (count($query->getScalarResult()) < 1) {
                            $dql = 'SELECT c.id FROM AppBundle:Card c JOIN c.tariff t WHERE c.cityId > 1251 ORDER BY t.weight DESC, c.dateTariffStart DESC, c.dateUpdate DESC';
                            $query = $em->createQuery($dql);

                            $query->setMaxResults(9);
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
        }

        // ---------------------------- end of similar ----------------------------------


        $topSlider = $this->getDoctrine()
                    ->getRepository(Card::class)
                    ->getTopSlider($this->get('session')->get('city')->getId());

        return $this->render('search/search_main.html.twig', [

            'cards' => $cards,
            'view' => $view,
            'order' => $sort,
            'get_array' => $get,
            'total_cards' => $total_cards,
            'total_pages' => $total_pages,
            'pager_center_start' => $pager_center_start,
            'pages_in_center' => $pages_in_center,
            'current_page' => $page,
            'onpage' => $cards_per_page,
            'service' => $p_service,

            'countryCode' => $countryCode,
            'regionId' => $regionId,

            'cities' => $cities,
            'cityId' => $cityId,
            'city' => $city,

            'general' => $general,

            'mark' => $mark,
            'model' => $model,
            'marks' => $marks,
            'models' => $models,
            'car_type_id' => $mark->getCarTypeId(),

            'seo' => $seo,
            'custom_seo' => $custom_seo,


            'mark_arr_sorted' => $mark_arr_sorted,
            'models_in_mark' => $models_in_mark,

            'generalTypes' => $generalTypes,
            'all_gts' => $all_gts,
            'all_marks' => $all_marks,
            'gtm_ids' => $gtm_ids,

            'bodyTypes' => $bodyTypes,

            'in_city' => $in_city,
            'is_body' => $is_body,

            'page_type' => 'catalog',
            'lang' => $_SERVER['LANG'],
            'similar' => $similar,
            'filter' => $filter,
            'is_filter' => $is_filter,
            'is_feature' => $is_feature,
            'get_filter' => isset($get['filter']) ? $get['filter'] : [],
            'get_feature' => isset($get['feature']) ? $get['feature'] : [],
            'features' => $features,
            'topSlider' => $topSlider


        ]);
    }

    /**
     * @Route("/{url}", name="remove_trailing_slash",
     *     requirements={"url" = ".*\/$"}, methods={"GET"})
     */
    public function removeTrailingSlashAction(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();

        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);

        return $this->redirect($url, 301);
    }
}


///**
// * @Route("/translit")
// */
//public function translitAction()
//{
//
//    $cities = $this->getDoctrine()
//        ->getRepository(City::class)
//        ->findAll();
//
//    $em = $this->getDoctrine()->getManager();
//
//    foreach($cities as $city){
//        $city->setUrl($this->translit($city->getHeader()));
//        $em->persist($city);
//    }
//
//    $em->flush();
//
//    return new Response();
//}

//private function translit($string){
//    $converter = array(
//        'а' => 'a',   'б' => 'b',   'в' => 'v',
//        'г' => 'g',   'д' => 'd',   'е' => 'e',
//        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
//        'и' => 'i',   'й' => 'y',   'к' => 'k',
//        'л' => 'l',   'м' => 'm',   'н' => 'n',
//        'о' => 'o',   'п' => 'p',   'р' => 'r',
//        'с' => 's',   'т' => 't',   'у' => 'u',
//        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
//        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
//        'ь' => '',    'ы' => 'y',   'ъ' => '',
//        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
//
//        'А' => 'A',   'Б' => 'B',   'В' => 'V',
//        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
//        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
//        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
//        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
//        'О' => 'O',   'П' => 'P',   'Р' => 'R',
//        'С' => 'S',   'Т' => 'T',   'У' => 'U',
//        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
//        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
//        'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
//        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
//        ' ' => '_',   '.' => '.',   '«' => '',
//        '»' => '',   '"' => '', '№' => 'N', '“'=>'', '”'=>''
//    );
//    return strtr($string, $converter);
//}