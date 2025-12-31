<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Support\Facades\Storage;

class CarService
{
    public function list(array $filters = [])
    {
        return Car::query()
            ->transmission($filters['transmission'] ?? null)
            ->latest()
            ->paginate(6);
    }

    public function store(array $data)
    {
        $data['available_status'] = $data['available_status'] ?? 'ready';
        
        $images = [];
        if(!empty($data['images'])){
            foreach($data['images'] as $img){
                $path = $img->store('cars', 'public');
                $images[] = $path;
            }
        }

        $data['image'] = $images;
        unset($data['images']);

        return Car::create($data);
    }

    public function update(Car $car, array $data)
    {
        // Delete File tertentu
        $existingImages = $car->image ?? [];

        if (!empty($data['delete_images'])) {
            foreach ($data['delete_images'] as $index) {
                if (isset($existingImages[$index])) {
                    Storage::disk('public')->delete($existingImages[$index]);
                    unset($existingImages[$index]);
                }
            }
            $existingImages = array_values($existingImages);
        }

        unset($data['delete_images']);

        // Upload gambar
        if (!empty($data['images'])) {
            foreach ($data['images'] as $img) {
                $path = $img->store('cars', 'public');
                $existingImages[] = $path;
            }
        }

        unset($data['images']);

        $data['image'] = $existingImages;
        
        $car->update($data);

        return $car->fresh();
    }

    public function delete(Car $car)
    {
        if (!empty($car->image)) {
            foreach ($car->image as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        return $car->delete();
    }
}