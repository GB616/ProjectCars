<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model')
            ->add('year')
            ->add('power')
            ->add('torque')
            ->add('description')
            ->add('engineDescription')
            ->add('externalDescription')
            ->add('internalDescription')
            //->add('creationDate')
            ->add('capacity')
            //->add('slug')
            ->add('brand')
            ->add('drivetrain')
            ->add('fuel')
            //->add('owner')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
