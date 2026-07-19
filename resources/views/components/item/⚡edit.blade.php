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

new class extends Component {
    public ItemForm $form;

    #[On('edit-item-details')]
    public function getItemDetails($id)
    {
        $item = Item::findOrFail($id);
        $this->form->setData($item);
    }

    public function update(UpdateItemAction $updateItem)
    {
        $validated = $this->form->validate(UpdateItemRequest::rulesArray($this->form->item->id ?? null));

        $updateItem($this->form->item, $validated);
        $this->dispatch('close-edit-modal');
        $this->dispatch('item-changed');
    }

    public function sections()
    {
        return Section::all();
    }

    public function categories($id)
    {
        if (!$id) {
            return [];
        }
        return Category::where('section_id', $id)->get();
    }

    public function subcategories($id)
    {
        if (!$id) {
            return [];
        }
        return Subcategory::where('category_id', $id)->get();
    }
};
?>


<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false">
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل الوجبة" />

        <form id="form-edit" wire:submit.prevent="update">
            <div class="modal-body modal-form-grid">
                <div class="field span-2">
                    <label class="field-label">اسم الوجبة <span class="req">*</span></label>
                    <input type="text" class="input" id="edit-name" required value="{{ $this->form->name }}"
                        wire:model="form.name">
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
                <div class="field">
                    <label class="field-label">السعر <span class="req">*</span></label>
                    <input type="number" step="0.01" class="input" id="edit-price" required
                        value="{{ $this->form->price }}" wire:model="form.price">
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
                    <input type="number" class="input" id="edit-order" min="0"
                        value="{{ $this->form->display_order }}" wire:model="form.display_order">
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
                    <label class="field-label">القسم</label>
                    <select wire:model.live="form.section_id" class="select" id="edit-section">
                        <option value="" wire:click="$set('form.section_id', null)">اختر القسم</option>
                        @foreach ($this->sections() as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('form.section_id')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>
                @if ($this->form->section_id)
                    <div class="field">
                        <label class="field-label">الصنف الرئيسي</label>
                        <select wire:model.live="form.category_id" class="select" id="edit-category">
                            <option value="" wire:click="$set('form.category_id', null)">اختر الصنف الرئيسي</option>
                            @foreach ($this->categories($this->form->section_id) as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('form.category_id')
                            <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14"
                                    height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg> {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                @if ($this->form->category_id)
                    <div class="field">
                        <label class="field-label">الصنف الفرعي</label>
                        <select wire:model="form.subcategory_id" class="select" id="edit-subcategory">
                            <option value="" wire:click="$set('form.subcategory_id', null)">اختر الصنف الفرعي</option>
                            @foreach ($this->subcategories($this->form->category_id) as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        @error('form.subcategory_id')
                            <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14"
                                    height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg> {{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="field">
                    <label class="field-label">حالة التنشيط</label>
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
                <div class="field span-2">
                    <label class="field-label">الوصف</label>
                    <textarea class="textarea" id="edit-description" wire:model="form.description">{{ $this->form->description }}</textarea>
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
