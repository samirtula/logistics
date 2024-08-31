<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CalculatePriceRequest;
use App\Services\LogisticService;
use Exception;
use Illuminate\Http\JsonResponse;

class LogisticController extends Controller
{
    public function __construct(
        private readonly LogisticService $logisticService,
    ) {
    }

    public function calculate(CalculatePriceRequest $request): JsonResponse
    {
        try {
            $request->validateResolved();
            $addresses = $request->json('addresses');

            $vehicleTypes = $this->logisticService->getVehicleTypes();
            $totalDistance = $this->logisticService->calculateTotalDistance($addresses);
            $prices = $this->logisticService->calculatePrices($totalDistance, $vehicleTypes);

            return response()
                ->json([
                    'total_distance' => round($totalDistance, 2),
                    'prices' => $prices,
                ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
