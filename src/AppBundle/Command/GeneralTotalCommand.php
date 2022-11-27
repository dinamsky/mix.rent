<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuGeneralType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class GeneralTotalCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:general-total');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gt  = $this->getContainer()->get('AppBundle\Menu\MenuGeneralType');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $conn->query("UPDATE general_type SET total = 0");


        $cards = $conn->fetchAll('SELECT general_type_id FROM card');
        foreach ($cards as $card) {
            $gt->updateTotal($card['general_type_id']);
        }

        $output->writeln('All done!');
    }


}