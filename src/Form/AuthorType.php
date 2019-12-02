<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => "Prénom",
            ])
            ->add('name', TextType::class, [
                'label' => "Nom",
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date de naissance",
            ])
            ->add('deathDate', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date de mort",
                'required' => false,
            ])
            ->add('biography', TextareaType::class, [
                'label' => "Biographie",
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
