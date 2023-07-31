<?php

declare(strict_types=1);

namespace App\ReadModel\Student\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class, [
                'required' => false,
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'filter';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CourseFilter::class,
                'method' => 'GET',
                'csrf_protection' => false,
            ]
        );
    }
}
