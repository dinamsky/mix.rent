<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class CoordsCityCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:city-coords');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $mc  = $this->getContainer()->get('AppBundle\Menu\MenuCity');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $cities = $conn->fetchAll("SELECT id,header FROM city WHERE parent_id IS NOT NULL AND coords =''");
        foreach ($cities as $city) {
            $id = $city['id'];
            $adress = urlencode($city['header']);
            $json = json_decode(file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$adress.'&sensor=false'),true);
            //$output->writeln(dump($json));
            if(isset($json['results'][0]['geometry']['location'])) {
                $coords = $json['results'][0]['geometry']['location'];
                $coords = $coords['lat'] . ',' . $coords['lng'];
                $conn->query("UPDATE city SET coords = '$coords' WHERE id=$id");
            }
        }

        $output->writeln('All coords done!');
    }


}