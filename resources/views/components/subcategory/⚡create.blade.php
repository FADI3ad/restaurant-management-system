<?php

use App\Http\Requests\Subcategory\StoreSubcategoryRequest;
use App\Livewire\Forms\SubcategoryForm;
use App\Models\Category;
use App\Models\Section;
use App\Services\Subcategory\CreateSubcategoryAction;
use Livewire\Component;

new class extends Component {
    public SubcategoryForm $form;

    public function save(CreateSubcategoryAction $createSubcategory)
    {
        $validated = $this->form->validate(StoreSubcategoryRequest::rulesArray());

        $createSubcategory($validated);

        $this->dispatch('close-add-modal');
        $this->dispatch('subcategory-changed');
        $this->form->reset();
    }

    public function sections()
    {
        return Section::where('status', true)->orderBy('display_order')->get();
    }

    public function categories($sectionId = null)
    {
        return Category::query()
            ->when($sectionId, function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            })
            ->where('status', true)
            ->orderBy('display_order')
            ->get();
    }
};
?>

<div id="modal-add" class="modal-overlay is-active" x-show="addOpen" x-cloak @click.self="addOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="إضافة صنف فرعي جديد" />
        <form id="form-add" wire:submit.prevent="save">
            <div class="modal-body modal-form-grid">
                <div class="field span-2">
                    <label class="field-label">اسم الصنف الفرعي <span class="req">*</span></label>
                    <input wire:model="form.name" type="text" class="input" placeholder="اسم الصنف الفرعي...">
                    @error('form.name')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">القسم</label>
                    <select wire:model.live="form.section_id" class="select" wire:change="$set('form.category_id', null)">
                        <option value="">اختر القسم</option>
                        @foreach ($this->sections() as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('form.section_id')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">الصنف الرئيسي <span class="req">*</span></label>
                    <select wire:model="form.category_id" class="select">
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
                    <input wire:model="form.display_order" type="number" class="input" placeholder="0" min="0">
                    @error('form.display_order')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="field-label">حالة التنشيط</label>
                    <select wire:model="form.status" class="select">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                    @error('form.status')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
                <div class="field span-2">
                    <label class="field-label">الوصف</label>
                    <textarea wire:model="form.description" class="textarea" placeholder="اكتب وصفاً للصنف الفرعي..."></textarea>
                    @error('form.description')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="addOpen = false">إلغاء</button>
                <button type="submit" class="btn btn--primary" @close-add-modal.window="addOpen = false">
                    حفظ
                </button>
            </div>
        </form>
    </div>
</div>
