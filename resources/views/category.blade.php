@extends('layouts.app')
@section('title', 'الأصناف الرئيسية')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')

    <main class="content" x-data="{ addOpen: false, showOpen: false, editOpen: false, deleteOpen: false }">

        <x-hero-section-component title="إدارة الأصناف الرئيسية" des="إدارة الأصناف الرئيسية وتعديل حالتها وترتيب ظهورها." btnText="إضافة صنف"/>

        <div class="grid">
            <section class="col-12 card">

                <!-- Table -->
                <livewire:category.index />

            </section>
        </div>

        <!--Add Category Modal-->
        <livewire:category.create />

        <!--Show Category Modal-->
        <livewire:category.show />

        <!--Edit Category Modal-->
        <livewire:category.edit />

        <!--Delete Category Modal-->
        <livewire:category.delete />

    </main>

@endsection
