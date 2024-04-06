@if ($themeInfo->theme_version == 1 || $themeInfo->theme_version == 3)
    @if (isset($hs) && $hs->footer == 1)
        @includeIf('user-front.theme1.partials.footer-v1')
    @endif
@elseif ($themeInfo->theme_version == 2)
    @if (isset($hs) && $hs->footer == 1)
        @includeIf('user-front.theme2.partials.footer-v2')
    @endif
@elseif($themeInfo->theme_version == 4)
    @includeIf('user-front.theme4.partials.footer-v4')
@elseif($themeInfo->theme_version == 5)
    @includeIf('user-front.theme-5.partials.footer')
@elseif($themeInfo->theme_version == 6)
    @includeIf('user-front.theme-6.partials.footer')
@else
    @includeIf('user-front.theme-7.partials.footer')
@endif
