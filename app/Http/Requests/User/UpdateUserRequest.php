<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public static function rulesArray($userId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($userId ? ',' . $userId : ''),
            'phone' => 'nullable|string|max:20|unique:users,phone' . ($userId ? ',' . $userId : ''),
            'type' => 'required|string|in:admin,manager,cashier,waiter,kitchen',
            'password' => 'nullable|string|min:6',
        ];
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;
        return self::rulesArray($userId);
    }
}
