<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <div id="modal-show" class="modal-overlay">
        <div class="modal-content">
            <x-modal-head-component title="تفاصيل القسم" />

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
</div>
