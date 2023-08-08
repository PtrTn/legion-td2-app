<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\Unit;
use App\Repository\UnitsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class UnitFormType extends AbstractType
{
    public function __construct(
        private readonly UnitsRepository $unitRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $units = $this->unitRepository->getDefensiveUnits();

        $builder->add('units', ChoiceType::class, [
            'label' => 'Select what units you can build',
            'choices' => $units,
            'choice_value' => 'unitId',
            'choice_label' => fn(?Unit $unit): string => $unit ? $unit->name : 'Unknown',
            'choice_attr' => fn(?Unit $unit): array => $unit ? ['imagePath' => $unit->iconPath] : [],
            'multiple' => true,
        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'What should I build?',
            'attr' => ['class' => 'btn btn-primary btn-lg'],
        ]);
    }

}
