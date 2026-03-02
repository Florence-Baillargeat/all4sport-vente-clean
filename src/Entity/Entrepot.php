<?php

namespace App\Entity;

use App\Repository\EntrepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepotRepository::class)]
class Entrepot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?bool $web = null;

    /**
     * @var Collection<int, entreposer>
     */
    #[ORM\OneToMany(targetEntity: entreposer::class, mappedBy: 'entrepot')]
    private Collection $entreposer;

    public function __construct()
    {
        $this->entreposer = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function isWeb(): ?bool
    {
        return $this->web;
    }

    public function setWeb(bool $web): static
    {
        $this->web = $web;

        return $this;
    }

    /**
     * @return Collection<int, entreposer>
     */
    public function getEntreposer(): Collection
    {
        return $this->entreposer;
    }

    public function addEntreposer(entreposer $entreposer): static
    {
        if (!$this->entreposer->contains($entreposer)) {
            $this->entreposer->add($entreposer);
            $entreposer->setEntrepot($this);
        }

        return $this;
    }

    public function removeEntreposer(entreposer $entreposer): static
    {
        if ($this->entreposer->removeElement($entreposer)) {
            // set the owning side to null (unless already changed)
            if ($entreposer->getEntrepot() === $this) {
                $entreposer->setEntrepot(null);
            }
        }

        return $this;
    }
}
