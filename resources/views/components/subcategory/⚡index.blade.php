<?php

use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Section;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sectionFilter = '';
    public $categoryFilter = '';

    #[Computed]
    public function subcategories()
    {
        return Subcategory::query()
            ->with(['category.section'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->sectionFilter !== '', function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('section_id', $this->sectionFilter);
                });
            })
            ->when($this->categoryFilter !== '', function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->orderBy('display_order')
            ->paginate(5);
    }

    #[Computed]
    public function sections()
    {
        return Section::orderBy('display_order')->get();
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->when($this->sectionFilter !== '', function ($q) {
                $q->where('section_id', $this->sectionFilter);
            })
            ->orderBy('display_order')
            ->get();
    }

    #[On('subcategory-changed')]
    public function refreshTable()
    {
        $this->subcategories();
    }

    public function makeShowEvent($id)
    {
        $this->dispatch('show-subcategory-details', $id);
    }

    public function makeEditEvent($id)
    {
        $this->dispatch('edit-subcategory-details', $id);
    }

    public function makeDeleteEvent($id)
    {
        $this->dispatch('confirm-subcategory-delete', $id);
    }
};
?>

<div>
    <div class="smart-filter-bar">
        <div class="filter-search">
            <div class="input-icon">
                <span class="ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                </span>
                <input type="text" class="input" placeholder="ابحث عن صنف فرعي..."
                    wire:model.live.debounce.300ms="search" />
            </div>
        </div>
        <div class="filter-actions">
            <select class="select filter-select" wire:model.live="sectionFilter" wire:change="$set('categoryFilter', '')">
                <option value="">جميع الأقسام</option>
                @foreach ($this->sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
            <select class="select filter-select" wire:model.live="categoryFilter">
                <option value="">جميع الأصناف الرئيسية</option>
                @foreach ($this->categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <select class="select filter-select" wire:model.live="statusFilter">
                <option value="">جميع الحالات</option>
                <option value="1">نشط</option>
                <option value="0">غير نشط</option>
            </select>
            <button type="button" class="btn btn-filter" wire:click="$reset('search', 'statusFilter', 'sectionFilter', 'categoryFilter')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                تصفية
            </button>
        </div>
    </div>
    <div class="table-scroll">
        <div style="overflow-x: auto; width: 100%;">
            <table class="table">
                <thead>
                    <tr>
                        <th>الصنف الفرعي</th>
                        <th>الصنف الرئيسي</th>
                        <th>القسم</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($this->subcategories as $subcategory)
                        <tr>
                            <td class="cell-name">{{ $subcategory->name }}</td>
                            <td>{{ $subcategory->category->name ?? '-' }}</td>
                            <td>{{ $subcategory->category->section->name ?? '-' }}</td>
                            <td>
                                <div class="order-controls">
                                    <span class="badge-order">{{ $subcategory->display_order }}</span>
                                </div>
                            </td>
                            @if ($subcategory->status)
                                <td><span class="tag t-active">نشط</span></td>
                            @else
                                <td><span class="tag t-inactive">غير نشط</span></td>
                            @endif
                            <td>
                                <div class="data-cell-actions">
                                    <button type="button" class="btn-action-icon btn--soft-info" title="عرض التفاصيل"
                                        @click="await $wire.makeShowEvent({{ $subcategory->id }}); showOpen = true;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-action-icon btn--soft-primary" title="تعديل"
                                        @click="await $wire.makeEditEvent({{ $subcategory->id }}); editOpen = true;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-action-icon btn--soft-danger" title="حذف"
                                        @click="await $wire.makeDeleteEvent({{ $subcategory->id }}); deleteOpen = true;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $this->subcategories()->links() }}
</div>
