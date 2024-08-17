<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'is_active' => 'required|boolean',
            'title.*' => 'required|string',
            'short_content.*' => 'required|string',
            'content.*' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
