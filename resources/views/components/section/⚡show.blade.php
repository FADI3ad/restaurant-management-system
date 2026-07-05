<?php

use App\Models\Section;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {

public ?Section $section = null;
    public $name = '';
    public $display_order = 0;
    public $description = '';
    public $status = 1;



    #[On('show-section-details')]
    public function getSectionDetails($id)
    {
        $section = Section::findOrfail($id);
        $this->section = $section;
        $this->setData();
        
    }

    public function setData(){
        $this->name = $this->section->name;
        $this->display_order = $this->section->display_order;
        $this->description = $this->section->description;
        $this->status = $this->section->status;
    }
};
?>



<div id="modal-show" class="modal-overlay is-active" x-show="showOpen" x-cloak @click.self="showOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تفاصيل القسم" />

        <div class="modal-body modal-form-stack">
            <div class="modal-details-grid">
                <div class="detail-item">
                    <span class="detail-label">اسم القسم</span>
                    <span class="detail-value" id="show-name">{{ $this->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">حالة التنشيط</span>
                    <span class="detail-value" id="show-status">{{ $this->status ? 'نشط' : 'غير نشط' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">عدد الفئات</span>
                    {{-- <span class="detail-value" id="show-categories">{{ $this->section->categories->count() }}</span> --}}
                </div>
                <div class="detail-item">
                    <span class="detail-label">ترتيب العرض</span>
                    <span class="detail-value" id="show-order">{{ $this->display_order }}</span>
                </div>
            </div>
            <div class="field">
                <label class="field-label">الوصف</label>
                <p class="modal-desc-text" id="show-description">{{ $this->description }}</p>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn--primary" @click="showOpen = false">إغلاق</button>
        </div>
    </div>
</div>
