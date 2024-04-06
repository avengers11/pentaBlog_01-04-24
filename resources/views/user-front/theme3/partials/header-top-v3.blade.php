<div class="top_header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-4">
        <div class="social_widget">
          @if (count($socialLinkInfos) > 0)
            <ul>
              @foreach ($socialLinkInfos as $socialLink)
                <li><a href="{{ $socialLink->url }}" target="_blank"><i class="{{ $socialLink->icon }}"></i></a></li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>

      <div class="col-lg-4">
        <div class="brand_logo">
          @if (!is_null($websiteInfo))
            <a href="{{route('front.user.detail.view', getParam())}}">
              <img data-src="{{!empty($websiteInfo->logo) ? asset('assets/user/img/'.$websiteInfo->logo) : asset('assets/user/img/lgoo.png')}}" class="img-fluid lazy" alt="website logo">
            </a>
          @endif
        </div>
      </div>

      <div class="col-lg-4">
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

          <div class="search-nav">
            <a href="#" data-toggle="modal" data-target="#search-modal" class="search-btn"><i class="fas fa-search"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
