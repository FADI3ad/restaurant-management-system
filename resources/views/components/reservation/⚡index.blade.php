<?php

use Livewire\Component;

new class extends Component {
    public $timelineTrack;

    public function mount()
    {
        $this->timelineTrack = config('timelineTrack');
    }
};
?>

<div>
    {{-- ── Toolbar ── --}}
    {{-- <div class="rsv-toolbar">
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

    {{-- ── Timeline Shell ── --}}
    <div class="rsv-shell">
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

                {{-- Row 1 --}}
                <div class="rsv-row">
                    <div class="rsv-row-label">
                        <div class="rsv-table-num">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="3" rx="1" />
                                <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                            </svg>
                            <span class="rsv-table-name">طاولة #1</span>
                        </div>
                        <div class="rsv-table-meta">
                            <span class="rsv-cap-badge">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                4–6
                            </span>
                            <span class="rsv-type-tag">خاص</span>
                        </div>
                    </div>
                    <div class="rsv-row-canvas">
                        <div class="rsv-now-line" style="left: 160px;"></div>
                        {{-- Block: 12:00 – 14:00 = offset 160px, width 160px --}}
                        <div class="rsv-block confirmed" style="left:160px; width:158px;">
                            <span class="rsv-block-dot"></span>
                            <span class="rsv-block-name">أحمد خالد</span>
                            <span class="rsv-block-time">12:00 – 14:00</span>
                            <span class="rsv-block-guests">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                4 أشخاص
                            </span>
                            <div class="rsv-tooltip">
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                    </svg>
                                    <span class="rsv-tooltip-key">العميل</span>
                                    <span class="rsv-tooltip-val">أحمد خالد</span>
                                </div>
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    <span class="rsv-tooltip-key">الوقت</span>
                                    <span class="rsv-tooltip-val">12:00 – 14:00</span>
                                </div>
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    <span class="rsv-tooltip-key">الأشخاص</span>
                                    <span class="rsv-tooltip-val">4 أشخاص</span>
                                </div>
                            </div>
                        </div>
                        {{-- Block: 19:00 – 20:30 --}}
                        <div class="rsv-block pending" style="left:720px; width:118px;">
                            <span class="rsv-block-dot"></span>
                            <span class="rsv-block-name">سارة محمد</span>
                            <span class="rsv-block-time">19:00 – 20:30</span>
                            <span class="rsv-block-guests">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                2 أشخاص
                            </span>
                            <div class="rsv-tooltip">
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                    </svg>
                                    <span class="rsv-tooltip-key">العميل</span>
                                    <span class="rsv-tooltip-val">سارة محمد</span>
                                </div>
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    <span class="rsv-tooltip-key">الوقت</span>
                                    <span class="rsv-tooltip-val">19:00 – 20:30</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Row 2 --}}
                <div class="rsv-row">
                    <div class="rsv-row-label">
                        <div class="rsv-table-num">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="3" rx="1" />
                                <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                            </svg>
                            <span class="rsv-table-name">طاولة #2</span>
                        </div>
                        <div class="rsv-table-meta">
                            <span class="rsv-cap-badge">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                2–4
                            </span>
                            <span class="rsv-type-tag public">عام</span>
                        </div>
                    </div>
                    <div class="rsv-row-canvas">
                        <div class="rsv-block seated" style="left:240px; width:198px;">
                            <span class="rsv-block-dot"></span>
                            <span class="rsv-block-name">عمر يوسف</span>
                            <span class="rsv-block-time">12:30 – 15:00</span>
                            <span class="rsv-block-guests">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                3 أشخاص
                            </span>
                            <div class="rsv-tooltip">
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                    </svg>
                                    <span class="rsv-tooltip-key">العميل</span>
                                    <span class="rsv-tooltip-val">عمر يوسف</span>
                                </div>
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    <span class="rsv-tooltip-key">الوقت</span>
                                    <span class="rsv-tooltip-val">12:30 – 15:00</span>
                                </div>
                            </div>
                        </div>
                        <div class="rsv-block cancelled" style="left:640px; width:118px;">
                            <span class="rsv-block-dot"></span>
                            <span class="rsv-block-name">نور علي</span>
                            <span class="rsv-block-time">18:00 – 19:30</span>
                            <span class="rsv-block-guests">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                2 أشخاص
                            </span>
                            <div class="rsv-tooltip">
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                    </svg>
                                    <span class="rsv-tooltip-key">العميل</span>
                                    <span class="rsv-tooltip-val">نور علي</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 3 --}}
                <div class="rsv-row">
                    <div class="rsv-row-label">
                        <div class="rsv-table-num">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="3" rx="1" />
                                <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                            </svg>
                            <span class="rsv-table-name">طاولة #3</span>
                        </div>
                        <div class="rsv-table-meta">
                            <span class="rsv-cap-badge">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                6–8
                            </span>
                            <span class="rsv-type-tag">خاص</span>
                        </div>
                    </div>
                    <div class="rsv-row-canvas">
                        <div class="rsv-block confirmed" style="left:80px; width:238px;">
                            <span class="rsv-block-dot"></span>
                            <span class="rsv-block-name">مجموعة الشركة</span>
                            <span class="rsv-block-time">11:00 – 14:00</span>
                            <span class="rsv-block-guests">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                8 أشخاص
                            </span>
                            <div class="rsv-tooltip">
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                    </svg>
                                    <span class="rsv-tooltip-key">العميل</span>
                                    <span class="rsv-tooltip-val">مجموعة الشركة</span>
                                </div>
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                    <span class="rsv-tooltip-key">الوقت</span>
                                    <span class="rsv-tooltip-val">11:00 – 14:00</span>
                                </div>
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    <span class="rsv-tooltip-key">الأشخاص</span>
                                    <span class="rsv-tooltip-val">8 أشخاص</span>
                                </div>
                            </div>
                        </div>
                        <div class="rsv-block pending" style="left:800px; width:158px;">
                            <span class="rsv-block-dot"></span>
                            <span class="rsv-block-name">رانيا حسن</span>
                            <span class="rsv-block-time">20:00 – 22:00</span>
                            <span class="rsv-block-guests">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                5 أشخاص
                            </span>
                            <div class="rsv-tooltip">
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                    </svg>
                                    <span class="rsv-tooltip-key">العميل</span>
                                    <span class="rsv-tooltip-val">رانيا حسن</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 4 --}}
                <div class="rsv-row">
                    <div class="rsv-row-label">
                        <div class="rsv-table-num">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="3" rx="1" />
                                <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                            </svg>
                            <span class="rsv-table-name">طاولة #4</span>
                        </div>
                        <div class="rsv-table-meta">
                            <span class="rsv-cap-badge">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                2–4
                            </span>
                            <span class="rsv-type-tag public">عام</span>
                        </div>
                    </div>
                    <div class="rsv-row-canvas">
                        {{-- empty row --}}
                    </div>
                </div>

                {{-- Row 5 --}}
                <div class="rsv-row">
                    <div class="rsv-row-label">
                        <div class="rsv-table-num">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="3" rx="1" />
                                <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                            </svg>
                            <span class="rsv-table-name">طاولة #5</span>
                        </div>
                        <div class="rsv-table-meta">
                            <span class="rsv-cap-badge">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                4–6
                            </span>
                            <span class="rsv-type-tag">خاص</span>
                        </div>
                    </div>
                    <div class="rsv-row-canvas">
                        <div class="rsv-block confirmed" style="left:480px; width:198px;">
                            <span class="rsv-block-dot"></span>
                            <span class="rsv-block-name">كريم عادل</span>
                            <span class="rsv-block-time">16:00 – 18:30</span>
                            <span class="rsv-block-guests">
                                <svg viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                6 أشخاص
                            </span>
                            <div class="rsv-tooltip">
                                <div class="rsv-tooltip-row">
                                    <svg class="rsv-tooltip-ico" viewBox="0 0 24 24">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                    </svg>
                                    <span class="rsv-tooltip-key">العميل</span>
                                    <span class="rsv-tooltip-val">كريم عادل</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /rsv-body --}}
        </div>{{-- /rsv-scroll-container --}}
    </div>{{-- /rsv-shell --}}

    {{-- ── Legend ── --}}
    <div class="rsv-legend">
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
    </div>

</div>
