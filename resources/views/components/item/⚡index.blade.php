<?php

use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[Computed]
    public function items()
    {
        return Item::with('subcategory.category')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('subcategory', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('display_order')
            ->paginate(15);
    }

    #[On('item-changed')]
    public function refreshTable()
    {
        $this->items();
    }

    public function makeShowEvent($id)
    {
        $this->dispatch('show-item-details', $id);
    }

    public function makeEditEvent($id)
    {
        $this->dispatch('edit-item-details', $id);
    }

    public function makeDeleteEvent($id)
    {
        $this->dispatch('confirm-item-delete', $id);
    }
};
?>

<div>
    <div class="smart-filter-bar" style="margin-bottom: 20px;">
        <div class="filter-search" style="flex: 1;">
            <div class="input-icon">
                <span class="ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" class="input" placeholder="ابحث عن وجبة أو صنف فرعي...">
            </div>
        </div>
    </div>

    <div class="table-scroll">
        <div style="overflow-x: auto; width: 100%;">
            <table class="table">
                <thead>
                    <tr>
                        <th>الصورة</th>
                        <th>اسم الوجبة</th>
                        <th>الصنف الفرعي</th>
                        <th>السعر</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->items as $item)
                        <tr>
                            <td>
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover;">
                                @else
                                    <div style="width: 44px; height: 44px; border-radius: 8px; background: var(--b-card); display: flex; align-items: center; justify-content: center; color: var(--t-light);">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="cell-name">{{ $item->name }}</td>
                            <td>
                                @if($item->subcategory)
                                    <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: #6366f1; padding: 4px 8px; border-radius: 6px; font-weight: 500;">
                                        {{ $item->subcategory->name }}
                                    </span>
                                @else
                                    <span style="color: var(--t-light);">-</span>
                                @endif
                            </td>
                            <td><strong>{{ number_format($item->price, 2) }} ر.س</strong></td>
                            <td>
                                <div class="order-controls">
                                    <span class="badge-order">{{ $item->display_order }}</span>
                                </div>
                            </td>
                            @if ($item->status)
                                <td><span class="tag t-active">نشط</span></td>
                            @else
                                <td><span class="tag t-inactive">غير نشط</span></td>
                            @endif
                            <td>
                                <div class="data-cell-actions">
                                    <button type="button" class="btn-action-icon btn--soft-info" title="عرض التفاصيل"
                                        @click="await $wire.makeShowEvent({{ $item->id }}); showOpen = true;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-action-icon btn--soft-primary" title="تعديل"
                                        @click="await $wire.makeEditEvent({{ $item->id }}); editOpen = true;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-action-icon btn--soft-danger" title="حذف"
                                        @click="await $wire.makeDeleteEvent({{ $item->id }}); deleteOpen = true;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--t-light);">
                                لا يوجد وجبات مضافة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div style="margin-top: 16px;">
        {{ $this->items()->links() }}
    </div>
</div>
