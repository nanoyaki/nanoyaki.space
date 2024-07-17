<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class BlockedEmailData
{
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
