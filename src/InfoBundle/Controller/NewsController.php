<?php

namespace InfoBundle\Controller;
use InfoBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    /**
     * @Route("/news/{slug}")
     */
    public function indexAction($slug)
    {

        $city = $this->get('session')->get('city');

        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->findOneBy(['slug' => $slug]);

        if(!$news) return new Response('No such article',404);

        return $this->render('InfoBundle:news:one_news.html.twig', [
            'news' => $news,
            'city' => $city,
            'lang' => $_SERVER['LANG'],

        ]);
    }

    /**
     * @Route("/news")
     */
    public function contactsAction()
    {
        $city = $this->get('session')->get('city');

        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->findBy(['country'=>'USA'],['dateCreate' => 'DESC']);

        return $this->render('InfoBundle:news:all_news.html.twig', [
            'all_news' => $news,
            'city' => $city,
            'lang' => $_SERVER['LANG'],

        ]);

        //
    }
}
