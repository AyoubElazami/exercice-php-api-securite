<?php

// src/Entity/UserSociete.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class UserSociete
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userSocietes')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Societe::class, inversedBy: 'userSocietes')]
    #[ORM\JoinColumn(nullable: false)]
    private Societe $societe;

    #[ORM\Column(type: 'string', length: 100)]
    private string $role;

    // Getters and Setters
    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getSociete(): Societe
    {
        return $this->societe;
    }

    public function setSociete(Societe $societe): self
    {
        $this->societe = $societe;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }
}
