<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?User $user = null;

    #[On('show-user-details')]
    public function loadUserData(User $user)
    {
        $this->user = $user;
    }
};
?>

<div id="modal-show" class="modal-overlay is-active" x-show="showOpen" x-cloak @click.self="showOpen = false;"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="تفاصيل حساب المستخدم" />

        <div class="modal-body">
            @if ($user)
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color, #e2e8f0);">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--primary, #e63946), #d62828); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px;">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 18px; font-weight: 700;">{{ $user->name }}</h3>
                            <p style="margin: 4px 0 0 0; color: var(--text-muted, #6c757d); font-size: 14px;">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
                        <div>
                            <strong>رقم الهاتف:</strong>
                            <div style="margin-top: 4px;">{{ $user->phone ?? 'غير محدد' }}</div>
                        </div>
                        <div>
                            <strong>الدور / الصلاحية:</strong>
                            <div style="margin-top: 4px;">
                                @switch($user->type)
                                    @case('admin')
                                        <span class="tag t-active">مدير النظام (Admin)</span>
                                        @break
                                    @case('manager')
                                        <span class="tag">مدير المطعم (Manager)</span>
                                        @break
                                    @case('cashier')
                                        <span class="tag">كاشير (Cashier)</span>
                                        @break
                                    @case('waiter')
                                        <span class="tag">صالة / ويتر (Waiter)</span>
                                        @break
                                    @case('kitchen')
                                        <span class="tag">مطبخ / شيف (Kitchen)</span>
                                        @break
                                    @default
                                        <span class="tag">{{ $user->type }}</span>
                                @endswitch
                            </div>
                        </div>
                        <div>
                            <strong>تاريخ إنشاء الحساب:</strong>
                            <div style="margin-top: 4px;">{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '—' }}</div>
                        </div>
                        <div>
                            <strong>آخر تحديث:</strong>
                            <div style="margin-top: 4px;">{{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i') : '—' }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="modal-foot">
            <button type="button" class="btn btn--ghost" @click="showOpen = false">
                إغلاق
            </button>
        </div>
    </div>
</div>
