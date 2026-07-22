@extends('layouts.app')
@section('title', 'طلبات اليوم ومتابعة المطبخ')
@section('shell-class', 'shell')
@section('main-class', 'main')

@section('content')
    <main class="content">

        {{-- <x-hero-section-component title="طلبات اليوم ومتابعة المطبخ"
            des="شاشة متابعة الطلبات اليومية وحالات التحضير في المطبخ، وتحديث حالة الطلب لحظياً (لم يبدأ، قيد التحضير، جاهز)."
        /> --}}

        <div class="grid">
            <section class="col-12 card" style="background: transparent; border: none; padding: 0; box-shadow: none;">
                <livewire:order.index />
            </section>
        </div>

    </main>
@endsection
