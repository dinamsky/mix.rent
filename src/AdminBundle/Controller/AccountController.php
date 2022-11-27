<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\UserInfo;
use UserBundle\Security\Password;
use UserBundle\Entity\User;
use AdminBundle\Entity\Admin;

class AccountController extends Controller
{

    /**
     * @Route("/adminUserBan", name="adminUserBan")
     */
    public function userBanAction(Request $request)
    {
        if($request->isMethod('GET')) {
            if ($this->get('session')->get('admin') === null) return $this->render('AdminBundle::admin_enter_form.html.twig');
            else {

                $allBanned = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findBy(['isBanned' => true]);
                return $this->render('AdminBundle::admin_user_ban.html.twig',['allBanned'=>$allBanned]);
            }
        } elseif ($request->isMethod('POST')) {
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($request->request->get('user_id'));
            $user->setIsBanned(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'notice',
                'Пользователь с ID '.$user->getId().' успешно заблокирован!'
            );
            return $this->redirectToRoute('adminUserBan');
        }
    }

    /**
     * @Route("/adminUserUnBan")
     */
    public function userUnBanAction(Request $request)
    {

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($request->request->get('user_id'));
        $user->setIsBanned(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash(
            'notice',
            'Пользователь с ID '.$user->getId().' успешно РАЗблокирован!'
        );
        return $this->redirectToRoute('adminUserBan');

    }
}
