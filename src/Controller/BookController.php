<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book_list")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function book(BookRepository $bookRepository)
    {
        // On utilise le repository de book pour pouvoir sélectionner tous mes éléments de ma table book
        // Les repositories permettent de faire les requêtes SELECT dans les tables de la BDD (find, findAll, ...)
        $book = $bookRepository->findAll();

        return $this->render('book/book.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/admin/book", name="admin_book_list")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function bookAdmin(BookRepository $bookRepository)
    {
        // On utilise le repository de book pour pouvoir sélectionner tous mes éléments de ma table book
        // Les repositories permettent de faire les requêtes SELECT dans les tables de la BDD (find, findAll, ...)
        $book = $bookRepository->findAll();

        return $this->render('admin/book/book.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/admin/book/new", name="admin_book_create")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function bookCreate(BookRepository $bookRepository)
    {
        $book = $bookRepository->findAll();

        return $this->render('admin/book/book-create.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/admin/book/insert", name="admin_book_insert")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function insertBook(EntityManagerInterface $entityManager)
    {
        // On crée un nouveau livre pour l'envoyer dans la table book
        // On instancie l'entité Book qui est le mirroir de la table Book de la BDD
        $book = new Book();

        // On utilise les setters de chaque colonnes
        $book->setTitle('Oh il a l\'air bien ton livre');
        $book->setResume('Ceci est un résumé');
        $book->setStyle('Policier');
        $book->setInStock('false');
        $book->setNbPages('738');
        $book->setAuthor('Florian Soumaille');

        // On fait persister, ça stocke sans envoyer sur la BDD
        $entityManager->persist($book);
        // Le flush envoie les informations à la BDD
        $entityManager->flush();

        return $this->render('admin/book/book-insert.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/admin/book/update/{id}", name="admin_book_update")
     * @param BookRepository $bookRepository
     * @param EntityManagerInterface $entityManager
     * @param AuthorRepository $authorRepository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateBook(BookRepository $bookRepository, EntityManagerInterface $entityManager, AuthorRepository $authorRepository, $id)
    {
        // J'utilise le Repository de Book pour récupérer un livre en fonction de son id
        $book = $bookRepository->find($id);

        // Je récupère un auteur en fonction de son id
        $author = $authorRepository->find(1);

        // Je donne un nouveau titre à mon livre
        $book->setTitle('Nouveau titre du livre dont l\'id est le '.$id);

        // Dans mon livre, j'utilise le setter SetAuthor pour lui indiquer
        // quel est l'auteur relié à ce livre (attention, je dois lui
        // passer une entité author, et non juste un id)
        $book->setAuthor($author);

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('admin_book_list');
    }

    /**
     * @Route("/admin/book/delete/{id}", name="admin_book_delete")
     * @param BookRepository $bookRepository
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteBook(BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
    {
        // Je récupère un enregistrement en BDD grâce au Repository de Book
        // $book = Entité Book
        $book = $bookRepository->find($id);

        $message = "";

        // J'utilise l'entityManager avec la méthode remove pour enregistrer la suppression du book dans l'unité de travail
        $entityManager->remove($book);
        // Je valide la suppression en BDD avec la méthode flush()
        $entityManager->flush();

        $this->addFlash('success', 'Le livre a bien été supprimé');

        return $this->redirectToRoute('admin', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/admin/book/insert_form", name="admin_book_insert_form")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    // je crée la méthode insertBookForm et je lui passe en paramètres les classes
    // Request avec la variable $request et EntityManagerInterface avec la variable $entityManager
    // j'instancie les classes Request et EntityManagerInterface dans des variables
    // c'est comme faire $request = new Request
    public function insertBookForm(Request $request, EntityManagerInterface $entityManager)
    {
        // je crée un nouveau Book en créant une nouvelle instance de l'entité Book
        $book = new Book();

        // je déclare une variable $message
        $message= "";

        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour le Book : BookType (que j'ai généré en ligne de commandes)
        // Et je lui associe mon entité Book vide
        $bookForm = $this->createForm(BookType::class, $book);

        // Si le formulaire a été envoyé avec la méthode POST
        if ($request->isMethod('Post')) {

            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $bookForm->handleRequest($request);

            // Si les données de mon formulaire sont valides
            // (types dans les inputs ok, champs obligatoires renseignés)
            if ($bookForm->isValid()) {
                // je fais persister les données
                $entityManager->persist($book);
                // j'enregistre en BDD ma variable $book remplie avec les données du formulaire
                $entityManager->flush();
            }
            $this->addFlash('success', 'Le livre a bien été ajouté');
            return $this->redirectToRoute('admin');
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $bookFormView = $bookForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient mon formulaire
        return $this->render('admin/book/book-insert-form.html.twig', [
            'bookFormView' => $bookFormView,
            'message' => $message,
        ]);
    }

    /**
     * @Route("/admin/book/update_form/{id}", name="admin_book_update_form")
     * @param BookRepository $bookRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     */
    public function updateBookForm(BookRepository $bookRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $book = $bookRepository->find($id);
        $message= "";

        // permet de générer un livre avec toutes ses infos préenregistrées
        $bookForm = $this->createForm(BookType::class, $book);

        if ($request->isMethod('Post')) {

            $bookForm->handleRequest($request);
            if ($bookForm->isValid()) {

                $entityManager->persist($book);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Le livre a bien été modifié');
            return $this->redirectToRoute('admin');
        }

        $bookFormView = $bookForm->createView();
        return $this->render('admin/book/book-insert-form.html.twig', [
            'bookFormView' => $bookFormView,
            'message' => $message,
        ]);
    }

    /**
     * @Route("/book/{id}", name="book")
     * @param BookRepository $bookRepository
     * @param $id
     * @return Response
     */
    public function bookShow(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        return $this->render('book/book-show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/admin/book/{id}", name="admin_book")
     * @param BookRepository $bookRepository
     * @param $id
     * @return Response
     */
    public function bookShowAdmin(BookRepository $bookRepository, $id)
    {
        $book = $bookRepository->find($id);

        return $this->render('admin/book/book-show.html.twig', [
            'book' => $book,
        ]);
    }

}