@extends('layouts.app')
@section('title', 'الرئيسية')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')
    <main class="content">
        <section class="hero anim-fade-up" style="margin-bottom: 2rem;">
            <div class="hero-text">
                <span class="eyebrow" id="heroDate"></span>
                <h1 class="hero-title">
                    مرحباً بك مجدداً، <span class="accent">{{ Auth::user()->name }}</span>
                </h1>
                <p class="hero-sub">
                    متابع ومحلل بيانات المطعم الفورية. يمكنك مراجعة الإحصائيات، الحجوزات، والتحكم بالمهام بسهولة.
                </p>
            </div>
        </section>

        <livewire:dashboard.index />
    </main>
@endsection
