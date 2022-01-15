<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /** @ORM\Column(type="integer") */
    private $start_at;

    /** @ORM\Column(type="integer") */
    private $end_at;

    /** @ORM\Column(type="integer") */
    private $create_at;

    /** @ORM\ManyToOne(targetEntity=User::class) */
    private $invited;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->start_at;
    }

    /**
     * @param mixed $start_at
     */
    public function setStartAt($start_at): void
    {
        $this->start_at = $start_at;
    }

    /**
     * @return mixed
     */
    public function getEndAt()
    {
        return $this->end_at;
    }

    /**
     * @param mixed $end_at
     */
    public function setEndAt($end_at): void
    {
        $this->end_at = $end_at;
    }

    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * @param mixed $create_at
     */
    public function setCreateAt($create_at): void
    {
        $this->create_at = $create_at;
    }

    /**
     * @return mixed
     */
    public function getInvited()
    {
        return $this->invited;
    }

    /**
     * @param mixed $invited
     */
    public function setInvited($invited): void
    {
        $this->invited = $invited;
    }

}
