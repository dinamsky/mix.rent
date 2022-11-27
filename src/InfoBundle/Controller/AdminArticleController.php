<?php

namespace InfoBundle\Controller;
use InfoBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminArticleController extends Controller
{
    /**
     * @Route("/adminArticles", name="adminArticles")
     */
    public function indexAction()
    {

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        return $this->render('InfoBundle::admin_articles_list.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/adminArticleEdit/{id}")
     */
    public function editAction($id = '', Request $request)
    {

        if($request->isMethod('GET')) {
            $article = $this->getDoctrine()
                ->getRepository(Article::class)
                ->find((int)$id);

            return $this->render('InfoBundle::admin_article_edit.html.twig', [
                'article' => $article,
            ]);
        }

        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $post = $request->request;
            $article = $this->getDoctrine()
                ->getRepository(Article::class)
                ->find($post->get('id'));
            $article->setHeader($post->get('header'));
            $article->setSlug($post->get('slug'));
            $article->setContent($post->get('content'));
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('adminArticles');
        }
    }


}
