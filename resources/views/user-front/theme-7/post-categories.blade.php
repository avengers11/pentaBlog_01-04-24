<div class="video-blog">
    <div class="container">
        <div class="section-title title-inline mb-30">
            <h3 class="title">
                {{ $keywords['Categories'] ?? 'Categories' }}
            </h3>
        </div>
        @if (count($postCategories) > 0)
            <div class="swiper blog-slider-1">
                <div class="swiper-wrapper">
                    @foreach ($postCategories as $postCategory)
                        <div class="swiper-slide">
                            <article class="blog blog_v6">
                                <div class="blog_img mb-15 radius-md">
                                    <div class="lazy-container ratio ratio-2-3">
                                        <img class="lazyload"
                                            data-src="{{ asset('assets/user/img/post-categories/' . $postCategory->image) }}"
                                            alt="{{ $postCategory->name }}">
                                    </div>
                                </div>
                                <div class="blog_content">
                                    <h6 class="blog_title lc-2 mb-0">
                                        <a href="{{ route('front.user.posts', ['category' => $postCategory->id, getParam()]) }}"
                                            target="_self" title="{{ $postCategory->name }}">
                                            {{ $postCategory->name }}
                                        </a>
                                    </h6>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination blog-slider-1-pagination position-static mt-20"></div>
            </div>
        @else
            <div class="info-text">
                <div class="wrapper p-30 bg-light">
                    <h5 class="text-center mb-0">
                        {{ $keywords['No_Categories_Found'] ?? __('No Categories  Found !') }}
                    </h5>
                </div>
            </div>
        @endif
    </div>
</div>
