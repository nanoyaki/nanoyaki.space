<?php

namespace App\Exception;

use App\Entity\User;
use Throwable;

class EmailConfirmationInvalidTokenException extends \Exception
{
    public function __construct(
        string $message = "The email confirmation token is invalid",
        int $code = 0,
        private readonly ?User $user = null,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            $user?->getEmailConfirmation()->isTokenExpired()
            ? "This token has expired"
            : $message,
            $code,
            $previous
        );
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}