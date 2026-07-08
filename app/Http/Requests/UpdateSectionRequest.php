<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // For standard form request injection, we might need to extract the ID from the route.
        // But since we use Livewire and rulesArray statically mostly, we provide a default id.
        $id = $this->route('section') ? $this->route('section')->id : null;
        return self::rulesArray($id);
    }

    public static function rulesArray($id = null): array
    {
        return [
            'name' => 'required|max:255|min:3|unique:sections,name' . ($id ? ',' . $id : ''),
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ];
    }
}
