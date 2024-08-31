<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\AddressDTO;
use App\DTO\DistanceResponseDTO;
use App\DTO\VehicleTypeDTO;
use App\Http\Client\LogisticHTTPClient;
use App\Repositories\LogisticRepository;
use RuntimeException;

class LogisticService
{
    public function __construct(
        private readonly LogisticRepository $repository,
        private readonly LogisticHTTPClient $httpClient
    ) {
    }

    public function calculateTotalDistance(array $addresses): float
    {
        $addressesDTO = array_map(
            fn($address) => new AddressDTO($address['country'], $address['zip'], $address['city']),
            $addresses
        );
        $origin = array_shift($addressesDTO);
        $totalDistance = 0;

        foreach ($addressesDTO as $destination) {
            $distanceDto = $this->fetchDistance($origin, $destination);
            $totalDistance += $distanceDto->getDistanceKm();
            $origin = $destination;
        }

        return $totalDistance;
    }

    public function calculatePrices(float $totalDistance, array $vehicleTypes): array
    {
        $prices = [];

        /** @var VehicleTypeDTO $vehicleType */
        foreach ($vehicleTypes as $vehicleType) {
            $price = max($totalDistance * $vehicleType->getCostPerKm(), $vehicleType->getMinimumPrice());
            $prices[] = [
                'vehicle_type' => $vehicleType->getVehicleTypeNumber(),
                'price' => round($price, 2),
            ];
        }

        return $prices;
    }

    private function fetchDistance(AddressDTO $origin, AddressDTO $destination): DistanceResponseDTO
    {
        $url = sprintf(
            '%s?origin=%s&destination=%s&key=%s',
            config('app.google.maps_uri'),
            urlencode($origin->getFormated()),
            urlencode($destination->getFormated()),
            config('app.google.api_key')
        );

        $response = $this->httpClient->get($url);
        $distance = $response['routes'][0]['legs'][0]['distance']['value'] ?? null;

        if (!$distance) {
            throw new RuntimeException('No distance information in the response');
        }

        $distance /= 1000;

        return new DistanceResponseDTO($distance);
    }

    public function getVehicleTypes(): array
    {
        $vehicleTypes = $this->repository->getVehicleTypes();

        return array_map(
            fn($vehicleType) => new VehicleTypeDTO(
                $vehicleType['cost_km'],
                $vehicleType['minimum'],
                $vehicleType['number']
            ),
            $vehicleTypes
        );
    }

    public function getCity(AddressDTO $addressDTO): array
    {
        return $this->repository->getCity($addressDTO);
    }
}
