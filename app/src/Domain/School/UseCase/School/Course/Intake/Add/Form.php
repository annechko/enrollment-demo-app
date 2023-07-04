<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Intake\Add;

use App\Domain\School\Entity\Campus\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public const NAME = 'intake';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startDate', Type\DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
            ])
            ->add('endDate', Type\DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
            ])
            ->add('classSize')
            ->add('campus', EntityType::class, [
                'multiple' => false,
                'class' => Campus::class,
            ]);
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
