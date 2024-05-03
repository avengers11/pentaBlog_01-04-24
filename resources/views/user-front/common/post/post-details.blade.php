@extends('user-front.common.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->post_details_title }}
  @else
    {{ __('Post Details') }}
  @endif
@endsection


@section('meta-description', $details->meta_description)
@section('meta-keywords', $details->meta_keywords)

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
            <h1>{{ $details->title }}</h1>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="breadcrumb-link">
            <ul>
              <li class="text-uppercase"><a href="{{route('front.user.detail.view', getParam())}}">{{$keywords['Home'] ?? __('Home') }}</a></li>
              <li class="active text-uppercase">{{ !empty($pageHeading) ? $pageHeading->post_details_title : null}}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Olima Breadcrumb Section -->

  <!-- Start Olima Post Details Section -->
  <section class="olima_blog_details pt-140 pb-140">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="blog_details_wrapper">
            <div class="post_content pb-70">
              @php $sldImgs = json_decode($details->post->slider_images); @endphp

              <div class="post-gallery-box pb-45">
                <div class="post-gallery-slider row">
                  @foreach ($sldImgs as $img)
                    <div class="col-lg-6">
                      <div class="olima_img">
                        <a href="{{ asset('assets/user/img/posts/slider-images/' . $img) }}" class="gallery-single">
                          <img data-src="{{ $img != null ? Storage::url($img) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="slider image">
                        </a>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <h3>{{ $details->title }}</h3>

              <div class="post_meta">
                <span class="calender">{{ date_format($details->created_at, 'F d, Y') }}</span>
                <span class="writer">{{ $details->author }}</span>

                @php
                    $bookmarkCount = \App\Models\User\BookmarkPost::where('post_id', $details->post_id)->count();
                @endphp
                <a href="{{ route('front.user.make_bookmark', ['id' => $details->post_id, getParam()]) }}" class="btn_heart post-info-{{ $details->post_id }} {{ Auth::guard('customer')->check() == true && $postBookmarked == 1 ? 'post-bookmarked' : '' }}"><i class="fas fa-heart"></i><span id="bookmark-info-{{ $details->post_id }}" class="@if ($currentLanguageInfo->rtl == 1) mx-2  @endif">{{ $bookmarkCount }}</span></a>
              </div>

              <div class="summernote-content">{!! !empty($details->content) ? replaceBaseUrl($details->content, 'summernote') : null !!}</div>
            </div>

            <div class="post_share_tag mb-40">
              <div class="row">

                <div class="col-lg-12">
                  <div class="social_box float-left">
                    <ul>
                      <li><span>{{ $keywords["Share"] ?? "Share" }}:</span></li>
                      <li><a href="//www.facebook.com/sharer/sharer.php?u={{urlencode(url()->current()) }}"><i class="fab fa-facebook-f"></i></a></li>
                      <li><a href="//twitter.com/intent/tweet?text=my share text&amp;url={{urlencode(url()->current()) }}"><i class="fab fa-twitter"></i></a></li>
                      <li><a href="//plus.google.com/share?url={{urlencode(url()->current()) }}"><i class="fab fa-google-plus-g"></i></a></li>
                      <li><a href="//www.linkedin.com/shareArticle?mini=true&amp;url={{urlencode(url()->current()) }}&amp;title={{$details->title}}"><i class="fab fa-linkedin-in"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
              @if (!empty(showAd(3)))
                <div class="text-center mb-2">
                  {!! showAd(3) !!}
                </div>
              @endif
            @endif

            <div class="related_post pb-60 pt-40">
              <h3 class="mb-35">{{ $keywords["Related_Posts"] ?? "Related Posts" }}</h3>

              @if (count($relatedPosts) == 0)
                <div class="row">
                  <div class="col py-5 bg-light text-center">
                    <h4>{{ $keywords["No_Related_Post_Found"] ?? "Related Posts" }}</h4>
                  </div>
                </div>
              @else
                <div class="related_post_slide">
                  @foreach ($relatedPosts as $post)
                    <div class="grid_item">
                      <div class="post_img">
                        @php $sldImgs = json_decode($post->slider_images); @endphp

                        <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}">
                          <img data-src="{{ $post->thumbnail_image != null ? Storage::url($post->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="img-fluid lazy" alt="post image">
                        </a>
                      </div>

                      <div class="post_content">
                        @php
                          // first, convert the string into date object
                          $date = Carbon\Carbon::parse($post->created_at);
                        @endphp

                        <div class="post_meta">
                          <span class="calender">{{ date_format($date, 'M d, Y') }}</span>
                          <span class="writer">{{ $post->author }}</span>
                        </div>
                        <h3>
                          <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}">{{ strlen($post->title) > 30 ? mb_substr($post->title, 0, 30, 'UTF-8') . '...' : $post->title }}</a>
                        </h3>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif

              @if (is_array($packagePermissions) && in_array('Advertisement',$packagePermissions))
                @if (!empty(showAd(3)))
                  <div class="text-center mt-5">
                    {!! showAd(3) !!}
                  </div>
                @endif
              @endif
            </div>

            @if ($disqusInfo->disqus_status == 1)
              <div id="disqus_thread" class="pt-50"></div>
            @endif
          </div>
        </div>

        @includeIf('user-front.common.post.sidebar')
      </div>
    </div>
  </section>
  <!-- End Olima Post Details Section -->

@endsection

@section('disqus-script')
  @if ($disqusInfo->disqus_status == 1)
    <script>
      (function() { // DON'T EDIT BELOW THIS LINE
        let d = document, s = d.createElement('script');
        s.src = '//{{ $disqusInfo->disqus_short_name }}.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
      })();
    </script>
  @endif
@endsection
