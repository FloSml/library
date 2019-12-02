<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @UniqueEntity("title")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2, max=100, minMessage="Le titre doit comporter au moins 2 caractères", maxMessage="Le titre ne doit pas comporter plus de 100 caractères.")
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Positive
     */
    private $nbPages;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $style;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull
     * @Assert\Length(min=30, minMessage="La biographie doit comporter au moins 30 caractères.")
     */
    private $resume;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 1200,
     *     minHeight = 200,
     *     maxHeight = 1200
     * )
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="book")
     * @Assert\NotNull(message="Choisissez un auteur.")
     */
    private $author;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Assert\Type(
     * type="bool")
     */
    private $inStock;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNbPages(): ?int
    {
        return $this->nbPages;
    }

    public function setNbPages(int $nbPages): self
    {
        $this->nbPages = $nbPages;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    public function getInStock(): ?bool
    {
        return $this->inStock;
    }

    public function setInStock(?bool $inStock): self
    {
        $this->inStock = $inStock;

        return $this;
    }

}
