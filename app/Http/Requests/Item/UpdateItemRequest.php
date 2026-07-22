<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('item') ? $this->route('item')->id : null;
        return self::rulesArray($id);
    }

    public static function rulesArray($id = null): array
    {
        return [
            'name' => 'required|max:255|min:3|unique:items,name' . ($id ? ',' . $id : ''),
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|max:1000',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
            'image' => 'nullable|image|max:3072',
        ];
    }
}
