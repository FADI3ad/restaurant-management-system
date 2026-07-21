@extends('layouts.app')
@section('title', 'إدارة الحجوزات')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')
    <main class="content" x-data="{ addOpen: false, editOpen: false, deleteOpen: false, showOpen: false, orderOpen: false }">

        <x-hero-section-component title="إدارة الحجوزات"
            des="تابع حجوزات الطاولات، وأضف حجوزات جديدة، وأدر حالتها بكل سهولة." 
            btnText="إضافة حجز"
        />

        <div class="grid">
            <section class="col-12 card">

                <!-- Reservation Index -->
                <livewire:reservation.index />

            </section>
        </div>

        <!-- Edit Reservation Modal -->
        <livewire:reservation.edit />

        <!-- Show Reservation Modal -->
        <livewire:reservation.show />

        <!-- Create Reservation Modal -->
        <livewire:reservation.create />

        <!-- Delete Reservation Modal -->
        <livewire:reservation.delete />

        <!-- Order Now Modal -->
        <livewire:reservation.order />

    </main>
@endsection
