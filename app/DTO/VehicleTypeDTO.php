<?php

declare(strict_types=1);

namespace App\DTO;

class VehicleTypeDTO
{
    public function __construct(
        private readonly float $costPerKm,
        private readonly float $minimumPrice,
        private readonly int $vehicleTypeNumber
    ) {
    }

    public function getCostPerKm(): float
    {
        return $this->costPerKm;
    }

    public function getMinimumPrice(): float
    {
        return $this->minimumPrice;
    }

    public function getVehicleTypeNumber(): int
    {
        return $this->vehicleTypeNumber;
    }
}
