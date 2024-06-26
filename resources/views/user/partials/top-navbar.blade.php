@php
$user = Auth::guard('web')->user();
$userLanguages = \App\Models\User\Language::where('user_id', $user->id)->get();
@endphp
@if (Session::has('currentLangCode'))
    @php
        $default = \App\Models\User\Language::where('code', Session::get('currentLangCode'))
            ->where('user_id', $user->id)
            ->first();
    @endphp
@else
    @php
        $default = \App\Models\User\Language::where('is_default', 1)
            ->where('user_id', $user->id)
            ->first();
    @endphp
@endif

<div class="main-header">
    <!-- Logo Header -->
    <div class="logo-header" @if (request()->cookie('user-theme') == 'dark') data-background-color="dark2" @endif>
        <a href="{{ route('front.index') }}" class="logo" target="_blank">
            <img src="{{ !empty($userBs->logo) ? asset('assets/user/img/' . $userBs->logo) : asset('assets/user/img/lgoo.png') }}"
                alt="navbar brand" class="navbar-brand">
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
            data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="icon-menu"></i>
            </span>
        </button>
        <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>
    <!-- End Logo Header -->
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-expand-lg"
        @if (request()->cookie('user-theme') == 'dark') data-background-color="dark" @endif>
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                @if (!empty($userLanguages))
                    <select name="userLanguage" class="form-control"
                        onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                        <option value="" selected disabled>
                            {{ $keywords['Select_a_Language'] ?? __('Select a Language') }}</option>
                        @foreach ($userLanguages as $lang)
                            <option value="{{ $lang->code }}"
                                {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                                {{ $lang->name }}</option>
                        @endforeach
                    </select>
                @endif
                <form action="{{ route('user.theme.change') }}" class="mr-4 form-inline" id="adminThemeForm">
                    <div class="form-group">
                        <div class="selectgroup selectgroup-secondary selectgroup-pills">
                            <label class="selectgroup-item">
                                <input type="radio" name="theme" value="light" class="selectgroup-input"
                                    {{ empty(request()->cookie('user-theme')) || request()->cookie('user-theme') == 'light' ? 'checked' : '' }}
                                    onchange="document.getElementById('adminThemeForm').submit();">
                                <span class="selectgroup-button selectgroup-button-icon"><i
                                        class="fa fa-sun"></i></span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="theme" value="dark" class="selectgroup-input"
                                    {{ request()->cookie('user-theme') == 'dark' ? 'checked' : '' }}
                                    onchange="document.getElementById('adminThemeForm').submit();">
                                <span class="selectgroup-button selectgroup-button-icon"><i
                                        class="fa fa-moon"></i></span>
                            </label>
                        </div>
                    </div>
                </form>
                <li class="mr-4">
                    <a class="btn btn-primary btn-sm btn-round mx-3" target="_blank"
                        href="{{ route('front.user.detail.view', Auth::user()->username) }}"
                        title="{{ $keywords['View_Profile'] ?? 'View Profile' }}">
                        <i class="fas fa-eye"></i>
                    </a>
                </li>

                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            @if (!empty(Auth::user()->photo))
                                <img src="{{ asset('assets/user/img/' . Auth::user()->photo) }}" alt="..."
                                    class="avatar-img rounded-circle">
                            @else
                                <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..."
                                    class="avatar-img rounded-circle">
                            @endif
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if (!empty(Auth::user()->photo))
                                            <img src="{{ asset('assets/user/img/' . Auth::user()->photo) }}"
                                                alt="..." class="avatar-img rounded">
                                        @else
                                            <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}"
                                                alt="..." class="avatar-img rounded">
                                        @endif
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                        <a href="{{ route('user-profile-update') }}"
                                            class="btn btn-xs btn-secondary btn-sm">{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                    href="{{ route('user-profile-update') }}">{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                    href="{{ route('user.changePass') }}">{{ $keywords['Change_Password'] ?? __('Change Password') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                    href="{{ route('user-logout') }}">{{ $keywords['Logout'] ?? __('Logout') }}</a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>
