@if ($themeInfo->theme_version == 5)
    @include('user-front.theme-5.include.header-footer-js')
@elseif($themeInfo->theme_version == 6)
    @include('user-front.theme-6.include.header-footer-js')
@elseif($themeInfo->theme_version == 7)
    @include('user-front.theme-7.include.header-footer-js')
@endif
