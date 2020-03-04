<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResaNavetteRepository")
 */
class ResaNavette
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
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $aller;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $retour;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getUuid() : ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid) : self
    {
        $this->uuid = $uuid;

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

    public function getAller() : ?bool
    {
        return $this->aller;
    }

    public function setAller(?bool $aller) : self
    {
        $this->aller = $aller;

        return $this;
    }

    public function getRetour() : ?bool
    {
        return $this->retour;
    }

    public function setRetour(?bool $retour) : self
    {
        $this->retour = $retour;

        return $this;
    }
}
