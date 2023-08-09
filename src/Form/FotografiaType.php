<?php

namespace App\Form;

use App\Entity\Empleado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FotografiaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fotografia_empleado', FileType::class, [
                'label' => 'Ingresa tu fotografía',
                'mapped' => false,
                'attr' => array('accept' => 'image/jpeg,image/png')])
            ->add('Almacenar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Empleado::class,
        ]);
    }
}
