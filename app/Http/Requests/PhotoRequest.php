<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotoRequest extends FormRequest
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
            //
            'title' => ['required', 'max:191'],
            'photo' => ['required', 'file', 'image', 'mimes:jpeg,png', 'dimensions: min_width=1920,min_height=1080'],
            'tags' => ['required', 'max:191'],
        ];
    }
}
