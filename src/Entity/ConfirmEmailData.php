<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ConfirmEmailData
{
    private const TOKEN_LENGTH_MESSAGE = 'The confirmation code should be exactly 6 characters.';

    #[Assert\Length(
        min: 6,
        max: 6,
        minMessage: self::TOKEN_LENGTH_MESSAGE,
        maxMessage: self::TOKEN_LENGTH_MESSAGE
    )]
    #[Assert\Regex(pattern: '/\d{6}/')]
    protected string $token;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }
}