<?php

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public CategoryForm $form;

    #[On('show-category-details')]
    public function getCategoryDetails($id)
    {
        $category = Category::with('section')->withCount('subcategories')->findOrFail($id);
        $this->form->setData($category);
    }
};
?>

<div id="modal-show" class="modal-overlay is-active" x-show="showOpen" x-cloak @click.self="showOpen = false"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="تفاصيل الصنف الرئيسي" />

        <div class="modal-body modal-form-stack">
            <div class="modal-details-grid">
                <div class="detail-item">
                    <span class="detail-label">اسم الصنف الرئيسي</span>
                    <span class="detail-value" id="show-name">{{ $this->form->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">حالة التنشيط</span>
                    <span class="detail-value" id="show-status">{{ $this->form->status ? 'نشط' : 'غير نشط' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">القسم</span>
                    <span class="detail-value" id="show-section">{{ $this->form->category->section->name ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">عدد الأصناف الفرعية</span>
                    <span class="detail-value" id="show-subcategories-count">{{ $this->form->category->subcategories_count ?? 0 }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ترتيب العرض</span>
                    <span class="detail-value" id="show-order">{{ $this->form->display_order }}</span>
                </div>
            </div>
            <div class="field">
                <label class="field-label">الوصف</label>
                <p class="modal-desc-text" id="show-description">{{ $this->form->description }}</p>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn--primary" @click="showOpen = false">إغلاق</button>
        </div>
    </div>
</div>
