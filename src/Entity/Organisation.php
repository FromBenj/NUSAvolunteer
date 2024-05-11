<?php

namespace App\Entity;

use App\Repository\OrganisationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganisationRepository::class)]
class Organisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $representative = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $organisationPictureName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activityPictureName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $keywords;

    #[ORM\Column(type: Types::ARRAY)]
    private array $links;

    public function __construct() {
        $this->keywords = [];
        $this->links = [];
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getRepresentative(): ?string
    {
        return $this->representative;
    }

    public function setRepresentative(?string $representative): static
    {
        $this->representative = $representative;

        return $this;
    }

    public function getOrganisationPictureName(): ?string
    {
        return $this->organisationPictureName;
    }

    public function setOrganisationPictureName(?string $organisationPictureName): static
    {
        $this->organisationPictureName = $organisationPictureName;

        return $this;
    }

    public function getActivityPictureName(): ?string
    {
        return $this->activityPictureName;
    }

    public function setActivityPictureName(?string $activityPictureName): static
    {
        $this->activityPictureName = $activityPictureName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getLinks(): array
    {
        return $this->links;
    }

    public function setLinks(array $links): static
    {
        $this->links = $links;

        return $this;
    }

    public function addLink(string $link): static
    {
        if (!in_array($link, $this->links, true)) {
            $this->links[] = $link;
        }

        return $this;
    }
}
