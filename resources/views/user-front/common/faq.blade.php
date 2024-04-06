@extends('user-front.common.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->faq_title }}
    @else
    {{$keywords['Faqs'] ?? 'Faqs' }}
    @endif
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_faq : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_faq : '')

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
                        <h1>{{ !empty($pageHeading) ? $pageHeading->faq_title : 'Faq' }}</h1>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">{{ !empty($pageHeading) ? $pageHeading->faq_title : 'Faq' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Breadcrumb Section -->

    <!-- Start Olima FAQ Section -->
    <section class="faq-area-v1 light_bg pt-120 pb-90">
        <div class="container">
            @if (count($faqs) == 0)
                <div class="row text-center">
                    <div class="col">
                        <h1>{{ $keywords['No_FAQ_Found'] ? $keywords['No_FAQ_Found'] . '!' : __('No FAQ Found') . '!' }}
                        </h1>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="faq-details-wrapper">
                            <div class="accordion" id="faq-accordion">
                                @foreach ($faqs as $faq)
                                    <div class="card mb-30">
                                        <a class="collapsed card-header" id="heading-{{ $faq->id }}" href="#"
                                            data-toggle="collapse" data-target="#faq-ans-{{ $faq->id }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-controls="collapse-{{ $faq->id }}">
                                            {{ $faq->question }}<span class="toggle_btn"></span>
                                        </a>

                                        <div id="faq-ans-{{ $faq->id }}"
                                            class="collapse {{ $loop->first ? 'show' : '' }}"
                                            aria-labelledby="heading-{{ $faq->id }}" data-parent="#faq-accordion">
                                            <div class="card-body">
                                                <p>{{ $faq->answer }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- End Olima FAQ Section -->
@endsection
