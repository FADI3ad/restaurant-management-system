@extends('layouts.app')
@section('title', 'الأصناف الرئيسية الفرعية')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')

    <main class="content" x-data="{ addOpen: false, showOpen: false, editOpen: false, deleteOpen: false }">

        <x-hero-section-component title="إدارة الأصناف الرئيسية الفرعية"
            des="إدارة الأصناف الرئيسية الفرعية وتعديل حالتها وترتيب ظهورها." 
            btnText="إضافة صنف فرعي"
        />

        <div class="grid">
            <section class="col-12 card">

                <!-- Table -->
                <livewire:subcategory.index />

            </section>
        </div>

        <!--Add Subcategory Modal-->
        <livewire:subcategory.create />

        <!--Show Subcategory Modal-->
        <livewire:subcategory.show />

        <!--Edit Subcategory Modal-->
        <livewire:subcategory.edit />

        <!--Delete Subcategory Modal-->
        <livewire:subcategory.delete />

    </main>

@endsection
