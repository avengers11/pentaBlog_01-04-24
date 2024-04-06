<!DOCTYPE html>
@php
    $selLang = App\Models\User\Language::where('code', request()->input('language'))
        ->where('user_id', Auth::guard('web')->user()->id)
        ->first();
    $currentLang = App\Models\User\Language::where('code', Session::get('currentLangCode'))
        ->where('user_id', Auth::guard('web')->user()->id)
        ->first();
@endphp
<html lang="en" @if ($currentLang->rtl == 1) dir="rtl" @endif>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <title>{{ $bs->website_title }} - {{ __('User Dashboard') }}</title>
    <link rel="icon" href="{{ !empty($userBs->favicon) ? asset('assets/user/img/' . $userBs->favicon) : '' }}">
    @includeif('user.partials.styles')
    @yield('styles')
</head>
<body @if (request()->cookie('user-theme') == 'dark') data-background-color="dark" @endif>
    <div class="wrapper">
        {{-- top navbar area start --}}
        @includeif('user.partials.top-navbar')
        {{-- top navbar area end --}}
        {{-- side navbar area start --}}
        @includeif('user.partials.side-navbar')
        {{-- side navbar area end --}}
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>
            @includeif('user.partials.footer')
        </div>
    </div>

    @includeif('user.partials.scripts')
    {{-- Loader --}}
    <div class="request-loader">
        <img src="{{ asset('assets/admin/img/loader.gif') }}" alt="">
    </div>
    {{-- Loader --}}
</body>

</html>
