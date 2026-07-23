@extends('layouts.app')
@section('title', 'الرئيسية')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')
    <main class="content">
        <section class="hero anim-fade-up">
            <div class="hero-text">
                <span class="eyebrow" id="heroDate"></span>
                <h1 class="hero-title">
                    مرحباً بك مجدداً، <span class="accent">{{ Auth::user()->name }}</span>
                </h1>
                <p class="hero-sub">
                    إجمالي المبيعات ارتفعت بمعدل <strong>+10%</strong> أسبوعياً،
                    والزوار الفريدون مستقرون، ومعدل الارتداد عند 33%. منطقتان
                    جديدتان دخلتا الخدمة الليلة الماضية.
                </p>
            </div>
            <div class="hero-actions">
                <button class="btn btn--ghost">
                    <svg viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <path d="M7 10l5 5 5-5" />
                        <path d="M12 15V3" />
                    </svg>
                    تصدير
                </button>
                <button class="btn btn--primary">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    تقرير جديد
                </button>
            </div>
        </section>
        {{-- <x-hero-section-component title="" des="" /> --}}

        <section class="kpi-grid anim-stagger" aria-label="Key metrics">

            <x-home-article-card-component />

            <article class="kpi-card c-danger">
                <div class="kpi-top">
                    <div class="kpi-identity">
                        <div class="kpi-icon danger">
                            <svg viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6M8 13h8M8 17h8M8 9h2" />
                            </svg>
                        </div>
                        <div class="kpi-label">مشاهدات الصفحة</div>
                    </div>
                    <span class="kpi-pill down"><svg viewBox="0 0 24 24">
                            <path d="M7 7l10 10M7 17h10V7" />
                        </svg>
                        −7%</span>
                </div>
                <div class="kpi-value">4.08<sup>M</sup></div>
                <div class="kpi-compare">
                    <svg class="down" viewBox="0 0 24 24">
                        <path d="M7 7l10 10M7 17h10V7" />
                    </svg>
                    down from <strong>4.39M</strong> <span class="sep">·</span> last
                    week
                </div>
            </article>
            <article class="kpi-card c-purple">
                <div class="kpi-top">
                    <div class="kpi-identity">
                        <div class="kpi-icon purple">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8" />
                            </svg>
                        </div>
                        <div class="kpi-label">الزوار الفريدون</div>
                    </div>
                    <span class="kpi-pill flat"><svg viewBox="0 0 24 24">
                            <path d="M5 12h14" />
                        </svg>
                        ~12%</span>
                </div>
                <div class="kpi-value">842<sup>K</sup></div>
                <div class="kpi-compare">
                    <svg class="flat" viewBox="0 0 24 24">
                        <path d="M5 12h14" />
                    </svg>
                    مستقر عند حوالي <strong>835K</strong>
                    <span class="sep">·</span> الأسبوع الماضي
                </div>
            </article>
            <article class="kpi-card c-primary">
                <div class="kpi-top">
                    <div class="kpi-identity">
                        <div class="kpi-icon primary">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 20V10M18 20V4M6 20v-4" />
                            </svg>
                        </div>
                        <div class="kpi-label">معدل الارتداد</div>
                    </div>
                    <span class="kpi-pill info"><svg viewBox="0 0 24 24">
                            <path d="M5 12h14" />
                        </svg>
                        مستقر</span>
                </div>
                <div class="kpi-value">33<sup>%</sup></div>
                <div class="kpi-compare">
                    <svg class="info" viewBox="0 0 24 24">
                        <path d="M5 12h14" />
                    </svg>
                    مطابق لـ <strong>33%</strong> <span class="sep">·</span> الأسبوع
                    الماضي
                </div>
            </article>
        </section>
        <div class="grid">
            <section class="col-12 card">
                <div class="card-head">
                    <div class="card-title-wrap">
                        <span class="eyebrow">الجغرافيا</span>
                        <h2 class="card-title">زيارات الموقع</h2>
                    </div>
                    <a class="card-action" href="#">عرض التقرير
                        <svg viewBox="0 0 24 24">
                            <path d="M5 12h14M13 5l7 7-7 7" />
                        </svg></a>
                </div>
                <div class="sv-regions">
                    <div class="sv-region">
                        <div class="sv-region-head">
                            <span class="marker" style="background: var(--purple)"></span>
                            الولايات المتحدة
                        </div>
                        <div class="sv-region-value">
                            100K<span class="pct">50%</span>
                        </div>
                        <div class="sv-region-bar">
                            <div class="sv-region-bar-fill" style="width: 50%; background: var(--purple)"></div>
                        </div>
                    </div>
                    <div class="sv-region">
                        <div class="sv-region-head">
                            <span class="marker" style="background: var(--success)"></span>
                            أوروبا
                        </div>
                        <div class="sv-region-value">
                            1M<span class="pct">80%</span>
                        </div>
                        <div class="sv-region-bar">
                            <div class="sv-region-bar-fill" style="width: 80%; background: var(--success)"></div>
                        </div>
                    </div>
                    <div class="sv-region">
                        <div class="sv-region-head">
                            <span class="marker" style="background: var(--info)"></span>
                            أستراليا
                        </div>
                        <div class="sv-region-value">
                            450K<span class="pct">40%</span>
                        </div>
                        <div class="sv-region-bar">
                            <div class="sv-region-bar-fill" style="width: 40%; background: var(--info)"></div>
                        </div>
                    </div>
                    <div class="sv-region">
                        <div class="sv-region-head">
                            <span class="marker" style="background: #64748b"></span>
                            الهند
                        </div>
                        <div class="sv-region-value">
                            1B<span class="pct">90%</span>
                        </div>
                        <div class="sv-region-bar">
                            <div class="sv-region-bar-fill" style="width: 90%; background: #64748b"></div>
                        </div>
                    </div>
                </div>
                <div class="sv-divider"></div>
                <div class="sv-radials">
                    <div class="sv-radial">
                        <div class="sv-radial-chart">
                            <svg viewBox="0 0 80 80">
                                <circle class="radial-track" cx="40" cy="40" r="32" />
                                <circle class="radial-fill danger" cx="40" cy="40" r="32"
                                    stroke-dasharray="201.06" stroke-dashoffset="50.27" />
                            </svg>
                            <span class="pct">75%</span>
                        </div>
                        <div class="sv-radial-text">
                            <div class="sv-radial-name">مستخدمون جدد</div>
                            <div class="sv-radial-caption">الزوار لأول مرة</div>
                        </div>
                    </div>
                    <div class="sv-radial">
                        <div class="sv-radial-chart">
                            <svg viewBox="0 0 80 80">
                                <circle class="radial-track" cx="40" cy="40" r="32" />
                                <circle class="radial-fill info" cx="40" cy="40" r="32"
                                    stroke-dasharray="201.06" stroke-dashoffset="100.53" />
                            </svg>
                            <span class="pct">50%</span>
                        </div>
                        <div class="sv-radial-text">
                            <div class="sv-radial-name">مشتريات جديدة</div>
                            <div class="sv-radial-caption">من الزيارات الجديدة</div>
                        </div>
                    </div>
                    <div class="sv-radial">
                        <div class="sv-radial-chart">
                            <svg viewBox="0 0 80 80">
                                <circle class="radial-track" cx="40" cy="40" r="32" />
                                <circle class="radial-fill warning" cx="40" cy="40" r="32"
                                    stroke-dasharray="201.06" stroke-dashoffset="20.11" />
                            </svg>
                            <span class="pct">90%</span>
                        </div>
                        <div class="sv-radial-text">
                            <div class="sv-radial-name">معدل الارتداد</div>
                            <div class="sv-radial-caption">متوسط التفاعل</div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-6 card">
                <div class="card-head">
                    <div class="card-title-wrap">
                        <span class="eyebrow">الأداء</span>
                        <h2 class="card-title">الإحصائيات الشهرية</h2>
                    </div>
                    <span class="card-action">أبريل 2026</span>
                </div>
                <div class="chart-canvas-wrap" style="height: 240px">
                    <canvas data-chart-key="dashboard-monthly"></canvas>
                </div>
                <div class="monthly-footer">
                    <div class="stat-cell">
                        <div class="stat-cell-label">نمو المبيعات</div>
                        <div class="stat-cell-value">
                            54%
                            <svg class="trend-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M7 17l10-10M7 7h10v10" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-cell">
                        <div class="stat-cell-label">مبيعات ديسمبر</div>
                        <div class="stat-cell-value">
                            $185K
                            <svg class="trend-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M7 17l10-10M7 7h10v10" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-cell">
                        <div class="stat-cell-label">نمو الأرباح</div>
                        <div class="stat-cell-value">
                            60%
                            <svg class="trend-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M7 17l10-10M7 7h10v10" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-cell">
                        <div class="stat-cell-label">أرباح ديسمبر</div>
                        <div class="stat-cell-value">
                            $72K
                            <svg class="trend-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M7 17l10-10M7 7h10v10" />
                            </svg>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-6 card">
                <div class="card-head">
                    <div class="card-title-wrap">
                        <span class="eyebrow">شخصي</span>
                        <h2 class="card-title">قائمة المهام</h2>
                    </div>
                    <a class="card-action" href="#">إضافة مهمة
                        <svg viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        ></a>
                </div>
                <ul class="todo-list">
                    <li class="todo-item">
                        <input type="checkbox" class="todo-check" id="td1" />
                        <label for="td1" class="todo-text">الاتصال بجون لتناول العشاء</label>
                        <span class="todo-badge low">في أي وقت</span>
                    </li>
                    <li class="todo-item">
                        <input type="checkbox" class="todo-check" id="td2" />
                        <label for="td2" class="todo-text">حجز رحلة المدير</label>
                        <span class="todo-badge upcoming">يومان</span>
                    </li>
                    <li class="todo-item">
                        <input type="checkbox" class="todo-check" id="td3" />
                        <label for="td3" class="todo-text">الذهاب إلى النادي الرياضي</label>
                        <span class="todo-badge urgent">3 دقائق</span>
                    </li>
                    <li class="todo-item">
                        <input type="checkbox" class="todo-check" id="td4" />
                        <label for="td4" class="todo-text">تقديم تقرير المشتريات</label>
                        <span class="todo-badge warn">أولوية منخفضة</span>
                    </li>
                    <li class="todo-item">
                        <input type="checkbox" class="todo-check" id="td5" />
                        <label for="td5" class="todo-text">مشاهدة مسلسل Foundation S03E04</label>
                        <span class="todo-badge upcoming">غداً</span>
                    </li>
                    <li class="todo-item is-done">
                        <input type="checkbox" class="todo-check" id="td6" checked="checked" />
                        <label for="td6" class="todo-text">تقديم تقرير المشتريات</label>
                        <span class="todo-badge done">مكتمل</span>
                    </li>
                </ul>
            </section>
            <section class="col-6 card">
                <div class="card-head">
                    <div class="card-title-wrap">
                        <span class="eyebrow">التجارة</span>
                        <h2 class="card-title">تقرير المبيعات</h2>
                    </div>
                </div>
                <div class="sales-summary">
                    <div class="sales-summary-label">
                        <span class="eyebrow">الفترة</span>
                        <h4>أبريل 2026</h4>
                    </div>
                    <div class="sales-summary-total"><sup>$</sup>6,000</div>
                </div>
                <div style="overflow-x: auto; width: 100%;"><table class="table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th style="text-align: right">السعر</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="cell-name">العنصر #١</td>
                            <td><span class="tag t-unavail">غير متوفر</span></td>
                            <td class="cell-date">١٨ أبريل</td>
                            <td class="cell-price pos">١٢ $</td>
                        </tr>
                        <tr>
                            <td class="cell-name">العنصر #٢</td>
                            <td><span class="tag t-new">جديد</span></td>
                            <td class="cell-date">١٩ أبريل</td>
                            <td class="cell-price pos">٣٤ $</td>
                        </tr>
                        <tr>
                            <td class="cell-name">العنصر #٣</td>
                            <td><span class="tag t-new">جديد</span></td>
                            <td class="cell-date">٢٠ أبريل</td>
                            <td class="cell-price neg">−٤٥ $</td>
                        </tr>
                        <tr>
                            <td class="cell-name">العنصر #٤</td>
                            <td><span class="tag t-unavail">غير متوفر</span></td>
                            <td class="cell-date">٢١ أبريل</td>
                            <td class="cell-price pos">٦٥ $</td>
                        </tr>
                        <tr>
                            <td class="cell-name">العنصر #٥</td>
                            <td><span class="tag t-used">مستعمل</span></td>
                            <td class="cell-date">٢٢ أبريل</td>
                            <td class="cell-price pos">٧٨ $</td>
                        </tr>
                        <tr>
                            <td class="cell-name">العنصر #٦</td>
                            <td><span class="tag t-used">مستعمل</span></td>
                            <td class="cell-date">٢٣ أبريل</td>
                            <td class="cell-price neg">−٨٨ $</td>
                        </tr>
                        <tr>
                            <td class="cell-name">العنصر #٧</td>
                            <td><span class="tag t-old">قديم</span></td>
                            <td class="cell-date">٢٢ أبريل</td>
                            <td class="cell-price pos">٥٦ $</td>
                        </tr>
                    </tbody>
                </table></div>
                <div class="sales-all">
                    <a href="#">التحقق من جميع المبيعات
                        <svg viewBox="0 0 24 24">
                            <path d="M5 12h14M13 5l7 7-7 7" />
                        </svg></a>
                </div>
            </section>
            <section class="col-6 card">
                <div class="card-head">
                    <div class="card-title-wrap">
                        <span class="eyebrow">اليوم</span>
                        <h2 class="card-title">الطقس</h2>
                    </div>
                    <span class="card-action">Rīga, LV</span>
                </div>
                <div class="wx-hero">
                    <div class="wx-temp-block">
                        <div class="wx-icon">
                            <svg viewBox="0 0 64 64">
                                <circle cx="22" cy="22" r="8" />
                                <path d="M22 6v4M22 34v4M6 22h4M34 22h4M10 10l3 3M31 31l3 3M34 10l-3 3M13 31l-3 3" />
                                <path d="M18 44a10 10 0 0 1 10-10 10 10 0 0 1 9.5 7 8 8 0 0 1-1.5 16H20a8 8 0 0 1-2-13z" />
                            </svg>
                        </div>
                        <div>
                            <div class="wx-temp">٣٢<sup>°ف</sup></div>
                            <div class="wx-condition">
                                <strong>غائم جزئياً</strong> · نسيم خفيف
                            </div>
                        </div>
                    </div>
                    <div class="wx-date">
                        <h5>الخميس</h5>
                        <p>٢٣ أبريل، ٢٠٢٦</p>
                    </div>
                </div>
                <div class="wx-stats">
                    <div>
                        <div class="wx-stat-label">الرياح</div>
                        <div class="wx-stat-value">
                            ١٠ <span class="unit">كم/س</span>
                        </div>
                    </div>
                    <div>
                        <div class="wx-stat-label">شروق الشمس</div>
                        <div class="wx-stat-value">
                            ٠٥:٣٢ <span class="unit">ص</span>
                        </div>
                    </div>
                    <div>
                        <div class="wx-stat-label">الضغط الجوي</div>
                        <div class="wx-stat-value">
                            ١٠١٣ <span class="unit">هكتوباسكال</span>
                        </div>
                    </div>
                </div>
                <div class="wx-forecast">
                    <div class="wx-day is-today">
                        <div class="wx-day-name">الخميس</div>
                        <div class="wx-day-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M7 18a5 5 0 1 1 1-9.9A6 6 0 0 1 20 11.5 4.5 4.5 0 0 1 19 20.5" />
                            </svg>
                        </div>
                        <div class="wx-day-temp">٣٢°</div>
                    </div>
                    <div class="wx-day">
                        <div class="wx-day-name">الجمعة</div>
                        <div class="wx-day-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="4" />
                                <path
                                    d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" />
                            </svg>
                        </div>
                        <div class="wx-day-temp">٣٠°</div>
                    </div>
                    <div class="wx-day">
                        <div class="wx-day-name">السبت</div>
                        <div class="wx-day-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M7 18a5 5 0 1 1 1-9.9A6 6 0 0 1 20 11.5 4.5 4.5 0 0 1 19 20.5" />
                            </svg>
                        </div>
                        <div class="wx-day-temp">٢٨°</div>
                    </div>
                    <div class="wx-day">
                        <div class="wx-day-name">الأحد</div>
                        <div class="wx-day-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M18 10a6 6 0 0 0-12 0 5 5 0 0 0 0 10h12a5 5 0 0 0 0-10" />
                            </svg>
                        </div>
                        <div class="wx-day-temp">٣٢°</div>
                    </div>
                    <div class="wx-day">
                        <div class="wx-day-name">الاثنين</div>
                        <div class="wx-day-icon">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M8 19v-9M8 14l3 3M8 14l-3 3M16 19v-9M16 14l3 3M16 14l-3 3M20 6a6 6 0 0 0-12 0 5 5 0 0 0 0 10" />
                            </svg>
                        </div>
                        <div class="wx-day-temp">٢٤°</div>
                    </div>
                    <div class="wx-day">
                        <div class="wx-day-name">الثلاثاء</div>
                        <div class="wx-day-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M17.7 7.7a6 6 0 1 0-9.1 7.3M3 12h4M15 8h5M18 16h-6M9.5 20h7" />
                            </svg>
                        </div>
                        <div class="wx-day-temp">٢٨°</div>
                    </div>
                    <div class="wx-day">
                        <div class="wx-day-name">الأربعاء</div>
                        <div class="wx-day-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M7 18a5 5 0 1 1 1-9.9A6 6 0 0 1 20 11.5 4.5 4.5 0 0 1 19 20.5" />
                            </svg>
                        </div>
                        <div class="wx-day-temp">٣٢°</div>
                    </div>
                </div>
            </section>
            <section class="col-12 card">
                <div class="card-head">
                    <div class="card-title-wrap">
                        <span class="eyebrow">المحادثات</span>
                        <h2 class="card-title">الدردشة السريعة</h2>
                    </div>
                    <a class="card-action" href="#">فتح الدردشة
                        <svg viewBox="0 0 24 24">
                            <path d="M5 12h14M13 5l7 7-7 7" />
                        </svg></a>
                </div>
                <div class="chat-frame">
                    <div class="chat-messages">
                        <div class="chat-row">
                            <div class="chat-avatar">LK</div>
                            <div class="chat-stack">
                                <div class="chat-bubble">
                                    صباح الخير — لقد قمت بدفع تحديث التبعيات إلى الفرع
                                    الرئيسي.
                                </div>
                                <div class="chat-bubble">
                                    تم إغلاق جميع طلبات السحب الـ 10 القديمة لـ Dependabot
                                    🎉
                                </div>
                                <div class="chat-ts">١٠:٠٤ ص</div>
                            </div>
                        </div>
                        <div class="chat-row me">
                            <div class="chat-avatar me">JD</div>
                            <div class="chat-stack">
                                <div class="chat-bubble">
                                    عمل رائع. هل هناك أي تغييرات جذرية؟
                                </div>
                                <div class="chat-ts">١٠:٠٦ ص</div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-input-row">
                        <input class="chat-input" type="text" placeholder="الرد على ليو..." />
                        <button class="chat-send" aria-label="Send">
                            <svg viewBox="0 0 24 24">
                                <path d="m22 2-7 20-4-9-9-4z" />
                                <path d="M22 2 11 13" />
                            </svg>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
