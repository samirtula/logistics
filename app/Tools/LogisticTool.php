<?php

declare(strict_types=1);

namespace App\Tools;

class LogisticTool
{
    public static function formatAddress(array $address): string
    {
        return "{$address['city']},{$address['zip']},{$address['country']}";
    }

    public static function getCostPerKm(array $vehicleType): float
    {
        return (float)$vehicleType['cost_km'];
    }

    public static function getMinimumPrice(array $vehicleType): float
    {
        return (float)$vehicleType['minimum'];
    }

    public static function getVehicleTypeNumber(array $vehicleType): int
    {
        return (int)$vehicleType['number'];
    }

    public static function getArrayTypeMap(): array
    {
        return [
            'typeMap' => [
                'root' => 'array',
                'document' => 'array',
                'array' => 'array',
            ]
        ];
    }
}
