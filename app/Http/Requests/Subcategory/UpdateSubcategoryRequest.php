<?php

namespace App\Http\Requests\Subcategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubcategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('subcategory') ? $this->route('subcategory')->id : null;
        return self::rulesArray($id);
    }

    public static function rulesArray($id = null): array
    {
        return [
            'name' => 'required|max:255|min:3|unique:subcategories,name' . ($id ? ',' . $id : ''),
            'section_id' => 'nullable|exists:sections,id',
            'description' => 'nullable|max:1000',
            'display_order' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
