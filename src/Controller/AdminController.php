<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/", name="admin")
     * @param BookRepository $bookRepository
     * @param AuthorRepository $authorRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(BookRepository $bookRepository, AuthorRepository $authorRepository)
    {
        $book = $bookRepository->findAll();
        $author = $authorRepository->findAll();

        return $this->render('admin.html.twig', [
            'book' => $book,
            'author' => $author,
        ]);
    }
}
