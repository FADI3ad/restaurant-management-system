<?php

use App\Models\Table;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public function mount()
    {
        $this->tables();
    }

    #[Computed]
    public function tables()
    {
        return Table::orderBy('table_number')->paginate(15);
    }

    #[On('table-changed')]
    public function refreshTable()
    {
        $this->tables();
    }

    public function makeShowEvent($id)
    {
        $this->dispatch('show-table-details', $id);
    }

    public function makeEditEvent($id)
    {
        $this->dispatch('edit-table-details', $id);
    }

    public function makeDeleteEvent($id)
    {
        $this->dispatch('confirm-table-delete', $id);
    }
};
?>

<div>
    <div class="tables-grid" id="tables-grid">
        @foreach ($this->tables as $table)
            <div class="table-card">
                <div class="table-card-header table-card-header--{{ strtolower($table->status) }}">
                    <div class="table-card-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="3" rx="1"></rect>
                            <path d="M5 14v5"></path>
                            <path d="M19 14v5"></path>
                            <path d="M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"></path>
                        </svg>
                    </div>
                    <span class="table-card-header-title">طاولة #{{ $table->table_number }}</span>
                </div>

                <div class="table-card-body">
                    <div class="table-card-info">
                        <div class="table-card-name">{{ $table->type === 'Private' ? 'خاص' : 'عام' }}</div>
                        <div class="table-card-sub">
                            <span>{{ $table->min_capacity }} – {{ $table->max_capacity }} أشخاص</span>
                        </div>
                    </div>
                    @if ($table->status === 'Available')
                        <span class="tag t-active">متاح</span>
                    @elseif($table->status === 'Occupied')
                        <span class="tag t-inactive">مشغول</span>
                    @elseif($table->status === 'Reserved')
                        <span class="tag t-old">محجوز</span>
                    @else
                        <span class="tag"
                            style="background:var(--secondary-soft); color:var(--secondary);">صيانة</span>
                    @endif
                </div>

                <div class="table-card-footer">
                    <div class="table-card-actions">
                        {{-- QR Code --}}
                        <button type="button" class="btn-action-icon btn--soft-secondary" title="QR Code">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7" />
                                <rect x="14" y="3" width="7" height="7" />
                                <rect x="3" y="14" width="7" height="7" />
                                <path d="M14 14h.01M18 14h.01M14 18h.01M18 18h.01M14 21h.01M21 14v7" />
                            </svg>
                        </button>
                        {{-- Show Details --}}
                        <button type="button" class="btn-action-icon btn--soft-info" title="عرض التفاصيل"
                            @click="await $wire.makeShowEvent({{ $table->id }}); showOpen = true;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                        {{-- Edit --}}
                        <button type="button" class="btn-action-icon btn--soft-primary" title="تعديل"
                            @click="await $wire.makeEditEvent({{ $table->id }}); editOpen = true;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z" />
                            </svg>
                        </button>

                        {{-- Delete --}}
                        <button type="button" class="btn-action-icon btn--soft-danger" title="حذف"
                            @click="await $wire.makeDeleteEvent({{ $table->id }}); deleteOpen = true;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6" />
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2 2h4a2 2 0 0 1 2 2v2" />
                            </svg>
                        </button>


                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $this->tables->links() }}
</div>
