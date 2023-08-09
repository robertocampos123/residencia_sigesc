<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class)
            ->add('Roles', ChoiceType::class, [
                'required' =>true,
                'multiple' =>false,
                'expanded' =>false,
                'choices'  =>[
                    'Usuario' =>'ROLE_USER',
                    'Administrador' =>'ROLE_ADMIN',
                ],
               ])
            ->add('password', PasswordType::class)
        ;
        $builder->get('Roles', PasswordType::class)
        ->addModelTransformer(new CallbackTransformer(
            function($rolesArray){
                return count($rolesArray)? $rolesArray[0]:null;
            },
            function($roleString){
                return[$roleString];
            }
        ));
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }

    
}
