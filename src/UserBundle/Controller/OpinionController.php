<?php

namespace UserBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Opinion;
use UserBundle\Entity\User;
use AppBundle\Entity\Card;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OpinionController extends Controller
{
    /**
     * @Route("/userAddOpinion")
     */
    public function addOpinionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($request->request->get('user_id'));

        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($request->request->get('card_id'));

        $opinion = new Opinion();
        $opinion->setCard($card);
        $opinion->setUser($user);
        $opinion->setStars($request->request->get('stars'));
        $opinion->setContent($request->request->get('content'));

        $em->persist($opinion);
        $em->flush();

        $this->addFlash(
            'notice',
            'Ваш отзыв успешно добавлен!<br>К тексту отзыва могут быть применены правила сайта в процессе модерации'
        );

        return $this->redirect($request->request->get('return'));
    }

}
