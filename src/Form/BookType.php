<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre",
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur'
            ])
            ->add('nbPages', IntegerType::class, [
                'label' =>  "Nombre de pages",
            ])
            ->add('style', ChoiceType::class, [
                'choices' => [
                    'roman' => 'Roman',
                    'policier' => 'Policier',
                    'thriller' => 'Thriller',
                ],
                 'label' =>  "Genre",
            ])
            ->add('inStock', CheckboxType::class, [
                'label' => 'En stock'
            ])
            ->add('resume', TextareaType::class, [
                'label' => 'Résumé'
            ])
            ->add('image', FileType::class, [
                'label' => 'Ajouter une illustration',
                'required' => false
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
