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

    #[ORM\OneToOne(targetEntity: EmailConfirmation::class, cascade: ['persist', 'remove'])]
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
     * @param Image $profilePicture
     * @param array<string> $roles
     */
    public function __construct(
        #[ORM\Column(length: 32)]
        private string                     $username,
        #[ORM\Column(length: 180)]
        private string                     $email,
        #[ORM\ManyToOne(targetEntity: Image::class, cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(name: 'profile_picture_id', referencedColumnName: 'id')]
        private Image                      $profilePicture,
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

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    private function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    private function renewPassword(UserPasswordHasherInterface $passwordHasher, string $password): self
    {
        $hashedPassword = $passwordHasher->hashPassword($this, $password);
        return $this->setPassword($hashedPassword);
    }

    /**
     * Only used to set a new **hashed** password
     *
     * @param string $hashedPassword
     * @return self
     */
    public function upgradePassword(string $hashedPassword): self
    {
        return $this->setPassword($hashedPassword);
    }

    public function eraseCredentials(): void
    {
        // used to erase temporary sensitive data on the entity
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

    public function getEmailConfirmation(): EmailConfirmation
    {
        return $this->emailConfirmation;
    }

    public function setProfilePicture(Image $image): self
    {
        $this->profilePicture = $image;

        return $this;
    }

    public function getProfilePicture(): Image
    {
        return $this->profilePicture;
    }

    /**
     * @param RegisterData $registerData
     * @param UserPasswordHasherInterface $passwordHasher
     * @param Image $profilePicture
     * @param array<Role> $roles
     * @return self
     */
    public static function register(
        RegisterData $registerData,
        UserPasswordHasherInterface $passwordHasher,
        Image $profilePicture,
        array $roles = []
    ): self
    {
        $roles = array_map(fn (Role $role) => $role->value, $roles);

        $user = new self(
            $registerData->getUsername(),
            $registerData->getEmail(),
            $profilePicture,
            $roles
        );

        return $user->renewPassword($passwordHasher, $registerData->getPassword());
    }
}
