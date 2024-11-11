@php
    use App\Helpers\Helper;
    use Illuminate\Support\Facades\Route;
    $configData = Helper::appClasses();
@endphp

{{-- Submenu --}}
<ul class="menu-sub">
    @if (isset($menu))
        @foreach ($menu as $submenu)
            {{-- active menu method --}}
            @php
                $activeClass = null;
                $active = 'active open';
                $currentRouteName = Route::currentRouteName();
                $menuSlug = $submenu['slug'] ?? '';

                if ($currentRouteName === $menuSlug) {
                    $activeClass = 'active';
                } elseif (isset($submenu['submenu'])) {
                    if (is_array($menuSlug)) {
                        foreach ($menuSlug as $slug) {
                            if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                                $activeClass = $active;
                            }
                        }
                    } elseif ($menuSlug) {
                        if (str_contains($currentRouteName, $menuSlug) && strpos($currentRouteName, $menuSlug) === 0) {
                            $activeClass = $active;
                        }
                    }
                }
            @endphp

            <li class="menu-item {{ $activeClass }}">
                <a href="{{ isset($submenu['url']) ? url($submenu['url']) : 'javascript:void(0)' }}"
                    class="{{ isset($submenu['submenu']) ? 'menu-link menu-toggle' : 'menu-link' }}"
                    @if (isset($submenu['target']) and !empty($submenu['target'])) target="_blank" @endif>
                    @if (isset($submenu['icon']))
                        <i class="{{ $submenu['icon'] }}"></i>
                    @endif
                    <div>{{ isset($submenu['name']) ? __($submenu['name']) : '' }}</div>
                    @isset($submenu['badge'])
                        <div class="badge bg-{{ $submenu['badge'][0] }} rounded-pill ms-auto">{{ $submenu['badge'][1] }}
                        </div>
                    @endisset
                </a>

                {{-- Check if the submenu has another level of submenu --}}
                @isset($submenu['submenu'])
                    @include('layouts.sections.menu.submenu', ['menu' => $submenu['submenu']])
                @endisset
            </li>
        @endforeach
    @endif
</ul>
