<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('section') ? $this->route('section')->id : null;
        return self::rulesArray($id);
    }

    public static function rulesArray($id = null): array
    {
        return [
            'name' => 'required|max:255|min:3|unique:sections,name' . ($id ? ',' . $id : ''),
            'description' => 'nullable|max:1000',
            'display_order' => 'required|integer|min:0|unique:sections,display_order' . ($id ? ',' . $id : ''),
            'status' => 'required|boolean',
        ];
    }
}
