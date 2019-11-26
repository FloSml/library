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
    public function insertAuthor(EntityManagerInterface $entityManager)
    {
        // On crée un nouveau livre pour l'envoyer dans la table author
        $author = new Author();
        $authorFirstname = $author->setFirstname('Florian');
        $authorName = $author->setName('Soumaille');
        $authorBirthDate = $author->setBirthDate(new \DateTime('03/04/1986'));
        $authorDeathDate = $author->setDeathDate(null);
        $authorBiography = $author->setBiography('Cet auteur incompris n\'en est pas à son coup d\'essai cette année.');

        $entityManager->persist($author);
        $entityManager->flush();

        return $this->render('author-insert.html.twig', [
            'author' => $author,
            'firstname' => $authorFirstname,
            'name' => $authorName,
            'birthDate' => $authorBirthDate,
            'deathDate' => $authorDeathDate,
            'biography' => $authorBiography,
        ]);
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
    // On appelle le BookRepository (en le passant en paramètre de la méthode)
    // On appelle la méthode qu'on a créé dans le BookRepository ("getAuthorsByBio()")
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