<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Car;
use App\Services\CarService;

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

        return ApiResponse::success('Daftar mobil berhasil diambil', $cars, 200);
    }

    public function store(StoreCarRequest $request)
    {
        $car = $this->service->store($request->validated());
        return ApiResponse::success('Mobil berhasil ditambahkan', $car);
    }

    public function show(Car $car)
    {
        return ApiResponse::success('Detail mobil berhasil diambil', $car, 200);
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $updated = $this->service->update($car, $request->validated());
        return ApiResponse::success('Mobil berhasil diperbarui', $updated, 200);
    }

    public function destroy(Car $car)
    {
        $this->service->delete($car);
        return ApiResponse::success('Mobil berhasil dihapus', null, 200);
    }
}
