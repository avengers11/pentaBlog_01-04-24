@extends('user-front.common.layout')

@section('pageHeading')
  @if (!empty($details))
    {{ $details->title }}
  @endif
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_faq : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_faq : '')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/front/css/summernote-content.css') }}">
@endsection

@section('content')
  <!-- Start Olima Breadcrumb Section -->
  <section class="olima_breadcrumb bg_image lazy" @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
    <div class="bg_overlay" style="background: #{{$websiteInfo->breadcrumb_overlay_color}}; opacity: {{$websiteInfo->breadcrumb_overlay_opacity}}"></div>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="breadcrumb-title">
            <h1>{{ !empty($details) ? $details->title : '' }}</h1>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="breadcrumb-link">
            <ul>
              <li class="text-uppercase"><a href="{{route('front.user.detail.view', getParam())}}">{{$keywords['Home'] ?? __('Home') }}</a></li>
              <li class="active text-uppercase">{{ !empty($details) ? $details->title : '' }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Olima Breadcrumb Section -->

  <!-- Start Olima FAQ Section -->
  <section class="faq-area-v1 pt-120 pb-90">
    <div class="container">
        <div class="row">
          <div class="col-lg-12">
              <div class="summernote-content">
                  @if(!empty($details->content))
                   {!! replaceBaseUrl($details->content, 'summernote') !!}
                  @endif
              </div>
          </div>
        </div>
    </div>
  </section>
  <!-- End Olima FAQ Section -->

@endsection
