@if ($themeInfo->theme_version == 1 || $themeInfo->theme_version == 2)
<header class="olima_header header_v1">
    {{-- include header-top --}}
    @includeIf('user-front.theme1.partials.header-top-v1')

    {{-- include header-nav --}}
    @includeIf('user-front.theme1.partials.header-nav-v1')
</header>
@elseif ($themeInfo->theme_version == 3)
<header class="olima_header header_v3">
    {{-- include header-top --}}
    @includeIf('user-front.theme3.partials.header-top-v3')

    {{-- include header-nav --}}
    @includeIf('user-front.theme3.partials.header-nav-v3')
</header>
@elseif($themeInfo->theme_version == 4)
<header class="olima_header header_v4">
    {{-- include header-top --}}
    @includeIf('user-front.theme4.partials.header-top-v4')

    {{-- include header-nav --}}
    @includeIf('user-front.theme4.partials.header-nav-v4')
</header>
@elseif($themeInfo->theme_version == 5)
    @includeIf('user-front.theme-5.partials.header')
@elseif($themeInfo->theme_version == 6)
    @includeIf('user-front.theme-6.partials.header')
@else
    @includeIf('user-front.theme-7.partials.header')
@endif
