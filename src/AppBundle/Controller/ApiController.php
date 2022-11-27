<?php

namespace AppBundle\Controller;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Security\Password;

class ApiController extends Controller
{

    var $db;

    public function __construct(em $em)
    {
        $this->db = $em->getConnection();
        $this->base_url = (isset($_SERVER['HTTPS']) ? 'http' : 'https' ). "://" . $_SERVER['SERVER_NAME']  ;
    }

    /**
     * @Route("/api/card/{id}", name="api_card")
     */
    public function api_card($id)
    {
        $code = 200;
        $status = 'OK';
        $id = (int)$id;

        //$card = $this->db->fetchAssoc('SELECT id,header,content,general_type_id as vehicle_type_id,model_id,user_id,views,city_id,currency FROM card WHERE id = ?', array($id));

        $card = $this->db->fetchAssoc('SELECT c.id,c.header,c.content,c.user_id,c.views,c.currency,c.likes,c.coords,c.city_id,c.model_id,m.header as model,k.header as mark,k.id as mark_id,s.header as city,c.prod_year,g.url as category,c.general_type_id as vehicle_type_id FROM card as c LEFT JOIN car_model as m ON m.id = c.model_id LEFT JOIN car_mark as k ON k.id = m.car_mark_id LEFT JOIN city as s ON s.id = c.city_id LEFT JOIN general_type as g ON g.id = c.general_type_id WHERE c.id=? ',array($id));

        $prices = $this->db->fetchAll('SELECT cp.price_id,cp.value FROM card_price cp WHERE card_id = ?', array($id));

        $main_foto = $this->db->fetchAssoc('SELECT id,folder FROM foto WHERE card_id = ? AND is_main=1', array($id));
        $extra_fotos = $this->db->fetchAll('SELECT id,folder FROM foto WHERE card_id = ? AND is_main!=1', array($id));

        if($card) {

            $sub_fields = $this->db->fetchAll("SELECT t.header_en as header, f.field_id, t.form_element_type FROM card_field as f 
                LEFT JOIN field_type as t ON t.id = f.field_id               
                WHERE f.general_type_id = ?", array($card['vehicle_type_id']));

            foreach ($sub_fields as $i=>$sf){

                if($sf['form_element_type'] == 'numberInput') {
                    $v = $this->db->fetchAssoc('SELECT value FROM field_integer WHERE card_id = ? AND card_field_id=?', array($card['id'], $sf['field_id']));
                    $sub_fields[$i]['value'] = $v['value'];
                }
                if($sf['form_element_type'] == 'ajaxMenu') {
                    $v = $this->db->fetchAssoc('SELECT url FROM sub_field WHERE field_id = ?', array($sf['field_id']));
                    $sub_fields[$i]['value'] = $v['url'];
                }
                unset($sub_fields[$i]['form_element_type']);
                unset($sub_fields[$i]['field_id']);
            }

            $card['subfields'] = $sub_fields;


            foreach($this->db->fetchAll("SELECT * FROM card_feature WHERE card_id=?",array($card['id'])) as $cf){
                $cff[$cf['feature_id']] = 1;
            }

            foreach($this->db->fetchAll("SELECT * FROM feature WHERE parent_id IS NULL") as $af){
                $aff[] = $af;
            }

            foreach($this->db->fetchAll("SELECT * FROM feature WHERE parent_id IS NOT NULL ") as $ff){
                $zff[$ff['parent_id']][] = $ff;
            }

            foreach ($aff as $afff) {
                foreach ($zff[$afff['id']] as $zf) {
                    if(isset($cff[$zf['id']])) $fff[$afff['header_en']][] = $zf['header_en'];
                }
            }

            $card['features'] = $fff;

            //$card['status'] = 'OK';
            $card['prices'] = $prices;
            $card['main_foto_url'] = $this->base_url.'/assets/images/cards/'.$main_foto['folder'].'/'.$main_foto['id'].'.jpg';
            $card['main_foto_url_thumb'] = $this->base_url.'/assets/images/cards/'.$main_foto['folder'].'/t/'.$main_foto['id'].'.jpg';

            foreach ($extra_fotos as $ef){
                $card['extra_fotos'][] = $this->base_url.'/assets/images/cards/'.$ef['folder'].'/'.$ef['id'].'.jpg';
                $card['extra_fotos_thumb'][] = $this->base_url.'/assets/images/cards/'.$ef['folder'].'/t/'.$ef['id'].'.jpg';
            }
            $rez = $card;
        }
        else {
            $status = 'error';
            $code = '422';
            $rez = array('code'=>400);
        }



        $send['status'] = $status;
        $send['result'] = $rez;


//        header('Content-Type: application/json');
//        echo json_encode($card, JSON_PRETTY_PRINT);
//        return new Response();


        return new Response(json_encode($send, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);


    }

    /**
     * @Route("/api/vtype/{id}", name="api_vtype")
     */
    public function api_vtype($id)
    {
        $code = 200;
        $status = 'OK';
        $id = (int)$id;

        $sub_fields = $this->db->fetchAll("SELECT t.header_en as header, f.field_id, t.form_element_type FROM card_field as f 
            LEFT JOIN field_type as t ON t.id = f.field_id               
            WHERE f.general_type_id = ?", array($id));

        if($sub_fields)
        {
            foreach ($sub_fields as $i=>$sf){

                $sub_fields[$i]['type'] = $sf['form_element_type'] == 'ajaxMenu' ? 'select' : 'integer';

//                if($sf['form_element_type'] == 'numberInput') {
//                    $v = $this->db->fetchAssoc('SELECT value FROM field_integer WHERE card_id = ? AND card_field_id=?', array($card['id'], $sf['field_id']));
//                    $sub_fields[$i]['value'] = $v['value'];
//                }
                if($sf['form_element_type'] == 'ajaxMenu') {
                    $v = $this->db->fetchAll('SELECT url FROM sub_field WHERE field_id = ?', array($sf['field_id']));
                    $sub_fields[$i]['variants'] = $v;
                }
                unset($sub_fields[$i]['form_element_type']);
                unset($sub_fields[$i]['field_id']);
            }


            $rez = $sub_fields;
        }
        else {
            $status = 'error';
            $code = '422';
            $rez = array('code'=>400);
        }

        $send['status'] = $status;
        $send['result'] = $rez;

        return new Response(json_encode($send, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);
    }

    /**
     * @Route("/api/features", name="api_features")
     */
    public function api_features()
    {
        $code = 200;
        $status = 'OK';

        $fts = $this->db->fetchAll("SELECT * FROM feature");
        $ft = array();
        foreach ($fts as $f){
            unset($f['group_name']);
            unset($f['header']);
            unset($f['gts']);
            if($f['parent_id'] == null) $f['parent_id'] = 0;
            $ft[(int)$f['parent_id']][] = $f;
        }

        if($ft){
            $rez = $ft;
        } else {
            $status = 'error';
            $code = '422';
            $rez = array('code'=>400);
        }

        $send['status'] = $status;
        $send['result'] = $rez;

        return new Response(json_encode($send, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);

    }

    /**
     * @Route("/api/list", name="api_list")
     */
    public function api_list()
    {
        $data = json_decode(file_get_contents("php://input"),true);

        $code = 200;

        $rez = [];

        if(
            isset($data['vehicle_type_id']) and
            isset($data['city_id'])
        ){
//            $cards = $this->db->fetchAll('SELECT id,header,content,user_id,views FROM card WHERE model_id = ? AND general_type_id=? AND city_id=? ',
//            array($data['model_id'],$data['vehicle_type_id'],$data['city_id']));



            $sort = '';
            $condition = '';
            $limit = '';
            if(isset($data['popular'])) $sort = ' ORDER BY c.likes DESC ';
            if(isset($data['best'])) $sort = ' ORDER BY c.is_best DESC, c.date_update DESC ';

            if(isset($data['limit']) and (int)$data['limit'] != 0 ) $limit = ' LIMIT '.(int)$data['limit'];

            if(isset($data['ids'])) $condition = ' AND c.id IN ('.implode(",",$data['ids']).') ';

            if(isset($data['model_id'])) {
                $cards = $this->db->fetchAll('SELECT 
                c.id,c.header,c.content,c.user_id,c.views,c.city_id,c.model_id,c.likes,m.header as model,k.header as mark,k.id as mark_id,s.header as city,c.prod_year,g.url as category,c.general_type_id as vehicle_type_id 
                FROM card as c 
                LEFT JOIN car_model as m ON m.id = c.model_id 
                LEFT JOIN car_mark as k ON k.id = m.car_mark_id 
                LEFT JOIN city as s ON s.id = c.city_id 
                LEFT JOIN general_type as g ON g.id = c.general_type_id 
                
                WHERE c.model_id = ? AND c.general_type_id=? AND c.city_id=?
                
                '.$condition.$sort.$limit, array($data['model_id'], $data['vehicle_type_id'], $data['city_id']));
            } else {
                $cards = $this->db->fetchAll('SELECT 
                c.id,c.header,c.content,c.user_id,c.views,c.city_id,c.model_id,c.likes,m.header as model,k.header as mark,k.id as mark_id,s.header as city,c.prod_year,g.url as category,c.general_type_id as vehicle_type_id 
                FROM card as c 
                LEFT JOIN car_model as m ON m.id = c.model_id 
                LEFT JOIN car_mark as k ON k.id = m.car_mark_id 
                LEFT JOIN city as s ON s.id = c.city_id 
                LEFT JOIN general_type as g ON g.id = c.general_type_id 
                
                WHERE c.general_type_id=? AND c.city_id=?
                
                '.$condition.$sort.$limit, array($data['vehicle_type_id'], $data['city_id']));
            }

            if($cards) {
                foreach ($cards as $c) {
                    $c['prices'] = $this->db->fetchAll('SELECT cp.price_id,cp.value FROM card_price cp WHERE card_id = ?', array($c['id']));
                    $mfu = $this->db->fetchAssoc('SELECT id,folder FROM foto WHERE card_id = ? AND is_main=1', array($c['id']));
                    $c['main_foto_url_thumb'] = $this->base_url . '/assets/images/cards/' . $mfu['folder'] . '/t/' . $mfu['id'] . '.jpg';
                    $rez[] = $c;
                }

                $status = 'OK';
            } else {
                $code = '422';
                $rez = array('code'=>400);
                $status = 'error';
            }

        } else {
            $code = '422';
            $rez = array('code'=>300);
            $status = 'error';
        }

        $send['status'] = $status;
        $send['result'] = $rez;

        //header('Content-Type: application/json');
        //echo json_encode($send, JSON_PRETTY_PRINT);
        return new Response(json_encode($send, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);
    }

    /**
     * @Route("/api/vehicle_types", name="vehicle_types")
     */
    public function vehicle_types()
    {
        $status = 'OK';
        $code = 200;
        $vt = $this->db->fetchAll('SELECT id,url as header,car_type_ids as car_type_id, (SELECT COUNT(id) FROM card WHERE card.general_type_id = general_type.id) as qty FROM general_type WHERE parent_id IS NOT NULL ',array());
        //$vt = $this->db->fetchAll('SELECT id,url as header FROM general_type WHERE parent_id IS NOT NULL ',array());

        if($vt){
            foreach ($vt as $v) $rez[] = $v;
        } else {
            $code = '422';
            $rez = array('code'=>900);
            $status = 'error';
        }

        $send['status'] = $status;
        $send['result'] = $rez;

        return new Response(json_encode($send, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);
    }

    /**
     * @Route("/api/marks/{car_type_id}", name="api_marks")
     */
    public function api_marks($car_type_id)
    {
        $status = 'OK';
        $code = 200;
        $car_type_id = (int)$car_type_id;
        $m = $this->db->fetchAll('SELECT id,header FROM car_mark WHERE car_type_id=?',array($car_type_id));

        if($m){
            $rez = $m;
        } else {
            $code = '422';
            $rez = array('code'=>400);
            $status = 'error';
        }

        $send['status'] = $status;
        $send['result'] = $rez;

        return new Response(json_encode($send, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);

    }

    /**
     * @Route("/api/models/{mark_id}", name="api_models")
     */
    public function api_models($mark_id)
    {
        $status = 'OK';
        $code = 200;
        $mark_id = (int)$mark_id;
        $m = $this->db->fetchAll('SELECT id,header FROM car_model WHERE car_mark_id=?',array($mark_id));

        if($m){
            $rez = $m;
        } else {
            $code = '422';
            $rez = array('code'=>400);
            $status = 'error';
        }

        $send['status'] = $status;
        $send['result'] = $rez;

        return new Response(json_encode($send, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);

    }

    /**
     * @Route("/api/cities/{country_iso}", name="api_cities")
     */
    public function api_cities($country_iso)
    {
        $status = 'OK';
        $code = 200;
        $country_iso = htmlspecialchars(trim($country_iso));
        $m = $this->db->fetchAll('SELECT id,header,iso FROM city WHERE country=?',array($country_iso));


        if($m){
            $rez = $m;
        } else {
            $code = '422';
            $rez = array('code'=>400);
            $status = 'error';
        }

        $send['status'] = $status;
        $send['result'] = $rez;

        return new Response(json_encode($send, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);

    }

    /**
     * @Route("/api/get_user/{id}", name="get_user")
     */
    public function get_user($id)
    {
        $code = 200;

        $u = $this->db->fetchAssoc('SELECT id,email,header as name FROM user WHERE id=?',array($id));

        if($u){

            foreach ($this->db->fetchAll('SELECT * FROM user_info WHERE user_id=?',array($id)) as $i){
                if($i['ui_key'] == 'phone') $u['phone'] = $i['ui_value'];
                if($i['ui_key'] == 'foto') $foto = '/assets/images/users/t/'.$i['ui_value'].'.jpg';
            }

            $r['status'] = 'OK';

            if(isset($foto) and file_exists($_SERVER['DOCUMENT_ROOT'].$foto)) $u['foto'] = $this->base_url.$foto;
            else $u['foto'] = 'none';

            $cards = $this->db->fetchAll('SELECT c.id,c.header,c.content,c.user_id,c.views,c.city_id,c.model_id,m.header as model,k.header as mark,k.id as mark_id,s.header as city,c.prod_year,g.url as category,c.general_type_id as vehicle_type_id FROM card as c LEFT JOIN car_model as m ON m.id = c.model_id LEFT JOIN car_mark as k ON k.id = m.car_mark_id LEFT JOIN city as s ON s.id = c.city_id LEFT JOIN general_type as g ON g.id = c.general_type_id WHERE user_id=? ',array($id));

            $rez = array();

            if($cards and !empty($cards)) {
                foreach ($cards as $cd) $ids[] = $cd['user_id'];
                $ids = implode(",", $ids);
                foreach ($this->db->fetchAll("SELECT ui_value,user_id FROM user_info WHERE ui_key='foto' AND user_id IN (" . $ids . ")") as $if) $uf[$if['user_id']] = $if['ui_value'];

                foreach ($cards as $c) {
                    $c['prices'] = $this->db->fetchAll('SELECT cp.price_id,cp.value FROM card_price cp WHERE card_id = ?', array($c['id']));
                    $mfu = $this->db->fetchAssoc('SELECT id,folder FROM foto WHERE card_id = ? AND is_main=1', array($c['id']));
                    $c['main_foto_url_thumb'] = $this->base_url . '/assets/images/cards/' . $mfu['folder'] . '/t/' . $mfu['id'] . '.jpg';

                    if (isset($uf[$c['user_id']])) $c['user_foto'] = $this->base_url . '/assets/images/users/t/' . $uf[$c['user_id']] . '.jpg';
                    else $c['user_foto'] = 'none';

                    $rez[] = $c;
                }
            }
            $u['cards'] = $rez;
            $r['result'] = $u;

        } else {
            $r['status'] = 'error';
            $r['result'] = array('code'=>100);
            $code = '422';
        }


//        header('Content-Type: application/json');
//        echo json_encode($r, JSON_PRETTY_PRINT);
//        return new Response();

        return new Response(json_encode($r, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);

    }

    /**
     * @Route("/api/check_email", name="check_email")
     */
    public function check_email()
    {
        $code = 200;

        $data = json_decode(file_get_contents("php://input"),true);

        $u = $this->db->fetchAssoc('SELECT id FROM user WHERE email=?',array($data['email']));

        if($u) return $this->get_user($u['id']);
        else{
            $r['status'] = 'error';
            $code = 422;
            $r['result'] = array('code'=>100); // no user
            return new Response(json_encode($r, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);
        }

    }

    /**
     * @Route("/api/check_phone", name="check_phone")
     */
    public function check_phone()
    {
        $code = 200;
        $data = json_decode(file_get_contents("php://input"), true);

        $i = $this->db->fetchAssoc("SELECT user_id FROM user_info WHERE ui_key='phone' AND ui_value=?", array($data['phone']));

        if ($i and $i['user_id'] != 0) {
            return $this->get_user($i['user_id']);
        } else {
            $r['status'] = 'error';
            $code = 422;
            $r['result'] = array('code'=>100); // no user
            return new Response(json_encode($r, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);
        }

    }

    /**
     * @Route("/api/send_verification_code_SMS", name="send_verification_code")
     */
    public function send_verification_code()
    {
        $code = 200;
        $data = json_decode(file_get_contents("php://input"), true);

        $i = $this->db->fetchAssoc("SELECT user_id FROM user_info WHERE ui_key='phone' AND ui_value=?", array($data['phone']));

        if (isset($data['phone']) and isset($data['code'])) {
            $rq = array(
            "user-id"=>"mixrent",
            "api-key"=>"xXFkynY1f4OmLHwNZGhWQ4rnn1mPSFGWF8e5Gg86qg1Loy05",
            "number"=>$data['phone'],
            "security-code"=>$data['code']
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

            $r['status'] = 'OK';
            $r['result'] = array();
        } else {
            $r['status'] = 'error';
            $code = 422;
            $r['result'] = array('code'=>300);
        }

        return new Response(json_encode($r, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);
    }


    /**
     * @Route("/api/send_SMS_message", name="send_SMS_message")
     */
    public function send_SMS_message()
    {
        $code = 200;
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['phone']) and isset($data['message'])) {
            $rq = array(
            "user-id"=>"mixrent",
            "api-key"=>"xXFkynY1f4OmLHwNZGhWQ4rnn1mPSFGWF8e5Gg86qg1Loy05",
            "number"=>$data['phone'],
            "message"=>$data['message']
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

            $r['status'] = 'OK';
            $r['result'] = array();

        } else {
            $r['status'] = 'error';
            $code = 422;
            $r['result'] = array('code'=>300);

        }

        return new Response(json_encode($r, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);
    }


    /**
     * @Route("/api/authorize", name="authorize")
     */
    public function authorize()
    {
        $code = 200;

        $r = array();

        $data = json_decode(file_get_contents("php://input"),true);

        $user = $this->db->fetchAssoc('SELECT * FROM user WHERE email = ? AND is_activated=1', array($data['email']));

        if($user){

            $password = new Password();

            if ($password->CheckPassword($data['password'], $user['password'])){

                $token_name = hash("sha256",uniqid($user['id']));
                $server_hash = hash("sha256",uniqid($token_name));

                $this->db->executeQuery("INSERT INTO tokens SET 
                  user_id = ?,
                  token_name = ?,
                  server_hash = ?,
                  client_hash = ? 
                  ",array(
                      $user['id'],
                      $token_name,
                      $server_hash,
                      $data['client_hash']
                ));

                $r['status'] = 'OK';
                $r['result'] = array('token'=>$token_name,'server_hash'=>$server_hash);
            } else {
                $r['status'] = 'error';
                $code = '422';
                $r['result'] = array('code'=>110); // wrong password
            }

        } else {
            $r['status'] = 'error';
            $code = '422';
            $r['result'] = array('code'=>100); // no user
        }


        return new Response(json_encode($r, JSON_PRETTY_PRINT), $code, ['Content-Type'=>'application/json']);
    }
}

// 100 - no user
// 110 - wrong password

// 300 - required field in request not present

// 400 - MYSQL query error (bad request)

// 900 - unknown server error