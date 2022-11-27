<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GeneralTypesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:general-types');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (COMMERCIAL)') WHERE car_type_id = 2");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (HEAVY)') WHERE car_type_id = 3");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (TRAILER)') WHERE car_type_id = 4");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (BUS)') WHERE car_type_id = 6");



        $this->moving('2,3,4,6',5);

        $output->writeln('Trucks done!');

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (CRANE)') WHERE car_type_id = 8");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (BULLDOZER)') WHERE car_type_id = 9");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (FORKLIFT)') WHERE car_type_id = 10");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (EXCAVATOR)') WHERE car_type_id = 11");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (AGRO)') WHERE car_type_id = 12");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (CITY)') WHERE car_type_id = 13");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (LOADER)') WHERE car_type_id = 14");


        $this->moving('8,9,10,11,12,13,14',15);

        $output->writeln('Special done!');

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (CARTING)') WHERE car_type_id = 24");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (AMPHIBIAN)') WHERE car_type_id = 25");


        $this->moving('24,25',21);

        $output->writeln('Sport 4x4 done!');

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (JETBOAT)') WHERE car_type_id = 28");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (HYDROCYCLE)') WHERE car_type_id = 30");

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (BOAT)') WHERE car_type_id = 31");


        $this->moving('28,30,31',27);

        $output->writeln('Yachts done!');

        $conn->query("UPDATE car_mark SET header = CONCAT(header,' (RETRO)') WHERE car_type_id = 16");


        $this->moving('16',1);

        $output->writeln('Retro done!');
    }

    protected function moving($from,$to)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $conn->query("UPDATE car_characteristic SET car_type_id = $to WHERE car_type_id IN (".$from.")");

        $conn->query("UPDATE car_characteristic_value SET car_type_id = $to WHERE car_type_id IN (".$from.")");

        $conn->query("UPDATE car_generation SET car_type_id = $to WHERE car_type_id IN (".$from.")");

        $conn->query("UPDATE car_mark SET car_type_id = $to WHERE car_type_id IN (".$from.")");

        $conn->query("UPDATE car_model SET car_type_id = $to WHERE car_type_id IN (".$from.")");

        $conn->query("UPDATE car_modification SET car_type_id = $to WHERE car_type_id IN (".$from.")");

        $conn->query("UPDATE car_series SET car_type_id = $to WHERE car_type_id IN (".$from.")");

    }


}