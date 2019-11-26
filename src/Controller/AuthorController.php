<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
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
     * @Route("/author/{id}", name="author")
     */
    public function authorShow(AuthorRepository $authorRepository, $id)
    {
        $author = $authorRepository->find($id);

        return $this->render('author-show.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @Route("/author_by_bio/{word}", name="author_by_bio")
     */
    // On instencie une -> comme si on faisait un New Repository
    public function getAuthorsByBio(AuthorRepository $authorRepository, $word)
    {
        // on appelle classe $authorRepository -> dans la méthode getAuthorByBio
        $authors = $authorRepository->getAuthorsByBio($word);

        return $this->render('bio.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * @Route("/author/{search}", name="search")
     */
    public function authorSearch(AuthorRepository $authorRepository, $search)
    {
        $author = $authorRepository->find($id);

        return $this->render('author-show.html.twig', [
            'author' => $author,
        ]);
    }
}
