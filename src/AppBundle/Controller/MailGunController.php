<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Settings;
use Mailgun\Mailgun;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Card;
use UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface as em;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


class MailGunController extends Controller
{

    private $em;

    public function __construct(em $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/mc_test", name="mc_test")
     */
    public function mc_testAction(Request $request, em $em )
    {


        $query = $em->createQuery('SELECT c,u,f FROM AppBundle:Card c LEFT JOIN c.user u WITH u.id = c.userId LEFT JOIN c.fotos f WITH f.cardId = c.id AND f.isMain =1 WHERE c.cityId > 1257 GROUP BY c.userId');
        $query->setMaxResults(7);
        $result = $query->getResult();
        foreach($result as $r)
        {
            echo $this->renderView(
                            'email/admin_registration_en.html.twig',
                            array(
                                'header' => $r->getUser()->getHeader(),
                                'password' => $r->getUser()->getTempPassword(),
                                'email' => $r->getUser()->getEmail(),
                                'card' => $r,
                                'main_foto' => 'http://mix.rent/assets/images/cards/'.$r->getFotos()[0]->getFolder().'/t/'.$r->getFotos()[0]->getId().'.jpg',
                                'c_price' => 0,
                                'c_ed' => '$'
                            )
                        );
        }


//        # Instantiate the client.
//        $mg = Mailgun::create('key-5f23100bafffe48a6225c2bf4792e85f');
//        $domain = "mail.mix.rent";
//
//        # Make the call to the client.
//
//
//        $mg->messages()->send($domain, [
//            'from'    => 'mail@mix.rent',
//            'to'      => 'wqs-info@mail.ru',
//            'subject' => 'Hello',
//            'text'    => 'Testing some Mailgun awesomness!',
//            'html'    => 'Testing some <b>Mailgun</b> awesomness!'
//        ]);


        //return new RedirectResponse($url['links'][1]['href']);
        return new Response('ok2');
    }


    /**
     * @Route("/mctest2", name="mc_test")
     */
    public function mctest2Action(Request $request )
    {
        $_t = $this->get('translator');
        $this->sendMG('bazzalth@yandex.ru',$_t->trans('Регистрация на сайте multiprokat.com'),$this->renderView(
                        $_SERVER['LANG'] == 'ru' ? 'email/registration.html.twig' : 'email/registration_'.$_SERVER['LANG'].'.html.twig',
                    array(
                        'header' => '11111111',
                        'code' => '2222222'
                    )
                    ));

        return new Response('ok2');
    }



    public function sendMG($to,$subject,$message)
    {

        $mg = Mailgun::create('key-5f23100bafffe48a6225c2bf4792e85f');
        $domain = "mail.mix.rent";

        $mg->messages()->send($domain, [
            'from'    => 'MixRent <mail@mix.rent>',
            'to'      => $to,
            'bcc'     => 'mail@mix.rent',
            'subject' => $subject,
            'html'    => $message
        ]);

        return new Response();
    }

    public function sendForAll()
    {
        //$em = $this->getDoctrine()->getManager();

        $st = $this->em
                    ->getRepository(Settings::class)
                    ->findOneBy(['sKey'=>'mailsend']);

        if($st->getSValue() == 'ready') {

            $mg = Mailgun::create('key-5f23100bafffe48a6225c2bf4792e85f');
            $domain = "mail.mix.rent";

            $query = $this->em->createQuery('SELECT c,u,f FROM AppBundle:Card c LEFT JOIN c.user u WITH u.id = c.userId LEFT JOIN c.fotos f WITH f.cardId = c.id AND f.isMain =1 WHERE c.cityId > 1257 GROUP BY c.userId');
            //$query->setMaxResults(7);
            $result = $query->getResult();
            foreach ($result as $r) {

                $message = $this->renderView(
                    'email/admin_registration_en.html.twig',
                    array(
                        'header' => $r->getUser()->getHeader(),
                        'password' => $r->getUser()->getTempPassword(),
                        'email' => $r->getUser()->getEmail(),
                        'card' => $r,
                        'main_foto' => 'http://mix.rent/assets/images/cards/' . $r->getFotos()[0]->getFolder() . '/t/' . $r->getFotos()[0]->getId() . '.jpg',
                        'c_price' => 0,
                        'c_ed' => '$'
                    )
                );

                $to = $r->getUser()->getEmail();
                //$to = 'wqs-info@mail.ru';

                $subject = '#'.$r->getId().' Your transport in MixRent';

                $mg->messages()->send($domain, [
                    'from' => 'MixRent <mail@mix.rent>',
                    'to' => $to,
                    'subject' => $subject,
                    'html' => $message
                ]);
            }

                $st = $this->em
                    ->getRepository(Settings::class)
                    ->findOneBy(['sKey'=>'mailsend']);
                $st->setSValue('done');
                $this->em->persist($st);
                $this->em->flush();

        }
        //return new RedirectResponse($url['links'][1]['href']);
        return new Response('ok2');
    }
}
