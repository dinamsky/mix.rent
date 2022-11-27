<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Promo;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Seo;
use AppBundle\Foto\FotoUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\UserInfo;
use UserBundle\Security\Password;
use UserBundle\Entity\User;
use AdminBundle\Entity\Admin;

class PromoController extends Controller
{
    /**
     * @Route("/adminPromo", name="adminPromo")
     */
    public function PromoAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $dql = "SELECT p FROM AppBundle:Promo p WHERE p.pId=1 AND p.pKey != 'opinion'";
        $query = $em->createQuery($dql);
        foreach($query->getResult() as $row){
            $promo[$row->getPKey()] = $row->getPValue();
        }



        if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_main.html.twig');
        else {

            return $this->render('AdminBundle::admin_promo.html.twig',[
                'promo' => $promo
            ]);
        }

    }

    /**
     * @Route("/adminPromoUpdate")
     */
    public function UpdateAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        foreach($request->request->get('content') as $key=>$value){
            $promo = $this->getDoctrine()
                ->getRepository(Promo::class)
                ->findOneBy(['pKey'=>$key]);
            $promo->setPValue($value);
            $em->persist($promo);
        }
        $em->flush();
        return $this->redirectToRoute('adminPromo');
    }


    /**
     * @Route("/adminPromoOpinions", name="adminPromoOpinions")
     */
    public function PromoOpinionsAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $dql = "SELECT p FROM AppBundle:Promo p WHERE p.pId=1 AND p.pKey = 'opinion'";
        $query = $em->createQuery($dql);
        foreach($query->getResult() as $row){
            $r = json_decode($row->getPValue(),true);
            $r['id'] = $row->getId();
            $opinions[$r['sort']] = $r;
        }
        ksort($opinions);

        if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_main.html.twig');
        else {

            return $this->render('AdminBundle::admin_promo_opinions.html.twig',[
                'opinions' => $opinions
            ]);
        }

    }

    /**
     * @Route("/adminPromoOpinionsInsert")
     */
    public function OpInsertAction(Request $request, FotoUtils $fu)
    {
        $em = $this->get('doctrine')->getManager();

        $op = new Promo();
        $op->setPId(1);
        $op->setPKey('opinion');
        $op->setPValue(json_encode(array(
            'name'=>$request->request->get('name'),
            'sort'=>$request->request->get('sort'),
            'desc'=>$request->request->get('desc'),
        )));

        $em->persist($op);
        $em->flush();

        $fu->uploadImage('foto','op_'.$op->getId(),'',$_SERVER['DOCUMENT_ROOT'].'/assets/images/interface/promo');

        return $this->redirectToRoute('adminPromoOpinions');
    }

    /**
     * @Route("/adminPromoOpinionsUpdate")
     */
    public function OpUpdateAction(Request $request, FotoUtils $fu)
    {
        $em = $this->get('doctrine')->getManager();



        if($request->request->has('delete')){
            $promo = $this->getDoctrine()
                    ->getRepository(Promo::class)
                    ->find($request->request->get('delete'));
            $em->remove($promo);
            $em->flush();
            @unlink ($_SERVER['DOCUMENT_ROOT'].'/assets/images/interface/promo/op_'.$request->request->get('delete').'.jpg');
        } else {
            foreach($request->request->get('name') as $id=>$value)
            {
                $promo = $this->getDoctrine()
                    ->getRepository(Promo::class)
                    ->find($id);
                $promo->setPValue(json_encode(array(
                'name'=>$value,
                'sort'=>$request->request->get('sort')[$id],
                'desc'=>$request->request->get('desc')[$id],
                )));
                $em->persist($promo);
                if(isset($_FILES['foto_'.$id]['name'])) $fu->uploadImage('foto_'.$id,'op_'.$id,'',$_SERVER['DOCUMENT_ROOT'].'/assets/images/interface/promo');

            }

            $em->flush();
        }
        return $this->redirectToRoute('adminPromoOpinions');
    }

    /**
     * @Route("/adminPromoFotoUpdate")
     */
    public function PFUpdateAction(Request $request, FotoUtils $fu)
    {

        if(isset($_FILES['f_b1']['name'])) $fu->uploadImage('f_b1','b1',$_SERVER['DOCUMENT_ROOT'].'/assets/images/interface/promo');
        if(isset($_FILES['f_b2']['name'])) $fu->uploadImage('f_b2','b2',$_SERVER['DOCUMENT_ROOT'].'/assets/images/interface/promo');


        return $this->redirectToRoute('adminPromo');
    }

}
