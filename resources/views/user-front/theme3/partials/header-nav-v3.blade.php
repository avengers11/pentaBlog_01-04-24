<div class="header_navigation">
  <div class="container">
    <div class="nav-container d-flex align-items-center">
      <div class="nav-menu">
        <!-- Navbar Close Icon -->
        <div class="navbar-close">
          <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
        </div>

        <!-- nav-menu -->
        <nav class="main-menu">
          <ul>
            @php
                  $links = json_decode($userMenus, true);
            @endphp
            @foreach ($links as $link)
              @php
                  $href = getUserHref($link, $currentLanguageInfo->id);
              @endphp

              @if (!array_key_exists("children",$link))
                {{--- Level1 links which doesn't have dropdown menus ---}}
                <li><a href="{{$href}}" target="{{$link["target"]}}">{{$link["text"]}}</a></li>
              @else
                {{--- Level1 links which has dropdown menus ---}}
                <li class="menu-item menu-item-has-children"><a href="{{$href}}" target="{{$link["target"]}}">{{$link["text"]}}</a>
                  <ul class="sub-menu">
                    {{-- START: 2nd level links --}}
                    @foreach ($link["children"] as $level2)
                      @php
                          $l2Href = getUserHref($level2, $currentLanguageInfo->id);
                      @endphp
                      <li><a href="{{$l2Href}}" target="{{$level2["target"]}}">{{$level2["text"]}}</a></li>
                    @endforeach
                  </ul>
                </li>
              @endif
            @endforeach
          </ul>
        </nav>
      </div>

      <!-- site logo -->
      <div class="brand_logo">
        @if (!is_null($websiteInfo))
          <a href="{{route('front.user.detail.view', getParam())}}">
            @if ($websiteInfo->text_to_logo_status == 1)
              <h2 class="logo-txt">{{$websiteInfo->text_to_logo}}</h2>
            @else
              <img data-src="{{ $websiteInfo->logo != null ? Storage::url($websiteInfo->logo) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="website logo">
            @endif
          </a>
        @endif
      </div>

      <!-- Navbar Toggler -->
      <div class="navbar-toggler">
        <span></span><span></span><span></span>
      </div>
    </div>
  </div>
</div>
