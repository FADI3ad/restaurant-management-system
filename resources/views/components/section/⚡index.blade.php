<?php

use App\Models\Section;
use Livewire\Component;
use Livewire\Attributes\On;
new class extends Component {
    public $sections;
    
    public function mount()
    {
        $this->loadSections();
    }

    public function loadSections()
    {
        $this->sections = Section::orderBy('display_order')->get();
    }

    #[On('section-changed')]
    public function refreshTable()
    {
        $this->loadSections();
    }
};
?>

<div>
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
                @foreach ($sections as $section)
                    <tr>
                        <td class="cell-name">{{ $section->name }}</td>
                        <td><span class="tag t-active">نشط</span></td>
                        <td>14 فئة</td>
                        <td>
                            <div class="order-controls">
                                <span class="badge-order">{{ $section->display_order }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="data-cell-actions">
                                <button type="button" class="btn-action-icon btn--soft-info" title="عرض التفاصيل"
                                    onclick="showDetails()">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-icon btn--soft-primary" title="تعديل"
                                    onclick="editSection()">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-icon btn--soft-danger" title="حذف" >
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
                @endforeach
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
</div>
