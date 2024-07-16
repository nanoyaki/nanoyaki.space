<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\UserExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getUser', [UserExtensionRuntime::class, 'getUser']),
        ];
    }
}
