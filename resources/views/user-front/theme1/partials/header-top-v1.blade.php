<div class="top_header light_bg">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 col-md-6 col-sm-12">
        <!-- site logo -->
        <div class="brand_logo">
          @if (!is_null($websiteInfo))
            <a href="{{route('front.user.detail.view', getParam())}}">
              <img data-src="{{!empty($websiteInfo->logo) ? asset('assets/user/img/'.$websiteInfo->logo) : asset('assets/user/img/lgoo.png')}}" class="img-fluid lazy" alt="website logo">
            </a>
          @endif
        </div>
      </div>

      <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="top-right">
          <div class="lang">
            <form action="{{route('changeUserLanguage',getParam())}}" method="GET">
              <select class="olima_select" name="code" onchange="this.form.submit()">
                @foreach ($allLanguageInfos as $languageInfo)
                  <option value="{{ $languageInfo->code }}" {{ $languageInfo->code == $currentLanguageInfo->code ? 'selected' : '' }}>
                    {{ $languageInfo->name }}
                  </option>
                @endforeach
              </select>
            </form>
          </div>

          <div class="info">
            @guest('customer')
              <a href="{{route('customer.login', getParam())}}">{{$keywords['Login'] ?? __('Login') }}</a>
              <a href="{{route('customer.signup', getParam())}}">{{$keywords['Signup'] ?? __('Signup') }}</a>
            @endguest

            @auth('customer')
              @php $authUserInfo = Auth::guard('customer')->user(); @endphp

              <a href="{{route('customer.dashboard', getParam())}}">{{$keywords['Dashboard'] ?? __('Dashboard')  }}</a>
              <a href="{{route('customer.logout', getParam())}}">{{$keywords['Logout'] ?? __('Logout') }}</a>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
