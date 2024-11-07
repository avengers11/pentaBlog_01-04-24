<!-- Global site tag (gtag.js) - Google Analytics -->
@if ($websiteInfo->analytics_status == 1)
<script async src="//www.googletagmanager.com/gtag/js?id={{ $websiteInfo->measurement_id }}"></script>
<script>
"use strict";
window.dataLayer = window.dataLayer || [];

function gtag() {
dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', '{{ $websiteInfo->measurement_id }}');
</script>
@endif

<script data-host="{{ env("PENTA_ANALYTICS_URL") }}" data-dnt="false" src="{{ env("PENTA_ANALYTICS_URL") }}/js/script.js" id="ZwSg9rf6GA" async defer></script>


<script>
"use strict";
var mainurl = "{{ route('front.user.detail.view', getParam()) }}";
var textPosition = "{{ $userBs->base_currency_text_position }}";
var currSymbol = "{{ $userBs->base_currency_symbol }}";
var position = "{{ $userBs->base_currency_symbol_position }}";
</script>
<script>
"use strict";
const langDir = {{ $currentLanguageInfo->rtl }};
const previous = '{{ $keywords['Previous'] ?? 'Previous' }}';
const next = '{{ $keywords['Next'] ?? 'Next' }}';
</script>
<script>
"use strict";
var mainURL = "{{ route('front.user.detail.view', getParam()) }}";
</script>
{{-- jQuery --}}

@if ($themeInfo->theme_version == 1 || $themeInfo->theme_version == 2 || $themeInfo->theme_version == 3 || $themeInfo->theme_version == 4 || !request()->routeIs('front.user.detail.view'))
<script src="{{ asset('assets/user/js/common/jquery-3.4.1.min.js') }}"></script>
{{-- modernizr js --}}
<script src="{{ asset('assets/user/js/common/modernizr-3.6.0.min.js') }}"></script>

{{-- popper js --}}
<script src="{{ asset('assets/user/js/common/popper.min.js') }}"></script>

{{-- bootstrap js --}}
<script src="{{ asset('assets/user/js/common/bootstrap.min.js') }}"></script>

{{-- slick js --}}
<script src="{{ asset('assets/user/js/common/slick.min.js') }}"></script>

{{-- isotope-pkgd js --}}
<script src="{{ asset('assets/user/js/common/isotope-pkgd-3.0.6.min.js') }}"></script>

{{-- imagesloaded-pkgd js --}}
<script src="{{ asset('assets/user/js/common/imagesloaded.pkgd.min.js') }}"></script>

{{-- nice-number js --}}
<script src="{{ asset('assets/user/js/common/jquery.nice-number.min.js') }}"></script>

{{-- datatables js --}}
<script src="{{ asset('assets/user/js/common/datatables-1.10.23.min.js') }}"></script>

{{-- jQuery-ui js --}}
<script src="{{ asset('assets/user/js/common/jquery-ui.min.js') }}"></script>

{{-- jQuery-syotimer js --}}
<script src="{{ asset('assets/user/js/common/jquery-syotimer.min.js') }}"></script>
@include('user-front.common.partials.plugin-js')
{{-- miscellaneous js --}}
<script src="{{ asset('assets/user/js/common/misc.js') }}"></script>
{{-- main js --}}
<script src="{{ asset('assets/user/js/theme1234/main.js') }}"></script>
@endif

@if (
($themeInfo->theme_version == 5 || $themeInfo->theme_version == 6 || $themeInfo->theme_version == 7) &&
!request()->routeIs('front.user.detail.view'))
@include('user-front.common.partials.header-footer-js')
@endif
