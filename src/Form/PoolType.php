<?php

namespace App\Form;

use App\Entity\Pool;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, ['label' => 'Nombre del Pool', 'attr' => ['class' => 'form-control mb-2']])
            ->add('descripcion', TextType::class, ['label' => 'Descripción del Pool', 'attr' => ['class' => 'form-control mb-2']])
            ->add('inversionTotal', NumberType::class, ['scale' => 2, 'label' => 'Inversion Total Necesaria', 'attr' => ['class' => 'form-control mb-2']])
            ->add('icon', TextType::class, ['label' => 'Url del Icono', 'attr' => ['class' => 'form-control mb-2']])
            ->add('fechaInicio', DateType::class, ['label' => 'Fecha de Inicio', 'widget' => 'single_text', 'html5' => true, 'attr' => ['class' => 'form-control mb-2']])
            ->add('inversionMinima', NumberType::class, ['scale' => 2, 'label' => 'Inversión mínima por Usuario', 'attr' => ['class' => 'form-control mb-4']])
            ->add('save', SubmitType::class, ['label' => 'Crear', 'attr' => ['class' => 'form-submit form-control mb-5']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pool::class,
        ]);
    }
}
