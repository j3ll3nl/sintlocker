<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Position;

class CombinationPositionDto
{
    private Position $position;
    private string $base64QrCode;

    public function __construct(Position $position, string $base64QrCode)
    {
        $this->position = $position;
        $this->base64QrCode = $base64QrCode;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getBase64QrCode(): string
    {
        return $this->base64QrCode;
    }
}