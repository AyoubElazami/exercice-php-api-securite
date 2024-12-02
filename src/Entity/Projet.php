<?php
// src/Entity/Projet.php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['projet']],
    denormalizationContext: ['groups' => ['projet']],
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'put', 'delete']
)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["projet"])]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["projet"])]
    private string $titre;

    #[ORM\Column(type: "text")]
    #[Groups(["projet"])]
    private string $description;

    #[ORM\Column(type: "datetime")]
    #[Groups(["projet"])]
    private \DateTimeInterface $dateCreation;

    #[ORM\ManyToOne(targetEntity: Societe::class, inversedBy: 'projets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["projet"])]
    private Societe $societe;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDateCreation(): \DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
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
}
