<?php

namespace App\Entity;

use App\Repository\PositionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionsRepository::class)]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $lock = null;

    #[ORM\Column(length: 255)]
    private ?string $lock_column = null;

    #[ORM\Column]
    private ?int $digit = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLock(): ?int
    {
        return $this->lock;
    }

    public function setLock(int $lock): static
    {
        $this->lock = $lock;

        return $this;
    }

    public function getLockColumn(): ?string
    {
        return $this->lock_column;
    }

    public function setLockColumn(string $lock_column): static
    {
        $this->lock_column = $lock_column;

        return $this;
    }

    public function getDigit(): ?int
    {
        return $this->digit;
    }

    public function setDigit(int $digit): static
    {
        $this->digit = $digit;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'digit' => $this->getDigit(),
            'lock' => $this->getLock(),
            'lockColumn' => $this->getLockColumn(),
            'color' => $this->getColor()
        ];
    }
}
