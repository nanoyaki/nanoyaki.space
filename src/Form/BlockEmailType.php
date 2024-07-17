<?php

namespace App\Form;

use App\Entity\BlockedEmailData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', Type\EmailType::class, [
                'label' => 'Email*',
                'attr' => [
                    'placeholder' => 'name@provider.com'
                ]
            ])
            ->add('confirm', Type\SubmitType::class, [ 'label' => 'Confirm' ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlockedEmailData::class,
            'csrf_protection' => true,
            'csrf_token_id'   => 'block_email',
        ]);
    }
}
