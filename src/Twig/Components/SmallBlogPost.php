<?php

namespace App\Twig\Components;

use App\Entity\BlogPost;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class SmallBlogPost
{
    public BlogPost $post;
    public ?bool $hasJumpButton = false;
}
