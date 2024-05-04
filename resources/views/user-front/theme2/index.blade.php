@extends('user-front.common.layout')


@section('pageHeading')
  {{$keywords['Home'] ?? __('Home') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_home : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_home : '')


@section('content')
  @if ($hs->slider_posts == 1)
    <!-- Start Olima Banner Section -->
    <section class="olima_banner hero_post_v2 pb-140">
      <div class="container-full">
        @if (count($sliderPosts) == 0)
          <div class="row text-center">
            <div class="col">
              <h1 class="pt-5">{{$keywords['No_Slider_Post_Found'] ?? __('No Slider Post Found !') }}</h1>
            </div>
          </div>
        @else
          <div class="hero_post_slide_v2">
            @foreach ($sliderPosts as $sliderPost)
              <div class="grid_item">
                <div class="post_img">
                  <a href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}">
                    <img data-src="{{ $sliderPost->slider_post_image != null ? Storage::url($sliderPost->slider_post_image) : asset('assets/admin/img/noimage.jpg') }}" class="lazy" alt="post image">
                  </a>
                  <div class="post_overlay">
                    <div class="post_content" @if(count($sliderPosts) <= 3) style="visibility: visible; opacity: 1;" @endif>
                      @php
                        $sldPostCategory = App\Models\User\PostCategory::where('id', $sliderPost->post_category_id)->first();
                      @endphp

                      <span class="tag">{{ $sldPostCategory->name }}</span>
                      <h3>
                        <a href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}">{{ strlen($sliderPost->title) > 30 ? mb_substr($sliderPost->title, 0, 30, 'UTF-8') . '...' : $sliderPost->title }}</a>
                      </h3>
                      <p>{!! strlen(strip_tags($sliderPost->content)) > 100 ? mb_substr(strip_tags($sliderPost->content), 0, 100, 'UTF-8') . '...' : strip_tags($sliderPost->content) !!}</p>
                    </div>

                    <div class="post_meta">
                      <div class="admin">
                        <span>{{ $sliderPost->author }}</span>
                      </div>

                      <div class="meta_tag">
                        <span class="eye">{{ $sliderPost->views }}</span>

                        @php
                          // first, convert the string into date object
                          $date = Carbon\Carbon::parse($sliderPost->created_at);
                        @endphp

                        <span class="calender">{{ date_format($date, 'M d, Y') }}</span>
                      </div>
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


  <!-- Start Olima Featured & Latest Posts Section -->
  <section class="olima_blog blog_v1">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          @if ($hs->featured_posts == 1)
          <!-- Featured Posts Section -->
          <div class="row">
            <div class="col-lg-12">
              <div class="row no-gutters">
                <div class="col-lg-12">
                  <div class="section_title mb-40">
                    <h3>{{$keywords['Featured_Posts'] ?? __('Featured Posts') }}</h3>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-12 mb-50">
              @if (count($featuredPosts) == 0)
                <h5>{{$keywords['No_Featured_Post_Found'] ?? __('No Featured Post Found !') }}</h5>
              @else
                <div class="latest-slider-two">
                  @foreach ($featuredPosts as $featuredPost)
                    <div class="grid_item grid_post_big">
                      <div class="post_img">
                        <a href="{{ route('front.user.post_details', ['slug' => $featuredPost->slug, getParam()]) }}">
                          <img data-src="{{ $featuredPost->featured_post_image != null ? Storage::url($featuredPost->featured_post_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
                        </a>
                        <div class="ribbon">
                          <span><i class="fas fa-heart"></i>{{ $featuredPost->bookmarks }}</span>
                        </div>
                      </div>

                      <div class="post_content">
                        <div class="post_meta">
                          <span class="user"><a>{{ $featuredPost->author }}</a></span>

                          @php
                            // first, convert the string into date object
                            $date = Carbon\Carbon::parse($featuredPost->created_at);
                          @endphp

                          <span class="calender">{{ date_format($date, 'M d, Y') }}</span>
                        </div>
                        <h3>
                          <a href="{{ route('front.user.post_details', ['slug' => $featuredPost->slug, getParam()]) }}">{{ strlen($featuredPost->title) > 90 ? mb_substr($featuredPost->title, 0, 90, 'UTF-8') . '...' : $featuredPost->title }}</a>
                        </h3>
                        <a href="{{ route('front.user.post_details', ['slug' => $featuredPost->slug, getParam()]) }}" class="btn_link">{{ $keywords['Read_More'] ?? 'Read More' }}</a>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
            @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
              @if (!empty(showAd(3)))
                <div class="col-12 text-center mb-4">
                  {!! showAd(3) !!}
                </div>
              @endif
            @endif
          </div>
          @endif

          @if ($hs->latest_posts == 1)
          <!-- Latest Posts Section -->
          <div class="row">
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-12">
                  <div class="section_title mb-50">
                    <h3>{{$keywords['Latest_Posts'] ?? __('Latest Posts') }}</h3>
                  </div>
                </div>
              </div>
            </div>

            @if (count($latestPosts) == 0)
              <div class="col-lg-12 pb-80">
                <h5>{{$keywords['No_Latest_Post_Found'] ?? __('No Latest Post Found !')}}</h5>
              </div>
            @else
              @foreach ($latestPosts as $latestPost)
                <div class="col-lg-12">
                  <div class="grid_item d-lg-flex d-md-flex align-items-lg-center align-items-md-center mb-50">
                    <div class="post_img">
                      <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}">
                        <img data-src="{{ $latestPost->thumbnail_image != null ? Storage::url($latestPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
                      </a>
                    </div>

                    <div class="post_content">
                      <div class="post_meta">
                        <span class="user"><a >{{ $latestPost->author }}</a></span>

                        @php
                          // first, convert the string into date object
                          $date = Carbon\Carbon::parse($latestPost->created_at);
                        @endphp

                        <span class="calender">{{ date_format($date, 'M d, Y') }}</span>
                      </div>
                      <h3>
                        <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}">{{ strlen($latestPost->title) > 45 ? mb_substr($latestPost->title, 0, 45, 'UTF-8') . '...' : $latestPost->title }}</a>
                      </h3>
                      <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}" class="btn_link">{{ $keywords['Read_More'] ?? 'Read More' }}</a>
                    </div>
                  </div>
                </div>
              @endforeach
              <div class="button_box  pb-50 text-center col-12">
                <a href="{{ route('front.user.posts', getParam()) }}" class="load-btn">{{ $keywords['View_More'] ?? 'View More' }}</a>
              </div>
            @endif
            @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
              @if (!empty(showAd(3)))
                <div class="col-12 text-center pb-120">
                  {!! showAd(3) !!}
                </div>
              @endif
            @endif
          </div>
          @endif
        </div>

        <div class="col-lg-4">
          <div class="olima_sidebar sidebar_v1">
            @if ($hs->author_info == 1 && !empty($authorInfo))
            <div class="widget_box about_box mb-40">
              <div class="about_img">
                <img data-src="{{ $authorInfo->image != null ? Storage::url($authorInfo->image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
              </div>

              <div class="about_content">
                <h4>{{$keywords['Hi,_I_am'] ? $keywords['Hi,_I_am']. ' ' . $authorInfo->name :  __('Hi, I am') . ' ' . $authorInfo->name }}</h4>
                <p>{!! strlen(strip_tags($authorInfo->about)) > 100 ? mb_substr(strip_tags($authorInfo->about), 0, 100, 'UTF-8') . '...' : strip_tags($authorInfo->about) !!}</p>

                @if (count($socialLinkInfos) > 0)
                  <ul class="social_link">
                    @foreach ($socialLinkInfos as $socialLink)
                      <li><a href="{{ $socialLink->url }}" target="_blank"><i class="{{ $socialLink->icon }}"></i></a></li>
                    @endforeach
                  </ul>
                @endif
              </div>
              <a href="{{ route('front.user.about', getParam()) }}" class="olima_btn">{{ $keywords['Learn_More'] ?? 'Learn More' }}</a>
            </div>
            @endif

            @if ($hs->post_categories == 1)
            @if (count($postCategories) > 0)
              <div class="widget_box place_widget_box mb-50">
                <h4 class="widget-title">{{ $keywords['Categories'] ?? 'Categories' }}</h4>
                <ul class="categories">
                  @foreach ($postCategories as $postCategory)
                    <li>
                      <a href="#" data-category_id="{{ $postCategory->id }}">
                        {{ $postCategory->name }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
            @endif

            @if ($hs->popular_posts == 1)
            @if (count($mostViewedPosts) > 0)
              <div class="widget_box featured_post mb-40">
                <h4>{{ $keywords['Popular_Posts'] ?? 'Popular_Posts' }}</h4>
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

                        <span class="calender">{{ date_format($date, 'M d, Y') }}</span>
                        <span class="eye">{{ $mostViewedPost->views }}</span>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
            @endif

            @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
              @if ($hs->sidebar_ads == 1)
                <div class="widget_box add_widget_2 mb-40">
                  <div class="add_img">
                    {!! showAd(2) !!}
                  </div>
                </div>

                <div class="widget_box add_widget_2">
                  <div class="add_img">
                    {!! showAd(1) !!}
                  </div>
                </div>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Start Olima Featured & Latest Posts Section -->

  <!-- Start Olima Gallery Section -->
  @if (is_array($packagePermissions) && in_array('Gallery',$packagePermissions))
    @if ($hs->gallery == 1)
    <section class="olima_video video_v2 bg_image pt-130 pb-140 lazy" data-bg="{{ asset('assets/user/img/' . $galleryInfo->gallery_bg) }}">
      <div class="olima_overlay"></div>
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="section_title mb-50 text-center">
              <h3>{{$keywords['Gallery'] ?? __('Gallery') }}</h3>
            </div>
          </div>
        </div>

        @if (count($galleryItems) == 0)
          <div class="row text-center">
            <div class="col">
              <h3 class="text-light">{{$keywords['No_Gallery_Item_Found'] ?? __('No Gallery Item Found !')}}</h3>
            </div>
          </div>
        @else
          <div class="swiper-container video_slide_v2">
            <div class="swiper-wrapper">
              @foreach ($galleryItems as $item)
                <div class="swiper-slide grid_item">
                  <div class="post_img">
                    <img src="{{ $item->image != null ? Storage::url($item->image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid" alt="image">
                    <div class="post_overlay">
                      <div class="play_button">
                        @if ($item->item_type == 'video')
                          <a href="{{ $item->video_link }}" class="play_btn mfp-iframe"><i class="fas fa-play"></i></a>
                        @else
                          <a href="{{ asset('assets/user/img/gallery/' . $item->image) }}" class="play_btn"><i class="fas fa-image"></i></a>
                        @endif
                      </div>

                      <div class="post_content">
                        <h3><a href="#">{{ $item->title }}</a></h3>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <div class="video_slide_v2_nav">
              <div class="swiper-button-next"><span>{{ __('Next') }}<i class="flaticon-right"></i></span></div>
              <div class="swiper-button-prev"><span><i class="flaticon-back"></i>{{ __('Previous') }}</span></div>
            </div>
          </div>
        @endif
      </div>
    </section>
    @endif
  @endif
  <!-- End Olima Gallery Section -->

  <!-- Start Posts of Featured Categories Section -->
  @if ($hs->featured_category_posts == 1)
  <section class="olima_blog blog_v2 pt-130">
    <div class="container">

      @if (count($featPostCategories) == 0)
        <div class="row text-center">
          <div class="bg-light py-5 text-center col-12">
            <h1>{{ $keywords['No_Featured_Post_Category_Found'] ?? __('No Featured Post Category Found !')}}</h1>
          </div>
        </div>
      @else
        @php $langId = $currentLanguageInfo->id; @endphp

        @foreach ($featPostCategories as $featPostCategory)
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="section_title mb-50">
                <h3>{{ $featPostCategory->name }}</h3>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="button_box">
                <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}" class="olima_btn">{{ __('Show More') }}</a>
              </div>
            </div>
          </div>

          @php
            $featCatPosts = DB::table('posts')
              ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
              ->where('post_contents.language_id', '=', $langId)
              ->where('post_contents.post_category_id', '=', $featPostCategory->id)
              ->orderBy('posts.serial_number', 'asc')
              ->limit(3)
              ->get();
          @endphp

          @if (count($featCatPosts) == 0)
            <div class="row text-center">
              <div class="pt-5 pb-3 bg-light text-center col-12 mb-4">
                <h4 class="mb-5">{{ __('No Post Found Of This Category') . '!' }}</h4>
              </div>
            </div>
          @else
            <div class="row">
              @foreach ($featCatPosts as $featCatPost)
                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class="grid_item mb-40">
                    <div class="post_img">
                      <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}">
                        <img data-src="{{ $featCatPost->thumbnail_image != null ? Storage::url($featCatPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
                      </a>
                    </div>
                    <div class="post_content">
                      <div class="post_meta">
                        <span class="user"><a>{{ $featCatPost->author }}</a></span>

                        @php
                          // first, convert the string into date object
                          $date = Carbon\Carbon::parse($featCatPost->created_at);
                        @endphp

                        <span class="calender">{{ date_format($date, 'M d, Y') }}</span>
                      </div>

                      <h3>
                        <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}">{{ strlen($featCatPost->title) > 28 ? mb_substr($featCatPost->title, 0, 28, 'UTF-8') . '...' : $featCatPost->title }}</a>
                      </h3>
                      <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}" class="btn_link">{{ $keywords['Read_More'] ?? 'Read More' }}</a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif

          @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
            <div class="mx-auto mb-4 text-center">
              {!! showAd(3) !!}
            </div>
          @endif

        @endforeach
      @endif
    </div>
  </section>
  @endif
  <!-- End Posts of Featured Categories Section -->

  <!-- Start Olima Newsletter Section -->
  @if ($hs->newsletter == 1)
  <section class="olima_newsletter newsletter_v1 pt-90 pb-140 bg_image lazy" data-bg="{{ asset('assets/user/img/newsletter_bg.jpg') }}">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="section_title text-center mb-50">
            <h3>{{$keywords['Newsletter'] ??  __('Newsletter') }}</h3>
            <p>{{$keywords['Subscribe_to_Our_Newsletter_and_Stay_Updated'] ?? __('Subscribe to Our Newsletter and Stay Updated') }}</p>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="newsletter_box">
            <form class="subscriptionForm" action="{{ route('front.user.subscriber',getParam()) }}" method="POST">
              @csrf
              <div class="form_group">
                <input type="email" class="form_control" placeholder="{{ $keywords["Enter_Your_Email_Address"] ?? "Enter Your Email Address" }}" name="email">

                <button class="submit_btn d-flex align-items-center justify-content-center"><i class="fas fa-envelope"></i></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif
  <!-- End Olima Newsletter Section -->


  {{-- search form start --}}
  <form class="d-none" action="{{ route('front.user.posts', getParam()) }}" method="GET">
    <input type="hidden" id="categoryKey" name="category">
    <button type="submit" id="submitBtn"></button>
  </form>
  {{-- search form end --}}
@endsection
