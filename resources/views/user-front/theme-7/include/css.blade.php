{{--Bootstrap CSS --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/vendors/bootstrap.min.css') }}">
{{--Fontawesome Icon CSS --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/fonts/fontawesome/css/all.min.css') }}">
@include('user-front.common.partials.plugin-css')
{{-----plugin common  css--}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/plugin-common.css') }}">
{{---- theme 6 common css --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/theme-7-common.css') }}">
{{--Main Style CSS --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/style.css') }}">
{{--Responsive CSS --}}
<link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/responsive.css') }}">
{{--RTL CSS --}}
@if ($currentLanguageInfo->rtl == 1)
    <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/rtl.css') }}">
@endif
