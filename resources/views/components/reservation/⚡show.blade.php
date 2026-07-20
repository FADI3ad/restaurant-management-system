<?php

use App\Models\Reservation;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public ?Reservation $reservation = null;

    #[On('show-reservation')]
    public function showReservation($id)
    {
        $this->reservation = Reservation::with('table')->findOrFail($id);
    }
};
?>

<div id="modal-show" class="modal-overlay is-active" x-show="showOpen" x-cloak @click.self="showOpen = false"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="تفاصيل الحجز" />

        <div class="modal-body modal-form-stack">

            @if($this->reservation)

                <div class="rsv-show-hero">
                    <div class="rsv-show-code">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        {{ $this->reservation->code ?? 'بدون كود' }}
                    </div>
                    <span class="rsv-show-status rsv-status--{{ strtolower($this->reservation->status) }}">
                        {{ match($this->reservation->status) {
                            'Confirmed'  => 'مؤكد',
                            'Arrived'    => 'وصل',
                            'Cancelled'  => 'ملغي',
                            'Completed'  => 'مكتمل',
                            'No_Show'    => 'لم يحضر',
                            default      => $this->reservation->status,
                        } }}
                    </span>
                </div>

                <div class="rsv-show-section">
                    <div class="rsv-show-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        معلومات العميل
                    </div>
                    <div class="rsv-show-grid">
                        <div class="rsv-show-field">
                            <span class="rsv-show-label">الاسم</span>
                            <span class="rsv-show-value">{{ $this->reservation->customer_name }}</span>
                        </div>
                        <div class="rsv-show-field">
                            <span class="rsv-show-label">رقم الهاتف</span>
                            <span class="rsv-show-value" dir="ltr">{{ $this->reservation->customer_phone }}</span>
                        </div>
                        <div class="rsv-show-field">
                            <span class="rsv-show-label">عدد الأشخاص</span>
                            <span class="rsv-show-value">{{ $this->reservation->number_of_guests }} أشخاص</span>
                        </div>
                    </div>
                </div>

                {{-- معلومات الحجز --}}
                <div class="rsv-show-section">
                    <div class="rsv-show-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        تفاصيل الحجز
                    </div>
                    <div class="rsv-show-grid">
                        <div class="rsv-show-field">
                            <span class="rsv-show-label">التاريخ</span>
                            <span class="rsv-show-value">{{ \Carbon\Carbon::parse($this->reservation->date)->format('d M Y') }}</span>
                        </div>
                        <div class="rsv-show-field">
                            <span class="rsv-show-label">وقت البداية</span>
                            <span class="rsv-show-value" dir="ltr">{{ $this->reservation->start_time }}</span>
                        </div>
                        <div class="rsv-show-field">
                            <span class="rsv-show-label">وقت النهاية</span>
                            <span class="rsv-show-value" dir="ltr">{{ $this->reservation->end_time }}</span>
                        </div>
                        <div class="rsv-show-field">
                            <span class="rsv-show-label">المدة</span>
                            <span class="rsv-show-value">
                                @php
                                    $d = (int) $this->reservation->duration;
                                    $h = intdiv($d, 60);
                                    $m = $d % 60;
                                    echo ($h ? "{$h} ساعة " : '') . ($m ? "{$m} دقيقة" : '');
                                @endphp
                            </span>
                        </div>
                        <div class="rsv-show-field">
                            <span class="rsv-show-label">الطاولة</span>
                            <span class="rsv-show-value">طاولة {{ $this->reservation->table?->number }} {{ $this->reservation->table?->location ? '(' . $this->reservation->table->location . ')' : '' }}</span>
                        </div>
                    </div>
                </div>

            @endif

        </div>

        <div class="modal-foot">
            <button type="button" class="btn btn--ghost" @click="showOpen = false">
                إغلاق
            </button>
            @if($this->reservation)
                <button type="button" class="btn btn--primary"
                    @click="showOpen = false; $nextTick(() => { editOpen = true })"
                    @click.prevent="$wire.dispatch('load-reservation-edit', { id: {{ $this->reservation->id }} })">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    تعديل الحجز
                </button>
            @endif
        </div>
    </div>
</div>
