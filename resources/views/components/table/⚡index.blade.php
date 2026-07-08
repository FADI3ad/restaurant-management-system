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
    <div class="tables-toolbar" id="tables-toolbar" role="toolbar" aria-label="فلاتر الطاولات">
        <span class="tables-toolbar-label">النوع</span>
        <div class="tables-toolbar-group" role="group">
            <button class="filter-chip is-active" id="chip-type-all" type="button">
                <span class="filter-chip-dot" style="background:var(--primary)"></span>الكل
            </button>
            <button class="filter-chip" id="chip-type-private" type="button">
                <span class="filter-chip-dot" style="background:var(--purple)"></span>خاص
            </button>
            <button class="filter-chip" id="chip-type-public" type="button">
                <span class="filter-chip-dot" style="background:var(--info)"></span>عام
            </button>
        </div>
        <div class="tables-toolbar-sep" aria-hidden="true"></div>
        <span class="tables-toolbar-label">الحالة</span>
        <div class="tables-toolbar-group" role="group">
            <button class="filter-chip is-active" id="chip-status-all" type="button">
                <span class="filter-chip-dot" style="background:var(--primary)"></span>الكل
            </button>
            <button class="filter-chip" id="chip-status-available" type="button">
                <span class="filter-chip-dot" style="background:var(--success)"></span>متاح
            </button>
            <button class="filter-chip" id="chip-status-occupied" type="button">
                <span class="filter-chip-dot" style="background:var(--danger)"></span>مشغول
            </button>
            <button class="filter-chip" id="chip-status-reserved" type="button">
                <span class="filter-chip-dot" style="background:var(--warning)"></span>محجوز
            </button>
            <button class="filter-chip" id="chip-status-maintenance" type="button">
                <span class="filter-chip-dot" style="background:var(--secondary)"></span>صيانة
            </button>
        </div>
    </div>

    <div class="tables-grid" id="tables-grid">
        @foreach($this->tables as $table)
            <div class="table-card" id="table-card-{{ $table->id }}">
                <div class="table-card-accent table-card-accent--{{ strtolower($table->status) }}"></div>
                <div class="table-card-body">
                    <div class="table-num-badge table-num-badge--{{ strtolower($table->status) }}">{{ $table->table_number }}</div>
                    <div class="table-card-info">
                        <div class="table-card-name">طاولة #{{ $table->table_number }}</div>
                        <div class="table-card-sub">
                            <span>{{ $table->type === 'Private' ? 'خاص' : 'عام' }}</span>
                            <span class="table-card-sub-dot">·</span>
                            <span>{{ $table->min_capacity }} – {{ $table->max_capacity }} أشخاص</span>
                        </div>
                    </div>
                    @if($table->status === 'Available')
                        <span class="tag t-active">متاح</span>
                    @elseif($table->status === 'Occupied')
                        <span class="tag t-inactive">مشغول</span>
                    @elseif($table->status === 'Reserved')
                        <span class="tag t-old">محجوز</span>
                    @else
                        <span class="tag" style="background:var(--secondary-soft); color:var(--secondary);">صيانة</span>
                    @endif
                </div>
                <div class="table-card-footer">
                    <div class="table-card-actions" style="margin-right:auto">
                        <button type="button" class="btn-action-icon btn--soft-primary" title="تعديل"
                            @click="await $wire.makeEditEvent({{ $table->id }}); editOpen = true;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z" />
                            </svg>
                        </button>
                        <button type="button" class="btn-action-icon btn--soft-danger" title="حذف"
                            @click="await $wire.makeDeleteEvent({{ $table->id }}); deleteOpen = true;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $this->tables->links() }}
</div>
