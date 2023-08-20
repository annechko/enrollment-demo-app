<?php

declare(strict_types=1);

namespace App\ReadModel\Admin\School\Filter;

use App\Core\School\Entity\School\School;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Id',
                ],
            ])
            ->add('name', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Name',
                ],
            ])
            ->add('status', Type\ChoiceType::class, [
                'choices' => [
                    'Pending' => School::STATUS_NEW,
                    'Active' => School::STATUS_ACTIVE,
                ],
                'required' => false,
                'placeholder' => 'All statuses',
                'attr' => ['onchange' => 'this.form.submit()'], // todo move to view
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Filter::class,
                'method' => 'GET',
                'csrf_protection' => false,
            ]
        );
    }
}
