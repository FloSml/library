<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="author_list")
     */
    public function author(AuthorRepository $authorRepository)
    {
        $author = $authorRepository->findAll();

        return $this->render('author/author.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @Route("/author/new", name="author_create")
     */
    public function authorCreate(AuthorRepository $authorRepository)
    {
        $author = $authorRepository->findAll();

        return $this->render('author/author-create.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @Route("/author/insert", name="author_insert")
     */
    // public function est une méthode dans la classe
    public function insertAuthor(EntityManagerInterface $entityManager)
    {
        // On crée un nouveau livre pour l'envoyer dans la table author
        $author = new Author();
        $author->setFirstname('Florian');
        $author->setName('Soumaille');
        $author->setBirthDate(new \DateTime('03/04/1986'));
        $author->setDeathDate(new \DateTime(null));
        $author->setBiography('Cet auteur incompris n\'en est pas à son coup d\'essai cette année.');

        $entityManager->persist($author);
        $entityManager->flush();

        return $this->render('author/author-insert.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @Route("/author/update/{id}", name="author_update")
     */
    public function updateAuthor(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        // J'utilise le Repository de Author pour récupérer un livre en fonction de son id
        $author = $authorRepository->find($id);

        // Je donne un nouveau titre à mon livre
        $author->setFirstname('Jean Alfred');

        $entityManager->persist($author);
        $entityManager->flush();

        return $this->redirectToRoute('author_list');
    }

    /**
     * @Route("/author/delete/{id}", name="author_delete")
     */
    public function deleteAuthor(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        // Je récupère un enregistrement en BDD grâce au Repository de Author
        // $author = Entité Author
        $author = $authorRepository->find($id);

        // J'utilise l'entityManager avec la méthode remove pour enregistrer la suppression du author dans l'unité de travail
        $entityManager->remove($author);
        // Je valide la suppression en BDD avec la méthode flush()
        $entityManager->flush();

        return $this->redirectToRoute('author_list');
    }

    // On crée une annotation @Route à laquelle on donne un nom pour
    /**
     * @Route("/author_by_bio/{word}", name="author_by_bio")
     */
    // On appelle le AuthorRepository (en le passant en paramètre de la méthode)
    // On appelle la méthode qu'on a créé dans le AuthorRepository ("getAuthorsByBio()")
    // Symfony nous permet de faire une instance de la classe AuthorRepository en la passant en paramètre
    // C'est comme si on avait fait un NEW en PHP
    public function getAuthorsByBio(AuthorRepository $authorRepository, $word)
    {
        // On appelle classe $authorRepository -> dans la méthode getAuthorByBio
        // Grâce à Symfony on obtient l'instance de la classe Repository en la passant simplement en paramètre
        $authors = $authorRepository->getAuthorsByBio($word);

        return $this->render('author/bio.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * @Route("/author/insert_form", name="author_insert_form")
     */
    public function insertAuthorForm(Request $request, EntityManagerInterface $entityManager)
    {
        // J'utilise le gabarit de formulaire pour créer mon formulaire
        // j'envoie mon formulaire à un fichier twig et je l'affiche
        // je crée un nouveau Author en créant une nouvelle instance de l'entité Author
        $author = new Author();
        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour le Author : AuthorType (que j'ai généré en ligne de commandes)
        // Et je lui associe mon entité Author vide
        $authorForm = $this->createForm(AuthorType::class, $author);
        // Si je suis sur une méthode POST, donc qu'un formulaire a été envoyé
        if ($request->isMethod('Post')) {
            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $authorForm->handleRequest($request);
            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis etc)
            if ($authorForm->isValid()) {
                // J'enregistre en BDD ma variable $author
                // qui n'est plus vide, car elle a été remplie
                // avec les données du formulaire
                $entityManager->persist($author);
                $entityManager->flush();
            }
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $authorFormView = $authorForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('author/insert-form.html.twig', [
            'authorFormView' => $authorFormView
        ]);
    }

    /**
     * @Route("/author/{id}", name="author")
     */
    public function authorShow(AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);

        return $this->render('author/author-show.html.twig', [
            'author' => $author,
        ]);
    }
}