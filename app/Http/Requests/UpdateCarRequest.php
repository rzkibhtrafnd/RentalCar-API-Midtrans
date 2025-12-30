<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'            => 'sometimes|string|max:255',
            'plate_number'    => 'sometimes|string|unique:cars,plate_number,' . $this->car?->id,
            'seat_count'      => 'sometimes|integer',
            'transmission'    => 'sometimes|in:manual,auto',
            'price_per_day'   => 'sometimes|numeric|min:0',
            'available_status'=> 'sometimes|in:ready,booked,maintenance',
            'images'          => 'nullable|array',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'delete_images'   => 'sometimes|array',
            'delete_images.*' => 'integer',
        ];
    }
}
