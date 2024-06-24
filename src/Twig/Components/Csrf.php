<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Csrf
{
    public string $name = 'csrf';
    public string $fieldName = '_token';
}
