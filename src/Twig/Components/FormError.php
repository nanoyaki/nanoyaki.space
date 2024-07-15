<?php

namespace App\Twig\Components;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class FormError
{
    public ?FormErrorIterator $errors = null;
}
