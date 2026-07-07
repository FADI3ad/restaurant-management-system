<?php

use App\Models\Subcategory;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {

    public $subcategory = null;

    public $name = '';
    public $category_name = '';
    public $display_order = 0;
    public $description = '';
    public $status = 1;

    #[On('show-subcategory-details')]
    public function getSubcategoryDetails($id)
    {
        $subcategory = Subcategory::with('category')->findOrfail($id);
        $this->subcategory = $subcategory;
        $this->setData();
    }

    public function setData(){
        $this->name = $this->subcategory->name;
        $this->category_name = $this->subcategory->category ? $this->subcategory->category->name : '-';
        $this->display_order = $this->subcategory->display_order;
        $this->description = $this->subcategory->description;
        $this->status = $this->subcategory->status;
    }
};
?>

<div id="modal-show" class="modal-overlay is-active" x-show="showOpen" x-cloak @click.self="showOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تفاصيل الفئة الفرعية" />

        <div class="modal-body modal-form-stack">
            <div class="modal-details-grid">
                <div class="detail-item">
                    <span class="detail-label">اسم الفئة الفرعية</span>
                    <span class="detail-value" id="show-name">{{ $this->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">حالة التنشيط</span>
                    <span class="detail-value" id="show-status">{{ $this->status ? 'نشط' : 'غير نشط' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">الفئة الأساسية</span>
                    <span class="detail-value" id="show-category">{{ $this->category_name }}</span>
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
