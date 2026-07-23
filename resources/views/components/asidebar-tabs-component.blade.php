<div>
    @foreach ($tabs as $key => $tab)
        @if ($key === 'Menu Management')
            @php
                $isAnyActive = false;
                foreach ($tab as $subKey => $subValue) {
                    $routeName = $subValue['route'];
                    $baseRoute = \Illuminate\Support\Str::before($routeName, '.');
                    if (request()->routeIs($routeName) || ($baseRoute && request()->routeIs($baseRoute . '.*'))) {
                        $isAnyActive = true;
                        break;
                    }
                }
            @endphp
            <nav class="nav-section">
                <div class="nav-item-group {{ $isAnyActive ? 'is-open' : '' }}" data-nav-group>
                    <a class="nav-link {{ $isAnyActive ? 'is-active' : '' }}" href="javascript:void(0)" data-nav-toggle style="cursor: pointer;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path>
                        </svg>
                        <span>{{ __('keywords.' . $key) }}</span>
                        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </a>
                    <div class="nav-submenu">
                        @foreach ($tab as $subKey => $value)
                            @php
                                $routeName = $value['route'];
                                $baseRoute = \Illuminate\Support\Str::before($routeName, '.');
                                $isActive = request()->routeIs($routeName) || ($baseRoute && request()->routeIs($baseRoute . '.*'));
                            @endphp
                            <a class="{{ $isActive ? 'is-active' : '' }}" href="{{ route($value['route']) }}">
                                {!! $value['svg'] !!}
                                <span>{{ __('keywords.' . $subKey) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </nav>
        @else
            <nav class="nav-section">
                <div class="nav-label">{{ __('keywords.' . $key) }}</div>
                @foreach ($tab as $subKey => $value)
                    @php
                        $routeName = $value['route'];
                        $baseRoute = \Illuminate\Support\Str::before($routeName, '.');
                        $isActive = request()->routeIs($routeName) || ($baseRoute && request()->routeIs($baseRoute . '.*'));
                    @endphp
                    <a class="nav-link {{ $isActive ? 'is-active' : '' }}" href="{{ route($value['route']) }}">
                        {!! $value['svg'] !!}
                        <span>{{ __('keywords.' . $subKey) }}</span>
                    </a>
                @endforeach
            </nav>
        @endif
    @endforeach
</div>
