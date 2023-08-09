<?php

namespace App\Form;

use App\Entity\Empleado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('RFC')
            ->add('nombre_empleado')
            ->add('apellido_paterno')
            ->add('apellido_materno')
            
            ->add('genero', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => false,
    
                'choices' => array(
                    'Masculino' => 'M',
                    'Femenino' => 'F'
                ),
            ))  
            ->add('mail')
            ->add('cargo')
            ->add('departamento')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Empleado::class,
        ]);
    }
}
