@extends('layouts.app')
@section('title' , 'تسجيل الدخول')
@section('shell-class', 'auth-shell')
@section('main-class', 'auth-main')
@section('content')
  
        <div class="auth-card">
            <h2>مرحباً بك مجدداً</h2>
            <p class="sub">قم بتسجيل الدخول إلى مساحة عمل Adminator لمتابعة عملك.</p>
            <form class="auth-form" onsubmit='event.preventDefault(),window.location="index.html"'>
                <div class="field"><label class="field-label" for="email">Email</label>
                    <div class="input-icon">
                        <span class="ico">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="5" width="18" height="14" rx="2" />
                            <path d="m3 7 9 6 9-6" />
                        </svg>
                    </span><input id="email" class="input" type="email" placeholder="you@company.com"
                            autocomplete="email" required>
                    </div>
                </div>
                <div class="field">
                    <div class="field-row"><label class="field-label" for="password">Password</label> <a href="#">نسيت
                            كلمة المرور؟</a></div>
                    <div class="input-icon"><span class="ico"><svg viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg></span><input id="password" class="input" type="password" placeholder="••••••••"
                            autocomplete="current-password" required></div>
                </div><label class="check"><input type="checkbox" checked="checked"> <span class="box"></span>
                    تذكرني لمدة 30 يوماً</label> <button class="btn btn--primary auth-submit" type="submit">تسجيل
                    الدخول <svg viewBox="0 0 24 24">
                        <path d="M5 12h14M13 5l7 7-7 7" />
                    </svg></button>
            </form>
        </div>
   
@endsection
