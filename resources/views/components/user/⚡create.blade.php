<?php

use App\Http\Requests\User\StoreUserRequest;
use App\Livewire\Forms\UserForm;
use App\Services\User\CreateUserAction;
use Livewire\Component;

new class extends Component {
    public UserForm $form;

    public function save(CreateUserAction $createUser)
    {
        $validated = $this->form->validate(StoreUserRequest::rulesArray());

        $createUser($validated['form']);

        $this->dispatch('close-add-modal');
        $this->dispatch('user-changed');
        $this->form->reset();
    }
};
?>

<div id="modal-add" class="modal-overlay is-active" x-show="addOpen" x-cloak @click.self="addOpen = false;"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="إضافة حساب جديد (إنشاء حساب عامل)" />
        <form id="form-add" wire:submit.prevent="save">
            <div class="modal-body modal-form-grid">
                <div class="field span-2">
                    <label class="field-label">اسم المستخدم / الموظف <span class="req">*</span></label>
                    <input wire:model="form.name" type="text" class="input"
                        placeholder="مثال: أحمد محمد">
                    @error('form.name')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">البريد الإلكتروني <span class="req">*</span></label>
                    <input wire:model="form.email" type="email" class="input"
                        placeholder="user@example.com">
                    @error('form.email')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">رقم الهاتف</label>
                    <input wire:model="form.phone" type="text" class="input"
                        placeholder="01012345678">
                    @error('form.phone')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field span-2">
                    <label class="field-label">الدور / الصلاحية (Role) <span class="req">*</span></label>
                    <select wire:model="form.type" class="select">
                        <option value="admin">مدير النظام (Admin) - صلاحية كاملة</option>
                        <option value="manager">مدير المطعم (Manager)</option>
                        <option value="cashier">كاشير (Cashier)</option>
                        <option value="waiter">صالة / ويتر (Waiter)</option>
                        <option value="kitchen">مطبخ / شيف (Kitchen)</option>
                    </select>
                    @error('form.type')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field span-2">
                    <label class="field-label">كلمة المرور <span class="req">*</span></label>
                    <input wire:model="form.password" type="password" class="input"
                        placeholder="••••••••">
                    @error('form.password')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="addOpen = false">
                    إلغاء
                </button>
                <button type="submit" class="btn btn--primary" @close-add-modal.window="addOpen = false">
                    إنشاء الحساب
                </button>
            </div>
        </form>
    </div>
</div>
