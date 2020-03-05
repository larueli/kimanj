<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
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
    private $auteur;

    /**
     * @ORM\Column(type="text")
     */
    private $interrogation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $choixMultiple;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reponsesPubliques;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getInterrogation(): ?string
    {
        return $this->interrogation;
    }

    public function setInterrogation(string $interrogation): self
    {
        $this->interrogation = $interrogation;

        return $this;
    }

    public function getChoixMultiple(): ?bool
    {
        return $this->choixMultiple;
    }

    public function setChoixMultiple(bool $choixMultiple): self
    {
        $this->choixMultiple = $choixMultiple;

        return $this;
    }

    public function getReponsesPubliques(): ?bool
    {
        return $this->reponsesPubliques;
    }

    public function setReponsesPubliques(bool $reponsesPubliques): self
    {
        $this->reponsesPubliques = $reponsesPubliques;

        return $this;
    }
}
