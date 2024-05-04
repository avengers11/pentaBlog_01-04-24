@extends('user-front.common.layout')

@section('pageHeading')
    {{$keywords['Home'] ?? __('Home') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_home : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_home : '')

@section('content')
  @if ($hs->slider_posts == 1)
  <!-- Start Olima Banner Section -->
  <section class="olima_banner hero_post_v3 pt-30">
    <div class="container">
      @if (count($sliderPosts) == 0)
        <div class="row text-center">
          <div class="col">
            <h1 class="pt-5">{{$keywords['No_Slider_Post_Found'] ?? __('No Slider Post Found !') }}</h1>
          </div>
        </div>
      @else
        <div class="hero_post_slide_v4">
          @foreach ($sliderPosts as $sliderPost)
            <div class="grid_item">
              <div class="post_img">
                <img data-src="{{ $sliderPost->slider_post_image != null ? Storage::url($sliderPost->slider_post_image) : asset('assets/admin/img/noimage.jpg') }}" class="lazy" alt="image">
                <div class="post_overlay">
                  <div class="post_content">
                    <div class="post_meta">
                      @php
                        $sldPostCategory = App\Models\User\PostCategory::where('id', $sliderPost->post_category_id)->first();

                        // first, convert the string into date object
                        $date = Carbon\Carbon::parse($sliderPost->created_at);
                      @endphp

                      <span class="tag_btn">{{ $sldPostCategory->name }}</span>
                      <span class="date">{{ date_format($date, 'M d, Y') }}</span>
                    </div>
                    <h3>
                      <a href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}">{{ strlen($sliderPost->title) > 30 ? mb_substr($sliderPost->title, 0, 30, 'UTF-8') . '...' : $sliderPost->title }}</a>
                    </h3>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>
  <!-- End Olima Banner Section -->
  @endif

  <!-- Start Olima Latest Posts Section -->
  <section class="olima_latest_post latest_post_v2 pt-135" id="latest_post_v2">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          @if ($hs->latest_posts == 1)
          <div class="row">
            <div class="col-lg-12">
              <div class="section_title section_title_2 mb-40">
                <h3>{{ $keywords['Latest_Posts'] ?? __('Latest Posts') }}</h3>
              </div>
            </div>
          </div>

          @if (count($latestPosts) == 0)
            <div class="row text-center">
              <div class="col">
                <h4>{{$keywords['No_Latest_Post_Found'] ?? __('No Latest Post Found !')}}</h4>
              </div>
            </div>
          @else
            <div class="masonry_grid row">
              @foreach ($latestPosts as $latestPost)
                <div class="col-lg-6 grid_column">
                  <div class="grid_item mb-40">
                    <div class="post_img">
                      <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}">
                        <img src="{{ $latestPost->thumbnail_image != null ? Storage::url($latestPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid" alt="image">
                      </a>
                    </div>

                    <div class="post_content">
                      <div class="post_meta">
                        @php
                          $latestPostCategory = App\Models\User\PostCategory::where('id', $latestPost->post_category_id)->first();

                          // first, convert the string into date object
                          $date = Carbon\Carbon::parse($latestPost->created_at);
                        @endphp

                        <span class="tag_btn">{{ $latestPostCategory->name }}</span>
                        <span class="calendar">{{ date_format($date, 'M d, Y') }}</span>
                      </div>
                      <h3>
                        <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}">{{ strlen($latestPost->title) > 45 ? mb_substr($latestPost->title, 0, 45, 'UTF-8') . '...' : $latestPost->title }}</a>
                      </h3>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <div class="button_box pt-35 pb-50 text-center">
              <a href="{{ route('front.user.posts', getParam()) }}" class="load-btn">{{ $keywords['View_More'] ?? 'View More' }}</a>
            </div>
            @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
              @if (!empty(showAd(3)))
                <div class="text-center">
                  {!! showAd(3) !!}
                </div>
              @endif
            @endif
          @endif
          @endif
        </div>

        <div class="col-lg-4">
          <div class="olima_sidebar sidebar_v1">
            @if ($hs->author_info == 1)
            <div class="widget_box about_box mb-50">
              <div class="about_img">
                @if (!empty($authorInfo))
                  <img data-src="{{ $authorInfo->image != null ? Storage::url($authorInfo->image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
                @endif
              </div>

              <div class="about_content">
                @if (!empty($authorInfo))
                  <h4>{{$keywords['Hi,_I_am'] ? $keywords['Hi,_I_am']. ' ' . $authorInfo->name :  __('Hi, I am') . ' ' . $authorInfo->name }}</h4>
                  <p>{!! strlen(strip_tags($authorInfo->about)) > 100 ? mb_substr(strip_tags($authorInfo->about), 0, 100, 'UTF-8') . '...' : strip_tags($authorInfo->about) !!}</p>
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
            @endif

            @if ($hs->popular_posts == 1)
            @if (count($mostViewedPosts) > 0)
              <div class="widget_box featured_post mb-50">
                <h4>{{$keywords['Popular_Posts'] ?? __('Popular Posts') }}</h4>
                @foreach ($mostViewedPosts as $mostViewedPost)
                  <div class="single_post d-flex align-items-center">
                    <div class="post_img">
                      <a href="{{ route('front.user.post_details', ['slug' => $mostViewedPost->slug, getParam()]) }}">
                        <img data-src="{{ $mostViewedPost->thumbnail_image != null ? Storage::url($mostViewedPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="post image">
                      </a>
                    </div>

                    <div class="post_content">
                      <h3>
                        <a href="{{ route('front.user.post_details', ['slug' => $mostViewedPost->slug, getParam()]) }}">{{ strlen($mostViewedPost->title) > 30 ? mb_substr($mostViewedPost->title, 0, 30, 'UTF-8') . '...' : $mostViewedPost->title }}</a>
                      </h3>
                      <div class="post_meta">
                        @php
                          // first, convert the string into date object
                          $date = Carbon\Carbon::parse($mostViewedPost->created_at);
                        @endphp

                        <span class="calender date">{{ date_format($date, 'M d, Y') }}</span>
                        <span class="eye view">{{ $mostViewedPost->views }}</span>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
            @endif

            @if ($hs->newsletter == 1)
            <div class="widget_box newsletter_widget mb-50">
              <h4>{{$keywords['Newsletter'] ??  __('Newsletter') }}</h4>
              <form class="subscriptionForm" action="{{ route('front.user.subscriber',getParam()) }}" method="POST">
                @csrf
                <div class="form_group">
                  <input type="email" class="form_control" placeholder="{{ $keywords['Enter_Your_Email_Address'] ?? 'Enter Your Email Address' }}" name="email">

                  <button class="olima_btn">{{$keywords['Subscribe'] ?? __('Subscribe') }}</button>
                </div>
              </form>
            </div>
            @endif

            @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
              @if ($hs->sidebar_ads == 1)
                <div class="widget_box add_widget_2">
                  <div class="add_img">
                    {!! showAd(2) !!}
                  </div>
                </div>
              @endif
            @endif

          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Olima Latest Posts Section -->

  @if ($hs->featured_category_posts == 1)
  <!-- Start Posts of Featured Categories Section -->
  <section class="olima_features_post features_post_v1 pt-100 pb-100">
    <div class="container">
      @if (count($featPostCategories) == 0)
        <div class="row text-center">
          <div class="col">
            <h2>{{ $keywords['No_Featured_Post_Category_Found'] ?? __('No Featured Post Category Found !')}}</h2>
          </div>
        </div>
      @else
        <div class="row">
          @foreach ($featPostCategories as $featPostCategory)
            <div class="col-lg-6 col-xl-4">
              <div class="section_title section_title_2 mb-50">
                <div class="row">
                  <div class="col-6">
                    <h3><a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}">{{ $featPostCategory->name }}</a></h3>
                  </div>
                  <div class="col-6">
                    <div class="button_box">
                      <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}" class="olima_btn">{{ $keywords['Show_More'] ?? __('Show More') }}</a>
                    </div>
                  </div>
                </div>
              </div>

              @php
                $langId = $currentLanguageInfo->id;

                $featCatPosts = DB::table('posts')
                  ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
                  ->where('post_contents.language_id', '=', $langId)
                  ->where('post_contents.post_category_id', '=', $featPostCategory->id)
                  ->orderBy('posts.serial_number', 'asc')
                  ->limit(4)
                  ->get();
              @endphp

              @if (count($featCatPosts) == 0)
                <div class="row text-center">
                  <div class="col py-4 bg-light">
                    <h5>{{$keywords['No_Post_Found_Of_This_Category'] ?? __('No Post Found Of This Category !') }}</h5>
                  </div>
                </div>
              @else
                @foreach ($featCatPosts as $featCatPost)
                  @if ($loop->first)
                    <div class="grid_item grid_post_big mb-40">
                      <div class="post_img">
                        <img data-src="{{ $featCatPost->thumbnail_image != null ? Storage::url($featCatPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy w-100" alt="image">
                        <div class="post_overlay">
                          <div class="post_content">
                            <div class="post_meta">
                              <span class="tag_btn">{{ $featPostCategory->name }}</span>

                              @php
                                // first, convert the string into date object
                                $date = Carbon\Carbon::parse($featCatPost->created_at);
                              @endphp

                              <span>{{ date_format($date, 'M d, Y') }}</span>
                            </div>
                            <h3>
                              <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}">{{ strlen($featCatPost->title) > 30 ? mb_substr($featCatPost->title, 0, 30, 'UTF-8') . '...' : $featCatPost->title }}</a>
                            </h3>
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    <div class="grid_item d-flex align-items-center mb-40">
                      <div class="post_img">
                        <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}">
                          <img data-src="{{ $featCatPost->thumbnail_image != null ? Storage::url($featCatPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
                        </a>
                      </div>

                      <div class="post_content">
                        <h3>
                          <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}">{{ strlen($featCatPost->title) > 30 ? mb_substr($featCatPost->title, 0, 30, 'UTF-8') . '...' : $featCatPost->title }}</a>
                        </h3>
                        <div class="post_meta">
                          @php
                            // first, convert the string into date object
                            $date = Carbon\Carbon::parse($featCatPost->created_at);
                          @endphp
                          <span class="date">{{ date_format($date, 'M d, Y') }}</span>
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach
              @endif
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>
  <!-- End Posts of Featured Categories Section -->
  @endif

@endsection
