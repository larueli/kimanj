<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreneauRepository")
 */
class Creneau
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
    private $horaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="horaire", orphanRemoval=true)
     */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getHoraire() : ?string
    {
        return $this->horaire;
    }

    public function setHoraire(string $horaire) : self
    {
        $this->horaire = $horaire;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations() : Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation) : self
    {
        if ( !$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setHoraire($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation) : self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getHoraire() === $this) {
                $reservation->setHoraire(null);
            }
        }

        return $this;
    }
}
