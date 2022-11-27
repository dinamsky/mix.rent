<?php

namespace AppBundle\Foto;

use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Card;
use AppBundle\Entity\Foto;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


class FotoUtils extends Controller
{
    private $em;

    public function __construct(em $em)
    {
        $this->em = $em;
    }

    public function uploadImages(Card $card)
    {
        $main_dir = $_SERVER['DOCUMENT_ROOT'].'/assets/images/cards/'.date('Y').'/'.date('m');
        $thumbs = $_SERVER['DOCUMENT_ROOT'].'/assets/images/cards/'.date('Y').'/'.date('m').'/t';
        @mkdir($main_dir,'0755', true);
        @mkdir($thumbs,'0755', true);
        $ff = 'fotos';
        $is_main = false;
        if($card->getFotos()->isEmpty()) $is_main = true;

        //dump($_FILES);
        //dump($_POST['to_upload']);

        foreach($_FILES[$ff]['name'] as $k=>$v)
        {
            if (!empty($_FILES[$ff]['name'][$k]) and in_array($_FILES[$ff]['name'][$k],$_POST['to_upload'],true))
            {
                $ext = explode(".",basename($_FILES[$ff]['name'][$k]));
                $ext = strtolower($ext[(count($ext)-1)]);

                $foto = new Foto();
                $foto->setCard($card);
                $foto->setIsMain($is_main);
                $foto->setFolder(date('Y').'/'.date('m'));
                $this->em->persist($foto);
                $this->em->flush();

                $is_main = false;

                $file_id = $foto->getId();

                $file = @file_get_contents($_FILES[$ff]['tmp_name'][$k]);
                $im1 = @imagecreatefromstring($file);
                if ($im1 !== false)
                {
                    $width = 1280;
                    $height = 900;

                    $w_src1 = imagesx($im1);
                    $h_src1 = imagesy($im1);
                    $ratio = $w_src1/$h_src1;
                    if ($width/$height > $ratio) {
                        $width = $height*$ratio;
                    } else {
                        $height = $width/$ratio;
                    }
                    $image_p = imagecreatetruecolor($width, $height);
                    $bgColor = imagecolorallocate($image_p, 255,255,255);
                    imagefill($image_p , 0,0 , $bgColor);
                    imagecopyresampled($image_p, $im1, 0, 0, 0, 0, $width, $height, $w_src1, $h_src1);

                    imagejpeg($image_p, $main_dir.'/'.$file_id.'.jpg');

                    $width = 400;
                    $height = 300;

                    $w_src1 = imagesx($im1);
                    $h_src1 = imagesy($im1);
                    $ratio = $w_src1/$h_src1;
                    if ($width/$height > $ratio) {
                        $width = $height*$ratio;
                    } else {
                        $height = $width/$ratio;
                    }
                    $image_p = imagecreatetruecolor($width, $height);
                    $bgColor = imagecolorallocate($image_p, 255,255,255);
                    imagefill($image_p , 0,0 , $bgColor);
                    imagecopyresampled($image_p, $im1, 0, 0, 0, 0, $width, $height, $w_src1, $h_src1);
                    imagejpeg($image_p, $thumbs.'/'.$file_id.'.jpg');

                    unlink ($_FILES[$ff]['tmp_name'][$k]);
                }
                else
                {
                    $this->em->remove($foto);
                    $this->em->flush();
                }
            }
        }
    }

    public function moveResizeImage($from_img, $to_img, $to_thumb_img)
    {
        $im1 = false;
        $file = @file_get_contents($from_img);
        if ($file !== false) $im1 = @imagecreatefromstring($file);
        if ($im1 !== false) {

            $width = 1280;
            $height = 900;

            $w_src1 = imagesx($im1);
            $h_src1 = imagesy($im1);
            $ratio = $w_src1 / $h_src1;
            if ($width / $height > $ratio) {
                $width = $height * $ratio;
            } else {
                $height = $width / $ratio;
            }
            $image_p = imagecreatetruecolor($width, $height);
            $bgColor = imagecolorallocate($image_p, 255, 255, 255);
            imagefill($image_p, 0, 0, $bgColor);
            imagecopyresampled($image_p, $im1, 0, 0, 0, 0, $width, $height, $w_src1, $h_src1);

            imagejpeg($image_p, $to_img);

            $width = 400;
            $height = 300;

            $w_src1 = imagesx($im1);
            $h_src1 = imagesy($im1);
            $ratio = $w_src1 / $h_src1;
            if ($width / $height > $ratio) {
                $width = $height * $ratio;
            } else {
                $height = $width / $ratio;
            }
            $image_p = imagecreatetruecolor($width, $height);
            $bgColor = imagecolorallocate($image_p, 255, 255, 255);
            imagefill($image_p, 0, 0, $bgColor);
            imagecopyresampled($image_p, $im1, 0, 0, 0, 0, $width, $height, $w_src1, $h_src1);
            imagejpeg($image_p, $to_thumb_img);
        }
    }


    public function uploadImage($foto_var, $new_name, $target_folder = '', $thumb_folder = '')
    {
        $main_dir = $target_folder;
        $thumbs = $thumb_folder;
        if ($target_folder != '') @mkdir($main_dir,'0755', true);
        if ($thumb_folder != '') @mkdir($thumbs,'0755', true);
        $ff = $foto_var;

        if ($new_name == 'translit') $new_name = basename($this->translit($_FILES[$ff]['name']));
        if ($new_name == 'md5') $new_name = basename(md5($_FILES[$ff]['name']));

        if ($_FILES[$ff]['tmp_name']!='') {

            $file = file_get_contents($_FILES[$ff]['tmp_name']);

            $im1 = imagecreatefromstring($file);
            if ($im1 !== false) {
                if ($target_folder != '') {
                    $width = 1280;
                    $height = 900;

                    $w_src1 = imagesx($im1);
                    $h_src1 = imagesy($im1);
                    $ratio = $w_src1 / $h_src1;
                    if ($width / $height > $ratio) {
                        $width = $height * $ratio;
                    } else {
                        $height = $width / $ratio;
                    }
                    $image_p = imagecreatetruecolor($width, $height);
                    $bgColor = imagecolorallocate($image_p, 255, 255, 255);
                    imagefill($image_p, 0, 0, $bgColor);
                    imagecopyresampled($image_p, $im1, 0, 0, 0, 0, $width, $height, $w_src1, $h_src1);

                    imagejpeg($image_p, $main_dir . '/' . $new_name . '.jpg');

                }
                if ($thumb_folder != '') {
                    $width = 400;
                    $height = 300;

                    $w_src1 = imagesx($im1);
                    $h_src1 = imagesy($im1);
                    $ratio = $w_src1 / $h_src1;
                    if ($width / $height > $ratio) {
                        $width = $height * $ratio;
                    } else {
                        $height = $width / $ratio;
                    }
                    $image_p = imagecreatetruecolor($width, $height);
                    $bgColor = imagecolorallocate($image_p, 255, 255, 255);
                    imagefill($image_p, 0, 0, $bgColor);
                    imagecopyresampled($image_p, $im1, 0, 0, 0, 0, $width, $height, $w_src1, $h_src1);
                    imagejpeg($image_p, $thumbs . '/' . $new_name . '.jpg');
                }
                unlink($_FILES[$ff]['tmp_name']);
            }
        }
    }

    public function uploadImageKey($foto_var, $foto_key, $new_name, $target_folder = '', $thumb_folder = '')
    {
        $main_dir = $target_folder;
        $thumbs = $thumb_folder;
        if ($target_folder != '') @mkdir($main_dir,'0755', true);
        if ($thumb_folder != '') @mkdir($thumbs,'0755', true);

        $ff = $foto_var;

        if ($new_name == 'translit') $new_name = basename($this->translit($_FILES[$ff]['name'][$foto_key]));
        if ($new_name == 'md5') $new_name = basename(md5($_FILES[$ff]['name'][$foto_key]));

        if ($_FILES[$ff]['tmp_name'][$foto_key]!='') {

            $file = file_get_contents($_FILES[$ff]['tmp_name'][$foto_key]);

            $im1 = imagecreatefromstring($file);
            if ($im1 !== false) {
                if ($target_folder != '') {
                    $width = 1280;
                    $height = 900;

                    $w_src1 = imagesx($im1);
                    $h_src1 = imagesy($im1);
                    $ratio = $w_src1 / $h_src1;
                    if ($width / $height > $ratio) {
                        $width = $height * $ratio;
                    } else {
                        $height = $width / $ratio;
                    }
                    $image_p = imagecreatetruecolor($width, $height);
                    $bgColor = imagecolorallocate($image_p, 255, 255, 255);
                    imagefill($image_p, 0, 0, $bgColor);
                    imagecopyresampled($image_p, $im1, 0, 0, 0, 0, $width, $height, $w_src1, $h_src1);

                    imagejpeg($image_p, $main_dir . '/' . $new_name . '.jpg');

                }
                if ($thumb_folder != '') {
                    $width = 400;
                    $height = 300;

                    $w_src1 = imagesx($im1);
                    $h_src1 = imagesy($im1);
                    $ratio = $w_src1 / $h_src1;
                    if ($width / $height > $ratio) {
                        $width = $height * $ratio;
                    } else {
                        $height = $width / $ratio;
                    }
                    $image_p = imagecreatetruecolor($width, $height);
                    $bgColor = imagecolorallocate($image_p, 255, 255, 255);
                    imagefill($image_p, 0, 0, $bgColor);
                    imagecopyresampled($image_p, $im1, 0, 0, 0, 0, $width, $height, $w_src1, $h_src1);
                    imagejpeg($image_p, $thumbs . '/' . $new_name . '.jpg');
                }
                unlink($_FILES[$ff]['tmp_name'][$foto_key]);
            }

            return $new_name;
        }
    }

    /**
     * @Route("/ajax/deleteFoto")
     */

    public function deleteFoto(Request $request)
    {


        $main_dir = $_SERVER['DOCUMENT_ROOT'].'/assets/images/cards';


        $post = $request->request;
        $id = $post->get('id');

        $foto = $this->em
            ->getRepository(Foto::class)
            ->find($id);

        @unlink ($main_dir.'/'.$foto->getFolder().'/'.$id.'.jpg');
        @unlink ($main_dir.'/'.$foto->getFolder().'/t/'.$id.'.jpg');


        $card = $this->em
            ->getRepository(Card::class)
            ->find($foto->getCardId());

        if(count($card->getFotos())<2) return new Response('stop', 200);

        $setNewMain = false;
        if($foto->getIsMain()) $setNewMain = true;

        $this->em->remove($foto);
        $this->em->flush();

        if($setNewMain){
            $foto = $this->em
                ->getRepository(Foto::class)
                ->findOneBy(['cardId' => $card->getId()]);
            $foto->setIsMain(true);
            $this->em->persist($foto);
            $this->em->flush();
        }

        return new Response();
    }

    public function deleteAllFotos(Card $card)
    {

        $main_dir = $_SERVER['DOCUMENT_ROOT'].'/assets/images/cards';

        foreach ($card->getFotos() as $foto){
            if (is_file($main_dir.'/'.$foto->getFolder().'/'.$foto->getId().'.jpg')) unlink ($main_dir.'/'.$foto->getFolder().'/'.$foto->getId().'.jpg');
            if (is_file($main_dir.'/'.$foto->getFolder().'/t/'.$foto->getId().'.jpg')) unlink ($main_dir.'/'.$foto->getFolder().'/t/'.$foto->getId().'.jpg');
        }
        return new Response();
    }

    /**
     * @Route("/ajax/mainFoto")
     */

    public function mainFoto(Request $request)
    {
        $post = $request->request;
        $id = $post->get('id');

        $foto = $this->em
            ->getRepository(Foto::class)
            ->find($id);

        $query = $this->em->createQuery('UPDATE AppBundle:Foto f SET f.isMain = 0 WHERE f.cardId = ?1');
        $query->setParameter(1, $foto->getCardId());
        $query->execute();

        $foto->setIsMain(true);
        $this->em->persist($foto);
        $this->em->flush();

        return new Response();
    }

    public function translit($string){
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
            ' ' => '_',   '.' => '.',   '«' => '',
            '»' => '',   '"' => '', '№' => 'N', '“'=>'', '”'=>''
        );
        return strtr($string, $converter);
    }


}