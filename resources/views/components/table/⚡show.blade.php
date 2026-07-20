<?php

use App\Models\Table;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public ?Table $table = null;

    #[On('show-table-details')]
    public function getTableDetails($id)
    {
        $this->table = Table::findOrFail($id);
    }
};
?>

<div id="modal-show" class="modal-overlay is-active" x-show="showOpen" x-cloak @click.self="showOpen = false;"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="تفاصيل الطاولة" />

        <div class="modal-body">
            @if ($this->table)
                <div style="text-align: center; padding: 8px 0 20px;">
                    <div
                        style="background: var(--primary-soft); color: var(--primary); width: 72px; height: 72px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                            stroke-linecap="round" stroke-linejoin="round" style="width: 36px; height: 36px;">
                            <rect x="3" y="11" width="18" height="3" rx="1"></rect>
                            <path d="M5 14v5"></path>
                            <path d="M19 14v5"></path>
                            <path d="M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"></path>
                        </svg>
                    </div>
                    <div style="font-size: 22px; font-weight: 800; color: var(--t-base); letter-spacing: -0.5px;">
                        طاولة #{{ $this->table->number }}
                    </div>
                    <div style="margin-top: 6px;">
                        @if ($this->table->status === 'Available')
                            <span class="tag t-active">متاح</span>
                        @elseif ($this->table->status === 'Maintenance')
                            <span class="tag"
                                style="background:var(--danger-soft); color:var(--secondary);">صيانة
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Details Grid --}}
                <div class="show-details-grid">

                    <div class="show-detail-item">
                        <div class="show-detail-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path
                                    d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83">
                                </path>
                            </svg>
                            النوع
                        </div>
                        <div class="show-detail-value">
                            {{ $this->table->type === 'Private' ? 'خاص (Private)' : 'عام (Public)' }}</div>
                    </div>

                    <div class="show-detail-item">
                        <div class="show-detail-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            السعة
                        </div>
                        <div class="show-detail-value">{{ $this->table->min_capacity }} –
                            {{ $this->table->max_capacity }} أشخاص</div>
                    </div>

                    <div class="show-detail-item">
                        <div class="show-detail-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            الموقع
                        </div>
                        <div class="show-detail-value">{{ $this->table->location ?? '—' }}</div>
                    </div>

                    <div class="show-detail-item">
                        <div class="show-detail-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            تاريخ الإضافة
                        </div>
                        <div class="show-detail-value">{{ $this->table->created_at?->format('Y/m/d') ?? '—' }}</div>
                    </div>

                    <div class="show-detail-item">
                        <div class="show-detail-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            آخر تحديث
                        </div>
                        <div class="show-detail-value">{{ $this->table->updated_at?->format('Y/m/d') ?? '—' }}</div>
                    </div>

                    @if ($this->table->notes)
                        <div class="show-detail-item show-detail-item--full">
                            <div class="show-detail-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                ملاحظات
                            </div>
                            <div class="show-detail-value show-detail-notes">{{ $this->table->notes }}</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="modal-foot">
            <button type="button" class="btn btn--ghost" @click="showOpen = false">إغلاق</button>
            <button type="button" class="btn btn--primary"
                @click="showOpen = false; await $wire.$parent.makeEditEvent({{ $this->table?->id }}); editOpen = true;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    style="width:15px;height:15px;margin-inline-end:6px;">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z" />
                </svg>
                تعديل
            </button>
        </div>
    </div>
</div>
