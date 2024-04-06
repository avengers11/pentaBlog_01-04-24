
<!DOCTYPE html>
<html lang="en" @if ($currentLanguageInfo->rtl == 1) dir="rtl" @endif>
<head>
    {{-- required meta tags --}}
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('meta-description')">
    <meta name="keywords" content="@yield('meta-keywords')">

    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- title --}}
    <title>

        {{ isset($websiteInfo) && $websiteInfo->website_title ? $websiteInfo->website_title :   @$user->username   }} - @yield('pageHeading')
    </title>

    {{-- fav icon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/user/img/' . $websiteInfo->favicon) }}">

    {{-- include styles --}}
    @includeIf('user-front.common.partials.styles')
    @yield('styles')
    @yield('home-css')
    {{-- --============common css-==================--- --}}
    {{-- base-color css using a php file ....new theme add base color this file --}}
    <link rel="stylesheet" href="{{ asset('assets/user/css/theme1234/base-color.php?color=' . $websiteInfo->primary_color) }}">
    @if ($websiteInfo->whatsapp_status == 1)
        <style>
            .back-top .back-to-top {
                left: auto;
                right: 95%;
            }

            @media only screen and (max-width: 1199px) {
                .back-top .back-to-top {
                    right: 92%;
                }
            }

            @media only screen and (max-width: 767px) {
                .back-top .back-to-top {
                    right: 89%;
                }
            }

            @media only screen and (max-width: 575px) {
                .back-top .back-to-top {
                    right: 85%;
                }
            }

            @media only screen and (max-width: 414px) {
                .back-top .back-to-top {
                    right: 80%;
                }
            }
        </style>
    @endif

</head>
@php
    $bodyClass = '';
    if ($themeInfo->theme_version == 5) {
        $bodyClass = 'theme-dark';
    }
@endphp
<body class="{{$bodyClass}}">
    @if (isset($websiteInfo) && $websiteInfo->preloader_status == 1)
        {{-- preloader start --}}
        @includeIf('user-front.common.preloader')
        {{-- preloader end --}}
    @endif


    @if ($themeInfo->theme_version == 3 || $themeInfo->theme_version == 4)
        @includeIf('user-front.theme1.partials.search-post')
    @endif

    {{-- header start --}}
    @includeIf('user-front.common.header')
    {{-- header end --}}

    @yield('content')

    {{-- include footer --}}
    @includeIf('user-front.common.footer')
    {{-- include footer --}}

    {{-- back to top start --}}
    @if (
        $themeInfo->theme_version == 1 ||
            $themeInfo->theme_version == 2 ||
            $themeInfo->theme_version == 3 ||
            $themeInfo->theme_version == 4)
        <div class="back-top">
            <a href="#" class="back-to-top">
                <i class="far fa-angle-up"></i>
            </a>
        </div>
    @else
        <div class="go-top" dir="ltr"><i class="fal fa-long-arrow-up"></i></div>
    @endif
    {{-- back to top end --}}
    {{-- announcement popup --}}
    @includeIf('user-front.common.partials.popups')
    @php
        $userShop = App\Models\User\UserShopSetting::where('user_id', $user->id)->first();
    @endphp
    @if (!empty($userShop))
        @if ($userShop->is_shop == 1)
            <div id="cartIconWrapper">
                <a class="d-block" id="cartIcon" href="{{ route('front.user.cart', getParam()) }}">
                    <div class="cart-length">
                        <i class="fal fa-shopping-bag"></i>
                        <span class="length">{{ cartLength() }} {{ $keywords['Items'] ?? __('ITEMS') }}</span>
                    </div>
                    <div class="cart-total">
                        {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                        {{ cartTotal() }}
                        {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                    </div>
                </a>
            </div>
        @endif
    @endif
    <!---- Slider Lenght--->
    @if ($userBs->whatsapp_status == 1)
        {{-- WhatsApp Chat Button --}}
        <div id="WAButton"></div>
    @endif

    @if ($userBs->tawkto_status == 1)
        @php
            $directLink = str_replace('tawk.to', 'embed.tawk.to', $bs->tawkto_chat_link);
            $directLink = str_replace('chat/', '', $directLink);
        @endphp
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            "use strict";
            let directLink = '{{ $directLink }}';
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();
            (function() {
                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = '{{ $directLink }}';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
        <!--End of Tawk.to Script-->
    @endif

    @php
        $cookieStatus = !empty($cookieAlertInfo) && $cookieAlertInfo->cookie_alert_status == 1 ? true : false;
        $cookieName = str_replace(' ', '_', $userBs->website_title . '_' . $user->username);
        $cookieName = strtolower($cookieName) . '_cookie';

        \Config::set('cookie-consent.enabled', $cookieStatus);
        \Config::set('cookie-consent.cookie_name', $cookieName);
    @endphp
    {{-- cookie alert --}}
    <div class="cookie">
        @include('cookieConsent::index')
    </div>

    {{-- include scripts --}}
    @includeIf('user-front.common.partials.scripts')

    {{-- additional script --}}
    @yield('home-js')
    @yield('script')
    {{-- whatsapp script --}}
    <script type="text/javascript">
        var whatsapp_popup = {{ $websiteInfo->whatsapp_popup_status }};
        var whatsappImg = "{{ asset('assets/user/img/whatsapp.svg') }}";
        $(function() {
            $('#WAButton').floatingWhatsApp({
                phone: "{{ $websiteInfo->whatsapp_number }}", //WhatsApp Business phone number
                headerTitle: "{{ $websiteInfo->whatsapp_header_title }}", //Popup Title
                popupMessage: `{!! nl2br($websiteInfo->whatsapp_popup_message) !!}`, //Popup Message
                showPopup: whatsapp_popup == 1 ? true : false, //Enables popup display
                buttonImage: '<img src="' + whatsappImg + '" />', //Button Image
                position: "right"
            });
        });
    </script>
    {{-- disqus script --}}
    @yield('disqus-script')

    {{-- -toastr alert---- --}}
    @if (session()->has('success'))
        <script>
            "use strict";
            toastr['success']("{{ __(session('success')) }}");
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            "use strict";
            toastr['error']("{{ __(session('error')) }}");
        </script>
    @endif
    {{-- -toastr alert---- --}}

</body>
</html>
