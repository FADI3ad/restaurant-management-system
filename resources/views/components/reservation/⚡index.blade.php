<?php

use App\Models\Reservation;
use App\Models\Table;
use Livewire\Component;

new class extends Component {
    public $timelineTrack;

    public function mount()
    {
        $this->timelineTrack = config('timelineTrack');

        $this->tables();
    }

    public function tables()
    {
        return Table::with('reservations')->paginate(10);
    }
    
};
?>

<div>

    {{--     
    <div class="rsv-toolbar">
        <div class="rsv-toolbar-start">

            <div class="rsv-date-nav">
                <button type="button" class="rsv-date-nav-btn" title="اليوم السابق">
                    <svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <div class="rsv-date-label">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    الأربعاء، 16 يوليو 2025
                </div>
                <button type="button" class="rsv-date-nav-btn" title="اليوم التالي">
                    <svg viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>

            <div class="rsv-search-wrap">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" class="rsv-search" placeholder="بحث عن حجز أو عميل...">
            </div>

            <div class="rsv-filter-group">
                <button class="rsv-filter-btn all is-active">الكل</button>
                <button class="rsv-filter-btn confirmed"><span class="dot"></span> مؤكد</button>
                <button class="rsv-filter-btn pending"><span class="dot"></span> معلق</button>
                <button class="rsv-filter-btn seated"><span class="dot"></span> جالس</button>
                <button class="rsv-filter-btn cancelled"><span class="dot"></span> ملغي</button>
            </div>
        </div>

        <div class="rsv-toolbar-end">
            <button type="button" class="rsv-add-btn">
                <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                حجز جديد
            </button>
        </div>
    </div> --}}

    <div class="rsv -shell">
        <div class="rsv-scroll-container">

            <div class="rsv-head">
                <div class="rsv-head-corner">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="3" rx="1" />
                        <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                    </svg>
                    <span>الطاولة</span>
                </div>
                <div class="rsv-time-track">
                    @foreach ($this->timelineTrack as $time)
                        <div class="rsv-time-cell">{{ $time }}</div>
                    @endforeach
                </div>
            </div>



            <div class="rsv-body">

                @foreach ($this->tables() as $table)
                    <div class="rsv-row">
                        <div class="rsv-row-label">
                            <div class="rsv-table-num">
                                <svg viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="3" rx="1" />
                                    <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                                </svg>
                                <span class="rsv-table-name">{{ $table->table_number }}</span>
                            </div>
                            <div class="rsv-table-meta">
                                <span class="rsv-cap-badge">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                    </svg>
                                    {{ $table->max_capacity }} - {{ $table->min_capacity }}
                                </span>
                                <span class="rsv-type-tag">{{ $table->type }}</span>
                            </div>
                        </div>


                        <div class="rsv-row-canvas">

                            @foreach ($table->reservations as $reservation)
                                <div class="rsv-block confirmed" style="left:160px; width:158px;">
                                    <span class="rsv-block-dot"></span>
                                    <span class="rsv-block-name">{{ $reservation->customer_name }}</span>
                                    <span class="rsv-block-time">{{ $reservation->start_time }} – {{ $reservation->end_time }}</span>
                                    <span class="rsv-block-guests">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                        </svg>
                                        {{ $reservation->number_of_guests }} أشخاص
                                    </span>
                                </div>
                            @endforeach
                        </div>


                    </div>
                @endforeach






            </div>
        </div>
    </div>

    {{-- <div class="rsv-legend">
        <div class="rsv-legend-list">
            <div class="rsv-legend-item">
                <span class="rsv-legend-dot confirmed"></span>
                <span class="rsv-legend-label">مؤكد</span>
            </div>
            <div class="rsv-legend-item">
                <span class="rsv-legend-dot pending"></span>
                <span class="rsv-legend-label">معلق</span>
            </div>
            <div class="rsv-legend-item">
                <span class="rsv-legend-dot seated"></span>
                <span class="rsv-legend-label">جالس</span>
            </div>
            <div class="rsv-legend-item">
                <span class="rsv-legend-dot cancelled"></span>
                <span class="rsv-legend-label">ملغي</span>
            </div>
        </div>
        <div class="rsv-count-wrap">
            <div class="rsv-count-chip">
                إجمالي الحجوزات: <span class="n">7</span>
            </div>
            <div class="rsv-count-chip">
                الطاولات: <span class="n">5</span>
            </div>
        </div>
    </div> --}}

</div>
