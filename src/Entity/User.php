<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^\d{10,}$/', message: 'phoneNumber number must be at least 10 digits')]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['Free', 'Premium'], message: 'Choose a valid subscription type')]
    private ?string $subscriptionType = 'Free';

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Address::class, cascade: ['persist', 'remove'])]
    private ?Address $address = null;


    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getphoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setphoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getSubscriptionType(): ?string
    {
        return $this->subscriptionType;
    }

    public function setSubscriptionType(string $subscriptionType): self
    {
        $this->subscriptionType = $subscriptionType;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }
}
