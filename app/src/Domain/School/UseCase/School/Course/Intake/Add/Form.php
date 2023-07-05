<?php

declare(strict_types=1);

namespace App\Domain\School\UseCase\School\Course\Intake\Add;

use App\ReadModel\School\CampusFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public const NAME = 'intake';

    public function __construct(private readonly CampusFetcher $campusFetcher)
    {
    }

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
            ->add('campusId', Type\ChoiceType::class, [
                'multiple' => false,
                'choice_loader' => new CallbackChoiceLoader(function () {
                    return array_flip($this->campusFetcher->getCampusesIdToName());
                }),
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
