<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApplicationRepository::class)
 * @ORM\Table(name="applications")
 */
class Application
{
    public const STAGE_ORGANISATION_APROVAL = 'organisation_approval';
    public const STAGE_ALLOW_TO_PROCEED = 'allow_to_proceed';
    public const STAGE_PAID = 'paid';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="application_id", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="applications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Charity::class, inversedBy="applications")
     * @ORM\JoinColumn(name="charity_id", referencedColumnName="charity_id", nullable=false)
     */
    private $charity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCharity(): Charity
    {
        return $this->charity;
    }

    public function setCharity(Charity $charity): self
    {
        $this->charity = $charity;

        return $this;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function setStage(string $stage): self
    {
        $this->stage = $stage;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');

        return $this;
    }
}
