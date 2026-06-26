@props(['title', 'des'])
<section class="hero">
    <div class="hero-text">
        <h1 class="hero-title">{{ $title }}</h1>
        <p class="hero-sub">
            {{ $des }}
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
        <button class="btn btn--primary" onclick="openModal('modal-add')">
            <svg viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14" />
            </svg>
            تقرير جديد
        </button>
    </div>
</section>
