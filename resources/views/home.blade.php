@extends('layouts.app')
@section('title', 'الرئيسية')
@section('shell-class', 'shell')
@section('main-class', 'main')
@section('content')
    <main class="content">
        <section class="hero">
            <div class="hero-text">
                <span class="eyebrow" id="heroDate"></span>
                <h1 class="hero-title">
                    مرحباً بك مجدداً، <span class="accent">{{ Auth::user()->name }}</span>
                </h1>
                <p class="hero-sub">
                    إجمالي المبيعات ارتفعت بمعدل <strong>+10%</strong> أسبوعياً،
                    والزوار الفريدون مستقرون، ومعدل الارتداد عند 33%. منطقتان
                    جديدتان دخلتا الخدمة الليلة الماضية.
                </p>
            </div>
            <div class="hero-actions">
                <button class="btn btn--ghost">
                    <svg viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <path d="M7 10l5 5 5-5" />
                        <path d="M12 15V3" />
                    </svg>
                    تصدير
                </button>
                <button class="btn btn--primary">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    تقرير جديد
                </button>
            </div>
        </section>
    </main>
@endsection
