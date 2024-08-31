<?php

declare(strict_types=1);

namespace App\DTO;

class DistanceResponseDTO
{
    public function __construct(
        private readonly float $distanceKm
    ) {
    }

    public function getDistanceKm(): float
    {
        return $this->distanceKm;
    }
}
