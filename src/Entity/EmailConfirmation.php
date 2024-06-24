<?php

namespace App\Entity;

use App\Repository\EmailConfirmationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailConfirmationRepository::class)]
class EmailConfirmation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private bool $isConfirmed = false;

    #[ORM\Column]
    private DateTimeImmutable $validUntil;

    #[ORM\Column(length: 255)]
    private string $token;

    public function __construct()
    {
        $this->regenerate();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    public function regenerate(): static
    {
        $this->token = sprintf("%06d", mt_rand(1, 999999));
        $this->validUntil = new DateTimeImmutable('+15 minutes');

        return $this;
    }

    public function tryVerification(string $token): static
    {
        $this->isConfirmed = $token === $this->token && !$this->needsRegeneration();

        return $this;
    }

    private function needsRegeneration(): bool
    {
        return $this->validUntil->getTimestamp() < (new DateTimeImmutable())->getTimestamp();
    }
}
