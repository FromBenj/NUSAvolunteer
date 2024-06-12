<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
class Volunteer
{
    private const DISPONIBILITYCHOICES = [
        "sometime",
        "some hours per week",
        "some hours per month",
        "I am flexible"
    ];

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
    private array $disponibilities = null;

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

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getVolunteerPictureName(): ?string
    {
        return $this->volunteerPictureName;
    }

    public function setVolunteerPictureName(?string $volunteerPictureName): self
    {
        $this->volunteerPictureName = $volunteerPictureName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDisponibilities(): array
    {
        return $this->disponibilities;
    }

    public function setDisponibilities(array $disponibilities): self
    {
        $this->disponibilities = [];
        foreach ($disponibilities as $disponibility) {
            if (in_array($disponibility, self::DISPONIBILITYCHOICES)) {
                $this->addDisponibility($disponibility);
            }
        }

        return $this;
    }

    public function addDisponibility(string $disponibility): self
    {
        if (!in_array($disponibility, $this->disponibilities) && in_array($disponibility, self::DISPONIBILITYCHOICES)) {
            $this->disponibilities[] = $disponibility;
        }

        return $this;
    }

    public function removeDisponibility(string $disponibility): self
    {
        if (in_array($disponibility, $this->disponibilities)) {
            $disponibilityKey = array_search($disponibility, $this->disponibilities);
            unset($this->disponibilities[$disponibilityKey]);
        }

        return $this;
    }

    public function getKeywords(): ?array
    {
        return $this->keywords;
    }

    public function setKeywords(?array $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function addKeyword(?string $keyword): self
    {
        if ($keyword !== null && !in_array($keyword, $this->keywords)) {
            $this->keywords[] = $keyword;
        }

        return $this;
    }

    public function getFullName(): string
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

    public function setUser(?User $user): self
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

    public function getDisponibilityChoices(): array
     {
        return self::DISPONIBILITYCHOICES;
    }
}
