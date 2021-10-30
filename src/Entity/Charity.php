<?php

namespace App\Entity;

use App\Repository\CharityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CharityRepository::class)
 * @ORM\Table(name="charities")
 */
class Charity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="charity_id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="charity_name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="is_approved", type="boolean")
     */
    private $isApproved;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="charities")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", nullable=false)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity=Application::class, mappedBy="charity")
     */
    private $applications;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
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

    public function getIsApproved(): bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isAproved): self
    {
        $this->isApproved = $isAproved;

        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
