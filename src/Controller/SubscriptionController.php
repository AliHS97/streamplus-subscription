<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Payment;
use App\Form\UserType;
use App\Form\AddressType;
use App\Form\PaymentType;
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
        $userId = $session->get('user_id');
        $user = $entityManager->getRepository(User::class)->find($userId) ?? new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($user);
                $entityManager->flush();

                $session->set('user_id', $user->getId());

                if ($user->getSubscriptionType() === 'Free') {
                    $payment = $user->getPayment();
                    if ($payment) {
                        $entityManager->remove($payment);
                        $entityManager->flush();
                    }
                }

                return $this->redirectToRoute('subscription-address-information');
            } catch (UniqueConstraintViolationException $e) {
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
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('subscription-user-information-form');
        }

        $user = $entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->redirectToRoute('subscription-user-information');
        }

        $address = $user->getAddress() ?? new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user);
            $entityManager->persist($address);
            $entityManager->flush();

            if ($user->getSubscriptionType() === 'Premium') {
                return $this->redirectToRoute('subscription-payment-information');
            }

            return $this->redirectToRoute('subscription-confirmation');
        }

        return $this->render('subscription/address_information.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subscription/payment/information', name: 'subscription-payment-information')]
    public function paymentForm(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('subscription-user-information-form');
        }

        $user = $entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->redirectToRoute('subscription-user-information-form');
        }

        $payment = $user->getPayment() ?? new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payment->setUser($user);
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('subscription-confirmation');
        }

        return $this->render('subscription/payment_information.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subscription/confirmation', name: 'subscription-confirmation')]
    public function confirmation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $request->getSession()->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('subscription_user_information_form');
        }

        $user = $entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->redirectToRoute('subscription_user_information_form');
        }

        return $this->render('subscription/confirmation.html.twig', [
            'user' => $user,
        ]);
    }
}

