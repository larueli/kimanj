<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NavetteRepository")
 */
class Navette
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $aller;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $retour;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getAuthor() : ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author) : self
    {
        $this->author = $author;

        return $this;
    }

    public function getAller() : ?string
    {
        return $this->aller;
    }

    public function setAller(string $aller) : self
    {
        $this->aller = $aller;

        return $this;
    }

    public function getRetour() : ?string
    {
        return $this->retour;
    }

    public function setRetour(string $retour) : self
    {
        $this->retour = $retour;

        return $this;
    }

    public function getNom() : ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom) : self
    {
        $this->nom = $nom;

        return $this;
    }
}
