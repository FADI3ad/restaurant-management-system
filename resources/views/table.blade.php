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
