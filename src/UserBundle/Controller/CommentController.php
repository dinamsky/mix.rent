<?php

namespace UserBundle\Controller;

use AppBundle\Entity\Comment;
use UserBundle\Entity\User;
use AppBundle\Entity\Card;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CommentController extends Controller
{
    /**
     * @Route("/userAddComment")
     */
    public function addCommentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($request->request->get('user_id'));

        $card = $this->getDoctrine()
            ->getRepository(Card::class)
            ->find($request->request->get('card_id'));

        $comment = new Comment();
        $comment->setCard($card);
        $comment->setUser($user);
        $comment->setContent($request->request->get('comment'));

        $em->persist($comment);
        $em->flush();

        $this->addFlash(
            'notice',
            'Ваш комментарий успешно добавлен!<br>К тексту комментария могут быть применены правила сайта в процессе модерации'
        );

        return $this->redirect($request->request->get('return'));
    }

}
