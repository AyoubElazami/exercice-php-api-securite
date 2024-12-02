<?php
// src/Entity/Societe.php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['societe']],
    denormalizationContext: ['groups' => ['societe']]
)]
class Societe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["societe"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["societe"])]
    private string $nom;

    #[ORM\Column(type: "string", length: 14)]
    #[Groups(["societe"])]
    private string $siret;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["societe"])]
    private string $adresse;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getSiret(): string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;
        return $this;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }
}
