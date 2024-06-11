<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
class Volunteer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $volunteerPictureName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $disponibilities = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $keywords = null;

    #[ORM\OneToOne(mappedBy: 'volunteer', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastNa�me;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getVolunteerPictureName(): ?string
    {
        return $this->volunteerPictureName;
    }

    public function setVolunteerPictureName(?string $volunteerPictureName): static
    {
        $this->volunteerPictureName = $volunteerPictureName;

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

    public function getDisponibilities(): array
    {
        return $this->disponibilities;
    }

    public function setDisponibilities(array $disponibilities): static
    {
        $this->disponibilities = $disponibilities;

        return $this;
    }

    public function getKeywords(): ?array
    {
        return $this->keywords;
    }

    public function setKeywords(?array $keywords): static
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function addKeyword(?string $keyword): static
    {
        if ($keyword !== null && !in_array($keyword, $this->keywords)) {
            $this->keywords[] = $keyword;
        }

        return $this;
    }

    public function getFullName(): static
    {
        if ($this->firstName && $this->lastName) {
            $fullName = $this->firstName . ' ' . $this->lastName;
        } elseif (!$this->firstName) {
            $fullName = $this->lastName;
        } elseif (!$this->lastName) {
            $fullName = $this->firstName;
        } else {
            $fullName = "";
        }

        return $fullName;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setVolunteer(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getVolunteer() !== $this) {
            $user->setVolunteer($this);
        }

        $this->user = $user;

        return $this;
    }
}
