<header class="header-area header-area_v4 {{ !request()->routeIs('index') ? 'header-static' : '' }}">
    <!-- Start mobile menu -->
    <div class="mobile-menu">
        <div class="container">
            <div class="mobile-menu-wrapper"></div>
        </div>
    </div>
    <!-- End mobile menu -->

    <div class="main-responsive-nav">
        <div class="container">
            <!-- Mobile Logo -->
            @if (!is_null($websiteInfo))
                <div class="logo">
                    <a href="{{ route('front.user.detail.view', getParam()) }}" target="_self"
                        title="{{ $websiteInfo->website_title }}">
                        @if ($websiteInfo->logo)
                            <img class="lazyload" data-src="{{ $websiteInfo->logo != null ? Storage::url($websiteInfo->logo) : asset('assets/admin/img/noimage.jpg') }}"
                                src="{{ $websiteInfo->logo != null ? Storage::url($websiteInfo->logo) : asset('assets/admin/img/noimage.jpg') }}"
                                alt="{{ $websiteInfo->website_title }}">
                        @else
                            <img src="{{ asset('assets/user/img/themes/default_6.png') }}" alt="Logo">
                        @endif
                    </a>
                </div>
            @endif
            <!-- Menu toggle button -->
            <button class="menu-toggler" type="button">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <div class="main-navbar">
        <div class="container">
            <nav class="navbar navbar-expand-lg justify-content-between">
                <!-- Navigation items -->
                <div class="collapse navbar-collapse">
                    <ul id="mainMenu" class="navbar-nav mobile-item ">
                        @php
                            $links = json_decode($userMenus, true);
                        @endphp
                        @foreach ($links as $link)
                            @php
                                $href = getUserHref($link, $currentLanguageInfo->id);
                            @endphp
                            @if (!array_key_exists('children', $link))
                                <li class="nav-item">
                                    <a href="{{ $href }}" class="nav-link">
                                        {{ $link['text'] }}
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="#{{ $link['text'] }}" class="nav-link toggle"> {{ $link['text'] }}
                                        <i class="fal fa-plus"></i>
                                    </a>
                                    <ul class="menu-dropdown">
                                        @foreach ($link['children'] as $level2)
                                            @php
                                                $l2Href = getUserHref($level2, $currentLanguageInfo->id);
                                            @endphp
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ $l2Href }}">{{ $level2['text'] }}</a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <!-- Logo -->
                @if (!is_null($websiteInfo))
                    <a class="navbar-brand" href="{{ route('front.user.detail.view', getParam()) }}" target="_self"
                        title="{{ $websiteInfo->website_title }}">
                        @if ($websiteInfo->text_to_logo_status == 1)
                            <h2 class="logo-txt">{{$websiteInfo->text_to_logo}}</h2>
                        @else
                            @if ($websiteInfo->logo)
                                <img class="lazyload" data-src="{{ $websiteInfo->logo != null ? Storage::url($websiteInfo->logo) : asset('assets/admin/img/noimage.jpg') }}"
                                    alt="{{ $websiteInfo->website_title }}"
                                    src="{{ $websiteInfo->logo != null ? Storage::url($websiteInfo->logo) : asset('assets/admin/img/noimage.jpg') }}">
                            @else
                                <img class="text-white" data-src="{{ asset('assets/user/img/themes/default_dark.png') }}"
                                    alt="Logo" src="{{ asset('assets/user/img/themes/default_dark.png') }}">
                            @endif
                        @endif
                    </a>
                @endif

                <div class="more-option mobile-item">
                    <div class="item search-form">
                        <form action="{{ route('front.user.posts', getParam()) }}" method="GET" id="searchingForm">
                            <div class="form-group icon-start">
                                <input class="form-control border-0 radius-sm bg-primary-light title"
                                    placeholder="{{ $keywords['Search_Post'] ?? __('Search Post') }} ..."
                                    name="title"
                                    value="{{ !empty(request()->title) ? request()->input('title') : '' }}"
                                    required="">
                                <div class="icon">
                                    <button type="hidden" type="submit">
                                        <i class="far fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="item">
                        <div class="language">
                            <form action="{{ route('changeUserLanguage', getParam()) }}" method="GET">
                                <select class="niceselect" onchange="this.form.submit()" name="code">
                                    @foreach ($allLanguageInfos as $languageInfo)
                                        <option value="{{ $languageInfo->code }}"
                                            {{ $languageInfo->code == $currentLanguageInfo->code ? 'selected' : '' }}>
                                            {{ $languageInfo->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="item">
                        @auth('customer')
                            <a href="{{ route('customer.dashboard', getParam()) }}" class="btn-icon-text" target="_self"
                                title=" {{ $keywords['Dashboard'] ?? __('Dashboard') }}">
                                <span><i class="fas fa-user"></i></span>
                                <span>
                                    {{ $keywords['Dashboard'] ?? __('Dashboard') }}
                                </span>
                            </a>
                        @else
                            <a href="{{ route('customer.login', getParam()) }}" class="btn-icon-text" target="_self"
                                title="  {{ $keywords['Login'] ?? __('Login') }}">
                                <span><i class="fas fa-sign-in-alt"></i></span>
                                <span>
                                    {{ $keywords['Login'] ?? __('Login') }}
                                </span>
                            </a>
                            @endif
                        </div>
                    </div>
            </nav>
            </div>
        </div>
    </header>
