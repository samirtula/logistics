<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Client\LogisticHTTPClient;
use App\Repositories\LogisticRepository;
use App\Tools\LogisticTool;

class LogisticService
{
    public function __construct(
        private readonly LogisticRepository $repository,
        private readonly LogisticHTTPClient $httpClient
    ) {
    }

    public function calculateTotalDistance(array $addresses): float
    {
        $origin = $addresses[0];
        $destinations = array_slice($addresses, 1);
        $totalDistance = 0.0;

        foreach ($destinations as $destination) {
            $data = $this->fetchDistance($origin, $destination);
            $distance = $data['routes'][0]['legs'][0]['distance']['value'] / 1000.0;
            $totalDistance += $distance;
            $origin = $destination;
        }

        return $totalDistance;
    }

    public function calculatePrices(float $totalDistance, array $vehicleTypes): array
    {
        $prices = [];
        foreach ($vehicleTypes as $vehicleType) {
            $price = max(
                $totalDistance * LogisticTool::getCostPerKm($vehicleType),
                LogisticTool::getMinimumPrice($vehicleType)
            );
            $prices[] = [
                'vehicle_type' => LogisticTool::getVehicleTypeNumber($vehicleType),
                'price' => round($price, 2),
            ];
        }

        return $prices;
    }

    private function fetchDistance(array $origin, array $destination): array
    {
        $originFormatted = LogisticTool::formatAddress($origin);
        $destinationFormatted = LogisticTool::formatAddress($destination);

        $url = sprintf(
            '%s?origin=%s&destination=%s&key=%s',
            config('app.google.maps_uri'),
            urlencode($originFormatted),
            urlencode($destinationFormatted),
            config('app.google.api_key')
        );

        return $this->httpClient->get($url);
    }

    public function getVehicleTypes(): array
    {
        return $this->repository->getVehicleTypes();
    }

    public function getCity(string $country, string $zip, string $city): array
    {
        return $this->repository->getCity($country, $zip, $city);
    }
}
