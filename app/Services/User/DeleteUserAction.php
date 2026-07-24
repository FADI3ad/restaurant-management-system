<?php

namespace App\Services\User;

use App\Models\User;

class DeleteUserAction
{
    public function __invoke(User $user): bool
    {
        return $user->delete();
    }
}
