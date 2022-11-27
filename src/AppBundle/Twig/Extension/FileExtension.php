<?php

namespace AppBundle\Twig\Extension;


use AppBundle\Entity\Card;
use UserBundle\Entity\User;

class FileExtension extends \Twig_Extension
{

    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Return the functions registered as twig extensions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('file_exists', 'file_exists'),
            new \Twig_SimpleFunction('jsond', function ($json){
                return json_decode($json, true);
            }),
            new \Twig_SimpleFunction('getuser', function ($id){
                if($id) {
                    return $this->em
                        ->getRepository(User::class)
                        ->find($id);
                } else return var_dump($id);
            }),

            new \Twig_SimpleFunction('getcard', function ($id){
                return $this->em
                ->getRepository(Card::class)
                ->find($id);
            }),
            new \Twig_SimpleFunction('main_foto', function($fotos){
                return $fotos->filter(function($foto) {
                    return $foto->getIsMain() === TRUE;
                })->first();
            }),
        );
    }

    public function getName()
    {
        return 'app_file';
    }
}
?>