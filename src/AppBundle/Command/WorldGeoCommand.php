<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class WorldGeoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:w-geo-country');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {



        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $cntr = $conn->fetchAll('SELECT * FROM w_country Where coords is null');
        foreach ($cntr as $c) {
            $id= $c['id'];
                       $adress = urlencode($c['name']);
            $json = json_decode(file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$adress.'&sensor=false'),true);
            //$output->writeln(dump($json));
            if(isset($json['results'][0]['geometry']['location'])) {
                $coords = $json['results'][0]['geometry']['location'];
                $coords = $coords['lat'] . ',' . $coords['lng'];
                $conn->query("UPDATE w_country SET coords = '$coords' WHERE id=$id");
            }
        }



        $output->writeln('All regions done!');
    }


}