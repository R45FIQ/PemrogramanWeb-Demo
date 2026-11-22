<?php

namespace App\Http\Requests;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediaRequest extends FormRequest
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
        $routeParam = $this->route('medium') ?? $this->route('media');
        $mediaId = $routeParam instanceof Media ? $routeParam->id : $routeParam;

        return [
            'title' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('media', 'title')->ignore($mediaId),
            ],
            'type' => [
                'sometimes',
                'required',
                'string',
                Rule::in(['Anime', 'Manga', 'Drama']),
            ],
            'genre' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'release_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 5)],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'episodes' => ['nullable', 'integer', 'min:1'],
            'chapters' => ['nullable', 'integer', 'min:1'],
            'author' => ['nullable', 'string', 'max:255'],
            'studio' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi jika disertakan.',
            'title.string' => 'Judul media harus berupa teks.',
            'title.max' => 'Judul media tidak boleh melebihi 255 karakter.',
            'title.unique' => 'Judul media sudah digunakan oleh data media lain.',
            'type.in' => 'Jenis media harus salah satu dari: Anime, Manga, atau Drama.',
            'release_year.integer' => 'Tahun rilis harus berupa angka tahun yang valid.',
            'rating.numeric' => 'Rating harus berupa angka desimal antara 0 hingga 10.',
        ];
    }
}
