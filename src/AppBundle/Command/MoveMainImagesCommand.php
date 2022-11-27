<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class MoveMainImagesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:move-main-images');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('start');

        $dirs = array(
            './web/assets/images/cards/2014/12',
            './web/assets/images/cards/2015/01',
            './web/assets/images/cards/2015/02',
            './web/assets/images/cards/2017/02',
            './web/assets/images/cards/2017/03',
            './web/assets/images/cards/2017/04',
            './web/assets/images/cards/2017/05',
            './web/assets/images/cards/2017/06',
            './web/assets/images/cards/2017/07',
            './web/assets/images/cards/2017/08',
            './web/assets/images/cards/2017/09',
            './web/assets/images/cards/2014/12/t',
            './web/assets/images/cards/2015/01/t',
            './web/assets/images/cards/2015/02/t',
            './web/assets/images/cards/2017/02/t',
            './web/assets/images/cards/2017/03/t',
            './web/assets/images/cards/2017/04/t',
            './web/assets/images/cards/2017/05/t',
            './web/assets/images/cards/2017/06/t',
            './web/assets/images/cards/2017/07/t',
            './web/assets/images/cards/2017/08/t',
            './web/assets/images/cards/2017/09/t',
        );
        foreach($dirs as $dir) {
            @mkdir($dir, 0755, true);
        }

        $output->writeln('dirs created!');

        $fu = $this->getContainer()->get('AppBundle\Foto\FotoUtils');

        //$output->writeln(scandir('./web'));

        $file = file_get_contents('./web/dump.json');
        $json = json_decode($file,true);
        $em = $this->getContainer()->get('doctrine')->getManager();
        $new_conn = $em->getConnection();

        $users = $new_conn->fetchAll('SELECT ID FROM user');
        foreach($users as $user){
            $ids[] = $user['ID'];
        }

        $output->writeln(count($ids));

        $i=0;

        foreach($json['posts'] as $post_id => $post) if (in_array($post['post_author'],$ids) and isset($json['meta'][$post_id]) and $post_id > 33143) {

            $meta = $json['meta'][$post_id];

            $mainfoto_url = $json['mainfotos'][$meta['_thumbnail_id']];
            $x_url = explode("/",$mainfoto_url);
            $ext = explode(".",$x_url[7]);
            $from_img = './web/assets/images/source/'.$x_url[5].'/'.$x_url[6].'/'.$x_url[7];
            $to_img = './web/assets/images/cards/'.$x_url[5].'/'.$x_url[6].'/'.(int)$meta['_thumbnail_id'].'.jpg';
            $to_thumb_img = './web/assets/images/cards/'.$x_url[5].'/'.$x_url[6].'/t/'.(int)$meta['_thumbnail_id'].'.jpg';

            //$output->writeln($from_img);
            //$output->writeln($to_thumb_img);
            $fu->moveResizeImage($from_img, $to_img, $to_thumb_img);


            $i++;
            //if ($i==50) break;
            $output->writeln($i);
        }

        $output->writeln('All fotos moved!!');
    }
}