<?php

namespace App\Entity;

use App\Repository\IPAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IPAddressRepository::class)]
class IPAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    
    private ?string $Address = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Type('string')]
     
    private ?string $Mask = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'ipAddresses')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): static
    {
        $this->Address = $Address;

        return $this;
    }

    public function getMask(): ?string
    {
        return $this->Mask;
    }

    public function setMask(string $Mask): static
    {
        $this->Mask = $Mask;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    
     
}
