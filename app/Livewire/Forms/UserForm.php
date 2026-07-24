<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user = null;

    public ?string $name = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $type = 'cashier';

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function setData(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->type = $user->type;
        $this->password = null;
        $this->password_confirmation = null;
    }
}
