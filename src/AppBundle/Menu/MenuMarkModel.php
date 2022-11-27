<?php

namespace AppBundle\Menu;

use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MarkBundle\Entity\CarType;
use MarkBundle\Entity\CarMark;
use MarkBundle\Entity\CarModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class MenuMarkModel extends Controller
{
    private $em;

    public function __construct(em $em)
    {
        $this->em = $em;
    }

    public function getGroups()
    {
        $query = $this->em->createQuery('SELECT g FROM MarkBundle:CarType g');
        return $query->getResult();
    }

    public function getMark($mark_id)
    {
        $query = $this->em->createQuery('SELECT m FROM MarkBundle:CarMark m WHERE m.id=?1');
        $query->setParameter(1, $mark_id);
        return $query->getSingleResult();
    }

    public function getMarks($groupId)
    {
        $query = $this->em->createQuery('SELECT m FROM MarkBundle:CarMark m WHERE m.carTypeId = ?1');
        $query->setParameter(1, $groupId);
        return $query->getResult();
    }

    public function getModel($modelId)
    {
        $query = $this->em->createQuery('SELECT m FROM MarkBundle:CarModel m WHERE m.id = ?1');
        $query->setParameter(1, $modelId);
        return $query->getSingleResult();
    }

    public function getModels($markId)
    {
        $query = $this->em->createQuery('SELECT m FROM MarkBundle:CarModel m WHERE m.carMarkId = ?1');
        $query->setParameter(1, $markId);
        return $query->getResult();
    }

//    public function getLimitedArray($ids)
//    {
//        $mark = array();
//        $query = $this->em->createQuery('SELECT m FROM MarkBundle:CarMark m WHERE m.id IN ( :ids )');
//        $query->setParameter('ids', $ids);
//        foreach ($query->getResult() as $row){
//            $mark[$row->getParentId()][] = $row;
//        }
//        foreach ($mark as $id=>$models){
//            $result[] = array(
//                'object' => $this->getMark($id),
//                'childs' => $models
//            );
//        }
//        return $result;
//    }

    /**
     * @Route("/ajax/getMarks")
     */
    public function getMarksAction(Request $request)
    {
        $groupId = $request->request->get('groupId');
        return $this->render('common/ajax_options_url.html.twig', [
            'options' => $this->getMarks($groupId),
    'lang' => $_SERVER['LANG']
        ]);
    }

    /**
     * @Route("/ajax/getModels")
     */
    public function getModelsAction(Request $request)
    {
        $markId = $request->request->get('markId');
        return $this->render('common/ajax_options_url.html.twig', [
            'options' => $this->getModels($markId),
    'lang' => $_SERVER['LANG']
        ]);
    }

    /**
     * @Route("/ajax/getExistModels")
     */
    public function getExistModelsAction(Request $request)
    {
        $markId = $request->request->get('markId');
        $cityId = $request->request->get('cityId');
        $models = $this->getExistMarks($cityId)['models_in_mark'];
        return $this->render('selector/model_block.html.twig', [
            'model_arr'=>$models,
            'mark_id'=>$markId,
            'model'=>$models[$markId][0],
            'lang' => $_SERVER['LANG']
        ]);
    }

    /**
     * @Route("/ajax/getExistModelsLeft")
     */
    public function getExistModelsLAction(Request $request)
    {
        $markId = $request->request->get('markId');
        $cityId = $request->request->get('cityId');
        $models = $this->getExistMarks($cityId)['models_in_mark'];
        return $this->render('selector/model_block2.html.twig', [
            'model_arr'=>$models,
            'mark_id'=>$markId,
            'model'=>$models[$markId][0],
            'lang' => $_SERVER['LANG']
        ]);
    }

    /**
     * @Route("/ajax/getExistMarks")
     */
    public function getExistMarksAction(Request $request)
    {
        $cityId = $request->request->get('cityId');
        $gtURL = $request->request->get('gtURL');
        $marks = $this->getExistMarks($cityId)['sorted_marks'];

        if($marks) {
            $query = $this->em->createQuery('SELECT t FROM MarkBundle:CarType t WHERE t.url = ?1');
            $query->setParameter(1, $gtURL);
            $result = $query->getResult();

            if($result and isset($marks[$result[0]->getId()])) {

                return $this->render('selector/mark_block.html.twig', [
                    'mark_arr' => $this->getExistMarks($cityId)['sorted_marks'],
                    'mark' => $marks[$result[0]->getId()][0]['mark'],
                    'type' => $result[0]->getId(),
                    'lang' => $_SERVER['LANG']
                ]);
            } else return new Response();
        } else {
            return new Response();
        }
    }

    /**
     * @Route("/ajax/getExistMarksLeft")
     */
    public function getExistMarksLAction(Request $request)
    {
        $cityId = $request->request->get('cityId');
        $gtURL = $request->request->get('gtURL');
        $marks = $this->getExistMarks($cityId)['sorted_marks'];

        if($marks) {
            $query = $this->em->createQuery('SELECT t FROM MarkBundle:CarType t WHERE t.url = ?1');
            $query->setParameter(1, $gtURL);
            $result = $query->getResult();

            if($result and isset($marks[$result[0]->getId()])) {

                return $this->render('selector/mark_block2.html.twig', [
                    'mark_arr' => $this->getExistMarks($cityId)['sorted_marks'],
                    'mark' => $marks[$result[0]->getId()][0]['mark'],
                    'type' => $result[0]->getId(),
                    'lang' => $_SERVER['LANG']
                ]);
            } else return new Response();
        } else {
            return new Response();
        }
    }

    public function updateModelTotal($modelId)
    {
        $query = $this->em->createQuery('UPDATE MarkBundle:CarModel m SET m.total = m.total +1 WHERE m.id = ?1');
        $query->setParameter(1, $modelId);
        $query->execute();
    }

    public function getExistMarks($cityId = '',$carTypeId = '')
    {

        $mark_arr = [];
        $new_mark_arr = [];
        $mark_total = [];

//        $query = $this->em->createQuery('SELECT m,k FROM MarkBundle:CarModel m LEFT JOIN m.mark k ORDER BY m.total DESC, m.header ASC');
//        if ($carTypeId != ''){
//            $query = $this->em->createQuery('SELECT m,k FROM MarkBundle:CarModel m LEFT JOIN m.mark k WHERE m.carTypeId='.$carTypeId.' ORDER BY m.total DESC, m.header ASC');
//        }

        $query = $this->em->createQuery('SELECT m,k FROM MarkBundle:CarModel m LEFT JOIN m.mark k ORDER BY m.total DESC, m.header ASC');
        if ($carTypeId != ''){
            $query = $this->em->createQuery('SELECT m,k FROM MarkBundle:CarModel m LEFT JOIN m.mark k WHERE m.carTypeId='.$carTypeId.' ORDER BY m.total DESC, m.header ASC');
        }

        $result = $query->getResult();

        if(!empty($result)) {
            foreach ($result as $qm) {
                if (!isset($mark_arr[$qm->getCarTypeId()][$qm->getCarMarkId()])) {
                    $mark_arr[$qm->getCarTypeId()][$qm->getCarMarkId()] = [
                        'total' => 0,
                        'mark' => $qm->getMark(),
                        'models' => [],
                        'lang' => $_SERVER['LANG']
                    ];
                }
                $mark_arr[$qm->getCarTypeId()][$qm->getCarMarkId()]['total'] = $mark_arr[$qm->getCarTypeId()][$qm->getCarMarkId()]['total'] + $qm->getTotal();
                $mark_arr[$qm->getCarTypeId()][$qm->getCarMarkId()]['models'][] = $qm;
                $models_in_mark[$qm->getCarMarkId()][] = $qm;
            }
            $i = 0;
            foreach ($mark_arr as $type => $mas) {
                foreach ($mas as $id => $ma) {
                    $mark_total[$type][$i] = $ma['total'];
                    $new_mark_arr[$type][$i] = $ma;
                    $types[$type] = 1;
                    $i++;
                }
            }
            foreach (array_keys($types) as $type) {
                array_multisort($mark_total[$type], SORT_DESC, $new_mark_arr[$type]);
            }
            return ['sorted_marks' => $new_mark_arr, 'typed_marks' => $mark_arr, 'models_in_mark' => $models_in_mark, 'lang' => $_SERVER['LANG']];
        } else {
            return false;
        }
    }

    public function getExistMarkGtId($cityId)
    {
        $gt_ids = $model_ids = [];
        $query = $this->em->createQuery('SELECT c.modelId,c.generalTypeId FROM AppBundle:Card c WHERE c.cityId = '.$cityId);
        if ($cityId == '' or !$cityId) $query = $this->em->createQuery('SELECT c.modelId,c.generalTypeId FROM AppBundle:Card c ');
        $result = $query->getScalarResult();
        foreach ($result as $row){
            $gt_ids[] = $row['generalTypeId'];
            $model_ids[] = $row['modelId'];
        }
        return array('gts' => array_unique($gt_ids), 'models' => array_unique($model_ids));
    }

    public function getExistGt($gt_ids)
    {
        if(!empty($gt_ids)) {
            $query = $this->em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.id IN (' . implode(",", $gt_ids) . ')');
        } else {
            $query = $this->em->createQuery('SELECT g FROM AppBundle:GeneralType g');
        }
        return $query->getResult();
    }

    public function getExistMark($model_ids,$general)
    {
        if(!empty($model_ids)) {
            $mark_ids = [];
//        $query = $this->em->createQuery('SELECT t FROM MarkBundle:CarType t WHERE t.url = ?1');
//        $query->setParameter(1, $general->getUrl());
//        $carType = $query->getResult();
//        $carType = $carType[0];
            $mid = [];
            foreach($model_ids as $m) if($m!='') $mid[] = $m;
            $model_ids = $mid;

            $carTypeCondition = '';
            $carType = $general->getCarTypeIds();
            if ($carType and $carType>0) $carTypeCondition = 'AND m.carTypeId = '.$carType;


            sort($model_ids);
            $query = $this->em->createQuery('SELECT m.carMarkId FROM MarkBundle:CarModel m WHERE m.id IN (' . implode(",", $model_ids) . ') '.$carTypeCondition.' ORDER BY m.carMarkId');
            foreach ($query->getScalarResult() as $row) {
                $mark_ids[] = $row['carMarkId'];
            }
            $mark_ids = array_unique($mark_ids);
            if (!empty($mark_ids)) {
                $query = $this->em->createQuery('SELECT m FROM MarkBundle:CarMark m WHERE m.id IN (' . implode(",", $mark_ids) . ')');
                return $query->getResult();
            } else return [];
        } else return [];
    }

    /**
     * @Route("/ajax/getMarkByInput")
     */
    public function getMarkByInputAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $res = array();

        $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.url = ?1');
        $query->setParameter(1, $request->request->get('gt'));
        $gt = $query->getResult()[0];


        $marks = $em->getRepository("MarkBundle:CarMark")->createQueryBuilder('m')
            ->where('m.header LIKE :mrk')
            ->andWhere('m.carTypeId = '.$gt->getCarTypeIds())
            ->setParameter('mrk', '%'.$request->request->get('q').'%')
            ->getQuery()
            ->getResult();

        foreach($marks as $m){
            $res[] = $m->getHeader().'|'.$m->getId();
        }

        return new Response(json_encode($res));
    }

    /**
     * @Route("/ajax/getPrices")
     */
    public function getPricesAction(Request $request)
    {
        $prices = [];
        $prices_hour = [];
        $content = '';
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT c,p FROM AppBundle:Card c JOIN c.cardPrices p WHERE c.modelId = ?1');
        $query->setParameter(1, $request->request->get('modelId'));
        foreach ($query->getResult() as $row){
            foreach($row->getCardPrices() as $price){
                if($price->getPriceId() == 2) $prices[] = $price->getValue();
                if($price->getPriceId() == 1) $prices_hour[] = $price->getValue();
            }
        }

        if(count($prices_hour)>1) $content = min($prices_hour).' - '.max($prices_hour). ' <i class="fa fa-ruble"></i>/час<br>';
        if(count($prices)>1) $content = $content.min($prices).' - '.max($prices). ' <i class="fa fa-ruble"></i>/день';

        return new Response($content);
    }

    /**
     * @Route("/ajax/getGlobByInput")
     */
    public function getGlobByInputAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $res = array();

        $gt = $em->getRepository("AppBundle:GeneralType")->createQueryBuilder('g')
            ->where('g.header LIKE :gg')
            ->andWhere("g.carTypeIds != '' ")
            ->setParameter('gg', '%'.$request->request->get('q').'%')
            ->getQuery()
            ->getResult();

        if(count($gt)<1){
            $marks = $em->getRepository("MarkBundle:CarMark")->createQueryBuilder('m')
            ->where('m.header LIKE :mrk')
            ->setParameter('mrk', '%'.$request->request->get('q').'%')
            ->getQuery()
            ->getResult();

            foreach($marks as $m){
                $gt = $this->getCarTypeUrl($m->getCarTypeId());

                if($gt) {
                    $header = $m->getHeader() . ' ' . $gt['header'];

                    $hdr = explode(" ", $m->getHeader());

                    if ($hdr[count($hdr) - 1] == '(COMMERCIAL)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - легкий коммерческий транспорт';
                    }
                    if ($hdr[count($hdr) - 1] == '(HEAVY)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - тягач';
                    }
                    if ($hdr[count($hdr) - 1] == '(TRAILER)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - прицеп';
                    }
                    if ($hdr[count($hdr) - 1] == '(BUS)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - автобус';
                    }
                    if ($hdr[count($hdr) - 1] == '(CRANE)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - кран';
                    }
                    if ($hdr[count($hdr) - 1] == '(BULLDOZER)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - бульдозер';
                    }
                    if ($hdr[count($hdr) - 1] == '(EXCAVATOR)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - экскаватор';
                    }
                    if ($hdr[count($hdr) - 1] == '(AGRO)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - сельхозтехника';
                    }
                    if ($hdr[count($hdr) - 1] == '(CITY)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - коммунальный транспорт';
                    }
                    if ($hdr[count($hdr) - 1] == '(LOADER)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - самопогрузчик';
                    }
                    if ($hdr[count($hdr) - 1] == '(RETRO)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - ретро';
                    }
                    if ($hdr[count($hdr) - 1] == '(JETBOAT)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - рективный катер';
                    }
                    if ($hdr[count($hdr) - 1] == '(HYDROCYCLE)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - гидроцикл';
                    }
                    if ($hdr[count($hdr) - 1] == '(BOAT)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - лодка';
                    }
                    if ($hdr[count($hdr) - 1] == '(FORKLIFT)') {
                        unset($hdr[count($hdr) - 1]);
                        $header = implode(" ", $hdr) . ' - автопогрузчик';
                    }

                    $res[] = $header . '|/rent/' . $request->request->get('cityUrl') . '/all/' . $gt['url'] . '/' . $m->getHeader();
                }
            }




        } else {
            foreach($gt as $g){
                $res[] = $g->getHeader().'|/rent/'.$request->request->get('cityUrl').'/all/'.$g->getUrl();
            }
        }

        return new Response(json_encode($res));
    }

    public function getCarTypeUrl($carTypeId)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.carTypeIds = ?1');
        $query->setParameter(1, $carTypeId);
        $result = $query->getResult();
        if (isset($result[0])) return ['url'=>$result[0]->getUrl(),'header'=>$result[0]->getHeader()];
        else return false;
    }
}