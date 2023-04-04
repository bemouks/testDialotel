<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $responseUsers;


    public function __construct()
    {
        $this->responseUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getResponseUsers(): Collection
    {
        return $this->responseUsers;
    }

    public function addResponseUser(User $responseUser): self
    {
        if (!$this->responseUsers->contains($responseUser)) {
            $this->responseUsers->add($responseUser);
        }

        return $this;
    }

    public function removeResponseUser(User $responseUser): self
    {
        $this->responseUsers->removeElement($responseUser);

        return $this;
    }
    
}
