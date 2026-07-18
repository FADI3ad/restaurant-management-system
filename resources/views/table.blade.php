@extends('layouts.app')
@section('title', 'ادارة الطاولات')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')
    <main class="content" x-data="{ addOpen: false, editOpen: false, deleteOpen: false, showOpen: false }">

        <x-hero-section-component title="إدارة الطاولات"
            des="قم بإدارة طاولات المطعم، وأضف طاولات جديدة، وحدد سعتها وحالتها الحالية." 
            btnText="إضافة طاولة"
        />

        <div class="grid">
            <section class="col-12 card">
                {{-- <div class="tables-toolbar" id="tables-toolbar" role="toolbar" aria-label="فلاتر الطاولات">
                    <span class="tables-toolbar-label">النوع</span>
                    <div class="tables-toolbar-group" role="group">
                        <button class="filter-chip is-active" id="chip-type-all" type="button">
                            <span class="filter-chip-dot" style="background:var(--primary)"></span>الكل
                        </button>
                        <button class="filter-chip" id="chip-type-private" type="button">
                            <span class="filter-chip-dot" style="background:var(--purple)"></span>خاص
                        </button>
                        <button class="filter-chip" id="chip-type-public" type="button">
                            <span class="filter-chip-dot" style="background:var(--info)"></span>عام
                        </button>
                    </div>
                    <div class="tables-toolbar-sep" aria-hidden="true"></div>
                    <span class="tables-toolbar-label">الحالة</span>
                    <div class="tables-toolbar-group" role="group">
                        <button class="filter-chip is-active" id="chip-status-all" type="button">
                            <span class="filter-chip-dot" style="background:var(--primary)"></span>الكل
                        </button>
                        <button class="filter-chip" id="chip-status-available" type="button">
                            <span class="filter-chip-dot" style="background:var(--success)"></span>متاح
                        </button>
                        <button class="filter-chip" id="chip-status-occupied" type="button">
                            <span class="filter-chip-dot" style="background:var(--danger)"></span>مشغول
                        </button>
                        <button class="filter-chip" id="chip-status-reserved" type="button">
                            <span class="filter-chip-dot" style="background:var(--warning)"></span>محجوز
                        </button>
                        <button class="filter-chip" id="chip-status-maintenance" type="button">
                            <span class="filter-chip-dot" style="background:var(--secondary)"></span>صيانة
                        </button>
                    </div>
                </div> --}}




                
                <!-- Table Index -->
                <livewire:table.index />
            </section>
        </div>

        <!-- Create Table Modal -->
        <livewire:table.create />

        <!-- Edit Table Modal -->
        <livewire:table.edit />

        <!-- Delete Table Modal -->
        <livewire:table.delete />

        <!-- Show Table Modal -->
        <livewire:table.show />

    </main>
@endsection
