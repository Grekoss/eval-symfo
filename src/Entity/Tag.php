<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @UniqueEntity("name"))
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message = "Veuillez saisir un nom")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Question", mappedBy="tags")
     */
    private $questions;
    
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Assert\NotBlank(message = "Veuillez choisir une couleur de fond")
     */
    private $backgroundColor;
    
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Assert\NotBlank(message = "Veuillez choisir une couleur de texte")
     */
    private $textColor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->backgroundColor = '#FFFFFF';
        $this->textColor = '#000000';

    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->addTag($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            $question->removeTag($this);
        }

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function getSlug() : ? string
    {
        return $this->slug;
    }

    public function setSlug(? string $slug) : self
    {
        $this->slug = $slug;

        return $this;
    }
}
