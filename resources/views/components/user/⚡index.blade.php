<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $roleFilter = '';

    #[Computed]
    public function users()
    {
        return User::query()
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->when($this->roleFilter !== '', function ($query) {
                $query->where('type', $this->roleFilter);
            })
            ->latest()
            ->paginate(10);
    }

    #[On('user-changed')]
    public function refreshTable()
    {
        $this->users();
    }

    public function makeShowEvent($id)
    {
        $this->dispatch('show-user-details', $id);
    }

    public function makeEditEvent($id)
    {
        $this->dispatch('edit-user-details', $id);
    }

    public function makeDeleteEvent($id)
    {
        $this->dispatch('confirm-user-delete', $id);
    }
};
?>

<div>
    <div class="smart-filter-bar">
        <div class="filter-search">
            <div class="input-icon">
                <span class="ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                </span>
                <input type="text" class="input" placeholder="ابحث باسم المستخدم، البريد أو رقم الهاتف..."
                    wire:model.live.debounce.300ms="search" />
            </div>
        </div>
        <div class="filter-actions">
            <select class="select filter-select" wire:model.live="roleFilter">
                <option value="">جميع الصلاحيات / الأدوار</option>
                <option value="admin">مدير النظام (Admin)</option>
                <option value="manager">مدير المطعم (Manager)</option>
                <option value="cashier">كاشير (Cashier)</option>
                <option value="waiter">صالة / ويتر (Waiter)</option>
                <option value="kitchen">مطبخ / شيف (Kitchen)</option>
            </select>
            <button type="button" class="btn btn-filter" wire:click="$reset('search', 'roleFilter')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                إعادة ضبط
            </button>
        </div>
    </div>
    <div class="table-scroll">
        <div style="overflow-x: auto; width: 100%;">
            <table class="table">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الدور / الصلاحية</th>
                        <th>تاريخ الإضافة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->users as $user)
                        <tr>
                            <td class="cell-name">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--primary, #e63946), #d62828); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $user->name }}</div>
                                        @if(Auth::id() === $user->id)
                                            <span style="font-size: 11px; color: var(--primary, #e63946); font-weight: 700;">(حسابك الحالي)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td>
                                @switch($user->type)
                                    @case('admin')
                                        <span class="tag t-active" style="background: rgba(230, 57, 70, 0.15); color: #e63946; border: 1px solid rgba(230, 57, 70, 0.3);">مدير النظام</span>
                                        @break
                                    @case('manager')
                                        <span class="tag" style="background: rgba(13, 110, 253, 0.15); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.3);">مدير المطعم</span>
                                        @break
                                    @case('cashier')
                                        <span class="tag" style="background: rgba(25, 135, 84, 0.15); color: #198754; border: 1px solid rgba(25, 135, 84, 0.3);">كاشير</span>
                                        @break
                                    @case('waiter')
                                        <span class="tag" style="background: rgba(255, 193, 7, 0.15); color: #b58100; border: 1px solid rgba(255, 193, 7, 0.3);">صالة / ويتر</span>
                                        @break
                                    @case('kitchen')
                                        <span class="tag" style="background: rgba(108, 117, 125, 0.15); color: #495057; border: 1px solid rgba(108, 117, 125, 0.3);">مطبخ / شيف</span>
                                        @break
                                    @default
                                        <span class="tag t-inactive">{{ $user->type }}</span>
                                @endswitch
                            </td>
                            <td>{{ $user->created_at ? $user->created_at->format('Y-m-d') : '—' }}</td>
                            <td>
                                <div class="data-cell-actions">
                                    <button type="button" class="btn-action-icon btn--soft-info" title="عرض التفاصيل"
                                        @click="await $wire.makeShowEvent({{ $user->id }}); showOpen = true;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-action-icon btn--soft-primary" title="تعديل"
                                        @click="await $wire.makeEditEvent({{ $user->id }}); editOpen = true;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    @if(Auth::id() !== $user->id)
                                        <button type="button" class="btn-action-icon btn--soft-danger" title="حذف"
                                            @click="await $wire.makeDeleteEvent({{ $user->id }}); deleteOpen = true;">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted, #6c757d);">
                                لا يوجد مستخدمين مضافين حالياً.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $this->users()->links() }}
</div>
