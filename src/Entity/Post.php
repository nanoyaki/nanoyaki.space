<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private DateTimeImmutable $created;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $modified = null;

    /**
     * @var Collection<int, Image> $images
     */
    #[ORM\JoinTable(name: 'post_images')]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'image_id', referencedColumnName: 'id', unique: true)]
    #[ORM\ManyToMany(targetEntity: Image::class)]
    private Collection $images;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
        #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        private readonly User $author,
        #[ORM\Column(length: 255)]
        private string        $title,
        #[ORM\Column(length: 65535)]
        private string        $content,
        #[ORM\Column(length: 2048, nullable: true)]
        private ?string       $digest = null,
        #[ORM\Column]
        private bool          $isPinned = false
    ) 
    {
        $this->created = new DateTimeImmutable();
        $this->images = new ArrayCollection();
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        $this->modified = new DateTimeImmutable();

        return $this;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        $this->modified = new DateTimeImmutable();

        return $this;
    }

    public function setDigest(string $digest): static
    {
        $this->digest = $digest;
        $this->modified = new DateTimeImmutable();

        return $this;
    }

    public function removeDigest(): static
    {
        $this->digest = null;
        $this->modified = new DateTimeImmutable();

        return $this;
    }

    public function clearImages(): static
    {
        $this->images->clear();

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->isEmpty() || !$this->images->contains($image)) {
            return $this;
        }

        $this->images->removeElement($image);

        return $this;
    }

    public function addImage(Image $image): static
    {
        $this->images->add($image);

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function togglePin(): static
    {
        $this->isPinned = !$this->isPinned;

        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDigest(): ?string
    {
        return $this->digest;
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    public function getModified(): ?DateTimeInterface
    {
        return $this->modified;
    }

    public function isPinned(): bool
    {
        return $this->isPinned;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }
}
