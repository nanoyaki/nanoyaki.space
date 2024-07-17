<?php

namespace App\Service;

use App\Entity\BlockedEmail;
use App\Exception\EmailBlockedException;
use App\Repository\BlockedEmailRepository;

readonly class BlocklistService
{
    public function __construct(
        private BlockedEmailRepository $blockedEmailRepository
    ) {}

    /**
     * @throws EmailBlockedException
     */
    public function block(string $email): void
    {
        $existingBlockedEmail = $this->blockedEmailRepository->findOneByEmail($email);
        if ($existingBlockedEmail instanceof BlockedEmail) {
            throw new EmailBlockedException(blockedEmail: $existingBlockedEmail);
        }

        $blockedEmail = new BlockedEmail($email);
        $this->blockedEmailRepository->save($blockedEmail);
    }
}