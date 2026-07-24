@extends('layouts.app')
@section('title', 'العروض والخصومات')
@section('shell-class', 'shell')
@section('main-class', 'main')

@section('content')
<main class="content" x-data="{ 
    addOpen: false, 
    editOpen: false, 
    deleteOpen: false,
    editData: { id: '', name: '', duration: '', discount_price: '', status: true, item_ids: [] },
    deleteData: { id: '', name: '' }
}">

    @if(session('success'))
        <div class="alert alert-success" style="padding: 12px 20px; background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); border-radius: 10px; color: #22c55e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-weight: 500;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <x-hero-section-component 
        title="إدارة العروض والخصومات"
        des="قم بإنشاء وتعديل العروض الترويجية والخصومات الخاصة بالمطعم، وحدد الوجبات المشمولة ومدة صلاحية كل عرض وقيمة الخصم." 
        btnText="إضافة عرض جديد"
    />

    <!-- KPI Metrics -->
    <section class="kpi-grid anim-stagger" style="margin-bottom: 24px; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
        <article class="kpi-card c-primary">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                    </div>
                    <div class="kpi-label">إجمالي العروض</div>
                </div>
            </div>
            <div class="kpi-value" style="font-size: 1.6rem; font-weight: 700;">{{ $stats['total_offers'] }} <span style="font-size: 0.85rem; font-weight: 500;">عرض</span></div>
        </article>

        <article class="kpi-card c-success">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon success" style="background: rgba(34,197,94,0.15); color: #22c55e;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 8v4l3 3"></path>
                        </svg>
                    </div>
                    <div class="kpi-label">العروض النشطة</div>
                </div>
            </div>
            <div class="kpi-value" style="font-size: 1.6rem; font-weight: 700;">{{ $stats['active_offers'] }} <span style="font-size: 0.85rem; font-weight: 500;">نشط</span></div>
        </article>

        <article class="kpi-card c-purple">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon purple" style="background: rgba(168,85,247,0.15); color: #a855f7;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div class="kpi-label">متوسط قيمة الخصم</div>
                </div>
            </div>
            <div class="kpi-value" style="font-size: 1.6rem; font-weight: 700;">{{ number_format($stats['average_discount'], 2) }} <span style="font-size: 0.85rem; font-weight: 500;">ج.م</span></div>
        </article>
    </section>

    <!-- Table Section -->
    <div class="grid">
        <section class="col-12 card" style="padding: 24px;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color, #eee); text-align: right;">
                            <th style="padding: 12px; color: var(--text-muted); width: 60px;">#</th>
                            <th style="padding: 12px; color: var(--text-muted);">اسم العرض</th>
                            <th style="padding: 12px; color: var(--text-muted); max-width: 300px;">الوجبات المشمولة</th>
                            <th style="padding: 12px; color: var(--text-muted);">مدة العرض</th>
                            <th style="padding: 12px; color: var(--text-muted);">قيمة الخصم</th>
                            <th style="padding: 12px; color: var(--text-muted);">الحالة</th>
                            <th style="padding: 12px; color: var(--text-muted); text-align: center; width: 120px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            <tr style="border-bottom: 1px solid var(--border-color, #eee); transition: background 0.2s;">
                                <td style="padding: 14px 12px; font-weight: 600;">{{ $offer->id }}</td>
                                <td style="padding: 14px 12px; font-weight: 700; color: var(--primary);">{{ $offer->name }}</td>
                                <td style="padding: 14px 12px; max-width: 300px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @foreach($offer->items as $item)
                                            <span style="padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; background: var(--primary-soft, rgba(var(--primary-rgb, 124, 58, 237), 0.1)); color: var(--primary); border: 1px solid var(--primary-ring, rgba(var(--primary-rgb, 124, 58, 237), 0.2));">
                                                🍔 {{ $item->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td style="padding: 14px 12px; font-weight: 500;">
                                    <span style="display: inline-flex; align-items: center; gap: 4px; color: var(--text-muted);">
                                        📅 {{ $offer->duration }}
                                    </span>
                                </td>
                                <td style="padding: 14px 12px; font-weight: 700; color: #ef4444;">
                                    {{ number_format($offer->discount_price, 2) }} ج.م
                                </td>
                                <td style="padding: 14px 12px;">
                                    @if($offer->status)
                                        <span style="padding: 4px 10px; border-radius: 20px; font-size: 0.82rem; font-weight: 600; background: rgba(34,197,94,0.15); color: #16a34a; display: inline-block;">
                                            نشط
                                        </span>
                                    @else
                                        <span style="padding: 4px 10px; border-radius: 20px; font-size: 0.82rem; font-weight: 600; background: rgba(107,114,128,0.15); color: #4b5563; display: inline-block;">
                                            معطل
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 14px 12px; text-align: center;">
                                    <div style="display: flex; justify-content: center; gap: 8px;">
                                        <button 
                                            @click="editOpen = true; editData = { id: {{ $offer->id }}, name: {{ json_encode($offer->name) }}, duration: {{ json_encode($offer->duration) }}, discount_price: {{ json_encode($offer->discount_price) }}, status: {{ $offer->status ? 'true' : 'false' }}, item_ids: {{ json_encode($offer->items->pluck('id')) }} }"
                                            class="btn btn--ghost" 
                                            style="padding: 6px 10px; border-radius: 6px; font-size: 0.85rem;"
                                            title="تعديل"
                                        >
                                            ✏️
                                        </button>
                                        <button 
                                            @click="deleteOpen = true; deleteData = { id: {{ $offer->id }}, name: {{ json_encode($offer->name) }} }"
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
                                <td colspan="7" style="padding: 32px; text-align: center; color: var(--text-muted);">
                                    لا توجد عروض أو خصومات مسجلة حالياً.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px;">
                {{ $offers->links() }}
            </div>
        </section>
    </div>

    <!-- Modal Add Offer -->
    <div id="modal-add" class="modal-overlay is-active" x-show="addOpen" x-cloak @click.self="addOpen = false;"
        x-transition.opacity.duration.200ms>
        <div class="modal-content modal-md">
            <x-modal-head-component title="إضافة عرض ترويجي جديد" />
            <form method="POST" action="{{ route('offers.store') }}">
                @csrf
                <div class="modal-body modal-form-grid">
                    <div class="field span-2">
                        <label class="field-label">اسم العرض <span class="req">*</span></label>
                        <input type="text" name="name" required class="input" placeholder="مثال: عرض نهاية الأسبوع العائلي">
                    </div>

                    <div class="field">
                        <label class="field-label">المدة <span class="req">*</span></label>
                        <input type="text" name="duration" required class="input" placeholder="مثال: 3 أيام، أسبوع، حتى نهاية الشهر">
                    </div>
                    <div class="field">
                        <label class="field-label">قيمة الخصم (ج.م) <span class="req">*</span></label>
                        <input type="number" step="0.01" min="0" name="discount_price" required class="input" placeholder="0.00">
                    </div>

                    <div class="field span-2">
                        <label class="field-label">الوجبات المشمولة بالعرض <span class="req">*</span></label>
                        <div style="max-height: 180px; overflow-y: auto; border: 1px solid var(--border); padding: 12px; border-radius: 8px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; background: var(--bg-input);">
                            @foreach($items as $item)
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; cursor: pointer; color: var(--text);">
                                    <input type="checkbox" name="item_ids[]" value="{{ $item->id }}" style="width: 16px; height: 16px; accent-color: var(--primary);">
                                    <span>{{ $item->name }} ({{ number_format($item->price, 2) }} ج.م)</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="field span-2" style="display: flex; align-items: center; gap: 8px; flex-direction: row;">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" checked id="add_status" style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                        <label for="add_status" style="font-weight: 600; cursor: pointer; user-select: none;">تفعيل العرض فوراً</label>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" @click="addOpen = false" class="btn btn--ghost">إلغاء</button>
                    <button type="submit" class="btn btn--primary">حفظ العرض</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Offer -->
    <div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false"
        x-transition.opacity.duration.200ms>
        <div class="modal-content modal-md">
            <x-modal-head-component title="تعديل بيانات العرض الترويجي" />
            <form :action="'/offers/' + editData.id" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body modal-form-grid">
                    <div class="field span-2">
                        <label class="field-label">اسم العرض <span class="req">*</span></label>
                        <input type="text" name="name" x-model="editData.name" required class="input">
                    </div>

                    <div class="field">
                        <label class="field-label">المدة <span class="req">*</span></label>
                        <input type="text" name="duration" x-model="editData.duration" required class="input">
                    </div>
                    <div class="field">
                        <label class="field-label">قيمة الخصم (ج.م) <span class="req">*</span></label>
                        <input type="number" step="0.01" min="0" name="discount_price" x-model="editData.discount_price" required class="input">
                    </div>

                    <div class="field span-2">
                        <label class="field-label">الوجبات المشمولة بالعرض <span class="req">*</span></label>
                        <div style="max-height: 180px; overflow-y: auto; border: 1px solid var(--border); padding: 12px; border-radius: 8px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; background: var(--bg-input);">
                            @foreach($items as $item)
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.88rem; cursor: pointer; color: var(--text);">
                                    <input type="checkbox" name="item_ids[]" :value="{{ $item->id }}" x-model="editData.item_ids" style="width: 16px; height: 16px; accent-color: var(--primary);">
                                    <span>{{ $item->name }} ({{ number_format($item->price, 2) }} ج.م)</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="field span-2" style="display: flex; align-items: center; gap: 8px; flex-direction: row;">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" x-model="editData.status" id="edit_status" style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;">
                        <label for="edit_status" style="font-weight: 600; cursor: pointer; user-select: none;">تفعيل العرض</label>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" @click="editOpen = false" class="btn btn--ghost">إلغاء</button>
                    <button type="submit" class="btn btn--primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete Offer -->
    <div id="modal-delete" class="modal-overlay is-active" x-show="deleteOpen" x-cloak @click.self="deleteOpen = false"
        x-transition.opacity.duration.200ms>
        <div class="modal-content modal-sm">
            <x-modal-head-component title="تأكيد حذف العرض" />
            <form :action="'/offers/' + deleteData.id" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body modal-form-stack">
                    <div style="text-align: center; padding: 10px 0;">
                        <div style="background: var(--danger-soft); color: var(--danger); width: 64px; height: 64px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 32px; height: 32px;">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                        </div>
                        <h4 style="margin: 0 0 8px; color: var(--t-base); font-size: 16px; font-weight: 700;">هل أنت متأكد من حذف العرض؟</h4>
                        <p style="margin: 0; color: var(--t-light); font-size: 14px; line-height: 1.5;">
                            هل أنت متأكد من رغبتك في حذف العرض <strong style="color: var(--t-base);" x-text="deleteData.name"></strong>؟ لا يمكن التراجع عن هذا الإجراء.
                        </p>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn--ghost" @click="deleteOpen = false;">إلغاء</button>
                    <button type="submit" class="btn btn--danger">تأكيد الحذف</button>
                </div>
            </form>
        </div>
    </div>

</main>
@endsection
