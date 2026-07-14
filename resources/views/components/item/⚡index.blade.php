<?php

use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public function mount()
    {
        $this->items();
    }

    #[Computed]
    public function items()
    {
        return Item::with('subcategory')->orderBy('display_order')->paginate(15);
    }

    #[On('item-changed')]
    public function refreshTable()
    {
        $this->items();
    }

    //Handle Show Item Details
    public function makeShowEvent($id)
    {
        $this->dispatch('show-item-details', $id);
    }

    //Handle Edit Item
    public function makeEditEvent($id)
    {
        $this->dispatch('edit-item-details', $id);
    }

    //Handle Delete Item
    public function makeDeleteEvent($id)
    {
        $this->dispatch('confirm-item-delete', $id);
    }
};
?>

<div>
    <div class="table-scroll">
        <div style="overflow-x: auto; width: 100%;"><table class="table">
            <thead>
                <tr>
                    <th>اسم الوجبة</th>
                    <th>الصنف الفرعي</th>
                    <th>السعر</th>
                    <th>الترتيب</th>
                    <th>الحالة</th>
                    <th>العمليات</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($this->items as $item)
                    <tr>
                        <td class="cell-name">{{ $item->name }}</td>
                        <td>{{ $item->subcategory->name ?? '-' }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
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
                                    <svg viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-icon btn--soft-primary" title="تعديل"
                                    @click="await $wire.makeEditEvent({{ $item->id }}); editOpen = true;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-icon btn--soft-danger" title="حذف"
                                    @click="await $wire.makeDeleteEvent({{ $item->id }}); deleteOpen = true;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table></div>
    </div>
    {{ $this->items()->links() }}
</div>
