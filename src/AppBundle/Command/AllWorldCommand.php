<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class AllWorldCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:all-world');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {



        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $cntr = $conn->fetchAll('SELECT * FROM w_country WHERE id NOT IN (0,89,152)');
        foreach ($cntr as $c) {

            $reg = $conn->fetchAll('SELECT * FROM w_region WHERE country_id='.$c['id']);

            foreach($reg as $r){
                $conn->insert('city', array(
                    'parent_id' => NULL,
                    'country' => $c['iso3'],
                    'header' => $this->translit($r['name']),
                    'url' => str_replace(" ","_",  $this->translit($r['name'])),
                    'gde' => ' ',
                    'total' => 1,
                    'models' => ' ',
                    'coords' => ' ',
                    'iso' => $c['iso2']
                ));
                $reg_id = $conn->lastInsertId();
                $cts = $conn->fetchAll('SELECT * FROM w_city WHERE region_id='.$r['id']);
                foreach($cts as $ct){
                    $conn->insert('city', array(
                        'parent_id' => $reg_id,
                        'country' => $c['iso3'],
                        'header' => in_array($c['id'],[1,2,21,81]) ? $ct['name'] : $this->translit($ct['name']),
                        'url' => str_replace(" ","_",  $this->translit($ct['name'])).'_'.$c['iso3'],
                        'gde' => ' ',
                        'total' => 1,
                        'models' => ' ',
                        'coords' => $c['coords'],
                        'iso' => $c['iso2']
                    ));
                }
            }


        }



        $output->writeln('All regions done!');
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
               '.' => '.',   '«' => '',
            '»' => '',   '"' => '', '№' => 'N', '“'=>'', '”'=>''
        );
        return strtr($string, $converter);
    }


}