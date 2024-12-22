<?php

namespace App\Entity;

use App\Repository\SocioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SocioRepository::class)]
class Socio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['socio:read', 'empresa:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['socio:read'])]
    private ?string $nome = null;

    #[ORM\Column(length: 255)]
    #[Groups(['socio:read'])]
    private ?string $cpf = null;

    #[ORM\ManyToOne(inversedBy: 'socios')]
    #[ORM\JoinColumn(name: 'empresa_id', referencedColumnName: 'id')]
    #[Groups(['socio:read', 'empresa:read'])]
    private ?Empresa $empresa = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): static
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): static
    {
        $this->empresa = $empresa;

        return $this;
    }
}
