<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class CityTotalCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:city-total');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mc  = $this->getContainer()->get('AppBundle\Menu\MenuCity');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $q = $conn->query("UPDATE city SET total = 0, models = ''");
        $q->execute();

        $cards = $conn->fetchAll('SELECT city_id, model_id FROM card');
        foreach ($cards as $card) {
            $mc->updateCityTotal($card['city_id'],$card['model_id']);
        }

        $output->writeln('All done!');
    }


}