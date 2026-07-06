<?php

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $id = '';

    public function mount()
    {
        $this->categories();
    }

    //get paginated categories
    #[Computed]
    public function categories()
    {
        return Category::orderBy('display_order')->paginate(3);
    }

    #[On('category-changed')]
    public function refreshTable()
    {
        $this->categories();
    }

    //Handle Show Category Details
    public function makeShowEvent($id)
    {
        $this->dispatch('show-category-details', $id);
    }

    //Handle Edit Category
    public function makeEditEvent($id)
    {
        $this->dispatch('edit-category-details', $id);
    }

    //Handle Delete Category
    public function makeDeleteEvent($id)
    {
        $this->dispatch('confirm-category-delete', $id);
    }
};
?>

<div>
    <div class="table-scroll">
        <table class="table">
            <thead>
                <tr>
                    <th>الفئة</th>
                    <th>القسم</th>
                    <th>عدد العناصر</th>
                    <th>الترتيب</th>
                    <th>الحالة</th>
                    <th>العمليات</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($this->categories() as $category)
                    <tr>
                        <td class="cell-name">{{ $category->name }}</td>
                        <td>{{ $category->section }}</td>

                        <td>{{ $category->items_count }} عنصر</td>
                        <td>
                            <div class="order-controls">
                                <span class="badge-order">{{ $category->display_order }}</span>
                            </div>
                        </td>
                        @if ($category->status)
                            <td><span class="tag t-active">نشط</span></td>
                        @else
                            <td><span class="tag t-inactive">غير نشط</span></td>
                        @endif
                        <td>
                            <div class="data-cell-actions">
                                <button type="button" class="btn-action-icon btn--soft-info" title="عرض التفاصيل"
                                    @click="await $wire.makeShowEvent({{ $category->id }}); showOpen = true;">
                                    <svg viewBox="0
                                    0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-icon btn--soft-primary" title="تعديل"
                                    @click="await $wire.makeEditEvent({{ $category->id }}); editOpen = true;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>
                                <button type="button" class="btn-action-icon btn--soft-danger" title="حذف"
                                    @click="await $wire.makeDeleteEvent({{ $category->id }}); deleteOpen = true;">
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
    {{ $this->categories()->links() }}
</div>
