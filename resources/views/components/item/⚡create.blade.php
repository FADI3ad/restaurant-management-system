<?php

use App\Http\Requests\Item\StoreItemRequest;
use App\Livewire\Forms\ItemForm;
use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Item;
use App\Services\Item\CreateItemAction;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public ItemForm $form;
    
    public function updatedFormSectionId()
    {
        $this->form->category_id = null;
        $this->form->subcategory_id = null;
    }

    public function updatedFormCategoryId()
    {
        $this->form->subcategory_id = null;
    }

    public function save(CreateItemAction $createItem)
    {
        $validated = $this->form->validate(StoreItemRequest::rulesArray());

        if ($this->form->image) {
            $validated['image'] = $this->form->image;
        }

        $createItem($validated);

        $this->dispatch('close-add-modal');
        $this->dispatch('item-changed');
        $this->form->reset();
    }

    public function sections()
    {
        return Section::where('status', true)->get();
    }

    public function categories($sectionId)
    {
        if (!$sectionId) return [];
        return Category::where('section_id', $sectionId)->where('status', true)->get();
    }

    public function subcategories($categoryId)
    {
        if (!$categoryId) return [];
        return Subcategory::where('category_id', $categoryId)->where('status', true)->get();
    }
}
?>

<div id="modal-add" class="modal-overlay is-active" x-show="addOpen" x-cloak @click.self="addOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="إضافة وجبة جديدة" />
        <form id="form-add" wire:submit.prevent="save">
            <div class="modal-body modal-form-grid">
                <div class="field span-2">
                    <label class="field-label">اسم الوجبة <span class="req">*</span></label>
                    <input wire:model="form.name" type="text" class="input" placeholder="اسم الوجبة...">
                    @error('form.name')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Step 1: Section --}}
                <div class="field">
                    <label class="field-label">القسم <span class="req">*</span></label>
                    <select wire:model.live="form.section_id" class="select">
                        <option value="">اختر القسم...</option>
                        @foreach ($this->sections() as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Step 2: Main Category --}}
                <div class="field">
                    <label class="field-label">الصنف الرئيسي <span class="req">*</span></label>
                    <select wire:model.live="form.category_id" class="select" @if(!$this->form->section_id) disabled @endif>
                        <option value="">اختر الصنف الرئيسي...</option>
                        @foreach ($this->categories($this->form->section_id) as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Step 3: Subcategory --}}
                <div class="field span-2">
                    <label class="field-label">الصنف الفرعي <span class="req">*</span></label>
                    <select wire:model="form.subcategory_id" class="select" @if(!$this->form->category_id) disabled @endif>
                        <option value="">اختر الصنف الفرعي...</option>
                        @foreach ($this->subcategories($this->form->category_id) as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                    @error('form.subcategory_id')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">السعر (ر.س) <span class="req">*</span></label>
                    <input wire:model="form.price" type="number" step="0.01" class="input" placeholder="السعر...">
                    @error('form.price')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">ترتيب العرض</label>
                    <input wire:model="form.display_order" type="number" class="input" placeholder="0" min="0">
                    @error('form.display_order')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">حالة التنشيط <span class="req">*</span></label>
                    <select wire:model="form.status" class="select">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                    @error('form.status')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">صورة الوجبة</label>
                    <input wire:model="form.image" type="file" class="input" accept="image/*">
                    @error('form.image')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field span-2">
                    <label class="field-label">الوصف</label>
                    <textarea wire:model="form.description" class="textarea" placeholder="اكتب وصفاً للوجبة..."></textarea>
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
