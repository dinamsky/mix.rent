<?php

namespace AppBundle\Command;

use AppBundle\Menu\MenuCity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ModelTotalCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:model-total');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mm  = $this->getContainer()->get('AppBundle\Menu\MenuMarkModel');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $conn = $em->getConnection();

        $conn->query('UPDATE car_model SET total = 0');

        $cards = $conn->fetchAll('SELECT model_id FROM card');
        foreach ($cards as $card) {
            $mm->updateModelTotal($card['model_id']);
        }

        $output->writeln('All done!');
    }


}