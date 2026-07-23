@extends('layouts.app')
@section('title', 'إعدادات النظام والهوية')
@section('shell-class', 'shell')
@section('main-class', 'main')

@section('content')
    <main class="content">

        <section class="hero">
            <div class="hero-text">
                <h1 class="hero-title">إعدادات النظام والهوية البصرية</h1>
                <p class="hero-sub">
                    قم بتعديل اسم وشعار المطعم المخصص، والتحكم التام بالألوان والستايلات لجميع صفحات الويب سايت.
                </p>
            </div>
        </section>

        @if (session('success'))
            <div class="card" style="margin-bottom: 24px; padding: 16px 20px; background-color: var(--success-soft); border: 1px solid var(--success); color: var(--t-base); border-radius: 12px; display: flex; align-items: center; gap: 12px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span style="font-weight: 600;">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px;">

                <!-- Restaurant Info & Logo Card -->
                <div class="card" style="padding: 24px; border-radius: 16px; background: var(--bg-card); border: 1px solid var(--border);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--primary-soft); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h2 style="font-size: 1.1rem; font-weight: 700; margin: 0; color: var(--t-base);">هوية المطعم والشعار</h2>
                            <p style="font-size: 0.85rem; color: var(--t-muted); margin: 2px 0 0 0;">الاسم واللوجو المستعرض أعلى الهيدر والقائمة الجانبية</p>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--t-base);">اسم المطعم (بديل اللوجو):</label>
                        <input type="text" name="restaurant_name" value="{{ old('restaurant_name', $settings['restaurant_name']) }}" required
                            style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-body); color: var(--t-base); font-size: 0.95rem;">
                        @error('restaurant_name')
                            <span style="color: var(--danger); font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--t-base);">الوصف / الشعار اللفظي:</label>
                        <input type="text" name="restaurant_tagline" value="{{ old('restaurant_tagline', $settings['restaurant_tagline']) }}"
                            style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-body); color: var(--t-base); font-size: 0.95rem;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--t-base);">صورة اللوجو المخصص:</label>
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 60px; height: 60px; border-radius: 12px; border: 1px dashed var(--border); display: flex; align-items: center; justify-content: center; background: var(--bg-body); overflow: hidden;">
                                @if($settings['restaurant_logo'])
                                    <img id="logoPreview" src="{{ $settings['restaurant_logo'] }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                                @else
                                    <svg id="logoPlaceholder" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--t-light)" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <input type="file" name="logo" accept="image/*" id="logoInput"
                                    onchange="document.getElementById('logoPreview').src = window.URL.createObjectURL(this.files[0])"
                                    style="width: 100%; font-size: 0.85rem; color: var(--t-muted);">
                                <small style="display: block; color: var(--t-light); margin-top: 4px;">PNG, JPG, SVG أو WebP (الحد الأقصى 2 ميجابايت)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colors & Theme Customization Card -->
                <div class="card" style="padding: 24px; border-radius: 16px; background: var(--bg-card); border: 1px solid var(--border);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--primary-soft); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 style="font-size: 1.1rem; font-weight: 700; margin: 0; color: var(--t-base);">الستايلات ومتغيرات الـ CSS Global</h2>
                            <p style="font-size: 0.85rem; color: var(--t-muted); margin: 2px 0 0 0;">تغيير ألوان الثيم الفاتح والداكن والخط العام للموقع</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; color: var(--t-base);">اللون الرئيسي (الوضع الفاتح):</label>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="color" value="{{ old('primary_color', $settings['primary_color']) }}"
                                    onchange="document.getElementById('primary_color_text').value = this.value"
                                    style="width: 40px; height: 38px; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; padding: 2px;">
                                <input type="text" id="primary_color_text" name="primary_color" value="{{ old('primary_color', $settings['primary_color']) }}" required
                                    style="flex: 1; padding: 8px 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-body); color: var(--t-base); font-size: 0.85rem; font-family: monospace;">
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; color: var(--t-base);">اللون الرئيسي (الوضع الداكن):</label>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="color" value="{{ old('primary_dark_color', $settings['primary_dark_color']) }}"
                                    onchange="document.getElementById('primary_dark_color_text').value = this.value"
                                    style="width: 40px; height: 38px; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; padding: 2px;">
                                <input type="text" id="primary_dark_color_text" name="primary_dark_color" value="{{ old('primary_dark_color', $settings['primary_dark_color']) }}" required
                                    style="flex: 1; padding: 8px 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-body); color: var(--t-base); font-size: 0.85rem; font-family: monospace;">
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; color: var(--t-base);">خلفية القائمة الجانبية (فاتح):</label>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="color" value="{{ old('bg_sidebar_light', $settings['bg_sidebar_light']) }}"
                                    onchange="document.getElementById('bg_sidebar_light_text').value = this.value"
                                    style="width: 40px; height: 38px; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; padding: 2px;">
                                <input type="text" id="bg_sidebar_light_text" name="bg_sidebar_light" value="{{ old('bg_sidebar_light', $settings['bg_sidebar_light']) }}"
                                    style="flex: 1; padding: 8px 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-body); color: var(--t-base); font-size: 0.85rem; font-family: monospace;">
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; color: var(--t-base);">خلفية القائمة الجانبية (داكن):</label>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="color" value="{{ old('bg_sidebar_dark', $settings['bg_sidebar_dark']) }}"
                                    onchange="document.getElementById('bg_sidebar_dark_text').value = this.value"
                                    style="width: 40px; height: 38px; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; padding: 2px;">
                                <input type="text" id="bg_sidebar_dark_text" name="bg_sidebar_dark" value="{{ old('bg_sidebar_dark', $settings['bg_sidebar_dark']) }}"
                                    style="flex: 1; padding: 8px 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-body); color: var(--t-base); font-size: 0.85rem; font-family: monospace;">
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--t-base);">خط الويب سايت الرئيسي (Font Family):</label>
                        <select name="font_family" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-body); color: var(--t-base); font-size: 0.95rem;">
                            <option value="Cairo" {{ $settings['font_family'] === 'Cairo' ? 'selected' : '' }}>Cairo (الافتراضي - عصري ومقروء)</option>
                            <option value="Tajawal" {{ $settings['font_family'] === 'Tajawal' ? 'selected' : '' }}>Tajawal (تجول - أنيق وخفيف)</option>
                            <option value="Alexandria" {{ $settings['font_family'] === 'Alexandria' ? 'selected' : '' }}>Alexandria (الإسكندرية - تصميم فخم)</option>
                            <option value="Almarai" {{ $settings['font_family'] === 'Almarai' ? 'selected' : '' }}>Almarai (المراعي - واضح ومستقيم)</option>
                        </select>
                    </div>

                </div>

            </div>

            <!-- Save Action Bar -->
            <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="submit" class="btn btn--primary" style="padding: 12px 32px; font-size: 1rem; font-weight: 600; border-radius: 10px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    حفظ التغيرات والستايل
                </button>
            </div>

        </form>

    </main>
@endsection
