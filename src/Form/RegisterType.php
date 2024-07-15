<?php

namespace App\Form;

use App\Entity\RegisterData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', Type\EmailType::class, [ 'label' => 'Email*' ])
            ->add('username', Type\TextType::class, [ 'label' => 'Username*' ])
            ->add('password', Type\PasswordType::class, [ 'label' => 'Password*' ])
            ->add('passwordConfirmation', Type\PasswordType::class, [ 'label' => 'Confirm password*' ])
            ->add('register', Type\SubmitType::class, [ 'label' => 'Register' ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegisterData::class,
            'csrf_protection' => true,
            'csrf_token_id'   => 'register',
        ]);
    }
}
