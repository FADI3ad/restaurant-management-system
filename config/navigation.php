<?php

return [
    'Workspace' => [

        'Dashboard' => [
            'route' => 'home',
            'svg' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path d="M3 12 12 3l9 9" />
                    <path d="M5 10v10h14V10" />
                </svg>
            ',
        ],

    ],

    'Resources' => [

        'Sections' => [
            'route' => 'sections.index',

            'svg' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <rect x="4" y="4" width="7" height="7" rx="1"/>
                    <rect x="13" y="4" width="7" height="7" rx="1"/>
                    <rect x="4" y="13" width="7" height="7" rx="1"/>
                    <rect x="13" y="13" width="7" height="7" rx="1"/>
                </svg>
            ',
        ],

        'Categories' => [
            'route' => 'categories.index',

            'svg' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path d="M4 12V5h7l9 9-7 7-9-9z"/>
                    <circle cx="8" cy="8" r="1"/>
                </svg>
            ',
        ],

        'Subcategories' => [
            'route' => 'sections.index',

            'svg' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path d="M6 6v12"/>
                    <path d="M6 9h12"/>
                    <path d="M18 9v9"/>
                    <circle cx="6" cy="6" r="2"/>
                    <circle cx="18" cy="18" r="2"/>
                </svg>
            ',
        ],

        'Items' => [
            'route' => 'sections.index',

            'svg' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path d="M7 4v8a3 3 0 0 0 6 0V4"/>
                    <path d="M16 4v16"/>
                    <path d="M16 10c2 0 3-2 3-4V4"/>
                </svg>
            ',
        ],

    ],
];
