@extends('user-front.common.layout')

@section('pageHeading')
    {{$keywords['Home'] ?? __('Home') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_home : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_home : '')

@section('content')
@if ($hs->slider_posts == 1)
<!-- Start Olima Banner Section -->
<section class="olima_banner hero_post_v4">
  @if (count($sliderPosts) == 0)
    <div class="container">
      <div class="row text-center">
        <div class="col">
          <h1 class="pt-5">{{$keywords['No_Slider_Post_Found'] ?? __('No Slider Post Found !') }}</h1>
        </div>
      </div>
    </div>
  @else
    <div class="hero_post_slide_v3">
      @foreach ($sliderPosts as $sliderPost)
        <div class="grid_item">
          <div class="post_img">
            <a href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}">
              <img data-src="{{ $sliderPost->slider_post_image != null ? Storage::url($sliderPost->slider_post_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
            </a>
          </div>

          <div class="post_content" @if(count($sliderPosts) <= 3) style="visibility: visible; opacity: 1;" @endif>
            @php
              $sldPostCategory = App\Models\User\PostCategory::where('id', $sliderPost->post_category_id)->where('user_id',$user->id)->first();
            @endphp

            <a href="{{ route('front.user.posts', ['category' => $sldPostCategory->id, getParam()]) }}" class="post-category">{{ $sldPostCategory->name }}</a>
            <h3>
              <a href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}">{{ strlen($sliderPost->title) > 30 ? mb_substr($sliderPost->title, 0, 30, 'UTF-8') . '...' : $sliderPost->title }}</a>
            </h3>

            <div class="post_meta">
              <span class="eye">{{ $sliderPost->views }}</span>

              @php
                  $bookmarkCount = \App\Models\User\BookmarkPost::where('post_id', $sliderPost->post_id)->count();
              @endphp
              <span class="love">{{ $sliderPost->bookmarks }}</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</section>
<!-- End Olima Banner Section -->
@endif

  @if ($hs->featured_category_posts == 1)
  <!-- Start Posts of Featured Categories Section -->
  <section class="olima_highlights_post pt-145 pb-110" id="highlights_post">
    <div class="container">
        @if (count($featPostCategories) == 0)
          <div class="row text-center">
            <div class="col">
              <h1>{{ $keywords['No_Featured_Post_Category_Found'] ?? __('No Featured Post Category Found !')}}</h1>
            </div>
          </div>
        @else
          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="section_title section_title_2 mb-60">
                <h3>{{$keywords['Featured_Category_Posts'] ?? __('Featured Category Posts') }}</h3>
              </div>
            </div>

            <div class="col-lg-6">
              <ul class="post_filter nav nav-tabs border-0" id="myTab" role="tablist">
                @foreach ($featPostCategories as $featPostCategory)
                  @php
                      $id = $featPostCategory->id;
                  @endphp

                  <li class="nav-item" role="presentation">
                    <a class="{{ $loop->first ? 'active' : '' }} nav-link" id="{{ 'tab-' . $id }}" href="{{ '#fcat' . $id }}" data-toggle="tab" role="tab" aria-controls="{{ 'fcat' . $id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                      {{ $featPostCategory->name }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>

          @php $langId = $currentLanguageInfo->id; @endphp

          <div class="tab-content">
            @foreach ($featPostCategories as $featPostCategory)
              @php
                $featCatPosts = DB::table('posts')
                  ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
                  ->where('post_contents.language_id', '=', $langId)
                  ->where('post_contents.post_category_id', '=', $featPostCategory->id)
                  ->where('posts.is_featured', '!=', 10)
                  ->orderBy('posts.serial_number', 'ASC')
                  ->limit(4)
                  ->get();

                $id = $featPostCategory->id;
              @endphp

              <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ 'fcat' . $id }}" role="tabpanel" aria-labelledby="{{ 'tab-' . $id }}">
                @if (count($featCatPosts) == 0)
                  <div class="row text-center">
                    <div class="col py-5 bg-light text-center">
                      <h4>{{$keywords['No_Post_Found_Of_This_Category'] ?? __('No Post Found Of This Category !') }}</h4>
                    </div>
                  </div>
                @else
                  <div class="row">
                    @foreach ($featCatPosts as $featCatPost)
                      <div class="col-lg-3">
                        <div class="grid_item mb-30">
                          <div class="post_img">
                            <img data-src="{{ $featCatPost->thumbnail_image != null ? Storage::url($featCatPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">

                            @php
                              // first, convert the string into date object
                              $date = Carbon\Carbon::parse($featCatPost->created_at);
                            @endphp

                            <span class="date">{{ date_format($date, 'M d, Y') }}</span>
                          </div>

                          <div class="post_content">
                            <h3>
                              <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}">{{ strlen($featCatPost->title) > 40 ? mb_substr($featCatPost->title, 0, 40, 'UTF-8') . '...' : $featCatPost->title }}</a>
                            </h3>
                          </div>
                        </div>
                      </div>
                    @endforeach
                    <div class="col-12 text-center mt-3">
                      <div class="button_box">
                        <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}" class="olima_btn">{{ $keywords['Show_More'] ?? __('Show More') }}</a>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        @endif

        @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
          <div class="text-center mt-5">
            {!! showAd(3) !!}
          </div>
        @endif

    </div>
  </section>
  <!-- End Posts of Featured Categories Section -->
  @endif

  @if ($hs->latest_posts == 1)
  <!-- Start Olima Latest Post Section -->
  <section class="olima_features_post features_post_v1 features_post_v2 light_bg pt-140 pb-100">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="section_title section_title_2 mb-50">
            <h3>{{ $keywords['Latest_Posts'] ?? __('Latest Posts') }}</h3>
          </div>

          @if (count($latestPosts) == 0)
            <div class="row text-center">
              <div class="col">
                <h4>{{$keywords['No_Latest_Post_Found'] ?? __('No Latest Post Found !')}}</h4>
              </div>
            </div>
          @else
            <div class="row">
              @foreach ($latestPosts as $latestPost)
                <div class="col-xl-4 col-lg-6 grid_item d-flex align-items-center mb-40">
                  <div class="post_img">
                    <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}">
                      <img data-src="{{ $latestPost->thumbnail_image != null ? Storage::url($latestPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
                    </a>
                  </div>

                  <div class="post_content">
                    <div class="post_meta">
                      @php
                        // first, convert the string into date object
                        $date = Carbon\Carbon::parse($latestPost->created_at);
                      @endphp

                      <span class="date">{{ date_format($date, 'M d, Y') }}</span>
                    </div>
                    <h3>
                      <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}">{{ strlen($latestPost->title) > 35 ? mb_substr($latestPost->title, 0, 35, 'UTF-8') . '...' : $latestPost->title }}</a>
                    </h3>
                  </div>
                </div>
              @endforeach
              <div class="button_box  pb-20 text-center col-12 mt-4">
                <a href="{{ route('front.user.posts', getParam()) }}" class="load-btn">{{ $keywords['View_More'] ?? __('VIEW MORE') }}</a>
              </div>
            </div>
          @endif

          @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
            <div class="text-center mt-5">
              {!! showAd(3) !!}
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
  <!-- End Olima Latest Post Section -->
  @endif

  @if (is_array($packagePermissions) && in_array('Gallery',$packagePermissions))
    @if ($hs->gallery == 1)
    <!-- Start Olima Gallery Section -->
    <section class="olima_video video_v3 bg_image pt-140 pb-130 lazy" data-bg="{{ asset('assets/user/img/' . $galleryInfo->gallery_bg) }}">
      <div class="bg_overlay"></div>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="section_title section_title_2 text-center mb-50">
              <h3 class="text-white">{{$keywords['Gallery'] ?? __('Gallery') }}</h3>
            </div>
          </div>
        </div>

        @if (count($galleryItems) == 0)
          <div class="row text-center">
            <div class="col">
              <h3 class="text-white">{{$keywords['No_Gallery_Item_Found'] ?? __('No Gallery Item Found !')}}</h3>
            </div>
          </div>
        @else
          <div class="row">
            <div class="col-lg-7">
              <div class="video_big_slide">
                @foreach ($galleryItems as $item)
                  <div class="grid_item grid_post_big">
                    <div class="post_img">
                      <img data-src="{{ $item->image != null ? Storage::url($item->image) : asset('assets/admin/img/noimage.jpg') }}" class="w-100 lazy" alt="image">
                      <div class="post_overlay">
                        <div class="play_button">
                          @if ($item->item_type == 'video')
                            <a href="{{ $item->video_link }}" class="play_btn mfp-iframe"><i class="fas fa-play"></i></a>
                          @else
                            <a href="{{ asset('assets/user/img/gallery/' . $item->image) }}" class="play_btn"><i class="fas fa-image"></i></a>
                          @endif
                        </div>

                        <div class="post_content">
                          <h3><a>{{ $item->title }}</a></h3>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="col-lg-5">
              <div class="video_play_list video_thumb_slide">
                @foreach ($galleryItems as $item)
                  <div class="grid_item d-flex align-items-center">
                    <div class="post_img">
                      <img src="{{ $item->image != null ? Storage::url($item->image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid" alt="image">
                      <div class="post_overlay">
                      </div>
                    </div>

                    <div class="post_content">
                      <h3><a>{{ $item->title }}</a></h3>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif
      </div>
    </section>
    <!-- End Olima Gallery Section -->
    @endif
  @endif

  @if ($hs->featured_posts == 1)
  <!-- Start Olima Featured Posts Section -->
  <section class="olima_blog blog_v4 pt-130 pb-110">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="section_title section_title_2 text-center mb-50">
            <h3>{{$keywords['Featured_Posts'] ?? __('Featured Posts') }}</h3>
          </div>
        </div>
      </div>

      @if (count($featuredPosts) == 0)
        <div class="row text-center">
          <div class="col">
            <h4>{{$keywords['No_Featured_Post_Found'] ?? __('No Featured Post Found !') }}</h4>
          </div>
        </div>
      @else
        <div class="row">
          @foreach ($featuredPosts as $featuredPost)
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div class="grid_item mb-50">
                <div class="post_img">
                  <a href="{{ route('front.user.post_details', ['slug' => $featuredPost->slug, getParam()]) }}">
                    <img data-src="{{ $featuredPost->featured_post_image != null ? Storage::url($featuredPost->featured_post_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
                  </a>

                  @php
                    // first, convert the string into date object
                    $date = Carbon\Carbon::parse($featuredPost->created_at);
                  @endphp

                  <span class="date">{{ date_format($date, 'M d, Y') }}</span>
                </div>

                <div class="post_content">
                  <h3>
                    <a href="{{ route('front.user.post_details', ['slug' => $featuredPost->slug, getParam()]) }}">{{ strlen($featuredPost->title) > 40 ? mb_substr($featuredPost->title, 0, 40, 'UTF-8') . '...' : $featuredPost->title }}</a>
                  </h3>
                  <div class="post_meta">
                    <span>{{$keywords['By'] ?? 'By'}}{{ ' ' . $featuredPost->author }}</span>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif

      @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
        <div class="text-center mt-4">
          {!! showAd(3) !!}
        </div>
      @endif
    </div>
  </section>
  <!-- End Olima Featured Posts Section -->
  @endif
@endsection
