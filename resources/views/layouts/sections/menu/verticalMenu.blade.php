@php
    use App\Helpers\Helper;
    $configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    @if (!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                    @include('_partials.macros')
                </span>
                <span class="app-brand-text demo menu-text fw-bold ms-2"
                    style="margin: -5px !important;">{{ config('variables.templateName') }}</span>
            </a>
        </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData['menu'] as $menu)
            {{-- adding active and open class if child is active --}}

            {{-- menu headers --}}
            @if (isset($menu['menuHeader']))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">{{ $menu['menuHeader'] }}</span>
                </li>
            @else
                {{-- active menu method --}}
                @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();

                    if ($currentRouteName === $menu['slug']) {
                        $activeClass = 'active';
                    } elseif (isset($menu['submenu'])) {
                        if (gettype($menu['slug']) === 'array') {
                            foreach ($menu['slug'] as $slug) {
                                if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
                                    $activeClass = 'active open';
                                }
                            }
                        } else {
                            if (
                                str_contains($currentRouteName, $menu['slug']) and
                                strpos($currentRouteName, $menu['slug']) === 0
                            ) {
                                $activeClass = 'active open';
                            }
                        }
                    }
                @endphp

                {{-- main menu --}}
                <li class="menu-item {{ $activeClass }}">
                    <a href="{{ isset($menu['url']) ? url($menu['url']) : 'javascript:void(0);' }}"
                        class="{{ isset($menu['submenu']) ? 'menu-link menu-toggle' : 'menu-link' }}"
                        @if (isset($menu['target']) and !empty($menu['target'])) target="_blank" @endif>
                        @isset($menu['icon'])
                            <i class="{{ $menu['icon'] }}"></i>
                        @endisset
                        <div>{{ isset($menu['name']) ? __($menu['name']) : '' }}</div>
                        @isset($menu['badge'])
                            <div class="badge bg-{{ $menu['badge'][0] }} rounded-pill ms-auto">{{ $menu['badge'][1] }}</div>
                        @endisset
                    </a>

                    {{-- submenu --}}
                    @isset($menu['submenu'])
                        @include('layouts.sections.menu.submenu', ['menu' => $menu['submenu']])
                    @endisset
                </li>
            @endif
        @endforeach
    </ul>

</aside>

<style>
    /* Theme Variables */
    :root {
        --menu-bg: #fff;
        --menu-text: #435971;
        --menu-border: #eceef1;
        --menu-hover-bg: rgba(67, 89, 113, 0.04);
        --menu-hover-text: #435971;
        --menu-active-bg: rgba(105, 108, 255, 0.16);
        --menu-active-text: #696cff;
        --menu-header-text: #a1acb8;
        --menu-shadow-color: rgba(67, 89, 113, 0.1);
        --menu-scrollbar-thumb: #dbdee4;
        --menu-scrollbar-track: #f5f5f9;
        --menu-icon-color: #a1acb8;
        --menu-shadow-from: #fff;
        --menu-shadow-to-1: rgba(255, 255, 255, 0.11);
        --menu-shadow-to-2: rgba(255, 255, 255, 0);
    }

    [data-theme="dark"] {
        --menu-bg: #151521;
        --menu-text: #e0e2e7;
        --menu-border: rgba(255, 255, 255, 0.08);
        --menu-hover-bg: rgba(255, 255, 255, 0.06);
        --menu-hover-text: #fff;
        --menu-active-bg: rgba(124, 126, 255, 0.16);
        --menu-active-text: #7c7eff;
        --menu-header-text: #7983bb;
        --menu-shadow-color: rgba(0, 0, 0, 0.2);
        --menu-scrollbar-thumb: #2a2a3d;
        --menu-scrollbar-track: #151521;
        --menu-icon-color: #7983bb;
        --menu-shadow-from: #151521;
        --menu-shadow-to-1: rgba(21, 21, 33, 0.11);
        --menu-shadow-to-2: rgba(21, 21, 33, 0);
    }

    /* Layout Menu */
    .layout-menu {
        background-color: var(--menu-bg) !important;
        color: var(--menu-text);
    }

    /* App Brand */
    .app-brand {
        padding: 1.25rem 1.5rem;
        margin-bottom: 0.5rem;
        border-bottom: 1px solid var(--menu-border);
        background-color: var(--menu-bg);
    }

    .app-brand-text {
        color: var(--menu-text) !important;
    }

    /* Menu Items */
    .menu-inner {
        padding: 0 0.5rem;
    }

    .menu-item {
        margin: 0.25rem 0.5rem;
    }

    .menu-link {
        padding: 0.625rem 1rem;
        color: var(--menu-text) !important;
        border-radius: 0.375rem;
    }

    .menu-link:hover {
        background-color: var(--menu-hover-bg) !important;
        color: var(--menu-hover-text) !important;
    }

    .menu-link i {
        color: var(--menu-icon-color) !important;
        font-size: 1.25rem;
        margin-right: 0.75rem;
    }

    .menu-link:hover i {
        color: var(--menu-active-text) !important;
    }

    /* Active Menu Item */
    .menu-item.active > .menu-link {
        background-color: var(--menu-active-bg) !important;
        color: var(--menu-active-text) !important;
        font-weight: 500;
    }

    .menu-item.active > .menu-link i {
        color: var(--menu-active-text) !important;
    }

    /* Menu Header */
    .menu-header {
        margin: 1.25rem 0 0.5rem 0;
        padding: 0.5rem 1rem;
    }

    .menu-header-text {
        color: var(--menu-header-text) !important;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Menu Inner Shadow */
    .menu-inner-shadow {
        background: linear-gradient(var(--menu-shadow-from) 41%, var(--menu-shadow-to-1) 95%, var(--menu-shadow-to-2)) !important;
        height: 0.75rem;
        position: fixed;
        top: 4rem;
        left: 0;
        width: 16.25rem;
        pointer-events: none;
        z-index: 2;
    }

    /* Submenu */
    .menu-sub {
        padding-left: 3rem;
        margin: 0;
        background-color: transparent !important;
    }

    .menu-sub .menu-link {
        padding: 0.5rem 1rem;
    }

    /* Badge */
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 500;
    }

    /* Transitions */
    .menu-link,
    .menu-icon {
        transition: all 0.15s ease-in-out;
    }

    /* Menu Toggle */
    .menu-toggle::after {
        color: var(--menu-icon-color);
    }

    .menu-item.active > .menu-toggle::after {
        color: var(--menu-active-text);
    }

    /* Scrollbar */
    .layout-menu::-webkit-scrollbar {
        width: 4px;
    }

    .layout-menu::-webkit-scrollbar-track {
        background: var(--menu-scrollbar-track);
    }

    .layout-menu::-webkit-scrollbar-thumb {
        background: var(--menu-scrollbar-thumb);
        border-radius: 2px;
    }

    .layout-menu::-webkit-scrollbar-thumb:hover {
        background: var(--menu-active-text);
    }
</style>
