<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Pseudo',
                'attr' => [
                    'placeholder' => 'Saisir vote pseudo'
                ]
            ))
            ->add('email', EmailType::class, array(
                'attr' => [
                    'placeholder' => 'Votre adresse Mail'
                ]
            ))
            ->add('password', PasswordType::class, array(
                'attr' => [
                    'placeholder' => 'Votre un mot de passe'
                ]
            ))
            ->add('passwordConfirm', PasswordType::class, array(
                'label' => 'Confirmer votre Password',
                'attr' => [
                    'placeholder' => 'Comfirmer votre mot de passe',
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
