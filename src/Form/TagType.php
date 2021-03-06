<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Désignation',
                'attr' => [
                    'placeholder' => 'Choisir un nom'
                ]
            ))
            ->add('backgroundColor', ColorType::class, array(
                'label' => 'Choisir la couleur de Fond',
                'attr' => [
                    'style' => 'height: 50px;'
                ]
            ))
            ->add('textColor', ColorType::class, array(
                'label' => 'Choisir la couleur du Texte',
                'attr' => [
                    'style' => 'height: 50px;'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
