<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fournisseur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'Entreposer')]
    private Collection $categories;

    /**
     * @var Collection<int, Entreposer>
     */
    #[ORM\OneToMany(targetEntity: Entreposer::class, mappedBy: 'produit')]
    private Collection $entreposer;

    /**
     * @var Collection<int, Contenir>
     */
    #[ORM\OneToMany(targetEntity: Contenir::class, mappedBy: 'produit')]
    private Collection $contenir;

    #[ORM\ManyToOne(inversedBy: 'produit')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'produit')]
    
    private Collection $image;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->entreposer = new ArrayCollection();
        $this->contenir = new ArrayCollection();
        $this->image = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getFournisseur(): ?string
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?string $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, entreposer>
     */
    public function getentreposer(): Collection
    {
        return $this->entreposer;
    }

    public function addentreposer(entreposer $entreposer): static
    {
        if (!$this->entreposer->contains($entreposer)) {
            $this->entreposer->add($entreposer);
            $entreposer->setProduit($this);
        }

        return $this;
    }

    public function removeentreposer(entreposer $entreposer): static
    {
        if ($this->entreposer->removeElement($entreposer)) {
            // set the owning side to null (unless already changed)
            if ($entreposer->getProduit() === $this) {
                $entreposer->setProduit(null);
            }
        }

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
            $contenir->setProduit($this);
        }

        return $this;
    }

    public function removeContenir(contenir $contenir): static
    {
        if ($this->contenir->removeElement($contenir)) {
            // set the owning side to null (unless already changed)
            if ($contenir->getProduit() === $this) {
                $contenir->setProduit(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): static
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }
}
