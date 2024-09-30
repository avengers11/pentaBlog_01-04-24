@extends('user-front.common.layout')

@section('pageHeading')
    {{ $keywords['Home'] ?? __('Home') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_home : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_home : '')

@if (count($sliderPosts) <= 1)
    @section('styles')
        <style>
            .hero_post_v1 .grid_item .post_img:after {
                background: rgba(255, 255, 255, 0);
            }
        </style>
    @endsection
@endif

@section('content')
    @if ($hs->slider_posts == 1)
        <!-- Start Olima Banner Section -->
        <section class="olima_banner hero_post_v1">
            @if (count($sliderPosts) == 0)
                <div class="container">
                    <div class="row text-center">
                        <div class="col">
                            <h1 class="pt-5">{{ $keywords['No_Slider_Post_Found'] ?? __('No Slider Post Found !') }}</h1>
                        </div>
                    </div>
                </div>
            @else
                <div class="hero_post_slide_v1">
                    @foreach ($sliderPosts as $sliderPost)
                        <!-- grid item -->
                        <div class="grid_item">
                            <div class="post_img">
                                <a href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}"
                                    class="d-block">
                                    <img src="{{ $sliderPost->slider_post_image != null ? Storage::url($sliderPost->slider_post_image) : asset('assets/admin/img/noimage.jpg') }}"
                                        class="img-fluid" alt="image">
                                </a>
                                <div class="post_overlay"
                                    @if (count($sliderPosts) <= 1) style="visibility: visible; opacity: 1;" @endif>
                                    @php
                                        $sldPostCategory = App\Models\User\PostCategory::where('id', $sliderPost->post_category_id)
                                            ->where('user_id', $user->id)
                                            ->first();
                                    @endphp
                                    <div class="post_content">
                                        <a href="{{ route('front.user.posts', ['category' => $sldPostCategory->id, getParam()]) }}"
                                            class="cat_btn">{{ $sldPostCategory->name }}</a>
                                        <h3>
                                            <a
                                                href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}">{{ strlen($sliderPost->title) > 30 ? mb_substr($sliderPost->title, 0, 30, 'UTF-8') . '...' : $sliderPost->title }}</a>
                                        </h3>
                                        <div class="post_meta">
                                            <span class="eye">{{ $sliderPost->views }}</span>
                                            @php
                                                $bookmarkCount = \App\Models\User\BookmarkPost::where('post_id', $sliderPost->post_id)->count();
                                            @endphp
                                            <span class="love">{{ $sliderPost->bookmarks }}</span>
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
        </section>
        <!-- End Olima Banner Section -->

    @endif

    @if ($hs->post_categories == 1)
        <!-- Start Olima Categories Section -->
        <section class="olima_categories categories_v1 pt-130 pb-110">
            @if (count($postCategories) == 0)
                <div class="container">
                    <div class="row text-center">
                        <div class="col">
                            <h4>{{ $keywords['No_Post_Category_Found'] ?? __('No Post Category Found !') }}</h4>
                        </div>
                    </div>
                </div>
            @else
                <div class="container">
                    <div class="categories_slide">
                        @foreach ($postCategories as $postCategory)
                            <a href="{{ route('front.user.posts', ['category' => $postCategory->id, getParam()]) }}"
                                class="categories_box">
                                <div class="cat_img">
                                    <img data-src="{{ $postCategory->image != null ? Storage::url($postCategory->image) : asset('assets/admin/img/noimage.jpg') }}"
                                        class="img-fluid lazy" alt="image">
                                    <div class="cat_overlay">
                                        <div class="cat_content">
                                            <h5>{{ $postCategory->name }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
        <!-- End Olima Categories Section -->
    @endif

    <!-- Start Olima Featured & Latest Posts Section -->
    <section class="olima_latest_post latest_post_v1 pb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    @if ($hs->featured_posts == 1)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section_title mb-50">
                                    <h3>{{ $keywords['Featured_Posts'] ?? __('Featured Posts') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Featured Posts Section -->
                            @if (count($featuredPosts) == 0)
                                <div class="col-lg-12 mb-40">
                                    <h5>{{ $keywords['No_Featured_Post_Found'] ?? __('No Featured Post Found !') }}</h5>
                                </div>
                            @else
                                <div class="col-lg-12 mb-40">
                                    <div class="latest-slider-one">
                                        @foreach ($featuredPosts as $featuredPost)
                                            <div class="grid_item grid_post_big">
                                                <div class="post_img">
                                                    <a
                                                        href="{{ route('front.user.post_details', ['slug' => $featuredPost->slug, getParam()]) }}">
                                                        <img data-src="{{ $featuredPost->featured_post_image != null ? Storage::url($featuredPost->featured_post_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                            class="img-fluid lazy" alt="image">
                                                    </a>
                                                    <div class="post_overlay">
                                                        @php
                                                            $featPostCategory = App\Models\User\PostCategory::where('id', $featuredPost->post_category_id)->first();
                                                        @endphp

                                                        <div class="post_content">
                                                            <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}"
                                                                class="cat_btn">{{ $featPostCategory->name }}</a>
                                                            <h3>
                                                                <a
                                                                    href="{{ route('front.user.post_details', ['slug' => $featuredPost->slug, getParam()]) }}">{{ strlen($featuredPost->title) > 30 ? mb_substr($featuredPost->title, 0, 30, 'UTF-8') . '...' : $featuredPost->title }}</a>
                                                            </h3>
                                                            <div class="post_meta">
                                                                <span class="eye">{{ $featuredPost->views }}</span>
                                                                @php
                                                                    $bookmarkCount = \App\Models\User\BookmarkPost::where('post_id', $featuredPost->post_id)->count();
                                                                @endphp
                                                                <span class="love">{{ $bookmarkCount }}</span>

                                                                @php
                                                                    // first, convert the string into date object
                                                                    $date = Carbon\Carbon::parse($featuredPost->created_at);
                                                                @endphp

                                                                <span
                                                                    class="calender">{{ date_format($date, 'M d, Y') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                                @if (!empty(showAd(3)))
                                    <div class="text-center mb-4 mx-auto">
                                        {!! showAd(3) !!}
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif

                    @if ($hs->latest_posts == 1)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section_title mb-50">
                                    <h3>{{ $keywords['Latest_Posts'] ?? __('Latest Posts') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Latest Posts Section -->
                            @if (count($latestPosts) == 0)
                                <div class="col-lg-12">
                                    <h5>{{ $keywords['No_Latest_Post_Found'] ?? __('No Latest Post Found !') }}</h5>
                                </div>
                            @else
                                @foreach ($latestPosts as $latestPost)
                                    @auth('customer')
                                        @php
                                            $postBookmarked = 0;
                                            foreach ($bookmarkPosts as $bookmarkPost) {
                                                if ($bookmarkPost->post_id == $latestPost->post_id) {
                                                    $postBookmarked = 1;
                                                    break;
                                                }
                                            }
                                        @endphp
                                    @endauth

                                    <div class="col-lg-6">
                                        <div class="grid_item mb-40">
                                            <div class="post_img">
                                                <img data-src="{{ $latestPost->thumbnail_image != null ? Storage::url($latestPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    class="img-fluid lazy" alt="image {{$latestPost->thumbnail_image}}">
                                                <div class="post_overlay">
                                                    @php
                                                        $latestPostCategory = App\Models\User\PostCategory::where('id', $latestPost->post_category_id)->first();
                                                    @endphp

                                                    <div class="post_tag">
                                                        <a href="{{ route('front.user.posts', ['category' => $latestPostCategory->id, getParam()]) }}"
                                                            class="cat_btn">{{ $latestPostCategory->name }}</a>
                                                        <a href="{{ route('front.user.make_bookmark', ['id' => $latestPost->post_id, getParam()]) }}"
                                                            class="love_btn post-info-{{ $latestPost->post_id }} {{ Auth::guard('customer')->check() == true && $postBookmarked == 1 ? 'post-bookmarked' : '' }}"><i
                                                                class="fas fa-heart"></i></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="post_content">
                                                <h3>
                                                    <a
                                                        href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}">{{ strlen($latestPost->title) > 30 ? mb_substr($latestPost->title, 0, 30, 'UTF-8') . '...' : $latestPost->title }}</a>
                                                </h3>
                                                <div class="post_meta">
                                                    @php
                                                        // first, convert the string into date object
                                                        $date = Carbon\Carbon::parse($latestPost->created_at);
                                                    @endphp
                                                    <span class="calender"><a
                                                            href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}">{{ date_format($date, 'M d, Y') }}</a></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if (count($latestPosts) > 0)
                                <div class="button_box  pb-50 text-center col-12">
                                    <a href="{{ route('front.user.posts', getParam()) }}"
                                        class="load-btn">{{ $keywords['VIEW_MORE'] ?? __('VIEW MORE') }}</a>
                                </div>
                            @endif

                            @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                                @if (!empty(showAd(3)))
                                    <div class="text-center mb-4 mx-auto">
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
                                    <img data-src="{{ $authorInfo->image != null ? Storage::url($authorInfo->image) : asset('assets/admin/img/noimage.jpg') }}"
                                        class="img-fluid lazy" alt="image">
                                </div>

                                <div class="about_content">
                                    <h4>{{ $keywords['Hi,_I_am'] ? $keywords['Hi,_I_am'] . ' ' . $authorInfo->name : __('Hi, I am') . ' ' . $authorInfo->name }}
                                    </h4>
                                    <p>{!! strlen(strip_tags($authorInfo->about)) > 100
                                        ? mb_substr(strip_tags($authorInfo->about), 0, 100, 'UTF-8') . '...'
                                        : strip_tags($authorInfo->about) !!}</p>

                                    @if (count($socialLinkInfos) > 0)
                                        <ul class="social_link">
                                            @foreach ($socialLinkInfos as $socialLink)
                                                <li><a href="{{ $socialLink->url }}" target="_blank"><i
                                                            class="{{ $socialLink->icon }}"></i></a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <a href="{{ route('front.user.about', getParam()) }}"
                                    class="olima_btn">{{ $keywords['Learn_More'] ?? 'Learn More' }}</a>
                            </div>
                        @endif

                        @if ($hs->popular_posts == 1)
                            @if (count($mostViewedPosts) > 0)
                                <div class="widget_box featured_post mb-40">
                                    <h4>{{ $keywords['Popular_Posts'] ?? __('Popular Posts') }}</h4>
                                    @foreach ($mostViewedPosts as $mostViewedPost)
                                        <div class="single_post d-flex align-items-center">
                                            <div class="post_img">
                                                <a
                                                    href="{{ route('front.user.post_details', ['slug' => $mostViewedPost->slug, getParam()]) }}">
                                                    <img data-src="{{ $mostViewedPost->thumbnail_image != null ? Storage::url($mostViewedPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                        class="img-fluid lazy" alt="post image">
                                                </a>
                                            </div>

                                            <div class="post_content">
                                                <h3>
                                                    <a
                                                        href="{{ route('front.user.post_details', ['slug' => $mostViewedPost->slug, getParam()]) }}">{{ strlen($mostViewedPost->title) > 30 ? mb_substr($mostViewedPost->title, 0, 30, 'UTF-8') . '...' : $mostViewedPost->title }}</a>
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

                        @if ($hs->newsletter == 1)
                            <div class="widget_box newsletter_widget">
                                <img data-src="{{ asset('assets/user/img/icon_1.png') }}" class="lazy" alt="icon">
                                <h4>{{ $keywords['Newsletter'] ?? __('Newsletter') }}</h4>
                                <p>
                                    {{ $keywords['Subscribe_to_Our_Newsletter_and_Stay_Updated'] ?? __('Subscribe to Our Newsletter and Stay Updated') }}
                                </p>
                                <form class="subscriptionForm" action="{{ route('front.user.subscriber', getParam()) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form_group">
                                        <input type="email" class="form_control"
                                            placeholder="{{ $keywords['Email_Address'] ?? __('Email Address') }}"
                                            name="email" required>
                                    </div>

                                    <div class="form_group">
                                        <button
                                            class="olima_btn sidebar_btn">{{ $keywords['Subscribe'] ?? __('Subscribe') }}</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                            @if ($hs->sidebar_ads == 1)
                                @if (!empty(showAd(2)))
                                    <div class="widget_box booking_widget mb-40 text-center">
                                        {!! showAd(2) !!}
                                    </div>
                                @endif
                                @if (!empty(showAd(1)))
                                    <div class="widget_box add_widget mb-40 text-center">
                                        {!! showAd(1) !!}
                                    </div>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Featured & Latest Posts Section -->

    <!-- Start Olima Gallery Section -->
    @if (is_array($packagePermissions) && in_array('Gallery', $packagePermissions))
        @if ($hs->gallery == 1)
            <section class="olima_video video_v1 bg_image pt-130 pb-215 lazy"
                data-bg="{{ asset('assets/user/img/' . $galleryInfo->gallery_bg) }}">
                <div class="container-full">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="section_title mb-50 text-center">
                                <h3>{{ $keywords['Gallery'] ?? __('Gallery') }}</h3>
                            </div>
                        </div>
                    </div>

                    @if (count($galleryItems) == 0)
                        <div class="row text-center">
                            <div class="col">
                                <h3>{{ $keywords['No_Gallery_Item_Found'] ?? __('No Gallery Item Found !') }}</h3>
                            </div>
                        </div>
                    @else
                        <div class="video_slide_v1">
                            @foreach ($galleryItems as $item)
                                <div class="grid_item">
                                    <div class="post_img">
                                        <img src="{{ $item->image != null ? Storage::url($item->image) : asset('assets/admin/img/noimage.jpg') }}"
                                            class="img-fluid" alt="image">
                                        <div class="post_overlay">
                                            <div class="play_button"
                                                @if (count($galleryItems) <= 5) style="visibility: visible; opacity: 1;" @endif>
                                                @if ($item->item_type == 'video')
                                                    <a href="{{ $item->video_link }}" class="play_btn mfp-iframe"><i
                                                            class="fas fa-play"></i></a>
                                                @else
                                                    <a href="{{ Storage::url($item->image) }}"
                                                        class="play_btn"><i class="fas fa-image"></i></a>
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
                    @endif
                </div>
            </section>
        @endif
    @endif
    <!-- End Olima Gallery Section -->

    @if ($hs->featured_category_posts == 1)
        <!-- Start Posts of Featured Categories Section -->
        <section class="olima_vagetarian vagetarian_v1 pt-120">
            <div class="container">
                @if (count($featPostCategories) == 0)
                    <div class="row text-center pb-120">
                        <div class="col">
                            <h3>{{ $keywords['No_Featured_Post_Category_Found'] ?? __('No Featured Post Category Found !') }}
                            </h3>
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
                                    <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}"
                                        class="olima_btn">{{ $keywords['Show_More'] ?? __('Show More') }}</a>
                                </div>
                            </div>
                        </div>

                        @php
                            $featCatPosts = DB::table('posts')
                                ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
                                ->where('post_contents.language_id', '=', $langId)
                                ->where('post_contents.post_category_id', '=', $featPostCategory->id)
                                ->where('posts.user_id', $user->id)
                                ->where('posts.is_featured', '!=', 10)
                                ->orderBy('posts.serial_number', 'ASC')
                                ->limit(3)
                                ->get();
                        @endphp

                        @if (count($featCatPosts) == 0)
                            <div class="row text-center">
                                <div class="col pt-5 pb-3 mb-4 bg-light">
                                    <h5 class="mb-5">
                                        {{ $keywords['No_Post_Found_Of_This_Category'] ?? __('No Post Found Of This Category !') }}
                                    </h5>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                @foreach ($featCatPosts as $featCatPost)
                                    @auth('customer')
                                        @php
                                            $postBookmarked = 0;
                                            foreach ($bookmarkPosts as $bookmarkPost) {
                                                if ($bookmarkPost->post_id == $featCatPost->post_id) {
                                                    $postBookmarked = 1;
                                                    break;
                                                }
                                            }
                                        @endphp
                                    @endauth

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="grid_item mb-40">
                                            <div class="post_img">
                                                <a href="#">
                                                    <img data-src="{{ $featCatPost->thumbnail_image != null ? Storage::url($featCatPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                        class="img-fluid lazy" alt="image">
                                                </a>
                                                <div class="post_overlay">
                                                    <div class="post_tag">
                                                        <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}"
                                                            class="cat_btn">{{ $featPostCategory->name }}</a>
                                                        <a href="{{ route('front.user.make_bookmark', ['id' => $featCatPost->post_id, getParam()]) }}"
                                                            class="love_btn post-info-{{ $featCatPost->post_id }} {{ Auth::guard('customer')->check() == true && $postBookmarked == 1 ? 'post-bookmarked' : '' }}"><i
                                                                class="fas fa-heart"></i></a>
                                                    </div>

                                                    <div class="post_content">
                                                        <h3>
                                                            <a
                                                                href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}">{{ strlen($featCatPost->title) > 30 ? mb_substr($featCatPost->title, 0, 30, 'UTF-8') . '...' : $featCatPost->title }}</a>
                                                        </h3>
                                                        <div class="post_meta">
                                                            @php
                                                                // first, convert the string into date object
                                                                $date = Carbon\Carbon::parse($featCatPost->created_at);
                                                            @endphp
                                                            <span class="calender"><a
                                                                    href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}">{{ date_format($date, 'M d, Y') }}</a></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                            @if (!empty(showAd(3)))
                                <div class="mx-auto text-center pb-120">
                                    {!! showAd(3) !!}
                                </div>
                            @endif
                        @endif
                    @endforeach
                @endif
            </div>
        </section>
        <!-- End Posts of Featured Categories Section -->
    @endif

@endsection
