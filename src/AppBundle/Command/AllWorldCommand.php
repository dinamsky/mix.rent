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
            '??' => 'a',   '??' => 'b',   '??' => 'v',
            '??' => 'g',   '??' => 'd',   '??' => 'e',
            '??' => 'e',   '??' => 'zh',  '??' => 'z',
            '??' => 'i',   '??' => 'y',   '??' => 'k',
            '??' => 'l',   '??' => 'm',   '??' => 'n',
            '??' => 'o',   '??' => 'p',   '??' => 'r',
            '??' => 's',   '??' => 't',   '??' => 'u',
            '??' => 'f',   '??' => 'h',   '??' => 'c',
            '??' => 'ch',  '??' => 'sh',  '??' => 'sch',
            '??' => '',    '??' => 'y',   '??' => '',
            '??' => 'e',   '??' => 'yu',  '??' => 'ya',

            '??' => 'A',   '??' => 'B',   '??' => 'V',
            '??' => 'G',   '??' => 'D',   '??' => 'E',
            '??' => 'E',   '??' => 'Zh',  '??' => 'Z',
            '??' => 'I',   '??' => 'Y',   '??' => 'K',
            '??' => 'L',   '??' => 'M',   '??' => 'N',
            '??' => 'O',   '??' => 'P',   '??' => 'R',
            '??' => 'S',   '??' => 'T',   '??' => 'U',
            '??' => 'F',   '??' => 'H',   '??' => 'C',
            '??' => 'Ch',  '??' => 'Sh',  '??' => 'Sch',
            '??' => '',    '??' => 'Y',   '??' => '',
            '??' => 'E',   '??' => 'Yu',  '??' => 'Ya',
               '.' => '.',   '??' => '',
            '??' => '',   '"' => '', '???' => 'N', '???'=>'', '???'=>''
        );
        return strtr($string, $converter);
    }


}