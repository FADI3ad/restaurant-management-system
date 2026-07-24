@extends('layouts.app')
@section('title', 'إدارة المصروفات والنفقات')
@section('shell-class', 'shell')
@section('main-class', 'main')

@section('content')
<main class="content" x-data="{ 
    addOpen: false, 
    editOpen: false, 
    deleteOpen: false,
    editData: { id: '', title: '', category: 'electricity', amount: '', expense_date: '', notes: '' },
    deleteData: { id: '', title: '' }
}">

    @if(session('success'))
        <div class="alert alert-success" style="padding: 12px 20px; background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); border-radius: 10px; color: #22c55e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-weight: 500;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <x-hero-section-component 
        title="إدارة المصروفات والنفقات"
        des="إدارة متكاملة لجميع مصروفات المطعم اليومية والأسبوعية والشهرية، وتتبع فواتير الكهرباء والغاز والمياه والصيانة بسهولة." 
        btnText="إضافة مصروف جديد"
    />

    <!-- KPI Metrics -->
    <section class="kpi-grid anim-stagger" style="margin-bottom: 24px; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <article class="kpi-card c-primary">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                    </div>
                    <div class="kpi-label">مصروفات اليوم</div>
                </div>
            </div>
            <div class="kpi-value" style="font-size: 1.6rem; font-weight: 700;">{{ number_format($stats['total_today'], 2) }} <span style="font-size: 0.85rem; font-weight: 500;">ج.م</span></div>
        </article>

        <article class="kpi-card c-purple">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div class="kpi-label">مصروفات هذا الأسبوع</div>
                </div>
            </div>
            <div class="kpi-value" style="font-size: 1.6rem; font-weight: 700;">{{ number_format($stats['total_week'], 2) }} <span style="font-size: 0.85rem; font-weight: 500;">ج.م</span></div>
        </article>

        <article class="kpi-card c-success">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon success" style="background: rgba(34,197,94,0.15); color: #22c55e;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <div class="kpi-label">مصروفات هذا الشهر</div>
                </div>
            </div>
            <div class="kpi-value" style="font-size: 1.6rem; font-weight: 700;">{{ number_format($stats['total_month'], 2) }} <span style="font-size: 0.85rem; font-weight: 500;">ج.م</span></div>
        </article>

        <article class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon" style="background: rgba(234,179,8,0.15); color: #eab308;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    </div>
                    <div class="kpi-label">الكهرباء (الشهر)</div>
                </div>
            </div>
            <div class="kpi-value" style="font-size: 1.5rem; font-weight: 700;">{{ number_format($stats['electricity_month'], 2) }} <span style="font-size: 0.85rem;">ج.م</span></div>
        </article>

        <article class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon" style="background: rgba(14,165,233,0.15); color: #0ea5e9;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                    </div>
                    <div class="kpi-label">المياه (الشهر)</div>
                </div>
            </div>
            <div class="kpi-value" style="font-size: 1.5rem; font-weight: 700;">{{ number_format($stats['water_month'], 2) }} <span style="font-size: 0.85rem;">ج.م</span></div>
        </article>

        <article class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon" style="background: rgba(249,115,22,0.15); color: #f97316;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                    </div>
                    <div class="kpi-label">الغاز (الشهر)</div>
                </div>
            </div>
            <div class="kpi-value" style="font-size: 1.5rem; font-weight: 700;">{{ number_format($stats['gas_month'], 2) }} <span style="font-size: 0.85rem;">ج.م</span></div>
        </article>
    </section>

    <!-- Filters & Table Section -->
    <div class="grid">
        <section class="col-12 card" style="padding: 24px;">
            <!-- Filter Bar -->
            <form method="GET" action="{{ route('expenses.index') }}" style="margin-bottom: 24px; display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; background: var(--bg-surface-2, rgba(255,255,255,0.03)); padding: 18px; border-radius: 12px; border: 1px solid var(--border-color, rgba(255,255,255,0.08));">
                
                <div style="display: flex; flex-direction: column; gap: 6px; min-width: 140px;">
                    <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">الفترة الزمنية</label>
                    <select name="period" onchange="this.form.submit()" style="padding: 9px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-card, #fff); color: var(--text-color); font-size: 0.9rem;">
                        <option value="all" {{ $period == 'all' ? 'selected' : '' }}>جميع الأوقات</option>
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>اليوم</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>هذا الشهر</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>فترة مخصصة</option>
                    </select>
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px; min-width: 150px;">
                    <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">نوع المصروف</label>
                    <select name="category" onchange="this.form.submit()" style="padding: 9px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-card, #fff); color: var(--text-color); font-size: 0.9rem;">
                        <option value="all" {{ $category == 'all' || !$category ? 'selected' : '' }}>جميع الأنواع</option>
                        <option value="electricity" {{ $category == 'electricity' ? 'selected' : '' }}>⚡ كهرباء</option>
                        <option value="water" {{ $category == 'water' ? 'selected' : '' }}>💧 مياه</option>
                        <option value="gas" {{ $category == 'gas' ? 'selected' : '' }}>🔥 غاز</option>
                        <option value="rent" {{ $category == 'rent' ? 'selected' : '' }}>🏢 إيجار</option>
                        <option value="maintenance" {{ $category == 'maintenance' ? 'selected' : '' }}>🛠️ صيانة</option>
                        <option value="supplies" {{ $category == 'supplies' ? 'selected' : '' }}>📦 مستلزمات وتشغيل</option>
                        <option value="salaries" {{ $category == 'salaries' ? 'selected' : '' }}>💼 رواتب وأجور</option>
                        <option value="other" {{ $category == 'other' ? 'selected' : '' }}>📌 أخرى</option>
                    </select>
                </div>

                @if($period == 'custom')
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">من تاريخ</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" style="padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-card, #fff); color: var(--text-color);">
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">إلى تاريخ</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" style="padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-card, #fff); color: var(--text-color);">
                </div>
                <button type="submit" class="btn btn--primary" style="padding: 9px 18px; border-radius: 8px;">تطبيق الفلتر</button>
                @endif

                <a href="{{ route('expenses.index') }}" class="btn btn--ghost" style="padding: 9px 16px; border-radius: 8px;">إعادة ضبط</a>

                <div style="margin-right: auto; display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 1rem; color: var(--primary);">
                    إجمالي النتائج: {{ number_format($stats['filtered_total'], 2) }} ج.م
                </div>
            </form>

            <!-- Table -->
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color, #eee); text-align: right;">
                            <th style="padding: 12px; color: var(--text-muted);">#</th>
                            <th style="padding: 12px; color: var(--text-muted);">عنوان المصروف</th>
                            <th style="padding: 12px; color: var(--text-muted);">الفئة / النوع</th>
                            <th style="padding: 12px; color: var(--text-muted);">المبلغ</th>
                            <th style="padding: 12px; color: var(--text-muted);">التاريخ</th>
                            <th style="padding: 12px; color: var(--text-muted);">ملاحظات</th>
                            <th style="padding: 12px; color: var(--text-muted);">المُسجِّل</th>
                            <th style="padding: 12px; color: var(--text-muted); text-align: center;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr style="border-bottom: 1px solid var(--border-color, #eee); transition: background 0.2s;">
                                <td style="padding: 14px 12px; font-weight: 600;">{{ $expense->id }}</td>
                                <td style="padding: 14px 12px; font-weight: 600;">{{ $expense->title }}</td>
                                <td style="padding: 14px 12px;">
                                    @php
                                        $badgeMap = [
                                            'electricity' => ['name' => 'كهرباء ⚡', 'bg' => 'rgba(234,179,8,0.15)', 'color' => '#ca8a04'],
                                            'water' => ['name' => 'مياه 💧', 'bg' => 'rgba(14,165,233,0.15)', 'color' => '#0284c7'],
                                            'gas' => ['name' => 'غاز 🔥', 'bg' => 'rgba(249,115,22,0.15)', 'color' => '#ea580c'],
                                            'rent' => ['name' => 'إيجار 🏢', 'bg' => 'rgba(168,85,247,0.15)', 'color' => '#9333ea'],
                                            'maintenance' => ['name' => 'صيانة 🛠️', 'bg' => 'rgba(239,68,68,0.15)', 'color' => '#dc2626'],
                                            'supplies' => ['name' => 'مستلزمات 📦', 'bg' => 'rgba(59,130,246,0.15)', 'color' => '#2563eb'],
                                            'salaries' => ['name' => 'رواتب 💼', 'bg' => 'rgba(34,197,94,0.15)', 'color' => '#16a34a'],
                                            'other' => ['name' => 'أخرى 📌', 'bg' => 'rgba(107,114,128,0.15)', 'color' => '#4b5563'],
                                        ];
                                        $badge = $badgeMap[$expense->category] ?? $badgeMap['other'];
                                    @endphp
                                    <span style="padding: 4px 10px; border-radius: 20px; font-size: 0.82rem; font-weight: 600; background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; display: inline-block;">
                                        {{ $badge['name'] }}
                                    </span>
                                </td>
                                <td style="padding: 14px 12px; font-weight: 700; color: #ef4444;">
                                    {{ number_format($expense->amount, 2) }} ج.م
                                </td>
                                <td style="padding: 14px 12px;">{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</td>
                                <td style="padding: 14px 12px; color: var(--text-muted); max-width: 200px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                    {{ $expense->notes ?? '-' }}
                                </td>
                                <td style="padding: 14px 12px; font-size: 0.85rem; color: var(--text-muted);">
                                    {{ $expense->user->name ?? 'غير محدد' }}
                                </td>
                                <td style="padding: 14px 12px; text-align: center;">
                                    <div style="display: flex; justify-content: center; gap: 8px;">
                                        <button 
                                            @click="editOpen = true; editData = { id: {{ $expense->id }}, title: '{{ addslashes($expense->title) }}', category: '{{ $expense->category }}', amount: '{{ $expense->amount }}', expense_date: '{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}', notes: '{{ addslashes($expense->notes ?? '') }}' }"
                                            class="btn btn--ghost" 
                                            style="padding: 6px 10px; border-radius: 6px; font-size: 0.85rem;"
                                            title="تعديل"
                                        >
                                            ✏️
                                        </button>
                                        <button 
                                            @click="deleteOpen = true; deleteData = { id: {{ $expense->id }}, title: '{{ addslashes($expense->title) }}' }"
                                            class="btn btn--ghost" 
                                            style="padding: 6px 10px; border-radius: 6px; font-size: 0.85rem; color: #ef4444;"
                                            title="حذف"
                                        >
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 32px; text-align: center; color: var(--text-muted);">
                                    لا توجد مصروفات مسجلة مطابقة للبحث.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px;">
                {{ $expenses->links() }}
            </div>
        </section>
    </div>

    <!-- Modal Add Expense -->
    <div x-show="addOpen" style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 999; display: flex; align-items: center; justify-content: center; padding: 16px;" x-cloak>
        <div @click.away="addOpen = false" style="background: var(--bg-card, #fff); color: var(--text-color); width: 100%; max-width: 540px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); border: 1px solid var(--border-color, #eee);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color, #eee); padding-bottom: 12px;">
                <h3 style="font-size: 1.2rem; font-weight: 700; margin: 0;">تسجيل مصروف جديد</h3>
                <button @click="addOpen = false" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <form method="POST" action="{{ route('expenses.store') }}">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">عنوان المصروف <span style="color:red;">*</span></label>
                        <input type="text" name="title" required placeholder="مثال: فاتورة كهرباء الفرع الرئيسي" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">نوع / فئة المصروف <span style="color:red;">*</span></label>
                            <select name="category" required style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);">
                                <option value="electricity">⚡ كهرباء</option>
                                <option value="water">💧 مياه</option>
                                <option value="gas">🔥 غاز</option>
                                <option value="rent">🏢 إيجار</option>
                                <option value="maintenance">🛠️ صيانة</option>
                                <option value="supplies">📦 مستلزمات وتشغيل</option>
                                <option value="salaries">💼 رواتب وأجور</option>
                                <option value="other">📌 أخرى</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">المبلغ (ج.م) <span style="color:red;">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">تاريخ الصرف <span style="color:red;">*</span></label>
                        <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">ملاحظات إضافية</label>
                        <textarea name="notes" rows="3" placeholder="أدخل أي التفاصيل أو تفاصيل القراءة الخاصة بالعداد..." style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);"></textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 12px;">
                        <button type="button" @click="addOpen = false" class="btn btn--ghost">إلغاء</button>
                        <button type="submit" class="btn btn--primary">حفظ المصروف</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Expense -->
    <div x-show="editOpen" style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 999; display: flex; align-items: center; justify-content: center; padding: 16px;" x-cloak>
        <div @click.away="editOpen = false" style="background: var(--bg-card, #fff); color: var(--text-color); width: 100%; max-width: 540px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); border: 1px solid var(--border-color, #eee);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color, #eee); padding-bottom: 12px;">
                <h3 style="font-size: 1.2rem; font-weight: 700; margin: 0;">تعديل بيانات المصروف</h3>
                <button @click="editOpen = false" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <form :action="'/expenses/' + editData.id" method="POST">
                @csrf
                @method('PUT')
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">عنوان المصروف</label>
                        <input type="text" name="title" x-model="editData.title" required style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">نوع / فئة المصروف</label>
                            <select name="category" x-model="editData.category" required style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);">
                                <option value="electricity">⚡ كهرباء</option>
                                <option value="water">💧 مياه</option>
                                <option value="gas">🔥 غاز</option>
                                <option value="rent">🏢 إيجار</option>
                                <option value="maintenance">🛠️ صيانة</option>
                                <option value="supplies">📦 مستلزمات وتشغيل</option>
                                <option value="salaries">💼 رواتب وأجور</option>
                                <option value="other">📌 أخرى</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">المبلغ (ج.م)</label>
                            <input type="number" step="0.01" min="0.01" name="amount" x-model="editData.amount" required style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">تاريخ الصرف</label>
                        <input type="date" name="expense_date" x-model="editData.expense_date" required style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">ملاحظات إضافية</label>
                        <textarea name="notes" x-model="editData.notes" rows="3" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-color, #ccc); background: var(--bg-surface-1, #fff); color: var(--text-color);"></textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 12px;">
                        <button type="button" @click="editOpen = false" class="btn btn--ghost">إلغاء</button>
                        <button type="submit" class="btn btn--primary">حفظ التغييرات</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete Expense -->
    <div x-show="deleteOpen" style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 999; display: flex; align-items: center; justify-content: center; padding: 16px;" x-cloak>
        <div @click.away="deleteOpen = false" style="background: var(--bg-card, #fff); color: var(--text-color); width: 100%; max-width: 440px; border-radius: 16px; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); border: 1px solid var(--border-color, #eee); text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 12px;">⚠️</div>
            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 8px;">تأكيد حذف المصروف</h3>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 20px;">
                هل أنت تأكد من رغبتك في حذف المصروف <strong x-text="deleteData.title"></strong>؟ لا يمكن التراجع عن هذا الإجراء.
            </p>
            <form :action="'/expenses/' + deleteData.id" method="POST">
                @csrf
                @method('DELETE')
                <div style="display: flex; justify-content: center; gap: 12px;">
                    <button type="button" @click="deleteOpen = false" class="btn btn--ghost">إلغاء</button>
                    <button type="submit" class="btn" style="background: #ef4444; color: white;">نعم، تأكيد الحذف</button>
                </div>
            </form>
        </div>
    </div>

</main>
@endsection
