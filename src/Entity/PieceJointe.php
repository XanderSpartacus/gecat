<?php

namespace App\Entity;

use App\Repository\PieceJointeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PieceJointeRepository::class)]
class PieceJointe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\ManyToOne(inversedBy: 'pieceJointes')] // Indique le nom de la propriété dans l'entité Courrier qui contient toutes les pièces jointes (OneToMany)
    #[ORM\JoinColumn(nullable: false)]
    private ?Courrier $Courrier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getCourrier(): ?Courrier
    {
        return $this->Courrier;
    }

    public function setCourrier(?Courrier $Courrier): static
    {
        $this->Courrier = $Courrier;

        return $this;
    }
}
