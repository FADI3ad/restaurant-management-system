@extends('layouts.app')
@section('title', 'إدارة حسابات العاملين والمستخدمين')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')
    <main class="content" x-data="{ addOpen: false, showOpen: false, editOpen: false, deleteOpen: false }">

        <x-hero-section-component 
            title="إدارة الحسابات وصلاحيات العاملين"
            des="قم بإنشاء حسابات جديدة للموظفين والعاملين بالمطعم، وتحديد الأدوار والصلاحيات (أدمن، كاشير، مدير، صالة، مطبخ) مباشرة من لوحة التحكم." 
            btnText="إضافة حساب جديد"
        />

        <div class="grid">
            <section class="col-12 card">

                <!-- Table -->
                <livewire:user.index />

            </section>
        </div>

        <!--Create User Modal-->
        <livewire:user.create />

        <!--Show User Modal-->
        <livewire:user.show />

        <!--Edit User Modal-->
        <livewire:user.edit />

        <!--Delete User Modal-->
        <livewire:user.delete />

    </main>
@endsection
