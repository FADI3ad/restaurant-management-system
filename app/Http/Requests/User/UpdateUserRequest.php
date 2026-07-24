<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public static function rulesArray(int $userId): array
    {
        return [
            'form.name' => 'required|string|max:255',
            'form.email' => 'required|email|max:255|unique:users,email,' . $userId,
            'form.phone' => 'nullable|string|max:20|unique:users,phone,' . $userId,
            'form.type' => 'required|string|in:admin,manager,cashier,waiter,kitchen',
            'form.password' => 'nullable|string|min:6',
        ];
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $userId,
            'type' => 'required|string|in:admin,manager,cashier,waiter,kitchen',
            'password' => 'nullable|string|min:6',
        ];
    }
}
