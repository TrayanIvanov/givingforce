<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 * @ORM\Table(name="countries")
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="country_id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="country_name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="country_code", type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=Charity::class, mappedBy="country")
     */
    private $charities;

    public function __construct()
    {
        $this->charities = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCharities(): Collection
    {
        return $this->charities;
    }
}
