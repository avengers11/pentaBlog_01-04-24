@extends('user-front.common.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->posts_title }}
  @else
  {{__('Posts')}}
  @endif
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_posts : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_posts : '')

@section('content')
  <!-- Start Olima Breadcrumb Section -->
  <section class="olima_breadcrumb bg_image lazy" @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
    <div class="bg_overlay" style="background: #{{$websiteInfo->breadcrumb_overlay_color}}; opacity: {{$websiteInfo->breadcrumb_overlay_opacity}}"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-7">
          <div class="breadcrumb-title">
            <h1>{{ !empty($pageHeading) ? $pageHeading->posts_title : 'Posts' }}</h1>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="breadcrumb-link">
            <ul>
              <li class="text-uppercase"><a href="{{route('front.user.detail.view', getParam())}}">{{$keywords['Home'] ?? __('Home') }}</a></li>
              <li class="active text-uppercase">{{ !empty($pageHeading) ? $pageHeading->posts_title : 'Posts' }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Olima Breadcrumb Section -->

  <!-- Start Olima Posts Section -->
  @php $viewType = $userBs->post_view_type; @endphp

  @switch($viewType)
    @case('standard')
      @includeIf('user-front.common.post.standard-view')
      @break
    @case('grid')
      @includeIf('user-front.common.post.grid-view')
      @break
    @case('masonry')
      @includeIf('user-front.common.post.masonry-view')
      @break
    @default
      {{-- do nothing --}}
  @endswitch
  <!-- End Olima Posts Section -->
@endsection
