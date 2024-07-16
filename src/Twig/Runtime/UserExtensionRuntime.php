<?php

namespace App\Twig\Runtime;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\RuntimeExtensionInterface;

readonly class UserExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private Security $firewall
    ) {}

    public function getUser(): ?User
    {
        $user = $this->firewall->getUser();
        return $user instanceof User ? $user : null;
    }
}
