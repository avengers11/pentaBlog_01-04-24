<div class="hero-blog_v2 mt-50 mb-50">
    @if (count($sliderPosts) > 0)
        <div class="swiper hero-blog-slider_v2">
            <div class="swiper-wrapper">
                @foreach ($sliderPosts as $sliderPost)
                    <div class="swiper-slide">
                        <article class="blog">
                            <div class="blog_content">
                                <div class="content-inner">
                                    @php
                                        $sldPostCategory = App\Models\User\PostCategory::where('id', $sliderPost->post_category_id)
                                            ->where('user_id', $user->id)
                                            ->first();
                                    @endphp
                                    <div class="blog_lists">
                                        <ul class="blog_list list-unstyled">
                                            @php
                                                $date = Carbon\Carbon::parse($sliderPost->created_at);
                                            @endphp
                                            <li class="icon-start">
                                                <a target="_self" title="{{ date_format($date, 'd F') }}">
                                                    <i class="fal fa-clock"></i>{{ date_format($date, 'd F') }}
                                                </a>
                                            </li>
                                            <li class="icon-start">
                                                <a href="{{ route('front.user.posts', ['category' => $sldPostCategory->id, getParam()]) }}"
                                                    target="_self" title="{{ $sldPostCategory->name }}">
                                                    <i class="fal fa-tag"></i>{{ $sldPostCategory->name }}
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="blog_list list-unstyled">
                                            <li class="icon-start">
                                                <i class="fal fa-heart"></i>
                                                {{ formatNumberWithK($sliderPost->bookmarks) }}
                                            </li>
                                            <li class="icon-start">
                                                <i class="fal fa-eye"></i> {{ formatNumberWithK($sliderPost->views) }}
                                            </li>
                                        </ul>
                                    </div>

                                    <h3 class="blog_title lc-2 mb-20 mt-10">
                                        <a href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}"
                                            target="_self"
                                            title="{{ strlen($sliderPost->title) > 70 ? mb_substr($sliderPost->title, 0, 70, 'UTF-8') . '...' : $sliderPost->title }}">
                                            {{ strlen($sliderPost->title) > 70 ? mb_substr($sliderPost->title, 0, 70, 'UTF-8') . '...' : $sliderPost->title }}
                                        </a>
                                    </h3>

                                </div>
                            </div>
                            <div class="blog_img">
                                <a href="{{ route('front.user.post_details', ['slug' => $sliderPost->slug, getParam()]) }}"
                                    target="_self"
                                    title="{{ strlen($sliderPost->title) > 70 ? mb_substr($sliderPost->title, 0, 70, 'UTF-8') . '...' : $sliderPost->title }}"
                                    class="lazy-container ratio">
                                    <img class="lazyload"
                                        data-src="{{ $sliderPost->slider_post_image != null ? Storage::url($sliderPost->slider_post_image) : asset('assets/admin/img/noimage.jpg') }}"
                                        alt="{{ $sliderPost->title }}">
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="row text-center">
            <div class="alert alert-secondary" role="alert">
                {{ $keywords['No_Slider_Posts_Found'] ?? __('No Slider Posts Found !') }}
            </div>
        </div>
    @endif
    <div class="hero-blog-slider_v2_pagination"></div>
</div>
