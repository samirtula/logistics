<?php

declare(strict_types=1);

namespace Tests;

use App\Http\Controllers\LogisticController;
use App\Http\Requests\CalculatePriceRequest;
use App\Services\LogisticService;
use Exception;
use Illuminate\Http\JsonResponse;
use Mockery;
use PHPUnit\Framework\TestCase;

class LogisticControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCalculateSuccess()
    {
        $request = Mockery::mock(CalculatePriceRequest::class);
        $request->shouldReceive('validateResolved')->once();
        $request->shouldReceive('json')->with('addresses')->andReturn([
            'address1',
            'address2',
        ]);
        $logisticService = Mockery::mock(LogisticService::class);
        $logisticService->shouldReceive('getVehicleTypes')->andReturn([
            ['type' => 'car', 'price_per_km' => 1.2],
            ['type' => 'truck', 'price_per_km' => 2.5],
        ]);

        $logisticService->shouldReceive('calculateTotalDistance')
            ->with(['address1', 'address2'])
            ->andReturn(100.0);

        $logisticService->shouldReceive('calculatePrices')
            ->with(100.0, [
                ['type' => 'car', 'price_per_km' => 1.2],
                ['type' => 'truck', 'price_per_km' => 2.5],
            ])
            ->andReturn([
                ['type' => 'car', 'price' => 120.0],
                ['type' => 'truck', 'price' => 250.0],
            ]);


        $controller = new LogisticController($logisticService);
        $response = $controller->calculate($request);
        $data = $response->getData(true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(100.0, $data['total_distance']);
        $this->assertEquals([
            ['type' => 'car', 'price' => 120.0],
            ['type' => 'truck', 'price' => 250.0],
        ], $data['prices']);
    }

    public function testCalculateFailure()
    {
        $request = Mockery::mock(CalculatePriceRequest::class);
        $request->shouldReceive('validateResolved')->once();
        $request->shouldReceive('json')->with('addresses')->andThrow(new Exception('Invalid addresses'));

        $logisticService = Mockery::mock(LogisticService::class);
        $controller = new LogisticController($logisticService);
        $response = $controller->calculate($request);
        $data = $response->getData(true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('Error', $data['error']);
        $this->assertEquals('Invalid addresses', $data['message']);
    }
}
