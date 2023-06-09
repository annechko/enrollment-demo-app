<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Campus\Add;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public const NAME = 'campus';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Command::class,
                'csrf_protection' => false,
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return self::NAME;
    }
}
