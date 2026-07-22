<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::rulesArray($this->section_id);
    }
    public static function rulesArray(?int $sectionId = null): array
    {
        return [
            'name' => 'required|max:255|min:3|unique:categories,name',
            'description' => 'nullable|max:1000',

            'display_order' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('categories', 'display_order')
                    ->where('section_id', $sectionId),
            ],

            'status' => 'required|boolean',
            'section_id' => 'required|exists:sections,id',
        ];
    }
}
