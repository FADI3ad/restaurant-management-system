@extends('layouts.app')
@section('title', 'قائمة الطعام (المينيو الكامل)')
@section('shell-class', 'shell')
@section('main-class', 'main')

@section('content')
@php
    $totalSections = $sections->count();
    $totalCategories = $sections->sum(fn($s) => $s->categories->count());
    $totalSubcategories = $sections->sum(fn($s) => $s->categories->sum(fn($c) => $c->subcategories->count()));
    $totalItems = $sections->sum(fn($s) => $s->categories->sum(fn($c) => $c->subcategories->sum(fn($sc) => $sc->items->count())));
@endphp

<main class="content" x-data="{
    selectedSection: 'all',
    searchQuery: '',
    
    matchesSearch(itemName, itemDesc, subName, catName, secName) {
        if (!this.searchQuery.trim()) return true;
        const q = this.searchQuery.trim().toLowerCase();
        return (itemName && itemName.toLowerCase().includes(q)) ||
               (itemDesc && itemDesc.toLowerCase().includes(q)) ||
               (subName && subName.toLowerCase().includes(q)) ||
               (catName && catName.toLowerCase().includes(q)) ||
               (secName && secName.toLowerCase().includes(q));
    }
}">

    <x-hero-section-component 
        title="استعراض قائمة الطعام الكاملة (المينيو)" 
        des="استعرض الهيكل الكامل للمطعم: الأقسام، الأصناف الرئيسية والفرعية والوجبات بتنسيق تفاعلي سلس ومتجاوب مع جميع الشاشات." 
        btnText="إدارة المينيو" 
    />

    <style>
        /* Modern Menu Design Tokens & Component Styles */
        .menu-wrapper {
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin-top: 16px;
        }

        /* Stats Cards Bar */
        .menu-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .menu-stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
        }

        .menu-stat-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-card);
        }

        .menu-stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: var(--primary-soft);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .menu-stat-info {
            display: flex;
            flex-direction: column;
        }

        .menu-stat-val {
            font-size: 22px;
            font-weight: 800;
            color: var(--t-base);
            line-height: 1.2;
        }

        .menu-stat-lbl {
            font-size: 13px;
            font-weight: 500;
            color: var(--t-muted);
        }

        /* Sticky Filter Navigation Bar */
        .menu-top-bar {
            position: sticky;
            top: 16px;
            z-index: 15;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 14px 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-card);
            backdrop-filter: blur(12px);
        }

        .section-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
            max-width: 100%;
        }

        .section-pills::-webkit-scrollbar {
            height: 4px;
        }

        .section-pills::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 4px;
        }

        .section-pill-btn {
            background: var(--bg-muted);
            color: var(--t-muted);
            border: 1px solid var(--border-soft);
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .section-pill-btn:hover {
            background: var(--bg-hover);
            color: var(--primary);
            border-color: var(--primary-ring);
        }

        .section-pill-btn.is-active {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 4px 14px var(--primary-ring);
        }

        .section-pill-count {
            background: rgba(255, 255, 255, 0.22);
            color: inherit;
            padding: 2px 7px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
        }

        .section-pill-btn:not(.is-active) .section-pill-count {
            background: var(--border);
            color: var(--t-muted);
        }

        /* Search input styling */
        .search-box-wrap {
            min-width: 260px;
            flex: 1;
            max-width: 380px;
            position: relative;
        }

        .search-input-field {
            width: 100%;
            background: var(--bg-muted);
            border: 1px solid var(--border);
            color: var(--t-base);
            padding: 10px 40px 10px 38px;
            border-radius: 12px;
            font-size: 13px;
            font-family: inherit;
            transition: all 0.2s ease;
            outline: none;
        }

        .search-input-field:focus {
            border-color: var(--primary);
            background: var(--bg-card);
            box-shadow: 0 0 0 3px var(--primary-ring);
        }

        .search-input-field::placeholder {
            color: var(--t-light);
        }

        .search-icon-svg {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--t-light);
            pointer-events: none;
            display: flex;
        }

        .search-clear-btn {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--border);
            color: var(--t-muted);
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s ease;
        }

        .search-clear-btn:hover {
            background: var(--danger);
            color: #ffffff;
        }

        /* Section Container */
        .menu-section-block {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-card);
            transition: all 0.25s ease;
        }

        .menu-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            margin-bottom: 24px;
            border-bottom: 1px dashed var(--border);
        }

        .menu-section-title-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .menu-section-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: var(--primary-soft);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px var(--primary-ring);
        }

        .menu-section-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--t-base);
            margin: 0;
            line-height: 1.3;
        }

        .menu-section-subtitle {
            font-size: 12px;
            color: var(--t-muted);
            margin-top: 2px;
        }

        .menu-section-badge {
            background: var(--primary-soft);
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid var(--primary-ring);
        }

        /* Category Cards */
        .menu-category-card {
            background: var(--bg-muted);
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .menu-category-card:last-child {
            margin-bottom: 0;
        }

        .menu-category-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .menu-category-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .category-badge {
            background: var(--bg-card);
            color: var(--t-muted);
            border: 1px solid var(--border);
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 8px;
        }

        /* Subcategory Block */
        .menu-subcategory-block {
            margin-bottom: 22px;
        }

        .menu-subcategory-block:last-child {
            margin-bottom: 0;
        }

        .menu-subcategory-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--t-base);
            margin-bottom: 14px;
            padding-right: 12px;
            border-right: 4px solid var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .menu-subcategory-tag {
            font-size: 11px;
            font-weight: 500;
            color: var(--t-light);
        }

        /* Food Items Grid */
        .menu-items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 18px;
        }

        .item-card-box {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .item-card-box:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .item-img-container {
            width: 100%;
            height: 150px;
            overflow: hidden;
            position: relative;
            background: var(--bg-muted);
        }

        .item-img-holder {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .item-card-box:hover .item-img-holder {
            transform: scale(1.06);
        }

        .item-img-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--bg-muted), var(--primary-soft));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--t-light);
            gap: 6px;
        }

        .item-img-placeholder svg {
            color: var(--primary);
            opacity: 0.6;
        }

        .item-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .item-title-text {
            font-size: 15px;
            font-weight: 700;
            color: var(--t-base);
            margin: 0 0 6px 0;
            line-height: 1.35;
        }

        .item-desc-text {
            font-size: 13px;
            color: var(--t-muted);
            margin-bottom: 14px;
            line-height: 1.5;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .item-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid var(--border-soft);
            margin-top: auto;
        }

        .item-price-tag {
            font-size: 16px;
            font-weight: 800;
            color: var(--success);
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: var(--success-soft);
            padding: 4px 10px;
            border-radius: 10px;
        }

        .item-sub-tag {
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid var(--primary-ring);
        }

        .empty-state-card {
            background: var(--bg-card);
            border: 1px dashed var(--border);
            border-radius: 20px;
            padding: 48px 24px;
            text-align: center;
            margin-top: 16px;
        }

        .empty-state-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            background: var(--primary-soft);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .empty-state-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--t-base);
            margin: 0 0 6px 0;
        }

        .empty-state-desc {
            font-size: 13px;
            color: var(--t-muted);
            margin: 0;
        }
    </style>

    <div class="menu-wrapper">
        {{-- Stats Bar --}}
        <div class="menu-stats-grid">
            <div class="menu-stat-card">
                <div class="menu-stat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                </div>
                <div class="menu-stat-info">
                    <span class="menu-stat-val">{{ $totalSections }}</span>
                    <span class="menu-stat-lbl">أقسام طعام</span>
                </div>
            </div>

            <div class="menu-stat-card">
                <div class="menu-stat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 8h1a4 4 0 1 1 0 8h-1"></path>
                        <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"></path>
                        <line x1="6" y1="2" x2="6" y2="4"></line>
                        <line x1="10" y1="2" x2="10" y2="4"></line>
                        <line x1="14" y1="2" x2="14" y2="4"></line>
                    </svg>
                </div>
                <div class="menu-stat-info">
                    <span class="menu-stat-val">{{ $totalCategories }}</span>
                    <span class="menu-stat-lbl">أصناف رئيسية</span>
                </div>
            </div>

            <div class="menu-stat-card">
                <div class="menu-stat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </div>
                <div class="menu-stat-info">
                    <span class="menu-stat-val">{{ $totalSubcategories }}</span>
                    <span class="menu-stat-lbl">أصناف فرعية</span>
                </div>
            </div>

            <div class="menu-stat-card">
                <div class="menu-stat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="menu-stat-info">
                    <span class="menu-stat-val">{{ $totalItems }}</span>
                    <span class="menu-stat-lbl">وجبات وصنف متاح</span>
                </div>
            </div>
        </div>

        {{-- Sticky Top Bar & Filters --}}
        <div class="menu-top-bar">
            <div class="section-pills">
                <button type="button" 
                    class="section-pill-btn" 
                    :class="{ 'is-active': selectedSection === 'all' }"
                    @click="selectedSection = 'all'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>جميع الأقسام</span>
                    <span class="section-pill-count">{{ $totalItems }}</span>
                </button>
                @foreach($sections as $sec)
                    @php
                        $secItemCount = $sec->categories->sum(fn($c) => $c->subcategories->sum(fn($sc) => $sc->items->count()));
                    @endphp
                    <button type="button" 
                        class="section-pill-btn" 
                        :class="{ 'is-active': selectedSection == '{{ $sec->id }}' }"
                        @click="selectedSection = '{{ $sec->id }}'">
                        <span>{{ $sec->name }}</span>
                        <span class="section-pill-count">{{ $secItemCount }}</span>
                    </button>
                @endforeach
            </div>

            <div class="search-box-wrap">
                <div class="search-icon-svg">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input x-model="searchQuery" type="text" class="search-input-field" placeholder="ابحث في الوجبات، الأصناف أو الأقسام...">
                <button type="button" class="search-clear-btn" x-show="searchQuery.length > 0" @click="searchQuery = ''" style="display: none;">✕</button>
            </div>
        </div>

        {{-- Render Sections --}}
        @forelse($sections as $section)
            @php
                $secItemsTotal = $section->categories->sum(fn($c) => $c->subcategories->sum(fn($sc) => $sc->items->count()));
            @endphp
            <div class="menu-section-block" 
                x-show="selectedSection === 'all' || selectedSection == '{{ $section->id }}'"
                x-transition>
                
                <div class="menu-section-header">
                    <div class="menu-section-title-wrap">
                        <div class="menu-section-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="menu-section-title">{{ $section->name }}</h2>
                            <span class="menu-section-subtitle">قسم رئيسي • {{ $secItemsTotal }} وجبة</span>
                        </div>
                    </div>
                    <span class="menu-section-badge">{{ $section->categories->count() }} أصناف</span>
                </div>

                @forelse($section->categories as $category)
                    <div class="menu-category-card">
                        <div class="menu-category-header">
                            <h3 class="menu-category-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 8h1a4 4 0 1 1 0 8h-1"></path>
                                    <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"></path>
                                    <line x1="6" y1="2" x2="6" y2="4"></line>
                                    <line x1="10" y1="2" x2="10" y2="4"></line>
                                    <line x1="14" y1="2" x2="14" y2="4"></line>
                                </svg>
                                <span>{{ $category->name }}</span>
                            </h3>
                            <span class="category-badge">صنف رئيسي</span>
                        </div>

                        @forelse($category->subcategories as $subcategory)
                            <div class="menu-subcategory-block">
                                <div class="menu-subcategory-title">
                                    <span>{{ $subcategory->name }}</span>
                                    <span class="menu-subcategory-tag">({{ $subcategory->items->count() }} وجبة)</span>
                                </div>

                                <div class="menu-items-grid">
                                    @forelse($subcategory->items as $item)
                                        <div class="item-card-box" 
                                            x-show="matchesSearch({{ json_encode($item->name) }}, {{ json_encode($item->description ?? '') }}, {{ json_encode($subcategory->name) }}, {{ json_encode($category->name) }}, {{ json_encode($section->name) }})">
                                            
                                            <div class="item-img-container">
                                                @if($item->image)
                                                    <img src="{{ asset('storage/' . $item->image) }}" class="item-img-holder" alt="{{ $item->name }}">
                                                @else
                                                    <div class="item-img-placeholder">
                                                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/>
                                                            <path d="M12 6v6l4 2"/>
                                                        </svg>
                                                        <span style="font-size: 11px; font-weight: 500;">صورة الوجبة غير متوفرة</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="item-body">
                                                <h4 class="item-title-text">{{ $item->name }}</h4>
                                                <p class="item-desc-text">{{ $item->description ?? 'لا يوجد وصف محدد لهذه الوجبة حالياً.' }}</p>
                                                <div class="item-footer-row">
                                                    <span class="item-price-tag">
                                                        {{ number_format($item->price, 2) }}
                                                        <small style="font-size: 11px; font-weight: 600;">ر.س</small>
                                                    </span>
                                                    <span class="item-sub-tag">{{ $subcategory->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p style="color: var(--t-light); font-size: 13px; margin: 0 0 8px;">لا توجد وجبات مضافة لهذا الصنف الفرعي.</p>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <p style="color: var(--t-light); font-size: 13px; margin: 0;">لا توجد أصناف فرعية لهذا الصنف الرئيسي.</p>
                        @endforelse
                    </div>
                @empty
                    <p style="color: var(--t-light); font-size: 14px; margin: 0;">لا توجد أصناف رئيسية في هذا القسم.</p>
                @endforelse

            </div>
        @empty
            <div class="empty-state-card">
                <div class="empty-state-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <h3 class="empty-state-title">لا توجد أقسام في المينيو حالياً</h3>
                <p class="empty-state-desc">قم بإضافة الأقسام والأصناف من لوحة التحكم لإظهار المينيو هنا.</p>
            </div>
        @endforelse
    </div>
</main>
@endsection

