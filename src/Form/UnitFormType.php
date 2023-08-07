<?php

declare(strict_types=1);

namespace App\Form;

use App\Repositories\UnitRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class UnitFormType extends AbstractType
{
    public function __construct(
        private readonly UnitRepository $unitRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $units = $this->unitRepository->getDefensiveUnits();
        dump($units);

        $builder
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task']);
    }

}
