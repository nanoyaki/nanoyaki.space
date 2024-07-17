<?php

namespace App\Exception;

use App\Entity\BlockedEmail;
use Throwable;

class EmailBlockedException extends \Exception
{
    public function __construct(
        string $message = "That email is blocked",
        private readonly ?BlockedEmail $blockedEmail = null,
        int $code = 0,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            $blockedEmail instanceof BlockedEmail
            ? "Email '{$blockedEmail->getEmail()}' is blocked"
            : $message,
            $code,
            $previous
        );
    }

    public function getBlockedEmail(): ?BlockedEmail
    {
        return $this->blockedEmail;
    }
}