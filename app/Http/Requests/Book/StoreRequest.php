<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'note' => 'required|string',
            'preview_image' => 'nullable|file',
            'category_id' => 'nullable',
            'is_published' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'author_id' => 'nullable',
            'book_images' => 'nullable|array',

        ];
    }
}
