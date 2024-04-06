<section class="olima_footer footer_v2 blue_bg pt-180">
  <div class="container">
    <div class="footer_widget">
      <div class="row">
        @if (!is_null($footerInfo))
          <div class="col-lg-4">
            <div class="widget_box about_box">
              <a href="{{route('front.user.detail.view', getParam())}}">
                <img data-src="{{ asset('assets/user/img/footer/' . $footerInfo->logo) }}" class="img-fluid lazy" alt="website footer logo">
              </a>

              <p class="mt-3">{{ !is_null($footerInfo) ? $footerInfo->about_company : '' }}</p>

              @if (count($socialLinkInfos) > 0)
                <ul class="social_link">
                  @foreach ($socialLinkInfos as $socialLink)
                    <li><a href="{{ $socialLink->url }}" target="_blank"><i class="{{ $socialLink->icon }}"></i></a></li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
        @endif

        <div class="col-lg-4">
          <div class="widget_box useful_link_widget">
            <h4>{{ $keywords['Quick_Links'] ?? 'Quick Links' }}</h4>
            <div class="row">
              <div class="col">
                @if (count($quickLinkInfos) == 0)
                  <h6 class="text-light pb-60">{{ $keywords['No_Quick_Link_Found'] ?? 'No Quick Link Found' }}</h6>
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

        <div class="col-lg-4">
          <div class="widget_box contact_widget">
            <h4>{{ $keywords['Contact_Us'] ?? 'Contact Us' }}</h4>
            
            @isset($websiteInfo->address)
            <p><span><i class="fas fa-map-marker-alt"></i></span>{{ $websiteInfo->address }}</p>
            @endisset

            @isset($websiteInfo->support_email)
            <p><span><i class="fas fa-envelope"></i></span><a href="{{ 'mailto:' . $websiteInfo->support_email }}">{{ $websiteInfo->support_email }}</a></p>
            @endisset

            @isset($websiteInfo->support_contact)
            <p><span><i class="fas fa-phone"></i></span><a href="{{ 'tel:' . $websiteInfo->support_contact }}">{{ $websiteInfo->support_contact }}</a></p>
            @endisset
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="copyright_area">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="row">
            <div class="col">
              <div class="copy_text">
                <p>{!! !is_null($footerInfo) ? $footerInfo->copyright_text : '' !!}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
