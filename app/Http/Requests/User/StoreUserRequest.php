<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public static function rulesArray(): array
    {
        return [
            'form.name' => 'required|string|max:255',
            'form.email' => 'required|email|max:255|unique:users,email',
            'form.phone' => 'nullable|string|max:20|unique:users,phone',
            'form.type' => 'required|string|in:admin,manager,cashier,waiter,kitchen',
            'form.password' => 'required|string|min:6',
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'type' => 'required|string|in:admin,manager,cashier,waiter,kitchen',
            'password' => 'required|string|min:6',
        ];
    }
}
