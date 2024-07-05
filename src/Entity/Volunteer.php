<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
class Volunteer
{
    public const DISPONIBILITYCHOICES = [
        "sometimes" => "Sometimes",
        "hoursWeek" => "Some hours per week",
        "hoursMonth" => "Some hours per month",
        "flexible" => "I am flexible"
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
    private ?string $pictureName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $disponibilities = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $keywords = null;

    #[ORM\OneToOne(mappedBy: 'volunteer', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    /**
     * @var Collection<int, Matching>
     */
    #[ORM\OneToMany(targetEntity: Matching::class, mappedBy: 'volunteer')]
    private Collection $matchings;

    public function __construct()
    {
        $this->matchings = new ArrayCollection();
    }

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
        $this->changeSlug();

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        $this->changeSlug();

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

    public function getPictureName(): ?string
    {
        return $this->pictureName;
    }

    public function setPictureName(?string $pictureName): self
    {
        $this->pictureName = $pictureName;

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
            if (in_array($disponibility, array_flip((self::DISPONIBILITYCHOICES)))) {
                $this->addDisponibility($disponibility);
            }
        }

        return $this;
    }

    public function addDisponibility(string $disponibility): self
    {
        if (!in_array($disponibility, $this->disponibilities) && in_array($disponibility, array_flip(self::DISPONIBILITYCHOICES))) {
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function changeSlug(): static
    {
        $slugger = new AsciiSlugger();
        $this->slug = $slugger->slug($this->getFullName())->lower();

        return $this;
    }

    /**
     * @return Collection<int, Matching>
     */
    public function getMatchings(): Collection
    {
        return $this->matchings;
    }

    public function addMatching(Matching $matching): static
    {
        if (!$this->matchings->contains($matching)) {
            $this->matchings->add($matching);
            $matching->setVolunteer($this);
        }

        return $this;
    }

    public function removeMatching(Matching $matching): static
    {
        if ($this->matchings->removeElement($matching)) {
            // set the owning side to null (unless already changed)
            if ($matching->getVolunteer() === $this) {
                $matching->setVolunteer(null);
            }
        }

        return $this;
    }
}
