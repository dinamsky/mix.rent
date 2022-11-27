<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class UsaCoordsCityCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:usa-coords');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $cities = $conn->fetchAll("SELECT c.id,c.name,s.iso FROM usa_cities c LEFT JOIN usa_states s ON s.id = c.state_id WHERE c.coords = ''");
        foreach ($cities as $city) {
            $id = $city['id'];

            $sql = "SELECT * FROM usa_coords WHERE city LIKE '%".str_replace("'"," ",$city['name'])."%' AND state_id=?";
            $coords = $conn->fetchAssoc($sql, array($city['iso']));

            if($coords) {
                $c = $coords['lat'].','.$coords['lng'];
                $conn->query("UPDATE usa_cities SET coords = '$c' WHERE id=$id");
            }
        }

        $output->writeln('All usa coords done!');
    }


}