<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
            'preview_image' => 'required|file',
            'price' => 'nullable|integer',
            'count' => 'nullable|integer',
            'is_published' => 'nullable|boolean',
            'user_id' => 'required|integer',
            'tags' => 'nullable|array',
            'colors' => 'nullable|array',
            'author_id' => 'nullable',
            'category_id' => 'nullable',
            'book_images' => 'nullable|array',
        ];
    }
}
