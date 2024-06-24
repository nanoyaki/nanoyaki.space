<?php

namespace App\Form;

use App\Entity\ConfirmEmailData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('token', Type\IntegerType::class, [
                'label' => 'Code',
                'attr' => [
                    'placeholder' => '000000',
                    'pattern' => '\\d{6}'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConfirmEmailData::class,
            'csrf_protection' => true,
            'csrf_token_id'   => 'email_confirmation',
        ]);
    }
}
