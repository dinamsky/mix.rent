<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class WorldCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:w-country');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {



        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $cntr = $conn->fetchAll('SELECT * FROM w_full_countries');
        foreach ($cntr as $c) {
                       $sql = "UPDATE w_country SET name_en=?, iso2='".$c['Iso2']."', iso3='".$c['Iso3']."' WHERE name=?";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $c['NameEng']);
            $stmt->bindValue(2, $c['Name']);
            $stmt->execute();
        }



        $output->writeln('All regions done!');
    }


}