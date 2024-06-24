<?php

namespace App\Entity;

use App\Enums\Role;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $password;

    #[ORM\OneToOne(targetEntity: EmailConfirmation::class)]
    #[ORM\JoinColumn(name: 'email_confirmation_id', referencedColumnName: 'id')]
    private readonly EmailConfirmation $emailConfirmation;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author')]
    private Collection $posts;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'user')]
    private Collection $images;

    /**
     * @param string $username
     * @param string $email
     * @param array<string> $roles
     */
    public function __construct(
        #[ORM\Column(length: 32)]
        private string                     $username,
        #[ORM\Column(length: 180)]
        private string                     $email,
        #[ORM\Column]
        private array                      $roles = []
    ) {
        $this->posts = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->emailConfirmation = new EmailConfirmation();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = Role::User->value;

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    private function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    private function renewPassword(UserPasswordHasherInterface $passwordHasher, string $password): static
    {
        $hashedPassword = $passwordHasher->hashPassword($this, $password);
        return $this->setPassword($hashedPassword);
    }

    /**
     * Only used to set a new **hashed** password
     *
     * @param string $hashedPassword
     * @return $this
     */
    public function upgradePassword(string $hashedPassword): static
    {
        return $this->setPassword($hashedPassword);
    }

    public function eraseCredentials(): void
    {

    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getEmailCofirmation(): EmailConfirmation
    {
        return $this->emailConfirmation;
    }

    /**
     * @param RegisterData $registerData
     * @param UserPasswordHasherInterface $passwordHasher
     * @param array<string> $roles
     * @return static
     */
    public static function register(RegisterData $registerData, UserPasswordHasherInterface $passwordHasher, array $roles = []): static
    {
        $user = new static(
            $registerData->getUsername(),
            $registerData->getEmail(),
            $roles
        );

        return $user->renewPassword($passwordHasher, $registerData->getPassword());
    }
}
