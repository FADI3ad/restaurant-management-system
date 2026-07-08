@extends('layouts.app')
@section('title', 'الوجبات')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')

    <main class="content" x-data="{ addOpen: false, showOpen: false, editOpen: false, deleteOpen: false }">


        <x-hero-section-component title="إدارة الوجبات" des="إدارة الوجبات وتعديل حالتها وترتيب ظهورها." />


        <div class="grid">
            <section class="col-12 card">

                <!--Filter System -->
                <div class="smart-filter-bar">
                    <div class="filter-search">
                        <div class="input-icon">
                            <span class="ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"
                                        stroke-linecap="round"></line>
                                </svg>
                            </span>
                            <input type="text" class="input" placeholder="ابحث عن عنصر...">
                        </div>
                    </div>
                    <div class="filter-actions">
                        <select class="select filter-select">
                            <option value="">جميع الحالات</option>
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                        <select class="select filter-select">
                            <option value="">ترتيب حسب</option>
                            <option value="order_asc">الترتيب تصاعدي</option>
                            <option value="order_desc">الترتيب تنازلي</option>
                        </select>
                        <button type="button" class="btn btn-filter">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            تصفية
                        </button>
                    </div>
                </div>


                <!-- Table -->
                <livewire:item.index />

            </section>
        </div>

        <!--Add Item Modal-->
        <livewire:item.create />

        <!--Show Item Modal-->
        <livewire:item.show />

        <!--Edit Item Modal-->
        <livewire:item.edit />

        <!--Delete Item Modal-->
        <livewire:item.delete />

    </main>

@endsection
