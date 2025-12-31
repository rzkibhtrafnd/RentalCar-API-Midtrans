<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Car;
use App\Services\CarService;
use App\Http\Resources\CarResource;

class CarController extends Controller
{
    private CarService $service;

    public function __construct(CarService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $filters = request()->only(['transmission', 'available_status']);

        $cars = $this->service->list($filters);

        return ApiResponse::success('Daftar mobil berhasil diambil', CarResource::collection($cars), 200);
    }

    public function show(Car $car)
    {
        return ApiResponse::success('Detail mobil berhasil diambil', new CarResource($car), 200);
    }
}
