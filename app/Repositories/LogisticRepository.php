<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\AddressDTO;
use App\Repositories\interfaces\LogisticRepositoryInterface;
use App\Tools\LogisticTool;
use Predis\Client as RedisClient;
use Illuminate\Support\Facades\Log;
use MongoDB\Client;
use Throwable;

class LogisticRepository implements LogisticRepositoryInterface
{
    private const VEHICLE_TYPES_COLLECTION = 'vehicleTypes';
    private const CITIES_TYPES_COLLECTION = 'cities';
    private const VEHICLE_TYPES_CACHE_KEY = 'vehicle_types';
    private const CACHE_TTL = 3600;

    public function __construct(
        private readonly Client $mongoClient,
        private readonly RedisClient $redis
    ) {
    }

    public function getVehicleTypes(): array
    {
        try {
            $cachedData = $this->redis->get(self::VEHICLE_TYPES_CACHE_KEY);

            if ($cachedData !== null) {
                return json_decode($cachedData, true);
            }

            $database = $this->mongoClient->selectDatabase(config('database.mongo.db'));
            $vehicleTypes = $database
                ->selectCollection(self::VEHICLE_TYPES_COLLECTION, LogisticTool::getArrayTypeMap())
                ->find()
                ->toArray();

            $this->redis->setex(self::VEHICLE_TYPES_CACHE_KEY, self::CACHE_TTL, json_encode($vehicleTypes));

            return $vehicleTypes;
        } catch (Throwable $e) {
            Log::channel('logistic_storage')->error('Error fetching cities: ') . $e->getMessage();
            return [];
        }
    }

    public function getCity(AddressDTO $addressDTO): array
    {
        try {
            $database = $this->mongoClient->selectDatabase(config('database.mongo.db'));
             return $database
                ->selectCollection(self::CITIES_TYPES_COLLECTION, LogisticTool::getArrayTypeMap())
                ->findOne([
                    'country' => $addressDTO->getCountry(),
                    'zipCode' => $addressDTO->getZip(),
                    'name' => $addressDTO->getCity(),
                ]);
        } catch (Throwable $e) {
            Log::channel('logistic_storage')->error('Error fetching city: ') . $e->getMessage();
            return [];
        }
    }
}
