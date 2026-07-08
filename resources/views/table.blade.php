@extends('layouts.app')
@section('title', 'ادارة الطاولات')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')
    <main class="content" x-data="{ addOpen: false, editOpen: false, deleteOpen: false }">

        <x-hero-section-component title="إدارة الطاولات"
            des="قم بإدارة طاولات المطعم، وأضف طاولات جديدة، وحدد سعتها وحالتها الحالية." 
        />

        <div class="grid">
            <section class="col-12 card">
                <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
                    <button type="button" class="btn btn--primary" @click="addOpen = true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        إضافة طاولة
                    </button>
                </div>
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

    </main>
@endsection
