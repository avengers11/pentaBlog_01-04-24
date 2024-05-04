<header class="header-area header-area_v2 {{ !request()->routeIs('index') ? 'header-static' : '' }}">
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
                        @if($websiteInfo->logo)
                        <img class="lazyload" data-src="{{ $websiteInfo->logo != null ? Storage::url($websiteInfo->logo) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="{{ $websiteInfo->website_title }}"
                            src="{{ asset('assets/user/img/' . $websiteInfo->logo) }}">
                        @else
                        <img src="{{asset('assets/user/img/themes/default_6.png') }}" data-src="{{asset('assets/user/img/themes/default_6.png') }}" alt="Logo">
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
            <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                @if (!is_null($websiteInfo))
                    <a class="navbar-brand" href="{{ route('front.user.detail.view', getParam()) }}" target="_self"
                        title="{{ $websiteInfo->website_title }}">
                        @if($websiteInfo->logo)
                        <img class="lazyload" data-src="{{ $websiteInfo->logo != null ? Storage::url($websiteInfo->logo) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="{{ $websiteInfo->website_title }}"
                            src="{{ asset('assets/user/img/' . $websiteInfo->logo) }}">
                         @else
                         <img src="{{asset('assets/user/img/themes/default_6.png')}}" alt="Logo" data-src="{{asset('assets/user/img/themes/default_6.png')}}">
                         @endif
                    </a>
                @endif
                <!-- Navigation items -->
                <div class="collapse navbar-collapse">
                    <ul id="mainMenu" class="navbar-nav mobile-item ms-auto">
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
                                    <a href="#{{ $link['text'] }}" class="nav-link toggle"> {{ $link['text'] }} <i
                                            class="fal fa-plus"></i></a>
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
                <div class="more-option mobile-item">
                     <div class="item">
                        @auth('customer')
                            <a href="{{ route('customer.dashboard', getParam()) }}"
                                class="btn btn-md btn-primary rounded-pill" target="_self"
                                title="{{ $keywords['Dashboard'] ?? __('Dashboard') }}">
                                <span>
                                    {{ $keywords['Dashboard'] ?? __('Dashboard') }}
                                </span>
                            </a>
                        @else
                            <a href="{{ route('customer.login', getParam()) }}" class="btn btn-md btn-primary rounded-pill"
                                target="_self" title="{{ $keywords['Login'] ?? __('Login') }}">
                                <span>
                                    {{ $keywords['Login'] ?? __('Login') }}
                                </span>
                            </a>
                            @endif
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
                            <a href="#searchBox" class="btn-search btn-icon" target="_self" aria-label="Search Form"
                                title="Search Form" data-effect="mfp-zoom-in">
                                <i class="far fa-search"></i>
                            </a>
                            <div id="searchBox" class="search-box mx-auto mfp-with-anim mfp-hide mt-30">
                                <form action="{{ route('front.user.posts', getParam()) }}" method="GET" id="searching">
                                    <div class="input-inline p-1 border radius-sm">
                                        <input class="form-control border-0"
                                            placeholder="{{ $keywords['Search_Post'] ?? __('Search Post') }} ..."
                                            type="text" name="title"
                                            value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}"
                                            required="">
                                        <button class="btn-icon radius-sm" type="submit" aria-label="button">
                                            <i class="far fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
