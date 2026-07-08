<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category') ? $this->route('category')->id : null;
        return self::rulesArray($id);
    }

    public static function rulesArray($id = null): array
    {
        return [
            'name' => 'required|max:255|min:3|unique:categories,name' . ($id ? ',' . $id : ''),
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
            'section_id' => 'required|exists:sections,id',
        ];
    }
}
