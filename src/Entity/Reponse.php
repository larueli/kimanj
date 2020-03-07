<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponseRepository")
 */
class Reponse
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ChoixPossible", inversedBy="reponses", fetch="EAGER")
     */
    private $choix;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deposeeLe;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    public function __construct()
    {
        $this->choix = new ArrayCollection();
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

    public function getNom() : ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom) : self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getQuestion() : ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question) : self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection|ChoixPossible[]
     */
    public function getChoix() : Collection
    {
        return $this->choix;
    }

    public function addChoix(ChoixPossible $choix) : self
    {
        if ( !$this->choix->contains($choix)) {
            $this->choix[] = $choix;
        }

        return $this;
    }

    public function removeChoix(ChoixPossible $choix) : self
    {
        if ($this->choix->contains($choix)) {
            $this->choix->removeElement($choix);
        }

        return $this;
    }

    public function getDeposeeLe() : ?DateTimeInterface
    {
        return $this->deposeeLe;
    }

    public function setDeposeeLe(DateTimeInterface $deposeeLe) : self
    {
        $this->deposeeLe = $deposeeLe;

        return $this;
    }

    public function getCommentaire() : ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire) : self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
