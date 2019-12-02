<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            // Je créé un nouveau champs de formulaire, ce champs est pour la propriété 'author'
            // vu que ce champs contient une relation vers une autre entité, le type choisi doit être EntityType
            ->add('author', EntityType::class, [
                // je sélectionne l'entité à afficher, ici
                // Author car ma relation fait référence aux auteurs
                'class' => Author::class,
                // je choisi la propriété d'Author qui s'affiche
                // dans le select du html
                'choice_label' => function(Author $author){
                    return $author->getFirstname().' '.$author->getName();
                },
                'label' => 'Auteur',
                'placeholder' => 'Choisir un auteur',
                'required' => false
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
                'label' => 'En stock',
                'required' => false
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
