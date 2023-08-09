<?php

namespace App\Form;

use App\Entity\Materia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MateriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clave_materia')
            ->add('nombre_materia')
            ->add('objetivo_materia')
            ->add('caracterizacion_materia')
            ->add('intencion_didactica')
            ->add('horas_teoricas', IntegerType::class,[
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                  ]

            ])
            ->add('horas_practicas', IntegerType::class,[
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                  ]

            ])
            ->add('creditos', IntegerType::class,[
                'attr' => [
                    'min' => 4,
                    'max' => 8,
                  ]

            ])
            ->add('plan_academico')
            ->add('semestre', ChoiceType::class, array(
                'multiple' => false,
                'choices' => array(
                    'Primero' => '1',
                    'Segundo' => '2',
                    'Tercero' => '3',
                    'Cuarto' => '4',
                    'Quinto' => '5',
                    'Sexto' => '6',
                    'Séptimo' => '7',
                    'Octavo' => '8',
                    'Noveno' => '9'
                ),
            )) 
            ->add('carrera')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materia::class,
        ]);
    }
}
