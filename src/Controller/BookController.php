<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book_list")
     */
    public function book(BookRepository $bookRepository)
    {
        // On utilise le repository de book pour pouvoir sélectionner tous mes éléments de ma table book
        // Les repository permettent de faire les requêtes SELECT dans les tables de la BDD (find, findAll, ...)
        $book = $bookRepository->findAll();

        return $this->render('book.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/book/insert", name="book_insert")
     */
    public function insertBook(EntityManagerInterface $entityManager)
    {
        // On crée un nouveau livre pour l'envoyer dans la table book
        $book = new Book();
        $bookTitle = $book->setTitle('Oh il a l\'air bien ton livre');
        $bookResume = $book->setResume('Ceci est un résumé');
        $bookStyle = $book->setStyle('Policier');
        $bookInStock = $book->setInStock('false');
        $bookNbPages = $book->setNbPages('738');

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->render('book-insert.html.twig', [
            'book' => $book,
            'title' => $bookTitle,
            'resume' => $bookResume,
            'style' => $bookStyle,
            'inStock' => $bookInStock,
            'nbPages' => $bookNbPages,
        ]);
    }

    /**
     * @Route("/book/{id}", name="book")
     */
    public function bookShow(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        return $this->render('book-show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/book_by_genre", name="book_by_genre")
     */
    // On appelle le BookRepository (en le passant en paramètre de la méthode)
    // On appelle la méthode qu'on a créé dans le BookRepository ("getByGenre()")
    public function getBooksByGenre(BookRepository $bookRepository)
    {
        $books= $bookRepository->getByGenre();

        dump($books); die;
        // Cette méthode est censée nous retourner tous les livres en fonction de leur type
        // Elle va donc exécuter une requête SELECT en base de données
    }
}