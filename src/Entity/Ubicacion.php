<?php

namespace App\Entity;

use App\Repository\UbicacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UbicacionRepository::class)]
class Ubicacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?array $ub_coordenadas = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUbCoordenadas(): ?array
    {
        return $this->ub_coordenadas;
    }

    public function setUbCoordenadas(?array $ub_coordenadas): static
    {
        $this->ub_coordenadas = $ub_coordenadas;

        return $this;
    }
}
