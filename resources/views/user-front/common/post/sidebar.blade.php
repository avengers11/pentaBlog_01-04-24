<div class="col-lg-4">
  <div class="olima_sidebar sidebar_v1">
    @if (!empty($authorInfo))
      <div class="widget_box about_box mb-40">
        <div class="about_img">
            <img data-src="{{ $authorInfo->image != null ? Storage::url($authorInfo->image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
        </div>

        <div class="about_content">
          <h4>{{$keywords['Hi,_I_am'] ? $keywords['Hi,_I_am']. ' ' . $authorInfo->name :  __('Hi, I am') . ' ' . $authorInfo->name }}</h4>
          <p>{!! strlen(strip_tags($authorInfo->about)) > 100 ? mb_substr(strip_tags($authorInfo->about), 0, 100, 'UTF-8') . '...' : replaceBaseUrl(strip_tags($authorInfo->about), 'summernote') !!}</p>

          @if (count($socialLinkInfos) > 0)
            <ul class="social_link">
              @foreach ($socialLinkInfos as $socialLink)
                <li><a href="{{ $socialLink->url }}" target="_blank"><i class="{{ $socialLink->icon }}"></i></a></li>
              @endforeach
            </ul>
          @endif
        </div>
        <a href="{{route('front.user.about', getParam())}}" class="olima_btn mt-3">{{ $keywords["Learn_More"] ?? "Learn More" }}</a>
      </div>
    @endif

    @if (count($categories) > 0)
      <div class="widget_box categories_widget mb-40">
        <h4>{{ $keywords["Categories"] ?? "Categories" }}</h4>
        <ul class="categories">
          @foreach ($categories as $category)
            <li class="{{ $category->id == request()->input('category') ? 'active' : '' }}">
              <a href="#" data-category_id="{{ $category->id }}">
                {{ $category->name }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    @endif

    @if (count($popularPosts) > 0)
      <div class="widget_box featured_post mb-50">
        <h4>{{ $keywords["Popular_Posts"] ?? "Popular Posts" }}</h4>

        @foreach ($popularPosts as $popularPost)
          <div class="single_post d-flex align-items-center">
            <div class="post_img">
              <a href="{{ route('front.user.post_details', ['slug' => $popularPost->slug,getParam()]) }}">
                <img data-src="{{ $popularPost->thumbnail_image != null ? Storage::url($popularPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="post image">
              </a>
            </div>

            <div class="post_content">
              <h3>
                <a href="{{ route('front.user.post_details', ['slug' => $popularPost->slug, getParam()]) }}">{{ strlen($popularPost->title) > 30 ? mb_substr($popularPost->title, 0, 30, 'UTF-8') . '...' : $popularPost->title }}</a>
              </h3>
              <div class="post_meta">
                @php
                  // first, convert the string into date object
                  $date = Carbon\Carbon::parse($popularPost->created_at);
                @endphp

                <span class="calender"><a href="#">{{ date_format($date, 'M d, Y') }}</a></span>
                <span class="eye">{{ $popularPost->views }}</span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    <div class="widget_box newsletter_widget">
      <img data-src="{{ asset('assets/user/img/icon_1.png') }}" class="lazy" alt="icon">
      <h4>{{$keywords['Newsletter'] ??  __('Newsletter') }}</h4>
      <p>{{$keywords['Subscribe_to_Our_Newsletter_and_Stay_Updated'] ?? __('Subscribe to Our Newsletter and Stay Updated') }}</p>
      <form class="subscriptionForm" action="{{ route('front.user.subscriber',getParam()) }}" method="POST">
        @csrf
        <div class="form_group">
          <input type="email" class="form_control" placeholder="{{$keywords["Email_Address"] ?? "Email Address"}}" name="email">
          @if ($errors->has('email'))
                <p class="pb-3 text-danger">{{$errors->first('email')}}</p>
          @endif
        </div>

        <div class="form_group">
          <button class="olima_btn sidebar_btn">{{$keywords['Subscribe'] ?? __('Subscribe') }}</button>
        </div>
      </form>
    </div>

    @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
      @if (!empty(showAd(1)))
        <div class="widget_box add_widget mt-40 text-center">
          {!! showAd(1) !!}
        </div>
      @endif
    @endif

  </div>
</div>

{{-- search form start --}}
<form class="d-none" action="{{ route('front.user.posts', getParam()) }}" method="GET">
  <input type="hidden" id="categoryKey" name="category">
  <button type="submit" id="submitBtn"></button>
</form>
{{-- search form end --}}
