<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'name'            => 'required|string|max:255',
            'plate_number'    => 'required|string|unique:cars,plate_number',
            'seat_count'      => 'required|integer',
            'transmission'    => 'required|in:manual,auto',
            'price_per_day'   => 'required|numeric|min:0',
            'available_status'=> 'nullable|in:ready,booked,maintenance',
            'images'          => 'nullable|array',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ];
    }
}
