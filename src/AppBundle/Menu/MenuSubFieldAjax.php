<?php

namespace AppBundle\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\SubField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class MenuSubFieldAjax extends Controller
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getSubField($fieldId, $parentId = NULL)
    {
        $query = $this->em->createQuery('SELECT s FROM AppBundle:SubField s WHERE s.fieldId = ?1 AND s.parentId = ?2');
        if ($parentId == NULL) $query = $this->em->createQuery('SELECT s FROM AppBundle:SubField s WHERE s.fieldId = ?1 AND s.parentId IS NULL');
        $query->setParameter(1, $fieldId);
        if ($parentId != NULL) $query->setParameter(2, $parentId);
        return $query->getResult();
    }

}