@extends('user-front.common.layout')

@section('pageHeading')
    {{ $keywords['Home'] ?? __('Home') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_home : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_home : '')
@section('home-css')
    @includeIf('user-front.theme-7.include.css')
@endsection
@section('content')
    <!-- Hero-posts start -->
    @if ($hs->hero_section_posts === 1)
        @includeIf('user-front.theme-7.hero-posts')
    @else
        <div class="hero-blog_v4 pt-40 pb-20 header-next">
        </div>
    @endif
    <!-- Hero-posts end -->

    <!-- post categories start -->
    @if ($hs->post_categories === 1)
        @includeIf('user-front.theme-7.post-categories')
    @endif
    <!-- post categories end -->

    <!-- Page contents start -->
    <div class="page-contents pb-40">
        <div class="container">
            <div class="row gx-xl-5">
                <div class="col-lg-8">
                    <!-- Latest posts start -->
                    @if ($hs->latest_posts == 1)
                        @includeIf('user-front.theme-7.latest-posts')
                    @endif
                    <!-- Latest posts end -->

                    <!-- Add advertisement 1  start -->
                    @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                        @includeIf('user-front.theme-7.advertisement-1')
                    @endif
                    <!-- Add advertisement 1  end -->

                    <!-- Featured categories posts start -->
                    @if ($hs->featured_category_posts == 1)
                        @includeIf('user-front.theme-7.featured-categories')
                    @endif
                    <!-- Featured  categories posts end -->

                    <!-- Featured categories posts start -->
                    @if ($hs->featured_posts == 1)
                        @includeIf('user-front.theme-7.featured-posts')
                    @endif
                    <!-- Featured  categories posts end -->
                </div>
                <div class="col-lg-4">
                    <aside class="widget-area widget-area_v4 pb-10">
                        <!-- Spacer -->
                        <div class="mt-50 d-none d-lg-block"></div>

                        <!-- Widget banner  advertisement 2 -->
                        @if ($hs->sidebar_ads == 1)
                            @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                                @include('user-front.theme-7.advertisement-2')
                            @endif
                        @endif

                        <!-- Widget popular  posts -->
                        @if ($hs->popular_posts == 1)
                            @includeIf('user-front.theme-7.popular-posts')
                        @endif

                        <!-- Widget gallery -->
                        @if ($hs->gallery == 1)
                            @includeIf('user-front.theme-7.galleries')
                        @endif

                        <!-- Widget advertisement 3 -->
                        @if ($hs->sidebar_ads == 1)
                            @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                                @includeIf('user-front.theme-7.advertisement-3')
                            @endif
                        @endif

                        <!-- Widget newsletter -->
                        @if ($hs->newsletter == 1)
                            @includeIf('user-front.theme-7.newsletter')
                        @endif

                        <!-- Widget banner advertisement 4 -->
                        @if ($hs->sidebar_ads == 1)
                            @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                                @includeIf('user-front.theme-7.advertisement-4')
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
    @includeIf('user-front.theme-7.include.js')
@endsection
