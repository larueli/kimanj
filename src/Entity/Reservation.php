<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Creneau", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $horaire;

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

    public function getHoraire() : ?Creneau
    {
        return $this->horaire;
    }

    public function setHoraire(?Creneau $horaire) : self
    {
        $this->horaire = $horaire;

        return $this;
    }
}
