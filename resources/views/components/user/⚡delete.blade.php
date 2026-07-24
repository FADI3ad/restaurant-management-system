<?php

use App\Models\User;
use App\Services\User\DeleteUserAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?User $user = null;

    #[On('confirm-user-delete')]
    public function loadUserData(User $user)
    {
        $this->user = $user;
    }

    public function delete(DeleteUserAction $deleteUser)
    {
        if (!$this->user) {
            return;
        }

        // Prevent self deletion
        if (Auth::id() === $this->user->id) {
            $this->addError('delete', 'لا يمكنك حذف حسابك الحالي المسجل به الدخول!');
            return;
        }

        $deleteUser($this->user);

        $this->dispatch('close-delete-modal');
        $this->dispatch('user-changed');
    }
};
?>

<div id="modal-delete" class="modal-overlay is-active" x-show="deleteOpen" x-cloak @click.self="deleteOpen = false;"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-sm">
        <x-modal-head-component title="تأكيد حذف الحساب" />

        <div class="modal-body">
            @if ($user)
                <p style="font-size: 15px; line-height: 1.5; color: var(--text-color, #2b2d42);">
                    هل أنت تأكد من رغبتك في حذف حساب المستخدم <strong>"{{ $user->name }}"</strong> ({{ $user->email }})؟
                </p>
                <p style="font-size: 13px; color: var(--danger, #dc3545); margin-top: 8px;">
                    تنبيه: هذا الإجراء لا يمكن التراجع عنه وسينتج عنه إلغاء صلاحية الوصول لهذه الكوادر.
                </p>

                @error('delete')
                    <div class="field-error" style="margin-top: 10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            @endif
        </div>

        <div class="modal-foot">
            <button type="button" class="btn btn--ghost" @click="deleteOpen = false">
                إلغاء
            </button>
            <button type="button" class="btn btn--danger" wire:click="delete" @close-delete-modal.window="deleteOpen = false">
                تأكيد الحذف
            </button>
        </div>
    </div>
</div>
