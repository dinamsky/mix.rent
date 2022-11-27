<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class FixCrimeaCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:fix-crimea');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $cards = $conn->fetchAll('SELECT id, address FROM card WHERE city_id = 274');
        foreach ($cards as $card) {
            $xadress = explode(",", $card['address']);
            $cityId = 1243;
            foreach ($xadress as $adr) {
                $sql = "SELECT id FROM city WHERE header = '" . trim($adr)."'";
                $res = $conn->fetchColumn($sql);
                if ($res) $cityId = $res;
            }
            $q = $conn->query('UPDATE card SET city_id = '.$cityId.' WHERE id='.$card['id']);
            $q->execute();
        }

        $output->writeln('All done!');
    }

    //
}