<?php

namespace AdminBundle\Controller;

use AppBundle\Foto\FotoUtils;
use AppBundle\Menu\MenuCity;
use AppBundle\Menu\MenuGeneralType;
use AppBundle\Menu\MenuMarkModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminWPController extends Controller
{

//    /**
//     * @Route("/wp")
//     */
//    public function wpAction()
//    {
//        echo 'ok';
//        return new Response();
//    }
//
//    private function fillModel($header, $type_ids) // ready
//    {
//        $model = 20991;
//        $header = explode(" ", $header);
//
//        $marks = $this->checkCount($header,NULL, $type_ids);
//        if ($marks != 0) {
//            //echo 'parent - '.$header[$parent[1]].'<br>';
//            unset($header[$marks[1]]);
//            foreach ($marks[0] as $mark) {
//                $model = $this->checkCount($header, $mark['id'], $type_ids);
//                if ($model == 0) {
//
//                    //$mark = $this->getNonModel($parent[0]);
//                    //echo 'no model, set:'.$mark.'<br>';
//                } else {
//                    $model = $model[0][0]['id'];
//                    return $model;
//                }
//            }
//        } else {
//            //echo 'no parent<br>';
//            $model = $this->checkCount($header,'all', $type_ids);
//            if ($model != 0){
//                $model = $model[0][0]['id'];
//                //echo 'set:'.$mark.'<br>';
//            }
//        }
//        if ($model == 0) $model = 20991;
//        return $model;
//    }
//
//    private function checkCount($array, $parent_id, $type_ids){
//
//        $em = $this->get('doctrine')->getManager();
//        $new_conn = $em->getConnection();
//
//        foreach ($array as $key=>$mark_name){
//            $mark_name = trim(str_replace('.','',$mark_name));
//
//            if(mb_strlen($mark_name,'UTF-8')>1) {
//
//                $sql = "SELECT id FROM car_model WHERE car_mark_id=$parent_id AND header LIKE '%" . $mark_name . "%' AND car_type_id IN ('".$type_ids."')";
//                if ($parent_id == NULL) $sql = "SELECT id FROM car_mark WHERE header LIKE '%" . $mark_name . "%' AND car_type_id IN ('".$type_ids."')";
//                if ($parent_id == 'all') $sql = "SELECT id FROM car_model WHERE header LIKE '%" . $mark_name . "%' AND car_type_id IN ('".$type_ids."')";
//
//                $result = $new_conn->fetchAll($sql);
//                if (count($result) > 0)  {
//                    return array($result,$key);
//                }
//            }
//        }
//        return 0;
//    }
//
//    /**
//     * @Route("/wpModelSet")
//     */
//    public function wpModelSet()
//    {
//
//        $em = $this->get('doctrine')->getManager();
//        $conn = $em->getConnection();
//
//        $cards = $conn->fetchAll('SELECT id,header,general_type_id FROM card');
//        foreach ($cards as $card) {
//            $gt = $card['general_type_id'];
//            $type_ids = $conn->fetchColumn('SELECT car_type_ids FROM general_type WHERE id='.$gt);
//
//            $model_id = $this->fillModel($card['header'],$type_ids);
//            $id = $card['id'];
//            $sql = "UPDATE card SET model_id = $model_id WHERE id=$id";
//            $conn->query($sql);
//        }
//        echo 'done!';
//        return new Response();
//    }
}
