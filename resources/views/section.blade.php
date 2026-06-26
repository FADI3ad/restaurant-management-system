@extends('layouts.app')
@section('title', 'الاقسام الاساسية')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')
    <main class="content">


        <x-hero-section-component title="أقسام المينيو الأساسية" des="إدارة أقسام المنيو والوجبات الأساسية وتعديل حالتها وترتيب ظهورها."/>


        <div class="grid">
            <section class="col-12 card">
                <!-- Smart Filter System -->
                <div class="smart-filter-bar">
                    <div class="filter-search">
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"
                                        stroke-linecap="round"></line>
                                </svg>
                            </span>
                            <input type="text" class="input" placeholder="ابحث عن قسم (مثال: المأكولات)...">
                        </div>
                    </div>
                    <div class="filter-actions">
                        <select class="select filter-select">
                            <option value="">جميع الحالات</option>
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                        <select class="select filter-select">
                            <option value="">ترتيب حسب</option>
                            <option value="order_asc">الترتيب تصاعدي</option>
                            <option value="order_desc">الترتيب تنازلي</option>
                        </select>
                        <button type="button" class="btn btn-filter">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            تصفية
                        </button>
                    </div>
                </div>

                <div class="table-scroll">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>القسم</th>
                                <th>الحالة</th>
                                <th>الفئات</th>
                                <th>الترتيب</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- قسم المأكولات -->
                            <tr>
                                <td class="cell-name">قسم المأكولات</td>
                                <td><span class="tag t-active">نشط</span></td>
                                <td>14 فئة</td>
                                <td>
                                    <div class="order-controls">
                                        <span class="badge-order">1</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="data-cell-actions">
                                        <button type="button" class="btn-action-icon btn--soft-info" title="عرض التفاصيل"
                                            onclick="showDetails('قسم المأكولات', 'نشط', '14 فئة', '1', 'هذا القسم يحتوي على جميع المأكولات الرئيسية والوجبات والطلب الجانبي الخاص بالمطعم.')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                        <button type="button" class="btn-action-icon btn--soft-primary" title="تعديل"
                                            onclick="editSection('قسم المأكولات', '1', 'هذا القسم يحتوي على جميع المأكولات الرئيسية والوجبات والطلب الجانبي الخاص بالمطعم.', '1')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>
                                        <button type="button" class="btn-action-icon btn--soft-danger" title="حذف"
                                            onclick="alert('تم حذف القسم بنجاح (معاينة)')">
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

                        </tbody>
                    </table>
                </div>
                <div class="table-pagination">
                    <span>عرض
                        <strong class="txt-base">3 من أصل 3</strong>
                        أقسام</span>
                    <a class="card-action" href="#">عرض الكل
                        <svg viewBox="0 0 24 24">
                            <path d="M5 12h14M13 5l7 7-7 7" />
                        </svg></a>
                </div>
            </section>
        </div>


        
    </main>

    <!-- مودال إضافة قسم -->
    <div id="modal-add" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-head">
                <h3 class="modal-title">إضافة قسم جديد</h3>
                <span class="modal-close" onclick="closeModal('modal-add')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            </div>
            <form id="form-add"
                onsubmit="event.preventDefault(); alert('تمت إضافة القسم بنجاح (معاينة)'); closeModal('modal-add');">
                <div class="modal-body modal-form-stack">
                    <div class="field">
                        <label class="field-label">اسم القسم <span class="req">*</span></label>
                        <input type="text" class="input" placeholder="مثال: قسم المأكولات، المشروبات..." required>
                    </div>
                    <div class="field">
                        <label class="field-label">ترتيب العرض</label>
                        <input type="number" class="input" placeholder="0" min="0" value="0">
                    </div>
                    <div class="field">
                        <label class="field-label">الوصف</label>
                        <textarea class="textarea" placeholder="اكتب وصفاً مختصراً للقسم..."></textarea>
                    </div>
                    <div class="field">
                        <label class="field-label">حالة التنشيط</label>
                        <select class="select">
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn--ghost" onclick="closeModal('modal-add')">إلغاء</button>
                    <button type="submit" class="btn btn--primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- مودال عرض التفاصيل -->
    <div id="modal-show" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-head">
                <h3 class="modal-title">تفاصيل القسم</h3>
                <span class="modal-close" onclick="closeModal('modal-show')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            </div>
            <div class="modal-body modal-form-stack">
                <div class="modal-details-grid">
                    <div class="detail-item">
                        <span class="detail-label">اسم القسم</span>
                        <span class="detail-value" id="show-name"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">حالة التنشيط</span>
                        <span class="detail-value" id="show-status"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">عدد الفئات</span>
                        <span class="detail-value" id="show-categories"></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">ترتيب العرض</span>
                        <span class="detail-value" id="show-order"></span>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label">الوصف</label>
                    <p class="modal-desc-text" id="show-description"></p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--primary" onclick="closeModal('modal-show')">إغلاق</button>
            </div>
        </div>
    </div>

    <!-- مودال تعديل القسم -->
    <div id="modal-edit" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-head">
                <h3 class="modal-title">تعديل القسم</h3>
                <span class="modal-close" onclick="closeModal('modal-edit')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            </div>
            <form id="form-edit"
                onsubmit="event.preventDefault(); alert('تم تعديل القسم بنجاح (معاينة)'); closeModal('modal-edit');">
                <div class="modal-body modal-form-stack">
                    <div class="field">
                        <label class="field-label">اسم القسم <span class="req">*</span></label>
                        <input type="text" class="input" id="edit-name" required>
                    </div>
                    <div class="field">
                        <label class="field-label">ترتيب العرض</label>
                        <input type="number" class="input" id="edit-order" min="0">
                    </div>
                    <div class="field">
                        <label class="field-label">الوصف</label>
                        <textarea class="textarea" id="edit-description"></textarea>
                    </div>
                    <div class="field">
                        <label class="field-label">حالة التنشيط</label>
                        <select class="select" id="edit-status">
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn--ghost" onclick="closeModal('modal-edit')">إلغاء</button>
                    <button type="submit" class="btn btn--primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('is-active');
                }, 10);
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('is-active');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 250);
            }
        }

        function showDetails(name, status, categories, order, description) {
            document.getElementById('show-name').textContent = name;

            const statusEl = document.getElementById('show-status');
            if (status === 'نشط') {
                statusEl.innerHTML = '<span class="tag t-active">نشط</span>';
            } else {
                statusEl.innerHTML = '<span class="tag t-inactive">غير نشط</span>';
            }

            document.getElementById('show-categories').textContent = categories;
            document.getElementById('show-order').textContent = order;
            document.getElementById('show-description').textContent = description;
            openModal('modal-show');
        }

        function editSection(name, order, description, statusVal) {
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-order').value = order;
            document.getElementById('edit-description').value = description;
            document.getElementById('edit-status').value = statusVal;
            openModal('modal-edit');
        }

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                closeModal(event.target.id);
            }
        });
    </script>

@endsection
