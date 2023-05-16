<?php

namespace App\Form;

use App\Entity\Participaciones;
use App\Entity\Pool;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class ParticipacionesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('monto', NumberType::class, ['scale' => 2, 'label' => 'Monto', 'attr' => ['class' => 'form-control mb-2'], 'constraints' => [
                new LessThanOrEqual(['value' => $options['saldo'], 'message' => 'Este valor debe ser igual o menor a tu saldo (${{ compared_value }}).'])
            ]])
            ->add('pool', EntityType::class, ['attr' => ['class' => 'form-control mb-4'], 'class' => Pool::class, 'choice_label' => 'nombre', 'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u');
            }, 'label' => 'Pool'])
            ->add('save', SubmitType::class, ['label' => 'Entrar', 'attr' => ['class' => 'form-submit form-control mb-5 btn-success btn']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participaciones::class,
            'saldo' => 0,
        ]);
    }
}
