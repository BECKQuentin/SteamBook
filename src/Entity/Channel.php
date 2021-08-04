<?php

namespace App\Entity;

use App\Repository\ChannelRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ChannelRepository::class)
 * @UniqueEntity(fields={"name"}, message="Un salon porte déja ce nom")
 */
class Channel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Tchat::class, mappedBy="channel", cascade={"remove"})
     */
    private $tchats;

    public function __construct()
    {
        $this->tchats = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Tchat[]
     */
    public function getTchats(): Collection
    {
        return $this->tchats;
    }

    public function addTchat(Tchat $tchat): self
    {
        if (!$this->tchats->contains($tchat)) {
            $this->tchats[] = $tchat;
            $tchat->setChannel($this);
        }

        return $this;
    }

    public function removeTchat(Tchat $tchat): self
    {
        if ($this->tchats->removeElement($tchat)) {
            // set the owning side to null (unless already changed)
            if ($tchat->getChannel() === $this) {
                $tchat->setChannel(null);
            }
        }

        return $this;
    }
}
