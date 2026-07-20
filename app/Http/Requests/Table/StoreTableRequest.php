<?php

namespace App\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public static function rulesArray()
    {
        return [
            'number' => 'required|string|max:255|unique:tables,number',
            'type' => 'required|in:Public,Private',
            'min_capacity' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|gte:min_capacity',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Available,Maintenance',
            'notes' => 'nullable|string',
        ];
    }

    public function rules(): array
    {
        return self::rulesArray();
    }
}
