<!-- Google font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
{{----========================= Theme 1,2,3,4 & inner  page css =================--}}
@if ($themeInfo->theme_version == 1 || $themeInfo->theme_version == 2 || $themeInfo->theme_version == 3 || $themeInfo->theme_version == 4 || !request()->routeIs('front.user.detail.view'))
{{-- flaticon css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/common/flaticon.css') }}">
{{-- bootstrap css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/common/bootstrap.min.css') }}">
{{-- slick css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/common/slick.css') }}">
{{-- datatables bootstrap css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/common/datatables.bootstrap4.min.css') }}">
{{-- datatables css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/common/datatables-1.10.23.min.css') }}">
{{-- jQuery nice number css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/common/jquery.nice-number.css') }}">
{{-- jQuery-ui css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/common/jquery-ui.min.css') }}">
{{-- default css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme1234/default.css') }}">
@include('user-front.common.partials.plugin-css')
{{-- main css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme1234/main.css') }}">
{{-- user front common --}}
<link rel="stylesheet" href="{{ asset('assets/front/user/css/common.css') }}">
{{-- responsive css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme1234/responsive.css') }}">

@if ($currentLanguageInfo->rtl == 1)
    {{-- right-to-left css --}}
    <link rel="stylesheet" href="{{ asset('assets/user/css/theme1234/right-to-left.css') }}">

    {{-- right-to-left-responsive css --}}
    <link rel="stylesheet" href="{{ asset('assets/user/css/theme1234/right-to-left-responsive.css') }}">
@endif

@endif

@if (($themeInfo->theme_version == 5 || $themeInfo->theme_version == 6 || $themeInfo->theme_version == 7) &&
    !request()->routeIs('front.user.detail.view'))
@include('user-front.common.partials.header-footer-css')

{{-- Theme 5,6,7 common css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/theme567-common.css') }}">
@endif
