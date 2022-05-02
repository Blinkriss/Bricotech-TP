<?php

namespace App\Entity;

use App\Repository\ImageBricosphereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageBricosphereRepository::class)
 */
class ImageBricosphere
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Bricosphere::class, mappedBy="imageBricosphere")
     */
    private $bricospheres;

    public function __construct()
    {
        $this->bricospheres = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
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
     * @return Collection|Bricosphere[]
     */
    public function getBricospheres(): Collection
    {
        return $this->bricospheres;
    }

    public function addBricosphere(Bricosphere $bricosphere): self
    {
        if (!$this->bricospheres->contains($bricosphere)) {
            $this->bricospheres[] = $bricosphere;
            $bricosphere->setImageBricosphere($this);
        }

        return $this;
    }

    public function removeBricosphere(Bricosphere $bricosphere): self
    {
        if ($this->bricospheres->removeElement($bricosphere)) {
            // set the owning side to null (unless already changed)
            if ($bricosphere->getImageBricosphere() === $this) {
                $bricosphere->setImageBricosphere(null);
            }
        }

        return $this;
    }
}
