@extends('layouts.app')
@section('title', 'اقسام المطعم الاساسية')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')
    <main class="content" x-data="{ addOpen: false, showOpen: false, editOpen: false, deleteOpen: false }">

        <x-hero-section-component title="إدارة أقسام المطعم"
            des="قسّم عناصر المينيو إلى أقسام منظمة، وأدر ترتيبها وحالتها لتسهيل إدارة القائمة وتحسين تجربة العملاء." 
            btnText="إضافة قسم"
        />


        <div class="grid">
            <section class="col-12 card">

                <!-- Table -->
                <livewire:section.index />

            </section>
        </div>




        <!--Create Section Modal-->
        <livewire:section.create />

        <!--Show Section Modal-->
        <livewire:section.show />

        <!--Edit Section Modal-->
        <livewire:section.edit />

        <!--Delete Section Modal-->
        <livewire:section.delete />

    </main>
@endsection
