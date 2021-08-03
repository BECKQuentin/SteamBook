<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $IDSteam;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIDSteam(): ?string
    {
        return $this->IDSteam;
    }

    public function setIDSteam(string $IDSteam): self
    {
        $this->IDSteam = $IDSteam;

        return $this;
    }
}
