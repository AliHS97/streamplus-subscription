<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SubscriptionController extends AbstractController
{
    #[Route('/subscription/user/information', name: 'subscription-user-information')]
    public function userInformationForm(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $session->set('user_id', $user->getId());

            // return $this->redirectToRoute('subscription_step_two');
        }

        return $this->render('subscription/user_information.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

