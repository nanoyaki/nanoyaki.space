<?php

namespace App\Exception;

use App\Entity\User;
use Throwable;

class UserExistsException extends \Exception
{
    public function __construct(
        string $message = "That user already exists",
        int $code = 0,
        private readonly ?User $user = null,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            $user instanceof User
            ? "The user with the email {$user->getEmail()} and the username {$user->getUsername()} already exists"
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