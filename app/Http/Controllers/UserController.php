<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\User\CreateUserAction;
use App\Services\User\UpdateUserAction;
use App\Services\User\DeleteUserAction;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Operation successful'
        ]);
    }

    public function store(StoreUserRequest $request, CreateUserAction $action)
    {
        $user = $action($request->validated());

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'تم إضافة المستخدم بنجاح'
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Operation successful'
        ]);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action)
    {
        $action($user, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $user->fresh(),
            'message' => 'تم تعديل المستخدم بنجاح'
        ]);
    }

    public function destroy(User $user, DeleteUserAction $action)
    {
        $action($user);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'تم حذف المستخدم بنجاح'
        ]);
    }
}
