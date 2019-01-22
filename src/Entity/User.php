<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Role;

/**
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"username"},
 *     message="Ce nom est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cet email est déjà utilisé"
 * )
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message = "Veuillez saisir un Pseudo")
     * 
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message = "Veuillez saisir un Email")
     * @Assert\Email(message = "Votre Email n'est pas valide")
     * 
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Veuillez saisir un mot de passe")
     * @Assert\Length(
     *     min=6,
     *     max=64,
     *     minMessage="Veuillez saisir un mot de passe avec au moin 6 caractères"
     * )
     * 
     */
    private $password;
    
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Merci de confirmer votre mot de passe")
     * 
     */
    private $passwordConfirm;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="author", orphanRemoval=true)
     */
    private $reponses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="author", orphanRemoval=true)
     */
    private $questions;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Question", mappedBy="vote")
     */
    private $voteQuestions;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reponse", mappedBy="vote")
     */
    private $voteReponses;

    public function __construct()
    {
        // Ajout automatique de la date pour la création et update(1ere modif = création)
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->reponses = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->voteQuestions = new ArrayCollection();
        $this->voteReponses = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordConfirm() : ? string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(?string $passwordConfirm) : self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRoles()
    {
        return array($this->role->getName());
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setAuthor($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getAuthor() === $this) {
                $reponse->setAuthor(null);
            }
        }

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
            $question->setAuthor($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getAuthor() === $this) {
                $question->setAuthor(null);
            }
        }

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

        /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Question[]
     */
    public function getVoteQuestions(): Collection
    {
        return $this->voteQuestions;
    }

    public function addVoteQuestion(Question $voteQuestion): self
    {
        if (!$this->voteQuestions->contains($voteQuestion)) {
            $this->voteQuestions[] = $voteQuestion;
            $voteQuestion->addVote($this);
        }

        return $this;
    }

    public function removeVoteQuestion(Question $voteQuestion): self
    {
        if ($this->voteQuestions->contains($voteQuestion)) {
            $this->voteQuestions->removeElement($voteQuestion);
            $voteQuestion->removeVote($this);
        }

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getVoteReponses(): Collection
    {
        return $this->voteReponses;
    }

    public function addVoteReponse(Reponse $voteReponse): self
    {
        if (!$this->voteReponses->contains($voteReponse)) {
            $this->voteReponses[] = $voteReponse;
            $voteReponse->addVote($this);
        }

        return $this;
    }

    public function removeVoteReponse(Reponse $voteReponse): self
    {
        if ($this->voteReponses->contains($voteReponse)) {
            $this->voteReponses->removeElement($voteReponse);
            $voteReponse->removeVote($this);
        }

        return $this;
    }
}
