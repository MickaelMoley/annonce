<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DealerRepository")
 */
class Dealer
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
    private $dealer_ref;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dealer_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dealer_phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="dealer")
     */
    private $annonces;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDealerRef(): ?string
    {
        return $this->dealer_ref;
    }

    public function setDealerRef(string $dealer_ref): self
    {
        $this->dealer_ref = $dealer_ref;

        return $this;
    }

    public function getDealerName(): ?string
    {
        return $this->dealer_name;
    }

    public function setDealerName(string $dealer_name): self
    {
        $this->dealer_name = $dealer_name;

        return $this;
    }

    public function getDealerPhone(): ?string
    {
        return $this->dealer_phone;
    }

    public function setDealerPhone(string $dealer_phone): self
    {
        $this->dealer_phone = $dealer_phone;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setDealer($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getDealer() === $this) {
                $annonce->setDealer(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->dealer_ref;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = strtolower(str_replace("/","-",str_replace(" ", "-", $this->dealer_ref . " " . $this->dealer_name)));

        return $this;
    }
}
