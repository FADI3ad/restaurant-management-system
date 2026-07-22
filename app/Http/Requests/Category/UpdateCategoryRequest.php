<?php

namespace App\Http\Requests\Category;

use Illuminate\Validation\Rule;
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
        return self::rulesArray($id, $this->section_id);
    }

    public static function rulesArray($id = null, ?int $sectionId = null): array
    {
        return [
            'name' => 'required|max:255|min:3|unique:categories,name' . ($id ? ',' . $id : ''),
            'description' => 'nullable|max:1000',
            'display_order' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('categories', 'display_order')
                    ->where(fn($query) => $query->where('section_id', $sectionId ?? request('section_id')))
                    ->ignore($id),
            ],
            'status' => 'required|boolean',
            'section_id' => 'required|exists:sections,id',
        ];
    }
}
