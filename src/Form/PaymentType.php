<?php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cardNumber', TextType::class, [
                'label' => 'Card Number',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter card number'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 16, 'max' => 16]),
                    new Assert\Luhn(),
                ],
            ])
            ->add('expirationDate', TextType::class, [
                'label' => 'Expiration Date (MM/YY)',
                'attr' => ['class' => 'form-control', 'placeholder' => 'MM/YY'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex(['pattern' => '/^(0[1-9]|1[0-2])\/\d{2}$/', 'message' => 'Enter a valid MM/YY format']),
                    new Assert\Callback([$this, 'validateExpirationDate']),
                ],
            ])
            ->add('cvv', TextType::class, [
                'label' => 'CVV',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter CVV'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3, 'max' => 3]),
                    new Assert\Regex(['pattern' => '/^\d{3}$/', 'message' => 'Enter a valid 3-digit CVV']),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Submit Payment',
                'attr' => ['class' => 'btn btn-primary w-100 mt-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }

    public function validateExpirationDate($value, \Symfony\Component\Validator\Context\ExecutionContextInterface $context)
    {
        if (!$value) {
            return;
        }

        [$month, $year] = explode('/', $value);
        $month = (int) $month;
        $year = (int) ('20' . $year);

        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');

        if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
            $context->buildViolation('The expiration date must be in the future.')
                ->addViolation();
        }
    }
}
