<?php

namespace InfoBundle\Controller;

use AppBundle\Foto\FotoUtils;
use InfoBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminNewsController extends Controller
{



    /**
     * @Route("/adminNews", name="adminNews")
     */
    public function indexAction()
    {

        $city = $this->get('session')->get('city');

        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->findAll();

        return $this->render('InfoBundle:news:admin_news_list.html.twig', [
            'all_news' => $news,
            'city' => $city
        ]);
    }

    /**
     * @Route("/adminNewsEdit/{id}", name="adminNewsEdit")
     */
    public function editAction($id='', Request $request, FotoUtils $fu)
    {

        $city = $this->get('session')->get('city');

        if($request->isMethod('GET')) {
            $news = $this->getDoctrine()
                ->getRepository(News::class)
                ->find((int)$id);

            return $this->render('InfoBundle:news:admin_news_edit.html.twig', [
                'news' => $news,
                'city' => $city
            ]);
        }

        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $post = $request->request;
            $news = $this->getDoctrine()
                ->getRepository(News::class)
                ->find($post->get('id'));
            $news->setHeader($post->get('header'));
            $news->setSlug($post->get('slug'));




            if(isset($_FILES['thumbnail']['name']) and $_FILES['thumbnail']['name']!='') {
                $news->setThumbnail(basename(md5($_FILES['thumbnail']['name'])).'.jpg');
            }

            $news->setAnons($post->get('anons'));
            $news->setContent($post->get('content'));
            $em->persist($news);
            $em->flush();

            $fu->uploadImage('thumbnail', 'md5', '', $_SERVER['DOCUMENT_ROOT'].'/assets/images/articles' );
            return $this->redirectToRoute('adminNews');
        }
    }

    /**
     * @Route("/adminNewsAdd", name="adminNewsAdd")
     */
    public function addAction(Request $request, FotoUtils $fu)
    {

        $city = $this->get('session')->get('city');

        if($request->isMethod('GET')) {
            return $this->render('InfoBundle:news:admin_news_add.html.twig', [

                'city' => $city
            ]);
        }

        if($request->isMethod('POST')){

            $em = $this->getDoctrine()->getManager();
            $post = $request->request;
            $news = new News();
            $news->setHeader($post->get('header'));
            $news->setCountry('USA');
            $news->setSlug($fu->translit($post->get('header')));

            if(isset($_FILES['thumbnail']['name']) and $_FILES['thumbnail']['name']!='') {
                $news->setThumbnail(basename(md5($_FILES['thumbnail']['name'])).'.jpg');
            } else $news->setThumbnail('');

            $news->setAnons($post->get('anons'));
            $news->setContent($post->get('content'));
            $em->persist($news);
            $em->flush();

            $fu->uploadImage('thumbnail', 'md5', '', $_SERVER['DOCUMENT_ROOT'].'/assets/images/articles' );
            return $this->redirectToRoute('adminNews');
        }
    }

    /**
     * @Route("/adminNewsDelete", name="adminNewsDelete")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $request->request;
        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->find($post->get('id'));
        @unlink($_SERVER['DOCUMENT_ROOT'].'/assets/images/articles/'.$news->getThumbnail());
        $em->remove($news);
        $em->flush();

        return $this->redirectToRoute('adminNews');

    }

}
