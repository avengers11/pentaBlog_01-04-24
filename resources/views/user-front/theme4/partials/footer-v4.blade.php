<section class="olima_footer footer_v3">
  @if ($hs->footer == 1)
  <div class="footer_widget pb-90 pt-145">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="widget_box about_box">
            <h4>{{ $keywords["About_Us"] ?? __('About Us') }}</h4>
            @isset($footerInfo->about_company)
            <div class="about_content">
              <p>{{ !is_null($footerInfo) ? $footerInfo->about_company : '' }}</p>
            </div>
            @endisset

            @isset($websiteInfo->support_contact)
            <p class="text-light"><span>{{ $keywords["Phone"] ?? __('Phone') . ' :' }}</span><a href="{{ 'tel:' . $websiteInfo->support_contact }}">{{ $websiteInfo->support_contact }}</a></p>
            @endisset

            @isset($websiteInfo->address)
            <p class="text-light"><span>{{ $keywords["Address"] ?? __('Address') . ' :' }}</span>{{ $websiteInfo->address }}</p>
            @endisset
          </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="widget_box useful_link_widget">
            <h4>{{ $keywords["Quick_Links"] ?? __('Quick Links') }}</h4>
            <div class="row">
              <div class="col">
                @if (count($quickLinkInfos) == 0)
                  <h6 class="text-light">{{ __('No Quick Link Found') . '!' }}</h6>
                @else
                  <ul class="widget_link">
                    @foreach ($quickLinkInfos as $quickLink)
                      <li><a href="{{ $quickLink->url }}">{{ $quickLink->title }}</a></li>
                    @endforeach
                  </ul>
                @endif
              </div>
            </div>
          </div>
        </div>

        @isset($recentPostInfos)
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="widget_box featured_post">
            <h4>{{ $keywords["Recent_Posts"] ?? __('Recent Posts') }}</h4>

            @if (count($recentPostInfos) == 0)
              <h5>{{ __('No Recent Post Found') . '!' }}</h5>
            @else
              @foreach ($recentPostInfos as $recentPostInfo)
                <div class="single_post d-flex align-items-center">
                  <div class="post_img">
                    <img data-src="{{ asset('assets/user/img/posts/' . $recentPostInfo->thumbnail_image) }}" class="img-fluid lazy" alt="image" width="70">
                  </div>

                  <div class="post_content">
                    <h3>
                      <a href="{{ route('front.user.post_details', ['slug' => $recentPostInfo->slug, getParam()]) }}">{{ strlen($recentPostInfo->title) > 30 ? mb_substr($recentPostInfo->title, 0, 30, 'UTF-8') . '...' : $recentPostInfo->title }}</a>
                    </h3>

                    @php
                      // first, convert the string into date object
                      $date = Carbon\Carbon::parse($recentPostInfo->created_at);
                    @endphp

                    <span class="date">{{ date_format($date, 'M d, Y') }}</span>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>
        @endisset


      </div>
    </div>
  </div>
  @endif

  @if ($hs->newsletter == 1)
  <div class="footer_bottom">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <div class="row align-items-center">
            <div class="col-lg-4">
              <div class="newsletter_text">
                <h5><i class="fas fa-envelope"></i> {{ $keywords["Newsletter"] ?? __('Newsletter') }}</h5>
              </div>
            </div>

            <div class="col-lg-8">
              <div class="newsletter_box">
                <form class="subscriptionForm" action="{{ route('front.user.subscriber',getParam()) }}" method="POST">
                  @csrf
                  <div class="form_group">
                    <input type="email" class="form_control text-light" placeholder="{{$keywords["Email_Address"] ?? __('Email Address')}}" name="email" required>

                    <button class="olima_btn">{{ $keywords["Subscribe"] ?? __('Subscribe') }}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          @if (count($socialLinkInfos) > 0)
            <div class="social_widget">
              <ul>
                @foreach ($socialLinkInfos as $socialLink)
                  <li><a href="{{ $socialLink->url }}" target="_blank"><i class="{{ $socialLink->icon }}"></i></a></li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @endif

  @if ($hs->copyright_text == 1)
  <div class="copyright_area">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="copyright_text text-center">
            <p class="text-light">{!! !is_null($footerInfo) ? $footerInfo->copyright_text : '' !!}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
</section>
