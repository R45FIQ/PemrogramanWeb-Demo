<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', 'unique:media,title'],
            'type' => ['required', 'string', Rule::in(['Anime', 'Manga', 'Drama'])],
            'genre' => ['required', 'string', 'max:255'],
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
            'title.required' => 'Judul media wajib diisi.',
            'title.string' => 'Judul media harus berupa teks.',
            'title.max' => 'Judul media tidak boleh melebihi 255 karakter.',
            'title.unique' => 'Judul media sudah digunakan, gunakan judul yang berbeda.',
            'type.required' => 'Jenis media wajib diisi.',
            'type.in' => 'Jenis media harus salah satu dari: Anime, Manga, atau Drama.',
            'genre.required' => 'Genre media wajib diisi.',
            'description.string' => 'Deskripsi media harus berupa teks.',
            'release_year.integer' => 'Tahun rilis harus berupa angka tahun yang valid.',
            'rating.numeric' => 'Rating harus berupa angka desimal antara 0 hingga 10.',
        ];
    }
}
