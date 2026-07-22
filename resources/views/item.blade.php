@extends('layouts.app')
@section('title', 'الوجبات')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')

    <main class="content" x-data="{ addOpen: false, showOpen: false, editOpen: false, deleteOpen: false }">

        <x-hero-section-component title="إدارة الوجبات" des="إدارة الوجبات وتعديل حالتها وترتيب ظهورها." btnText="إضافة وجبة"/>

        <div class="grid">
            <section class="col-12 card">

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
