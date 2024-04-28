<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string'],
            'is_audio_article' => ['boolean'],
            'cover_image' => ['nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
            'audio_file' => ['required_if:is_audio_article,1', 'file', 'mimes:mp3,wav']
        ];
    }
}
