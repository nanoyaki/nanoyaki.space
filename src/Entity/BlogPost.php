<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogPostRepository::class)]
class BlogPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTimeImmutable $created;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified = null;

    public function __construct(
        #[ORM\Column(length: 255)]
        private string $title,
        #[ORM\Column(length: 65535)]
        private string $content,
        #[ORM\Column]
        private ?bool $isPinned = false,
    ) 
    {
        $this->created = new DateTimeImmutable('now');
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        $this->modified = new DateTime('now');

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        $this->modified = new DateTime('now');

        return $this;
    }

    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;    
    }
    
    public function togglePin(): static
    {
        $this->isPinned = !$this->isPinned;

        return $this;
    }

    public function isPinned(): bool
    {
        return $this->isPinned;
    }
}
