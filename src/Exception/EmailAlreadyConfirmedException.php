<?php

namespace App\Exception;

use App\Entity\User;
use Throwable;

class EmailAlreadyConfirmedException extends \Exception
{
    public function __construct(
        string $message = "This email has already been confirmed",
        int $code = 0,
        private readonly ?User $user = null,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            $user instanceof User
            ? "{$user->getUsername()}'s email has already been confirmed"
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