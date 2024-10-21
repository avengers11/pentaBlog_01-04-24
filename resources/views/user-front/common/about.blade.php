@extends('user-front.common.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->about_me_title }}
    @else
    {{$keywords['About'] ?? 'About' }}
    @endif
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_about : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_about : '')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/front/css/summernote-content.css') }}">
@endsection

@section('content')
    <!-- Start Olima Breadcrumb Section -->
    <section class="olima_breadcrumb bg_image lazy"
        @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
        <div class="bg_overlay"
            style="background: #{{ $websiteInfo->breadcrumb_overlay_color }}; opacity: {{ $websiteInfo->breadcrumb_overlay_opacity }}">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="breadcrumb-title">
                        <h1>{{ !empty($pageHeading) ? $pageHeading->about_me_title : 'About' }}</h1>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">
                                {{ !empty($pageHeading) ? $pageHeading->about_me_title : 'About' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Breadcrumb Section -->

    <!-- Start Olima About Section -->
    <section class="olima_about pt-140">
        <div class="container">
            @if (count($sldImgs) == 0)
                <div class="row text-center">
                    <div class="col">
                        <h1>{{ $keywords['No_Slider_Image_Found'] ? $keywords['No_Slider_Image_Found'] . '!' : __('No Slider Image Found') . '!' }}
                        </h1>
                    </div>
                </div>
            @else
                <div class="about-slider-one">
                    @foreach ($sldImgs as $sldImg)
                        <div class="olima_img">
                            <a href="{{ $sldImg->image != null ? Storage::url($sldImg->image) : asset('assets/admin/img/noimage.jpg') }}"
                                class="img-popup"> 
                                <img data-src="{{ $sldImg->image != null ? Storage::url($sldImg->image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="author image">
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="row pt-110 pb-80">
                <div class="col-lg-4">
                    <div class="olima_content_box">
                        @if (!empty($authorInfo))
                            <span>{{ $keywords['Welcome'] ?? __('Welcome') }}</span>
                            <h2>{{ $keywords['Hi,_I_am'] ? $keywords['Hi,_I_am'] . ' ' . $authorInfo->name : __('Hi, I am') . ' ' . $authorInfo->name }}
                            </h2>
                        @endif

                        @if (count($socialLinkInfos) > 0)
                            <ul class="social_link">
                                @foreach ($socialLinkInfos as $socialLink)
                                    <li><a href="{{ $socialLink->url }}" target="_blank"><i
                                                class="{{ $socialLink->icon }}"></i></a></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="summernote-content">
                        @if (!empty($authorInfo))
                            {!! replaceBaseUrl($authorInfo->about, 'summernote') !!}
                        @endif
                    </div>
                </div>
            </div>

            @if (!empty($authorInfo) && !empty($authorInfo->link))
                <div class="row pb-140">
                    <div class="col-lg-12">
                        <div class="grid_item">
                            <div class="post_img">
                                <img data-src="{{ $authorInfo->video_background_image != null ? Storage::url($authorInfo->video_background_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="background image">
                                <div class="post_button">
                                    <a href="{{ $authorInfo->link }}" class="play_btn mfp-iframe"><i class="fas fa-play"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </section>
    <!-- End Olima About Section -->

    <!-- Start Olima Testimonial Section -->
    @if (count($testimonials) > 0)
        <section class="testimonial-area-v1 pb-140">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="section_title pb-65">
                            <h3>{{ $keywords['What_Reader_Say'] ?? __('What Reader Say') }}</h3>
                        </div>
                    </div>
                </div>

                <div class="testimonial_slide_one">
                    @foreach ($testimonials as $testimonial)
                        <div class="testimonial_box">
                            <ul class="rating">
                                @for ($i = 0; $i < $testimonial->rating; $i++)
                                    <li><i class="fas fa-star"></i></li>
                                @endfor
                            </ul>
                            <p>{{ $testimonial->comment }}</p>
                            <div class="author_box">
                                <img data-src="{{ $testimonial->client_image != null ? Storage::url($testimonial->client_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="client image">
                                <span>{{ $testimonial->client_name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif
    <!-- End Olima Testimonial Section -->

    <!-- Start Olima Partner/Sponsor Section -->
    @if (count($partners) > 0)
        <section class="sponsor-area-v1 pb-140">
            <div class="container">
                <div class="sponsor_slide_one">
                    @foreach ($partners as $partner)
                        <a class="sponsor_box d-block" href="{{ $partner->url }}" target="_blank">
                            <div class="olima_img">
                                <img data-src="{{ $partner->brand_img != null ? Storage::url($partner->brand_img) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="image">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- End Olima Partner/Sponsor Section -->

@endsection
