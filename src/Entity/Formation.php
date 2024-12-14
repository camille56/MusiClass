<?php

namespace App\Entity;

use App\Enumeration\EtatPublication;
use App\Enumeration\Niveau;
use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_creation = null;

    #[ORM\Column(nullable: true)]
    private ?int $fois_suivie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $public = null;

    #[ORM\Column(length: 255)]
    private ?Niveau $niveau = null;

    #[ORM\Column(length: 255)]
    private ?EtatPublication $etat_publication = null;



    /**
     * @var Collection<int, Cours>
     */
    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'formation', cascade: ['persist', 'remove'])]
    private Collection $Cours;

    /**
     * @var Collection<int, Etudiant>
     */
    #[ORM\ManyToMany(targetEntity: Etudiant::class, inversedBy: 'formations')]
    private Collection $etudiants;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'formations')]
    private Collection $users;

    public function __construct()
    {
        $this->Cours = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
        $this->users = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeImmutable $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getFoisSuivie(): ?int
    {
        return $this->fois_suivie;
    }

    public function setFoisSuivie(?int $fois_suivie): static
    {
        $this->fois_suivie = $fois_suivie;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(Niveau $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getEtatPublication(): ?EtatPublication
    {
        return $this->etat_publication;
    }

    public function setEtatPublication(EtatPublication $etat_publication): static
    {
        $this->etat_publication = $etat_publication;

        return $this;
    }


    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->Cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->Cours->contains($cour)) {
            $this->Cours->add($cour);
            $cour->setFormation($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->Cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getFormation() === $this) {
                $cour->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): static
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): static
    {
        $this->etudiants->removeElement($etudiant);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFormation($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeFormation($this);
        }

        return $this;
    }
}
