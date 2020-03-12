<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChoixPossible", mappedBy="question", orphanRemoval=true)
     */
    private $choixPossibles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="question", orphanRemoval=true)
     */
    private $reponses;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estRAZQuotidien;

    /**
     * @ORM\Column(type="datetime")
     */
    private $poseeLe;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reponsesAnonymes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estVisible;

    public function __construct()
    {
        $this->choixPossibles = new ArrayCollection();
        $this->reponses       = new ArrayCollection();
    }

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

    public function getAuteur() : ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur) : self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getInterrogation() : ?string
    {
        return $this->interrogation;
    }

    public function setInterrogation(string $interrogation) : self
    {
        $this->interrogation = $interrogation;

        return $this;
    }

    public function getChoixMultiple() : ?bool
    {
        return $this->choixMultiple;
    }

    public function setChoixMultiple(bool $choixMultiple) : self
    {
        $this->choixMultiple = $choixMultiple;

        return $this;
    }

    public function getReponsesPubliques() : ?bool
    {
        return $this->reponsesPubliques;
    }

    public function setReponsesPubliques(bool $reponsesPubliques) : self
    {
        $this->reponsesPubliques = $reponsesPubliques;

        return $this;
    }

    /**
     * @return Collection|ChoixPossible[]
     */
    public function getChoixPossibles() : Collection
    {
        return $this->choixPossibles;
    }

    public function addChoixPossible(ChoixPossible $choixPossible) : self
    {
        if ( !$this->choixPossibles->contains($choixPossible)) {
            $this->choixPossibles[] = $choixPossible;
            $choixPossible->setQuestion($this);
        }

        return $this;
    }

    public function removeChoixPossible(ChoixPossible $choixPossible) : self
    {
        if ($this->choixPossibles->contains($choixPossible)) {
            $this->choixPossibles->removeElement($choixPossible);
            // set the owning side to null (unless already changed)
            if ($choixPossible->getQuestion() === $this) {
                $choixPossible->setQuestion(NULL);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses() : Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse) : self
    {
        if ( !$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse) : self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(NULL);
            }
        }

        return $this;
    }

    public function getTitre() : ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre) : self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getEstRAZQuotidien() : ?bool
    {
        return $this->estRAZQuotidien;
    }

    public function setEstRAZQuotidien(bool $estRAZQuotidien) : self
    {
        $this->estRAZQuotidien = $estRAZQuotidien;

        return $this;
    }

    public function getPoseeLe() : ?DateTimeInterface
    {
        return $this->poseeLe;
    }

    public function setPoseeLe(DateTimeInterface $poseeLe) : self
    {
        $this->poseeLe = $poseeLe;

        return $this;
    }

    public function getReponsesAnonymes() : ?bool
    {
        return $this->reponsesAnonymes;
    }

    public function setReponsesAnonymes(bool $reponsesAnonymes) : self
    {
        $this->reponsesAnonymes = $reponsesAnonymes;

        return $this;
    }

    public function getEstVisible() : ?bool
    {
        return $this->estVisible;
    }

    public function setEstVisible(bool $estVisible) : self
    {
        $this->estVisible = $estVisible;

        return $this;
    }
}
