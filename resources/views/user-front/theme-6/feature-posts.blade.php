<div class="single-blog mb-50">
    <div class="section-title section-title_v2 title-inline mb-30">
        <h4 class="title mt-0">
            {{ $keywords['Featured_Posts'] ?? __('Featured Posts') }}
            <span class="icons">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </h4>
    </div>
        @if (count($featuredPosts) > 0)
        <div class="swiper blog-slider-3">
            <div class="swiper-wrapper">
                @foreach ($featuredPosts as $post)
                    <div class="swiper-slide">
                        <article class="blog blog-lg_v2">
                            <div class="blog_img">
                                <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}" target="_self" title="{{ $post->title }}" class="lazy-container ratio ratio-2-3">
                                    <img class="lazyload"
                                        data-src="{{ asset('assets/user/img/posts/' . $post->featured_post_image) }}"
                                        alt="{{ $post->title }}">
                                </a>
                            </div>
                            <div class="blog_content p-30 bg-white text-center mx-auto">
                                @php
                                $ItemPostCategory = App\Models\User\PostCategory::where('id', $post->post_category_id)->first();
                                @endphp
                                <a href="{{ route('front.user.posts', ['category' => $ItemPostCategory->id, getParam()]) }}" target="_self" title="{{ $ItemPostCategory->name }}"
                                    class="blog_tag rounded-pill position-static font-xsm ms-0 mb-15">
                                    {{ $ItemPostCategory->name }}
                                </a>

                                <h4 class="blog_title lc-2 mb-15">
                                    <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}" target="_self" title="{{ strlen($post->title) > 70 ? mb_substr($post->title, 0, 70, 'UTF-8') . '...' : $post->title }}">
                                        {{ strlen($post->title) > 70 ? mb_substr($post->title, 0, 70, 'UTF-8') . '...' : $post->title }}
                                    </a>
                                </h4>
                                <ul class="blog_list list-unstyled justify-content-center">
                                    <li class="icon-start">
                                        <i class="fal fa-heart"></i> {{ formatNumberWithK($post->bookmarks) }}
                                    </li>
                                    <li class="icon-start">
                                        <i class="fal fa-eye"></i> {{ formatNumberWithK($post->views) }}
                                    </li>
                                    <li class="icon-start">
                                        @php
                                        // first, convert the string into date object
                                        $date = Carbon\Carbon::parse($post->created_at);
                                       @endphp
                                        <a target="_self" title="{{ date_format($date, 'd M') }}">
                                            <i class="fal fa-clock"></i> {{ date_format($date, 'd M') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            <div class="slider-navigation position-middle">
                <button type="button" title="Slide prev" class="slider-btn slider-btn-prev rounded-circle ms-3"
                    id="blog-slider-3-prev">
                    <i class="fal fa-angle-left"></i>
                </button>
                <button type="button" title="Slide next" class="slider-btn slider-btn-next rounded-circle me-3"
                    id="blog-slider-3-next">
                    <i class="fal fa-angle-right"></i>
                </button>
            </div>
        </div>
        @else
        <div class="row text-center">
            <div class="alert alert-secondary" role="alert">
                {{ $keywords['No_Featured_Posts_Found'] ?? __('No Featured Post Found !') }}
              </div>
        </div>
        @endif
</div>

