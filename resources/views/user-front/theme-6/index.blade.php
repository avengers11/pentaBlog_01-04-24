@extends('user-front.common.layout')

@section('pageHeading')
    {{ $keywords['Home'] ?? __('Home') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_home : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_home : '')
@section('home-css')
    @includeIf('user-front.theme-6.include.css')
@endsection
@section('content')
    <!-- Page contents start -->
    <div class="page-contents pb-40 header-next">
        <div class="container">
            <div class="row gx-xl-5">
                <div class="col-lg-8">
                    <!-- featured-posts start -->
                    @if ($hs->slider_posts)
                        @includeif('user-front.theme-6.sliders-posts')
                    @endif
                    <!-- featured posts end -->

                    <!-- Category-area start -->
                    @if ($hs->post_categories == 1)
                        @includeif('user-front.theme-6.category-area')
                    @endif
                    <!-- Category-area end -->

                    <!-- Latest posts start -->
                    @if ($hs->latest_posts == 1)
                        @includeif('user-front.theme-6.latest-posts')
                    @endif
                    <!-- Latest posts end -->

                    <!-- Featured category posts start -->

                    @if ($hs->featured_category_posts == 1)
                        @includeif('user-front.theme-6.featured-categories')
                    @endif
                    <!-- Featured category posts end -->

                    <!-- Single blog start -->
                    @if ($hs->featured_posts === 1)
                        @includeif('user-front.theme-6.feature-posts')
                    @endif
                    <!-- Single blog end -->
                </div>
                <div class="col-lg-4">
                    <aside class="widget-area widget-area_v2 pb-10">
                        <!-- Spacer -->
                        <div class="mt-50 d-none d-lg-block"></div>
                        <!-- Widget author -->

                        @if ($hs->author_info === 1)
                            @includeif('user-front.theme-6.author-area')
                        @endif

                        @if ($hs->popular_posts == 1)
                            @includeif('user-front.theme-6.populer-posts')
                        @endif
                        <!-- Widget banner -->
                        @if ($hs->sidebar_ads == 1)
                            @includeif('user-front.theme-6.advertisement')
                        @endif
                        <!-- Widget gallery -->
                        @if ($hs->gallery == 1)
                            @includeif('user-front.theme-6.galleries')
                        @endif
                        <!-- Widget newsletter -->
                        @if ($hs->newsletter == 1)
                            @includeif('user-front.theme-6.newsletter')
                        @endif
                        
                        @if ($hs->sidebar_ads == 1)
                            @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                                @if (!empty(showAd(2)))
                                    <div class="widget widget-add-banner mb-40">
                                        {!! showAd(2) !!}
                                    </div>
                                @endif
                            @endif
                        @endif

                    </aside>
                </div>
            </div>
        </div>
    </div>
    <!-- Page contents end -->
@endsection
@section('home-js')
    @includeIf('user-front.theme-6.include.js')
@endsection
