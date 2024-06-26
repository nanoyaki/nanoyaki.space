<?php

namespace App\Twig\Components;

use App\Entity\Post;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class SmallBlogPost
{
    public Post $post;
    public ?bool $hasJumpButton = false;
}
