<?php

namespace App\Form;

use App\Entity\Grupo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clave_grupo')
            ->add('cupo', IntegerType::class,[
                'attr' => [
                    'min' => 15,
                    'max' => 55,
                  ]

            ])
            ->add('inscritos', IntegerType::class,[
                'attr' => [
                    'min' => 10,
                    'max' => 55,
                  ]

            ])
            ->add('aula')
            ->add('horario')
            ->add('docente')
            ->add('materia')
            ->add('carrera')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Grupo::class,
        ]);
    }
}
