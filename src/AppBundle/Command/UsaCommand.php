<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class UsaCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:usa-city');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $ids = $iso = [];

        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $regions = $conn->fetchAll('SELECT * FROM usa_states');
        foreach ($regions as $r) {
            $conn->insert('city', array(
                'parent_id' => NULL,
                'country' => $r['country_id'] == 1 ? 'CAN' : 'USA',
                'header' => $r['name'],
                'url' => str_replace(" ","_", $r['name']),
                'gde' => ' ',
                'total' => 1,
                'models' => ' ',
                'coords' => ' ',
                'iso' => $r['iso']
            ));
            $ids[$r['id']] = $conn->lastInsertId();
            $iso[$r['id']] = $r['iso'];
        }

        $cities = $conn->fetchAll('SELECT * FROM usa_cities');
        foreach ($cities as $r) {

            if ($r['country_id'] == 1) $country = 'CAN';
            else $country = 'USA';

            $conn->insert('city', array(
                'parent_id' => $ids[$r['state_id']],
                'country' => $country,
                'header' => $r['name'],
                'url' => str_replace(" ","_", $r['name']).'_'.$iso[$r['state_id']].'_'.$country,
                'gde' => ' ',
                'total' => 1,
                'models' => ' ',
                'coords' => $r['coords'],
                'iso' => $iso[$r['state_id']]
            ));
        }

        $output->writeln('All regions done!');
    }


}