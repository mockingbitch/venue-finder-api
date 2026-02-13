<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for updating an existing venue.
 * Authorizes only admin users. All fields are optional (sometimes/required).
 */
class UpdateVenueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:65535'],
            'address' => ['nullable', 'string', 'max:500'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'capacity' => ['sometimes', 'integer', 'min:0'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string', 'url'],
        ];
    }
}
