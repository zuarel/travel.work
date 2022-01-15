<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{

    const STATUS_CREATED = 'created';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_DELETED = 'deleted';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $creator;

    /** @ORM\Column(type="integer") */
    private $start_at;

    /** @ORM\Column(type="integer") */
    private $end_at;

    /** @ORM\Column(type="integer") */
    private $create_at;

    /** @ORM\Column(type="integer") */
    private $invited;

    /** @ORM\Column(type="string", length=255) */
    private $name;

    /** @ORM\Column(type="string", length=20) */
    private $status = self::STATUS_CREATED;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): int
    {
        return $this->creator;
    }

    public function setCreator(int $creator): self
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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function hasAccessForUser(int $user_id)
    {
        if($this->getCreator() !== $user_id && $this->getInvited() !== $user_id) {
            return false;
        }

        return true;
    }

    public function isNowOn()
    {
        $now = time();
        return $this->getStartAt() < $now && $this->getEndAt() > $now;
    }

    public function isOwner(int $user_id)
    {
        return $this->getCreator() === $user_id;
    }
}
