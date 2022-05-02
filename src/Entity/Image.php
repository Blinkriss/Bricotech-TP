<?php

namespace App\Entity;

use App\Entity\BlogArticle;
use App\Entity\Bricosphere;
use App\Entity\Tool;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;
use App\Repository\BricosphereRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
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
     * @ORM\ManyToOne(targetEntity=BlogArticle::class, inversedBy="images")
     */
    private $blogArticle;

    /**
     * @ORM\ManyToOne(targetEntity=Tool::class, inversedBy="images")
     */
    private $tool;

    public function __construct()
    {
        $this->bricosphere = new ArrayCollection();
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
    
    public function getBlogArticle(): ?blogArticle
    {
        return $this->blogArticle;
    }

    public function setBlogArticle(?blogArticle $blogArticle): self
    {
        $this->blogArticle = $blogArticle;

        return $this;
    }

    public function getTool(): ?tool
    {
        return $this->tool;
    }

    public function setTool(?tool $tool): self
    {
        $this->tool = $tool;

        return $this;
    }
}
