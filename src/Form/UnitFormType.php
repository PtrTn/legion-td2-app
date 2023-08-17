<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\Fighter;
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

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $units = $this->unitRepository->getFightersBaseUnitsSortedByGoldCost();

        $builder->add('units', ChoiceType::class, [
            'choices' => $units,
            'choice_value' => 'unitId',
            'choice_label' => fn(?Fighter $unit): string => $unit ? $unit->name : 'Unknown',
            'choice_attr' => fn(?Fighter $unit): array => $unit ? ['imagePath' => $unit->iconPath] : [],
            'multiple' => true,
        ]);

        $builder->add('fighterAdvice', SubmitType::class, [
            'label' => 'Find best fighters',
            'attr' => ['class' => 'btn btn-primary btn-lg'],
        ]);

        $builder->add('mercenaryAdvice', SubmitType::class, [
            'label' => 'Find best mercenaries',
            'attr' => ['class' => 'btn btn-primary btn-lg'],
        ]);
    }

}
