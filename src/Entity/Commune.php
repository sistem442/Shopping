<?php

namespace App\Entity;

use App\Repository\CommuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommuneRepository::class)]
class Commune
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'commune', targetEntity: User::class)]
    private Collection $roommates;

    public function __construct()
    {
        $this->roommates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getRoommates(): Collection
    {
        return $this->roommates;
    }

    public function addRoommate(User $roommate): static
    {
        if (!$this->roommates->contains($roommate)) {
            $this->roommates->add($roommate);
            $roommate->setCommune($this);
        }

        return $this;
    }

    public function removeRoommate(User $roommate): static
    {
        if ($this->roommates->removeElement($roommate)) {
            // set the owning side to null (unless already changed)
            if ($roommate->getCommune() === $this) {
                $roommate->setCommune(null);
            }
        }

        return $this;
    }
}
