<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class MoveUserImagesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:move-user-images');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('start');

        @mkdir('./web/assets/images/users', 0755, true);
        @mkdir('./web/assets/images/users/t', 0755, true);


        $output->writeln('dir created!');

        $fu = $this->getContainer()->get('AppBundle\Foto\FotoUtils');

        $wp = $this->getContainer()->get('doctrine')->getManager('wpcon');
        $conn = $wp->getConnection();
        $users = $conn->fetchAll('SELECT * FROM wp_users');

        foreach($users as $user) if ($user['ID'] > 4046) {
            $ids[] = $user['ID'];
        }
        $ids = implode(",",$ids);

        $usermeta = $conn->fetchAll("SELECT *,p.guid FROM wp_usermeta m 
LEFT JOIN wp_posts p ON (p.ID = m.meta_value) AND m.meta_key = 'user_photo'
WHERE user_id IN ($ids)");
        $i=0;
        foreach($usermeta as $m){

            if($m['meta_key'] == 'user_photo' and $m['guid']!='') {


                $mainfoto_url = $m['guid'];
                $x_url = explode("/", $mainfoto_url);
                $ext = explode(".", $x_url[7]);
                $from_img = './web/assets/images/source/' . $x_url[5] . '/' . $x_url[6] . '/' . $x_url[7];
                $to_img = './web/assets/images/users/' . (int)$m['ID'] . '.jpg';
                $to_thumb_img = './web/assets/images/users/t/' . (int)$m['ID'] . '.jpg';

                //$output->writeln($from_img);
                //$output->writeln($to_thumb_img);
                $fu->moveResizeImage($from_img, $to_img, $to_thumb_img);

                $i++;
                //if ($i==50) break;
                $output->writeln($i);
            }
        }

        $output->writeln('All user fotos moved!!');
    }
}