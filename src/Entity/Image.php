<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private DateTimeImmutable $uploadDate;

    public function __construct(
        #[ORM\Column(length: 255)]
        private string $path,
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'images')]
        #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        private readonly ?User $user = null,
        #[ORM\Column(length: 255)]
        private string $description = "",
        #[ORM\Column]
        private bool $isUrl = false
    ) {
        $this->uploadDate = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUploadDate(): DateTimeImmutable
    {
        return $this->uploadDate;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setIsUrl(bool $isUrl): static
    {
        $this->isUrl = $isUrl;

        return $this;
    }

    public function isUrl(): bool
    {
        return $this->isUrl;
    }
}