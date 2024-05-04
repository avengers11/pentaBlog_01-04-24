<div class="hero-blog_v4 pt-50 pb-20 header-next">
    <div class="container">
        @php
            $slides = $heroPostsType_1->splitIn(2);
            $leftSlides = $slides[0] ?? [];
            $rightSlides = $slides[1] ?? [];
        @endphp
        @if (count($leftSlides) == 0 && count($heroPostsType_2) == 0 && count($rightSlides) == 0)
        <div class="info-text mb-30">
            <div class="wrapper p-30 bg-light">
                <h5 class="text-center mb-0">
                    {{ $keywords['No_Hero_Posts_Found'] ?? __('No Hero Posts Found!') }}
                </h5>
            </div>
        </div>
        @else
            <div class="row">
                <div class="col-lg-4">
                    <div class="row">
                        @foreach ($leftSlides as $item)
                            <div class="col-lg-12 col-md-6">
                                <article class="blog blog_v5 radius-md mb-30">
                                    <div class="blog_img mb-25">
                                        <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                            target="_self"
                                            title="{{ strlen($item->title) > 50 ? mb_substr($item->title, 0, 50, 'UTF-8') . '...' : $item->title }}"
                                            class="lazy-container ratio">
                                            <img class="lazyload"
                                                data-src="{{ $item->hero_post_image != null ? Storage::url($item->hero_post_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                alt="{{ strlen($item->title) > 50 ? mb_substr($item->title, 0, 50, 'UTF-8') . '...' : $item->title }}">
                                        </a>
                                    </div>
                                    <div class="blog_content p-20">
                                        <h5 class="blog_title lc-2 mb-15">
                                            <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                                target="_self"
                                                title="{{ strlen($item->title) > 50 ? mb_substr($item->title, 0, 50, 'UTF-8') . '...' : $item->title }}">
                                                {{ strlen($item->title) > 50 ? mb_substr($item->title, 0, 50, 'UTF-8') . '...' : $item->title }}
                                            </a>
                                        </h5>
                                        <ul class="blog_list no-border list-unstyled">
                                            <li class="icon-start">
                                                <a target="_self"
                                                    title="{{ $keywords['By'] ?? __('By') }} {{ $item->author }}">
                                                    <i class="fal fa-user-circle"></i>
                                                    {{ $keywords['By'] ?? __('By') }}
                                                    {{ $item->author }}
                                                </a>
                                            </li>
                                            @php
                                                $date = Carbon\Carbon::parse($item->created_at);
                                            @endphp
                                            <li class="icon-start" title="{{ date_format($date, 'M d, Y') }}">
                                                <i class="fal fa-calendar-alt"></i>{{ date_format($date, 'M d, Y') }}
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    @foreach ($heroPostsType_2 as $item)
                        <article class="blog blog_v5 radius-md mb-25">
                            <div class="blog_img mb-25">
                                <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                    target="_self"
                                    title=" {{ strlen($item->title) > 55 ? mb_substr($item->title, 0, 55, 'UTF-8') . '...' : $item->title }}"
                                    class="lazy-container ratio ratio-1-3">
                                    <img class="lazyload"
                                        data-src="{{ $item->hero_post_image != null ? Storage::url($item->hero_post_image) : asset('assets/admin/img/noimage.jpg') }}"
                                        alt="Blog Image">
                                </a>
                            </div>
                            <div class="blog_content p-20">
                                <h5 class="blog_title lc-2 mb-15">
                                    <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                        target="_self"
                                        title=" {{ strlen($item->title) > 55 ? mb_substr($item->title, 0, 55, 'UTF-8') . '...' : $item->title }}">
                                        {{ strlen($item->title) > 55 ? mb_substr($item->title, 0, 55, 'UTF-8') . '...' : $item->title }}
                                    </a>
                                </h5>
                                <ul class="blog_list no-border list-unstyled">
                                    <li class="icon-start">
                                        <a href="author-details" target="_self" title="{{ $item->author }}">
                                            <i class="fal fa-user-circle"></i>{{ $keywords['By'] ?? __('By') }}
                                            {{ $item->author }}
                                        </a>
                                    </li>
                                    @php
                                        $date = Carbon\Carbon::parse($item->created_at);
                                    @endphp
                                    <li class="icon-start" title="{{ date_format($date, 'M d, Y') }}">
                                        <i class="fal fa-calendar-alt"></i>{{ date_format($date, 'M d, Y') }}
                                    </li>
                                </ul>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        @foreach ($rightSlides as $item)
                            <div class="col-lg-12 col-md-6">
                                <article class="blog blog_v5 radius-md mb-30">
                                    <div class="blog_img mb-25">
                                        <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                            target="_self"
                                            title="{{ strlen($item->title) > 50 ? mb_substr($item->title, 0, 50, 'UTF-8') . '...' : $item->title }}"
                                            class="lazy-container ratio">
                                            <img class="lazyload"
                                                data-src="{{ $item->hero_post_image != null ? Storage::url($item->hero_post_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                alt="{{ strlen($item->title) > 50 ? mb_substr($item->title, 0, 50, 'UTF-8') . '...' : $item->title }}">
                                        </a>
                                    </div>
                                    <div class="blog_content p-20">
                                        <h5 class="blog_title lc-2 mb-15">
                                            <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                                target="_self"
                                                title="{{ strlen($item->title) > 50 ? mb_substr($item->title, 0, 50, 'UTF-8') . '...' : $item->title }}">
                                                {{ strlen($item->title) > 50 ? mb_substr($item->title, 0, 50, 'UTF-8') . '...' : $item->title }}
                                            </a>
                                        </h5>
                                        <ul class="blog_list no-border list-unstyled">
                                            <li class="icon-start">
                                                <a target="_self"
                                                    title="{{ $keywords['By'] ?? __('By') }} {{ $item->author }}">
                                                    <i class="fal fa-user-circle"></i>
                                                    {{ $keywords['By'] ?? __('By') }}
                                                    {{ $item->author }}
                                                </a>
                                            </li>
                                            @php
                                                $date = Carbon\Carbon::parse($item->created_at);
                                            @endphp
                                            <li class="icon-start" title="{{ date_format($date, 'M d, Y') }}">
                                                <i class="fal fa-calendar-alt"></i>{{ date_format($date, 'M d, Y') }}
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
