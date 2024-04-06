<div class="page-contents pb-40">
    <div class="container">
        <div class="row gx-xl-5">
            <div class="col-lg-8">
                <!-- Latest blog start -->
                @if ($hs->latest_posts == 1)
                    @includeIf('user-front.theme-5.latest-posts')
                @endif
                <!-- Latest blog end -->

                <!-- Add banner start -->
                @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                    @if (!empty(showAd(3)))
                        <div class="banner-img mb-50">
                            {!! showAd(3) !!}
                        </div>
                    @endif
                @endif
                <!-- Add banner end -->


                <!-- Featured Category start -->
                @if ($hs->featured_category_posts == 1)
                    @include('user-front.theme-5.featured-categories')
                @endif
                <!-- Featured category end -->

                <!-- Inline blog start -->
                @if ($hs->featured_posts == 1)
                    @include('user-front.theme-5.featured-posts')
                @endif
            </div>
            <div class="col-lg-4">
                <aside class="widget-area widget-area_v1 pb-10">
                    <!-- Spacer -->
                    <div class="pt-90 d-none d-lg-block"></div>

                    <!-- Widget author -->
                    @if ($hs->author_info == 1)
                        @includeIf('user-front.theme-5.author-area')
                    @endif

                    <!-- Widget categories -->
                    @if ($hs->post_categories == 1)
                        @includeIf('user-front.theme-5.categories')
                    @endif

                    <!-- Popular posts -->
                    @if ($hs->popular_posts == 1)
                        @includeIf('user-front.theme-5.popular-posts')
                    @endif


                    <!-- Widget banner -->
                    @if ($hs->sidebar_ads == 1)
                    @if (is_array($packagePermissions) && in_array('Advertisement', $packagePermissions))
                        @includeIf('user-front.theme-5.advertisement')
                    @endif
                    @endif


                    <!-- Widget gallery -->
                    @if ($hs->gallery)
                        @if (is_array($packagePermissions) && in_array('Gallery', $packagePermissions))
                            @includeIf('user-front.theme-5.gallery')
                        @endif
                    @endif

                </aside>
            </div>
        </div>
    </div>
</div>
