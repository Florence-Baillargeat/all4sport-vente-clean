<?php

namespace App\Entity;

use App\Repository\CommandeClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeClientRepository::class)]
class CommandeClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateCommande = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?StatutCommande $statutCommande_id = null;

    /**
     * @var Collection<int, contenir>
     */
    #[ORM\OneToMany(targetEntity: contenir::class, mappedBy: 'commandeClient')]
    private Collection $contenir;

    public function __construct()
    {
        $this->contenir = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTime
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTime $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getStatutCommandeId(): ?StatutCommande
    {
        return $this->statutCommande_id;
    }

    public function setStatutCommandeId(?StatutCommande $statutCommande_id): static
    {
        $this->statutCommande_id = $statutCommande_id;

        return $this;
    }

    /**
     * @return Collection<int, contenir>
     */
    public function getContenir(): Collection
    {
        return $this->contenir;
    }

    public function addContenir(contenir $contenir): static
    {
        if (!$this->contenir->contains($contenir)) {
            $this->contenir->add($contenir);
            $contenir->setCommandeClient($this);
        }

        return $this;
    }

    public function removeContenir(contenir $contenir): static
    {
        if ($this->contenir->removeElement($contenir)) {
            // set the owning side to null (unless already changed)
            if ($contenir->getCommandeClient() === $this) {
                $contenir->setCommandeClient(null);
            }
        }

        return $this;
    }
}
