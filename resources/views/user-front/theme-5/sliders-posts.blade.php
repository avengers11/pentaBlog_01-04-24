<div class="hero-blog_v1 bg-img pb-90" data-bg-image="{{ asset('assets/user/img/' . $userBs->hero_section_bg_image) }}">
    <div class="overlay opacity-65"></div>
    @if (count($sliderPosts) > 0)
        <div class="container">
            <div class="swiper hero-blog-slider">
                <div class="swiper-wrapper">
                    @foreach ($sliderPosts as $sliderPost)
                        <div class="swiper-slide">
                            <article class="blog blog_v1 radius-md">
                                <div class="blog_img">
                                    <div class="lazy-container ratio ratio-1-3">
                                        <img class="lazyload"
                                            data-src="{{ $sliderPost->slider_post_image != null ? Storage::url($sliderPost->slider_post_image) : asset('assets/admin/img/noimage.jpg') }}"
                                            alt="{{ $sliderPost->title }}">
                                    </div>
                                </div>
                                <div class="blog_content p-20">
                                    <div class="content-inner">
                                        @php
                                            $sldPostCategory = App\Models\User\PostCategory::where('id', $sliderPost->post_category_id)
                                                ->where('user_id', $user->id)
                                                ->first();
                                        @endphp

                                        <a href="{{ route('front.user.posts', ['category' => $sldPostCategory->id, getParam()]) }}"
                                            target="_self" title="{{ $sldPostCategory->name }}"
                                            class="blog_tag rounded-pill font-xsm mb-15">
                                            {{ $sldPostCategory->name }}
                                        </a>

                                        <h5 class="blog_title lc-2 mb-10">
                                            <a href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}"
                                                target="_self"
                                                title="{{ strlen($sliderPost->title) > 40 ? mb_substr($sliderPost->title, 0, 40, 'UTF-8') . '...' : $sliderPost->title }}">
                                                {{ strlen($sliderPost->title) > 40 ? mb_substr($sliderPost->title, 0, 40, 'UTF-8') . '...' : $sliderPost->title }}
                                            </a>
                                        </h5>
                                        <ul class="blog_list list-unstyled">
                                            <li class="icon-start font-sm">
                                                <i class="fal fa-heart"></i>
                                                {{ formatNumberWithK($sliderPost->bookmarks) }}
                                            </li>
                                            <li class="icon-start font-sm">
                                                <i class="fal fa-eye"></i>{{ formatNumberWithK($sliderPost->views) }}
                                            </li>
                                            <li class="icon-start font-sm">
                                                @php
                                                    // first, convert the string into date object
                                                    $date = Carbon\Carbon::parse($sliderPost->created_at);
                                                @endphp
                                                <i class="fal fa-clock"></i>{{ date_format($date, 'd M') }}

                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if (count($sliderPosts) > 1)
            <div class="slider-navigation position-middle">
                <button type="button" title="Slide prev" class="slider-btn slider-btn-prev btn-icon-text"
                    id="hero-blog-slider-prev">
                    <i class="fal fa-long-arrow-left"></i>
                    {{ $keywords['Previous'] ?? __('Previous') }}
                </button>
                <button type="button" title="Slide next" class="slider-btn slider-btn-next btn-icon-text"
                    id="hero-blog-slider-next">
                    {{ $keywords['Next'] ?? __('Next') }}
                    <i class="fal fa-long-arrow-right"></i>
                </button>
            </div>
        @endif
    @else
        <div class="row text-center">
            <div class="py-5 pt-80">
                <h5 class="text-center pt-5 text-warning">
                    {{ $keywords['No_Slider_Posts_Found'] ?? __('No Slider Posts Found !') }}
                </h5>
            </div>
        </div>
    @endif
</div>
