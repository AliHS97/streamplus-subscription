<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\UserType;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormError;

class SubscriptionController extends AbstractController
{
    #[Route('/subscription/user/information', name: 'subscription-user-information')]
    public function userInformationForm(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Get user ID from session
        $userId = $session->get('user_id');
        // Fetch existing user from database or create a new one
        $user = $userId ? $entityManager->getRepository(User::class)->find($userId) : new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($user);
                $entityManager->flush();

                // Store user ID in session
                $session->set('id', $user->getId());

                return $this->redirectToRoute('subscription-address-information');
            } catch (UniqueConstraintViolationException $e) {
                // Handle duplicate email error dynamically
                $form->get('email')->addError(new FormError('This email is already in use.'));
            }
        }

        return $this->render('subscription/user_information.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subscription/address/infromation', name: 'subscription-address-information')]
    public function addressForm(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Get the user from the session
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('subscription-user-information-form');
        }

        $user = $entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->redirectToRoute('subscription-user-information-form');
        }

        // Check if user already has an address
        $address = $user->getAddress() ?? new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user);
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('subscription-address-information'); // Next step
        }

        return $this->render('subscription/address_information.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}

