<?php

use Livewire\Component;

new class extends Component {
    
    public Section $section;

    

};
?>

<div>
    <div id="modal-edit" class="modal-overlay">
        <div class="modal-content">
            <x-modal-head-component title="تعديل القسم" />
            <form id="form-edit" onsubmit="event.preventDefault();  closeModal('modal-edit');">
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
</div>
