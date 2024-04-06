@extends('user-front.common.layout')

@section('pageHeading')
    {{ $keywords['Home'] ?? __('Home') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_home : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_home : '')
@section('home-css')
    @includeIf('user-front.theme-5.include.css')
@endsection
@section('content')

    <!-- Hero-blog start -->
    @if ($hs->slider_posts == 1)
        @includeif('user-front.theme-5.sliders-posts')
    @endif
    <!-- Hero-blog end -->

    <!-- Page contents start -->
    @includeif('user-front.theme-5.page-contents')
    <!-- Page contents end -->

    <!-- Newsletter-area start -->
    @if ($hs->newsletter == 1)
        @includeif('user-front.theme-5.newsletter-area')
    @endif
    <!-- Newsletter-area end -->

@endsection
@section('home-js')
    @includeIf('user-front.theme-5.include.js')
@endsection
@section('script')
<script>
      var sliderPosts = "{{ count($sliderPosts) }}"
      var sliderLoop = true;
        if(sliderPosts <= 3){
           var  sliderLoop = false;
        }
     // object value
    var obj_value ={
            loop: sliderLoop,
            spaceBetween: 24,
            speed: 1000,
            autoplay: {
                delay: 3000,
            },
            slidesPerView: 4,
            pagination: false,

            // Navigation arrows
            navigation: {
                nextEl: "#hero-blog-slider-next",
                prevEl: "#hero-blog-slider-prev",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1
                },
                576: {
                    slidesPerView: 2
                },
                992: {
                    slidesPerView: 3
                },
                1440: {
                    slidesPerView: 4
                },
            }
        }
    var heroBlogSlider = new Swiper(".hero-blog-slider",obj_value);
</script>
@endsection
