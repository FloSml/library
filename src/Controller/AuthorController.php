<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="author_list")
     */
    public function author(AuthorRepository $authorRepository)
    {
        $author = $authorRepository->findAll();

        return $this->render('author.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @Route("/author/new", name="author_create")
     */
    public function authorCreate(AuthorRepository $authorRepository)
    {
        $author = $authorRepository->findAll();

        return $this->render('author-create.html.twig', [
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

        return $this->render('author-insert.html.twig', [
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

    /**
     * @Route("/author/{id}", name="author")
     */
    public function authorShow(AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);

        return $this->render('author-show.html.twig', [
            'author' => $author,
        ]);
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

        return $this->render('bio.html.twig', [
            'authors' => $authors,
        ]);
    }
}