<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EmpresaRepository::class)]
class Empresa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['empresa:read', 'socio:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['empresa:read', 'socio:read'])]
    private ?string $nome = null;

    #[ORM\Column(length: 255)]
    #[Groups(['empresa:read', 'socio:read'])]
    private ?string $cnpj = null;

    /**
     * @var Collection<int, Socio>
     */
    #[ORM\OneToMany(targetEntity: Socio::class, mappedBy: 'empresa', cascade: ['remove'])]
    private Collection $socios;

    public function __construct()
    {
        $this->socios = new ArrayCollection();
    }

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

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): static
    {
        $this->cnpj = $cnpj;

        return $this;
    }


    /**
     * @return Collection<int, Socio>
     */
    public function getSocios(): Collection
    {
        return $this->socios;
    }

    public function addSocio(Socio $socio): static
    {
        if (!$this->socios->contains($socio)) {
            $this->socios->add($socio);
            $socio->setEmpresa($this);
        }

        return $this;
    }

    public function removeSocio(Socio $socio): static
    {
        if ($this->socios->removeElement($socio)) {
            // set the owning side to null (unless already changed)
            if ($socio->getEmpresa() === $this) {
                $socio->setEmpresa(null);
            }
        }

        return $this;
    }
}
