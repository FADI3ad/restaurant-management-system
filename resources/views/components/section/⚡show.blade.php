<?php

use App\Livewire\Forms\SectionForm;
use App\Models\Section;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {

    public SectionForm $form;

    #[On('show-section-details')]
    public function getSectionDetails($id)
    {
        $section = Section::findOrfail($id)->loadCount('categories');

        $this->form->setData($section);
    }

};
?>



<div id="modal-show" class="modal-overlay is-active" x-show="showOpen" x-cloak @click.self="showOpen = false"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="تفاصيل قسم المنيو" />

        <div class="modal-body modal-form-stack">
            <div class="modal-details-grid">
                <div class="detail-item">
                    <span class="detail-label">اسم قسم المنيو</span>
                    <span class="detail-value" id="show-name">{{ $this->form->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">حالة التنشيط</span>
                    <span class="detail-value" id="show-status">{{ $this->form->status ? 'نشط' : 'غير نشط' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">عدد الأصناف الرئيسية</span>
                    <span class="detail-value" id="show-categories">{{ $this->form->section->categories_count ?? 0 }}</span>
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
