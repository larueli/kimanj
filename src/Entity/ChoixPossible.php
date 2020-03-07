<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChoixPossibleRepository")
 */
class ChoixPossible
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="choixPossibles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $texte;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reponse", mappedBy="choix")
     */
    private $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
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

    public function getTexte() : ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte) : self
    {
        $this->texte = $texte;

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
            $reponse->addChoix($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse) : self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            $reponse->removeChoix($this);
        }

        return $this;
    }
}
