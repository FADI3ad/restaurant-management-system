<?php

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {

    public $category = '';

    public $name = '';
    public $section = '';
    public $items_count = 0;
    public $display_order = 0;
    public $description = '';
    public $status = 1;

    #[On('show-category-details')]
    public function getCategoryDetails($id)
    {
        $category = Category::findOrfail($id);
        $this->category = $category;
        $this->setData();
    }

    public function setData(){
        $this->name = $this->category->name;
        $this->section = $this->category->section;
        $this->items_count = $this->category->items_count;
        $this->display_order = $this->category->display_order;
        $this->description = $this->category->description;
        $this->status = $this->category->status;
    }
};
?>



<div id="modal-show" class="modal-overlay is-active" x-show="showOpen" x-cloak @click.self="showOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تفاصيل الفئة" />

        <div class="modal-body modal-form-stack">
            <div class="modal-details-grid">
                <div class="detail-item">
                    <span class="detail-label">اسم الفئة</span>
                    <span class="detail-value" id="show-name">{{ $this->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">حالة التنشيط</span>
                    <span class="detail-value" id="show-status">{{ $this->status ? 'نشط' : 'غير نشط' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">القسم</span>
                    <span class="detail-value" id="show-section">{{ $this->section }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">عدد العناصر</span>
                    <span class="detail-value" id="show-items-count">{{ $this->items_count }}</span>
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
