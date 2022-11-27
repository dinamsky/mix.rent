<?php

namespace AppBundle\SubFields;

use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\SubField;
use AppBundle\Entity\Card;
use AppBundle\Entity\CardField;
use AppBundle\Entity\FieldInteger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Menu\MenuSubFieldAjax;

class SubFieldUtils extends Controller
{
    private $em;
    private $menu;

    public function __construct(em $em, MenuSubFieldAjax $menu)
    {
        $this->em = $em;
        $this->menu = $menu;
    }

    public function getSubFields($generalTypeId)
    {

        $query = $this->em->createQuery('SELECT f, t FROM AppBundle:CardField f JOIN f.fieldType t WHERE f.generalTypeId = ?1');
        $query->setParameter(1, $generalTypeId);
        $fields = $query->getResult();
        foreach ($fields as $field){
            if ($field->getfieldType()->getformElementType() == 'numberInput'){
                $result[] = array(
                    'template' => 'common/input.html.twig',
                    'value' => $field->getfieldId(),
                    'label' => $_SERVER['LANG'] == 'ru' ? $field->getfieldType()->getHeader() : $field->getfieldType()->getHeaderEn()
                );
            }
            if ($field->getfieldType()->getformElementType() == 'ajaxMenu'){
                $result[] = array(
                    'template' => 'common/ajax_select.html.twig',
                    'value' => $this->menu->getSubField($field->getfieldId(),0),
                    'label' => $_SERVER['LANG'] == 'ru' ? $field->getfieldType()->getHeader() : $field->getfieldType()->getHeaderEn()
                );
            }
        }
        return $result;
    }

    public function getSubFieldsEdit(Card $card)
    {
        if(!$card->getFieldIntegers()->isEmpty()) {
            foreach ($card->getFieldIntegers() as $row) {
                $fieldInteger[$row->getCardFieldId()] = $row->getValue();
            }
        } else $fieldInteger = [];

            $query = $this->em->createQuery('SELECT f, t FROM AppBundle:CardField f JOIN f.fieldType t WHERE f.generalTypeId = ?1');
            $query->setParameter(1, $card->getGeneralTypeId());
            $fields = $query->getResult();

            $result = [];

            foreach ($fields as $field) {

                $query = $this->em->createQuery('SELECT s FROM AppBundle:SubField s WHERE s.id = ?1');
                if (isset($fieldInteger[$field->getfieldType()->getId()])) $query->setParameter(1, $fieldInteger[$field->getfieldType()->getId()]);
                else {
                    $query->setParameter(1, 0);
                    $fieldInteger[$field->getfieldType()->getId()] = 0;
                }
                $is_subfield = $query->getResult();
                if ($is_subfield) $subfield = $is_subfield[0];
                else $subfield = array();

                if ($field->getfieldType()->getformElementType() == 'numberInput') {
                    $result[] = array(
                        'template' => 'common/input_edit.html.twig',
                        'value' => $field->getfieldId(),
                        'label' => $_SERVER['LANG'] == 'ru' ? $field->getfieldType()->getHeader() : $field->getfieldType()->getHeaderEn(),
                        'data' => $fieldInteger[$field->getfieldType()->getId()],
                        'subfield' => $subfield,
                        'lang' => $_SERVER['LANG']
                    );
                }
                if ($field->getfieldType()->getformElementType() == 'ajaxMenu') {

                    $last = $subfield;
                    $level = 0;
                    if ($subfield instanceof SubField and $subfield->getParentId() != NULL) do {
                        $subfield = $subfield->getParent();
                        $level++;
                    } while ($subfield->getParentId() != NULL);

                    $query = $this->em->createQuery('SELECT s FROM AppBundle:SubField s WHERE s.fieldId=?1 AND s.parentId IS NULL');
                    $query->setParameter(1, $field->getfieldType()->getId());
                    $first = $query->getResult();

                    $result[] = array(
                        'template' => 'common/ajax_select_edit.html.twig',
                        'label' => $_SERVER['LANG'] == 'ru' ? $field->getfieldType()->getHeader() : $field->getfieldType()->getHeaderEn(),
                        'data' => $fieldInteger[$field->getfieldType()->getId()],
                        'subfield_first' => $subfield,
                        'subfield_last' => $last,
                        'first' => $first,
                        'level' => $level,
                        'field_id' => $field->getfieldId(),
                        'lang' => $_SERVER['LANG']
                    );
                }
            }
            return $result;
//        }
//        else return [];
    }

    public function getCardSubFields(Card $card)
    {
        $result = array();
        $generalTypeId = $card->getGeneralTypeId();

        $query = $this->em->createQuery('SELECT f, t FROM AppBundle:CardField f JOIN f.fieldType t WHERE f.generalTypeId = ?1');
        $query->setParameter(1, $generalTypeId);
        $fields = $query->getResult();


        /**
         * @var $field CardField
         */
        foreach ($fields as $field){
            $storage = 'AppBundle:'.$field->getfieldType()->getStorageType();
            $query = $this->em->createQuery('SELECT s FROM '.$storage.' s WHERE s.cardId = ?1 AND s.cardFieldId = ?2');
            $query->setParameter(1, $card->getId());
            $query->setParameter(2, $field->getFieldType()->getId());
            $value = $query->getOneOrNullResult();

            $type = 'integer';

            if ($field->getfieldType()->getformElementType() == 'ajaxMenu' and $value != null){
                $value = $this->em
                    ->getRepository(SubField::class)
                    ->find($value->getValue());
                $type = 'subfield';
            }

            $result[] = array(
                'value' => $value,
                'label' => $_SERVER['LANG'] == 'ru' ? $field->getfieldType()->getHeader() : $field->getfieldType()->getHeaderEn(),
                'type' => $type
            );


        }
        return $result;
    }
}