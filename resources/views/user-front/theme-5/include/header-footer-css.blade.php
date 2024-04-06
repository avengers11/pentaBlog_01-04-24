 {{----------=============theme 5 Inner Page css ================-------}}
    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/vendors/bootstrap.min.css') }}">
    {{-- Main Style CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/style.css') }}">
    {{-- Dark Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/theme-dark.css') }}">
    {{-- RTL CSS --}}
    @if ($currentLanguageInfo->rtl == 1)
        <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/rtl.css') }}">
    @endif
    {{-- Responsive CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/responsive.css') }}">
    {{--- theme 5 common css --}}
    <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/theme-5-common.css') }}">
