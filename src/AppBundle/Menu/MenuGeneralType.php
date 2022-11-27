<?php

namespace AppBundle\Menu;

use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\GeneralType;
use Symfony\Component\HttpFoundation\Response;

class MenuGeneralType extends Controller
{
    private $em;

    public function __construct(em $em)
    {
        $this->em = $em;
    }

    public function getGeneralTypeMenu($parent_id = null)
    {
        $array = array();
        $dql = 'SELECT gt FROM AppBundle:GeneralType gt WHERE gt.parentId = ?1';
        if ($parent_id == null) $dql = 'SELECT gt FROM AppBundle:GeneralType gt WHERE gt.parentId IS NULL';
        $query = $this->em->createQuery($dql);
        if ($parent_id != null) $query->setParameter(1, $parent_id);
        $result = $query->getResult();

        if (!empty($result)) foreach ($result as $row){
            $array[$row->getId()] = array(
                'header' => $row->getHeader(),
                'childs' => $this->getGeneralTypeMenu($row->getId())
            );
        }
        return $array;
    }

    public function getArrayOfChildIdsOfGeneralTypeMenu($gt,$type_id)
    {
        $result[] = $type_id;
        if(isset($gt[$type_id]['childs'])) foreach($gt[$type_id]['childs'] as $id=>$val){
            $result[] = $id;
            if($val['childs']) $result = array_merge($result, $this->getArrayOfChildIdsOfGeneralTypeMenu($gt,$id));
        }
        return $result;
    }

    public function getTopLevel()
    {
        $query = $this->em->createQuery('SELECT g FROM AppBundle:GeneralType g INDEX BY g.id WHERE g.parentId IS NULL');
        return $query->getResult();
    }

    public function getSecondLevel($parentId)
    {
        $query = $this->em->createQuery('SELECT g FROM AppBundle:GeneralType g INDEX BY g.id WHERE g.parentId = ?1');
        $query->setParameter(1, $parentId);
        return $query->getResult();
    }

    /**
     * @Route("/ajax/getGeneralTypeSecondLevel")
     */
    public function getSecondLevelAction(Request $request)
    {
        $generalTypeTopLevelId = $request->request->get('generalTypeTopLevelId');
        return $this->render('common/ajax_options_url.html.twig', [
            'options' => $this->getSecondLevel($generalTypeTopLevelId),
            'lang' => $_SERVER['LANG']
        ]);
    }

    public function updateTotal($gtId)
    {
        $query = $this->em->createQuery('UPDATE AppBundle:GeneralType g SET g.total = g.total + 1 WHERE g.id ='.$gtId);
        $query->execute();
    }

    /**
     * @Route("/ajax/getCarType")
     */
    public function getCarTypeAction(Request $request)
    {
        $gt = $request->request->get('gt');
        $query = $this->em->createQuery('SELECT g FROM AppBundle:GeneralType g WHERE g.id = ?1');
        $query->setParameter(1, $gt);
        $result =  $query->getResult();
        return new Response($result[0]->getCarTypeIds());
    }

    /**
     * @Route("/ajax/getGtByInput")
     */
    public function getGtByInputAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $res = array();

        $gts = $em->getRepository("AppBundle:GeneralType")->createQueryBuilder('g')
            ->where('g.header LIKE :ct')
            ->andWhere("g.carTypeIds != '' ")
            ->setParameter('ct', '%'.$request->request->get('q').'%')
            ->getQuery()
            ->getResult();

        foreach($gts as $c){
            $res[] = $c->getHeader().'|'.$c->getUrl();
        }

        return new Response(json_encode($res));
    }
}