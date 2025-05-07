<?php

namespace App\Entity;

use App\Repository\OrganisationRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

CONST DEFAULT_AVATAR = 'default_organisation_avatar.png';
const DEFAULT_ACTIVITY_PICTURE = 'default_organisation_activity_picture.jpg';

#[ORM\Entity(repositoryClass: OrganisationRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'An organization with this name already exists.')]
class Organisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $addressCoordonates = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $representative = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $representativePictureName = null;

    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $representativePictureFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarName = null;

    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $avatarFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activityPictureName = null;

    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpg', 'image/jpeg', 'image/png'],
    )]
    private ?File $activityPictureFile = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $presentation = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $keywords;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $links;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\OneToOne(mappedBy: 'organisation', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Matching>
     */
    #[ORM\OneToMany(targetEntity: Matching::class, mappedBy: 'organisation')]
    private Collection $matchings;



    public function __construct()
    {
        $this->keywords = ['',];
        $this->links = ['',];
        $this->matchings = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddressCoordonates(): ?array
    {
        return $this->addressCoordonates;
    }

    public function setAddressCoordonates(?array $addressCoordonates): static
    {
        if( isset($addressCoordonates) && count($addressCoordonates) === 2) {
            $this->addressCoordonates = $addressCoordonates;
        }

        return $this;
    }

    public function getRepresentative(): ?string
    {
        return $this->representative;
    }

    public function setRepresentative(?string $representative): self
    {
        $this->representative = $representative;

        return $this;
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    public function setAvatarName(?string $avatarName): self
    {
        $this->avatarName = $avatarName;

        return $this;
    }

    public function setAvatarFile(?File $image = null): Organisation
    {
        if ($image !== null) {
            $this->avatarFile = $image;
        }

        return $this;
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function removeAvatarFile(): void
    {
        $this->avatarFile = null;
    }

    public function getActivityPictureName(): ?string
    {
        return $this->activityPictureName;
    }

    public function setActivityPictureName(?string $activityPictureName): self
    {
        $this->activityPictureName = $activityPictureName;

        return $this;
    }

    public function setActivityPictureFile(?File $image = null): Organisation
    {
        if ($image !== null) {
            $this->activityPictureFile = $image;
        }

        return $this;
    }

    public function getActivityPictureFile(): ?File
    {
        return $this->activityPictureFile;
    }

    public function removeActivityPictureFile(): void
    {
        $this->activityPictureFile = null;
    }

    public function getRepresentativePictureName(): ?string
    {
        return $this->representativePictureName;
    }

    public function setRepresentativePictureName(?string $representativePictureName): static
    {
        $this->representativePictureName = $representativePictureName;

        return $this;
    }

    public function setRepresentativePictureFile(?File $image = null): Organisation
    {
        if ($image !== null) {
            $this->activityPictureFile = $image;
        }

        return $this;
    }

    public function getRepresentativePictureFile(): ?File
    {
        return $this->representativePictureFile;
    }

    public function removeRepresentativePictureFile(): void
    {
        $this->representativePictureFile = null;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getKeywords(): ?array
    {
        return $this->keywords;
    }

    public function setKeywords(?array $keywords): self
    {
        foreach ($keywords as $index => $keyword) {
            if ($keyword[0] !== '#') {
                $keywords[$index] = '#' . $keyword;
            }
        }
        $this->keywords = $keywords;

        return $this;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function setLinks(array $links): self
    {
        $this->links = $links;

        return $this;
    }

    public function addLink(string $link): self
    {
        if (!in_array($link, $this->links)) {
            $this->links[] = $link;
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setOrganisation(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getOrganisation() !== $this) {
            $user->setOrganisation($this);
        }

        $this->user = $user;

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
            $matching->setOrganisation($this);
        }

        return $this;
    }

    public function removeMatching(Matching $matching): static
    {
        if ($this->matchings->removeElement($matching)) {
            // set the owning side to null (unless already changed)
            if ($matching->getOrganisation() === $this) {
                $matching->setOrganisation(null);
            }
        }

        return $this;
    }
}
