<div>
    @foreach ($tabs as $key => $tab)
        <nav class="nav-section">
            <div class="nav-label">{{ __(('keywords.' . $key)) }}</div>
                @foreach ( $tab as $key => $value)
                    <a class="nav-link" href="">
                        {!! $value['svg'] !!}
                        <span>{{ __(('keywords.' . $key)) }}</span>
                    </a>
                @endforeach
        </nav>
    @endforeach
</div>

