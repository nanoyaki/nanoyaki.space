<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

abstract class AbstractController extends SymfonyAbstractController
{
    public function getUser(): ?User
    {
        $user = parent::getUser();

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}