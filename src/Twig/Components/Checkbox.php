<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Checkbox
{
    public ?string $name = null;
    public bool $checked = false;
}
