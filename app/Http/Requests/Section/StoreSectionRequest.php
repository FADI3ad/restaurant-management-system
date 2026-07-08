<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
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
            'name' => 'required|max:255|min:3|unique:sections,name',
            'description' => 'nullable|max:1000',
            'display_order' => 'required|integer|min:0|unique:sections,display_order',
            'status' => 'required|boolean',
        ];
    }
}
