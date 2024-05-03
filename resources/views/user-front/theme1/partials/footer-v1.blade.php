<footer class="olima_footer footer_v1 white_gray_bg pt-100 pb-90">
  <div class="footer_widget">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <div class="widget_box useful_link_widget">
            @if (count($quickLinkInfos) > 0)
              <ul class="widget_link">
                @foreach ($quickLinkInfos as $quickLink)
                  <li><a href="{{ $quickLink->url }}">{{ $quickLink->title }}</a></li>
                @endforeach
              </ul>
            @endif

            @if (count($socialLinkInfos) > 0)
              <ul class="social_link">
                @foreach ($socialLinkInfos as $socialLink)
                  <li><a href="{{ $socialLink->url }}" target="_blank"><i class="{{ $socialLink->icon }}"></i></a></li>
                @endforeach
              </ul>
            @endif
          </div>
        </div>

        <div class="col-lg-5 col-md-6 col-sm-12">
          <div class="widget_box about_box">
            @if (!is_null($footerInfo))
              <a href="{{route('front.user.detail.view', getParam())}}">
                <img data-src="{{ $footerInfo->logo != null ? Storage::url($footerInfo->logo) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="website footer logo">
              </a>
            @endif

            <p>{{ !is_null($footerInfo) ? $footerInfo->about_company : '' }}</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="widget_box copyright_box">
            <p>{!! !is_null($footerInfo) ? $footerInfo->copyright_text : '' !!}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
