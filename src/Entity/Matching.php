<?php

namespace App\Entity;

use App\Repository\MatchingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MatchingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Matching
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'matchings')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Organisation $organisation = null;

    #[ORM\ManyToOne(inversedBy: 'matchings')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Volunteer $volunteer = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'matching')]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function validateBeforePersisting()
    {
        if ($this->organisation === null && $this->volunteer === null) {
            throw new \InvalidArgumentException('Either organisation or volunteer must be set.');
        }
    }

    #[ORM\PreUpdate]
    public function validateBeforeUpdating()
    {
        if ($this->organisation === null && $this->volunteer === null) {
            throw new \InvalidArgumentException('Either organisation or volunteer must be set.');
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): static
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function getVolunteer(): ?Volunteer
    {
        return $this->volunteer;
    }

    public function setVolunteer(?Volunteer $volunteer): static
    {
        $this->volunteer = $volunteer;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setMatching($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getMatching() === $this) {
                $message->setMatching(null);
            }
        }

        return $this;
    }
}
