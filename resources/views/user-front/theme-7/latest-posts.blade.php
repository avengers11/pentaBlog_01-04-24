<div class="latest-blog mt-50 pb-25">
    <div class="section-title title-inline mb-50">
        <h3 class="title">
            {{ $keywords['Latest_Posts'] ?? __(' Latest Posts') }}
        </h3>
        @if (count($latestPosts) > 0)
        <a href="{{ route('front.user.posts', getParam()) }}" class="btn btn-md btn-primary radius-sm"
            title="{{ $keywords['View_More'] ?? __('View More') }}"
            target="_self">{{ $keywords['View_More'] ?? __('View More') }}</a>
        @endif
    </div>
    <div class="row">
        @if (count($latestPosts) > 0)
            @foreach ($latestPosts->take(5) as $index => $latestPost)
                @if ($index < 2)
                    <div class="col-md-6">
                        <article class="blog blog_v7 radius-md mb-25">
                            <div class="blog_img">
                                <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}"
                                    target="_self"
                                    title="{{ strlen($latestPost->title) > 50 ? mb_substr($latestPost->title, 0, 50, 'UTF-8') . '...' : $latestPost->title }}"
                                    class="lazy-container ratio ratio-5-3">
                                    <img class="lazyload"
                                        data-src="{{ asset('assets/user/img/posts/' . $latestPost->thumbnail_image) }}"
                                        alt="{{ strlen($latestPost->title) > 50 ? mb_substr($latestPost->title, 0, 50, 'UTF-8') . '...' : $latestPost->title }}">
                                </a>
                            </div>
                            <div class="blog_content p-20">
                                <h5 class="blog_title lc-2 mb-10">
                                    <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}"
                                        target="_self"
                                        title=" {{ strlen($latestPost->title) > 50 ? mb_substr($latestPost->title, 0, 50, 'UTF-8') . '...' : $latestPost->title }}">
                                        {{ strlen($latestPost->title) > 50 ? mb_substr($latestPost->title, 0, 50, 'UTF-8') . '...' : $latestPost->title }}
                                    </a>
                                </h5>
                                <ul class="blog_list no-border list-unstyled">
                                    <li class="icon-start">
                                        <a target="_self"
                                            title="{{ $keywords['By'] ?? __('By') }} {{ $latestPost->author }}">
                                            <i class="fal fa-user-circle"></i> {{ $keywords['By'] ?? __('By') }}
                                            {{ $latestPost->author }}
                                        </a>
                                    </li>
                                    <li class="icon-start">
                                        @php
                                            $date = Carbon\Carbon::parse($latestPost->created_at);
                                        @endphp
                                        <i class="fal fa-calendar-alt"></i> {{ date_format($date, 'M d, Y') }}
                                    </li>
                                </ul>
                            </div>
                        </article>
                    </div>
                @else
                    <div class="col-xl-4 col-sm-6">
                        <article class="blog mb-25">
                            <div class="blog_img mb-15">
                                <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}"
                                    target="_self" title="Link" class="lazy-container ratio radius-md">
                                    <img class="lazyload"
                                        data-src="{{ asset('assets/user/img/posts/' . $latestPost->thumbnail_image) }}"
                                        alt="{{ strlen($latestPost->title) > 40 ? mb_substr($latestPost->title, 0, 40, 'UTF-8') . '...' : $latestPost->title }}">
                                </a>
                            </div>
                            <div class="blog_content">
                                <ul class="blog_list no-border list-unstyled mb-10">
                                    <li class="icon-start font-sm">
                                        <a target="_self"
                                            title="{{ $keywords['By'] ?? __('By') }} {{ $latestPost->author }}">
                                            <i class="fal fa-user-circle"></i> {{ $keywords['By'] ?? __('By') }}
                                            {{ $latestPost->author }}
                                        </a>
                                    </li>
                                    @php
                                        $date = Carbon\Carbon::parse($latestPost->created_at);
                                    @endphp
                                    <li class="icon-start font-sm">
                                        <i class="fal fa-calendar-alt"></i> {{ date_format($date, 'M d, Y') }}
                                    </li>
                                </ul>
                                <h6 class="blog_title lc-2 mb-0">
                                    <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}"
                                        target="_self"
                                        title="{{ strlen($latestPost->title) > 40 ? mb_substr($latestPost->title, 0, 40, 'UTF-8') . '...' : $latestPost->title }}">
                                        {{ strlen($latestPost->title) > 40 ? mb_substr($latestPost->title, 0, 40, 'UTF-8') . '...' : $latestPost->title }}
                                    </a>
                                </h6>
                            </div>
                        </article>
                    </div>
                @endif
            @endforeach
        @else
            <div class="info-text">
                <div class="wrapper p-30 bg-light">
                    <h5 class="text-center mb-0">
                        {{ $keywords['No_Latest_Posts_Found'] ?? __('No Latest Posts Found !') }}
                    </h5>
                </div>
            </div>
        @endif
    </div>
</div>
