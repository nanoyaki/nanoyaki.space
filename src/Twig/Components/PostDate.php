<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class PostDate
{
    public \DateTimeImmutable $created;
    public ?\DateTimeInterface $modified = null;
}
