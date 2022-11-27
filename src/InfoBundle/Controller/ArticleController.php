<?php

namespace InfoBundle\Controller;
use InfoBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Settings;

class ArticleController extends Controller
{
    /**
     * @Route("/article/{slug}")
     */
    public function indexAction($slug)
    {

        $city = $this->get('session')->get('city');

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['slug' => $slug]);

        if(!$article) return new Response('No such article',404);

        return $this->render('InfoBundle::article.html.twig', [
            'article' => $article,
            'city' => $city,
            'lang' => $_SERVER['LANG']
        ]);
    }

    /**
     * @Route("/termsofuse")
     */
    public function termsofuseAction()
    {
        $city = $this->get('session')->get('city');
        return $this->render('InfoBundle::termsofuse.html.twig', [
            'city' => $city,
            'lang' => $_SERVER['LANG']
        ]);
    }
    
    /**
     * @Route("/privacypolicy")
     */
    public function privacypolicyAction()
    {
        $city = $this->get('session')->get('city');
        return $this->render('InfoBundle::privacypolicy.html.twig', [
            'city' => $city,
            'lang' => $_SERVER['LANG']
        ]);
    }
    
    /**
     * @Route("/contacts")
     */
    public function contactsAction()
    {
        $city = $this->get('session')->get('city');
        return $this->render('InfoBundle::contacts.html.twig', [
            'city' => $city,
            'lang' => $_SERVER['LANG']
        ]);
    }

    /**
     * @Route("/howdoesitwork")
     */
    public function howWorkAction()
    {
        $city = $this->get('session')->get('city');
        $slider = [
            '/assets/images/interface/howto/howtoslidet01.jpg',
            '/assets/images/interface/howto/howtoslidet02.jpg',
            '/assets/images/interface/howto/howtoslidet03.jpg',
            '/assets/images/interface/howto/howtoslidet04.jpg',
            '/assets/images/interface/howto/howtoslidet05.jpg',
        ];

        $st = $this->getDoctrine()
                ->getRepository(Settings::class)
                ->findOneBy(['sKey'=>'howitwork']);
        $content = $st->getSValue();

        $content = explode("***<br />",strip_tags($content,'<strong>,<br>'));
        $content[0] = explode("---<br />",$content[0]);
        $content[1] = explode("---<br />",$content[1]);


        return $this->render('InfoBundle::howdoesitwork.html.twig', [
            'city' => $city,
            'lang' => $_SERVER['LANG'],
            'slider' => $slider,
            'content' => $content,
        ]);
    }

    /**
     * @Route("/about")
     */
    public function aboutAction()
    {
        $city = $this->get('session')->get('city');
        return $this->render('InfoBundle::about.html.twig', [
            'city' => $city,
            'lang' => $_SERVER['LANG']
        ]);
    }
}
