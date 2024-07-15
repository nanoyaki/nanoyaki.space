<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterData
{
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.'
    )]
    private string $email;

    #[Assert\Length(
        min: 4,
        max: 32,
        minMessage: 'Username must be at least 4 characters long.',
        maxMessage: 'Username can\'t be longer than 32 characters'
    )]
    #[Assert\Regex(
        pattern: '/[a-zA-Z\d_\-\.]+/',
        message: 'Usernames are limited to characters from a-Z'
        . ', numbers, and the 3 special characters "_", "-", and "."'
    )]
    private string $username;

    #[Assert\Regex(
        pattern: '/[a-zA-Z\d\!@#$%^&*]+/',
        message: 'Passwords are limited to characters from a-Z (capitalized and non-capitalized)'
        . ' as well as the special characters !@#$%^&*'
    )]
    #[Assert\Length(
        min: 8,
        max: 64,
        minMessage: 'Passwords must be at least 8 characters long'
        . ' and must not be longer than 64 characters'
    )]
    private string $password;

    #[Assert\EqualTo(
        propertyPath: 'password',
        message: "The passwords are not the same"
    )]
    private string $passwordConfirmation;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordConfirmation(): string
    {
        return $this->passwordConfirmation;
    }

    public function setPasswordConfirmation(string $passwordConfirmation): static
    {
        $this->passwordConfirmation = $passwordConfirmation;

        return $this;
    }
}
