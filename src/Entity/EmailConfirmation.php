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
        $this->regenerateToken();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): string
    {
        if ($this->isTokenExpired()) {
            $this->regenerateToken();
        }

        return $this->token;
    }

    public function isConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    public function regenerateToken(): static
    {
        $this->token = sprintf("%06d", mt_rand(1, 999999));
        $this->validUntil = new DateTimeImmutable('+15 minutes');

        return $this;
    }

    /**
     * Returns a bool to indicate verification success
     *
     * @param string $token
     * @return bool
     */
    public function tryVerification(string $token): bool
    {
        $this->isConfirmed = $token === $this->token && !$this->isTokenExpired();

        return $this->isConfirmed;
    }

    private function isTokenExpired(): bool
    {
        return $this->validUntil->getTimestamp() < (new DateTimeImmutable())->getTimestamp() && !$this->isConfirmed;
    }
}
