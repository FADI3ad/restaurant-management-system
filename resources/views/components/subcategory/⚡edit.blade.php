<?php

use App\Http\Requests\Subcategory\UpdateSubcategoryRequest;
use App\Livewire\Forms\SubcategoryForm;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Section;
use App\Services\Subcategory\UpdateSubcategoryAction;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public SubcategoryForm $form;

    #[On('edit-subcategory-details')]
    public function getSubcategoryDetails($id)
    {
        $subcategory = Subcategory::with('category.section')->findOrFail($id);
        $this->form->setData($subcategory);
    }

    public function update(UpdateSubcategoryAction $updateSubcategory)
    {
        $validated = $this->form->validate(UpdateSubcategoryRequest::rulesArray($this->form->subcategory->id ?? null));

        $updateSubcategory($this->form->subcategory, $validated);

        $this->dispatch('close-edit-modal');
        $this->dispatch('subcategory-changed');
    }

    public function sections()
    {
        return Section::orderBy('display_order')->get();
    }

    public function categories($sectionId = null)
    {
        return Category::query()
            ->when($sectionId, function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            })
            ->orderBy('display_order')
            ->get();
    }
};
?>

<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل الصنف الفرعي" />

        <form id="form-edit" wire:submit.prevent="update">
            <div class="modal-body modal-form-grid">
                <div class="field span-2">
                    <label class="field-label">اسم الصنف الفرعي <span class="req">*</span></label>
                    <input type="text" class="input" id="edit-name" value="{{ $this->form->name }}"
                        wire:model.defer="form.name">
                    @error('form.name')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">القسم</label>
                    <select class="select" id="edit-section" wire:model.live="form.section_id" wire:change="$set('form.category_id', null)">
                        <option value="">اختر القسم</option>
                        @foreach ($this->sections() as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field-label">الصنف الرئيسي <span class="req">*</span></label>
                    <select class="select" id="edit-category" wire:model.defer="form.category_id">
                        <option value="">اختر الصنف الرئيسي</option>
                        @foreach ($this->categories($this->form->section_id) as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('form.category_id')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">ترتيب العرض <span class="req">*</span></label>
                    <input type="number" class="input" id="edit-order" min="0"
                        value="{{ $this->form->display_order }}" wire:model.defer="form.display_order">
                    @error('form.display_order')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">حالة التنشيط</label>
                    <select class="select" id="edit-status" wire:model.defer="form.status">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                    @error('form.status')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field span-2">
                    <label class="field-label">الوصف</label>
                    <textarea class="textarea" id="edit-description" wire:model.defer="form.description">{{ $this->form->description }}</textarea>
                    @error('form.description')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="editOpen = false">إلغاء</button>
                <button type="submit" class="btn btn--primary" @close-edit-modal.window="editOpen = false">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
