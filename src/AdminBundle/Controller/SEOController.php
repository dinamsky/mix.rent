<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Seo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\UserInfo;
use UserBundle\Security\Password;
use UserBundle\Entity\User;
use AdminBundle\Entity\Admin;

class SEOController extends Controller
{
    /**
     * @Route("/adminSEOPatterns", name="adminSEOPatterns")
     */
    public function SEOPatternsAction(Request $request)
    {
        $path = '../app/Resources/views/seo_templates/';
        if($request->isMethod('GET')) {
            if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_enter_form.html.twig');
            else {
                return $this->render('AdminBundle::admin_seo_patterns.html.twig', [
                    'p_title' => @file_get_contents($path.'p_title.html.twig'),
                    'p_description' => @file_get_contents($path.'p_description.html.twig'),
                    'c_title' => @file_get_contents($path.'c_title.html.twig'),
                    'c_description' => @file_get_contents($path.'c_description.html.twig'),
                    'c_h1' => @file_get_contents($path.'c_h1.html.twig'),
                    'p_h1' => @file_get_contents($path.'p_h1.html.twig'),
                    'p_alt' => @file_get_contents($path.'p_alt.html.twig'),
                    'c_text' => @file_get_contents($path.'c_text.html.twig'),
                ]);
            }
        };
        if($request->isMethod('POST')) {
            $post = $request->request;
            file_put_contents($path.'p_title.html.twig', $post->get('p_title'));
            file_put_contents($path.'p_description.html.twig', $post->get('p_description'));
            file_put_contents($path.'c_title.html.twig', $post->get('c_title'));
            file_put_contents($path.'c_description.html.twig', $post->get('c_description'));
            file_put_contents($path.'c_h1.html.twig', $post->get('c_h1'));
            file_put_contents($path.'p_h1.html.twig', $post->get('p_h1'));
            file_put_contents($path.'p_alt.html.twig', $post->get('p_alt'));
            file_put_contents($path.'c_text.html.twig', $post->get('c_text'));
            $this->addFlash(
                'notice',
                'Шаблоны успешно сохранены!'
            );
            return $this->redirectToRoute('adminSEOPatterns');
        }
    }

    /**
     * @Route("/adminSEOPages", name="adminSEOPages")
     */
    public function SEOPagesAction(Request $request)
    {
        if($request->isMethod('GET')) {
            if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_enter_form.html.twig');
            else {
                $pages = $this->getDoctrine()
                    ->getRepository(Seo::class)
                    ->findAll();
                return $this->render('AdminBundle::admin_seo_pages.html.twig', [
                    'pages' => $pages
                ]);
            }
        };
        if($request->isMethod('POST')) {
            $post = $request->request;

            return $this->redirectToRoute('adminSEOPages');
        }
    }


    /**
     * @Route("/adminAddSeoPage", name="adminAddSeoPage")
     */
    public function addSeoPageAction(Request $request)
    {
        $path = '../app/Resources/views/seo_templates/';
        if($request->isMethod('GET')) {
            if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_enter_form.html.twig');
            else {
                return $this->render('AdminBundle::admin_seo_add_page.html.twig');
            }
        };
        if($request->isMethod('POST')) {
            $post = $request->request;
            $seo = new Seo();
            $seo->setUrl($post->get('url'));
            $seo->setTitle($post->get('title'));
            $seo->setH1($post->get('h1'));
            $seo->setDescription($post->get('description'));
            $seo->setSeoText($post->get('seo_text'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($seo);
            $em->flush();
            return $this->redirectToRoute('adminSEOPages');
        }
    }

    /**
     * @Route("/adminSEOPage/{id}", name="adminSeoPage")
     */
    public function seoPageAction($id = '', Request $request)
    {
        $path = '../app/Resources/views/seo_templates/';
        if($request->isMethod('GET')) {
            if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_enter_form.html.twig');
            else {
                $seo = $this->getDoctrine()
                    ->getRepository(Seo::class)
                    ->find((int)$id);
                return $this->render('AdminBundle::admin_seo_edit_page.html.twig',['seo'=>$seo]);
            }
        };
        if($request->isMethod('POST')) {
            $post = $request->request;
            $seo = $this->getDoctrine()
                ->getRepository(Seo::class)
                ->find($post->get('id'));
            $em = $this->getDoctrine()->getManager();
            if($post->has('update')){
                $seo->setUrl($post->get('url'));
                $seo->setTitle($post->get('title'));
                $seo->setH1($post->get('h1'));
                $seo->setDescription($post->get('description'));
                $seo->setSeoText($post->get('seo_text'));

                $em->persist($seo);
                $em->flush();
                $this->addFlash(
                    'notice',
                    'Данные успешно сохранены!'
                );
                return $this->redirect('/adminSEOPage/'.$post->get('id'));
            }
            if($post->has('delete')){
                $em->remove($seo);
                $em->flush();
                return $this->redirect('/adminSEOPages');
            }

        }
    }
}
