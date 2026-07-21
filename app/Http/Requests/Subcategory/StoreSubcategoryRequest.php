<?php

namespace App\Http\Requests\Subcategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubcategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::rulesArray();
    }

    public static function rulesArray(): array
    {
        return [
            'name' => 'required|max:255|min:3|unique:subcategories,name',
            'section_id' => 'nullable|exists:sections,id',
            'description' => 'nullable|max:1000',
            'display_order' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
