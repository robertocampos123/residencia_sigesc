<?php

namespace App\Form;

use App\Entity\Tema;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero_unidad', IntegerType::class,[
                'attr' => [
                    'min' => 1,
                    'max' => 12,
                  ]

            ])
            ->add('nombre_unidad')
            ->add('subtemas')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tema::class,
        ]);
    }
}
