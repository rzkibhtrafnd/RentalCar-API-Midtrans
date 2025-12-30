<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name'      => ['nullable', 'string', 'max:255'],
            'email'     => ['nullable', 'email', Rule::unique('users')->ignore($this->user()->id)],
            'phone'     => ['nullable', 'string', 'max:20'],
            'NIK'       => ['nullable', 'string', 'max:20'],
            'address'   => ['nullable', 'string'],
            'city'      => ['nullable', 'string', 'max:255'],
            'province'  => ['nullable', 'string', 'max:255'],
        ];
    }
}
