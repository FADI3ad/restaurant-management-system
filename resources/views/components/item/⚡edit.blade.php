<?php

use App\Http\Requests\Item\UpdateItemRequest;
use App\Livewire\Forms\ItemForm;
use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Item;
use App\Services\Item\UpdateItemAction;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public ItemForm $form;

    #[On('edit-item-details')]
    public function getItemDetails($id)
    {
        $item = Item::with('subcategory.category.section')->findOrFail($id);
        $this->form->setData($item);
    }

    public function updatedFormSectionId()
    {
        $this->form->category_id = null;
        $this->form->subcategory_id = null;
    }

    public function updatedFormCategoryId()
    {
        $this->form->subcategory_id = null;
    }

    public function update(UpdateItemAction $updateItem)
    {
        $validated = $this->form->validate(UpdateItemRequest::rulesArray($this->form->item->id ?? null));

        if ($this->form->image) {
            $validated['image'] = $this->form->image;
        }

        $updateItem($this->form->item, $validated);
        $this->dispatch('close-edit-modal');
        $this->dispatch('item-changed');
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
};
?>

<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل الوجبة" />

        <form id="form-edit" wire:submit.prevent="update">
            <div class="modal-body modal-form-grid">
                <div class="field span-2">
                    <label class="field-label">اسم الوجبة <span class="req">*</span></label>
                    <input type="text" class="input" id="edit-name" required wire:model="form.name">
                    @error('form.name')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Step 1: Section --}}
                <div class="field">
                    <label class="field-label">القسم <span class="req">*</span></label>
                    <select wire:model.live="form.section_id" class="select" id="edit-section">
                        <option value="">اختر القسم...</option>
                        @foreach ($this->sections() as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Step 2: Main Category --}}
                <div class="field">
                    <label class="field-label">الصنف الرئيسي <span class="req">*</span></label>
                    <select wire:model.live="form.category_id" class="select" id="edit-category" @if(!$this->form->section_id) disabled @endif>
                        <option value="">اختر الصنف الرئيسي...</option>
                        @foreach ($this->categories($this->form->section_id) as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Step 3: Subcategory --}}
                <div class="field span-2">
                    <label class="field-label">الصنف الفرعي <span class="req">*</span></label>
                    <select wire:model="form.subcategory_id" class="select" id="edit-subcategory" @if(!$this->form->category_id) disabled @endif>
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
                    <input type="number" step="0.01" class="input" id="edit-price" required wire:model="form.price">
                    @error('form.price')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">ترتيب العرض</label>
                    <input type="number" class="input" id="edit-order" min="0" wire:model="form.display_order">
                    @error('form.display_order')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">حالة التنشيط <span class="req">*</span></label>
                    <select class="select" id="edit-status" wire:model="form.status">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                    @error('form.status')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">تغيير الصورة</label>
                    <input wire:model="form.image" type="file" class="input" accept="image/*">
                    @if($this->form->item?->image)
                        <small style="color: var(--t-light); margin-top: 4px; display: block;">الصورة الحالية: {{ basename($this->form->item->image) }}</small>
                    @endif
                    @error('form.image')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field span-2">
                    <label class="field-label">الوصف</label>
                    <textarea class="textarea" id="edit-description" wire:model="form.description"></textarea>
                    @error('form.description')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
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
