<div class="trending-blog pb-25">
    <div class="section-title title-inline mb-30">
        <h3 class="title mt-0">
            {{ $keywords['Featured_Posts'] ?? 'Featured Posts' }}
        </h3>
    </div>
    <div class="row">
        @if (count($featuredPosts) > 0)
            @php
                $items = $featuredPosts; // Replace with your actual model query
                $chunkSize = 5;
            @endphp
            @foreach ($items->chunk($chunkSize) as $chunk)
                @php
                    $firstItem = $chunk->shift();
                @endphp
                @if ($firstItem)
                    <div class="col-xl-7">
                        <article class="blog blog_v7 radius-lg mb-25">
                            <div class="blog_img">
                                <a href="{{ route('front.user.post_details', ['slug' => $firstItem->slug, getParam()]) }}"
                                    target="_self"
                                    title="   {{ strlen($firstItem->title) > 40 ? mb_substr($firstItem->title, 0, 40, 'UTF-8') . '...' : $firstItem->title }}"
                                    class="lazy-container ratio ratio-1-1">
                                    <img class="lazyload"
                                        data-src="{{ asset('assets/user/img/posts/' . $firstItem->featured_post_image) }}"
                                        alt="{{ $firstItem->title }}">
                                </a>
                            </div>
                            <div class="blog_content p-25">
                                <h4 class="blog_title lc-2 mb-15">
                                    <a href="{{ route('front.user.post_details', ['slug' => $firstItem->slug, getParam()]) }}"
                                        target="_self"
                                        title="{{ strlen($firstItem->title) > 40 ? mb_substr($firstItem->title, 0, 40, 'UTF-8') . '...' : $firstItem->title }}">
                                        {{ strlen($firstItem->title) > 55 ? mb_substr($firstItem->title, 0, 55, 'UTF-8') . '...' : $firstItem->title }}
                                    </a>
                                </h4>
                                <ul class="blog_list no-border list-unstyled">
                                    <li class="icon-start">
                                        <a target="_self"
                                            title=" {{ $keywords['By'] ?? 'By' }} {{ $firstItem->author }}">
                                            <i class="fal fa-user-circle"></i> {{ $keywords['By'] ?? 'By' }}
                                            {{ $firstItem->author }}
                                        </a>
                                    </li>
                                    @php
                                        $date = Carbon\Carbon::parse($firstItem->created_at);
                                    @endphp
                                    <li class="icon-start">
                                        <i class="fal fa-calendar-alt"></i> {{ date_format($date, 'M d, Y') }}
                                    </li>
                                </ul>
                            </div>
                        </article>
                    </div>
                @endif
                <div class="col-xl-5">
                    <div class="row">
                        @foreach ($chunk as $item)
                            <div class="col-sm-6 col-xl-12">
                                <article class="blog blog-inline blog-inline_v4 mb-25">
                                    <div class="blog_img">
                                        <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                            target="_self"
                                            title=" {{ strlen($item->title) > 30 ? mb_substr($item->title, 0, 30, 'UTF-8') . '...' : $item->title }}"
                                            class="lazy-container ratio ratio-1-1 radius-md">
                                            <img class="lazyload"
                                                data-src="{{ asset('assets/user/img/posts/' . $item->featured_post_image) }}"
                                                alt=" {{ strlen($item->title) > 30 ? mb_substr($item->title, 0, 30, 'UTF-8') . '...' : $item->title }}">
                                        </a>
                                    </div>
                                    <div class="blog_content">
                                        <h6 class="blog_title lc-2 mb-10">
                                            <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                                target="_self"
                                                title="{{ strlen($item->title) > 35 ? mb_substr($item->title, 0, 35, 'UTF-8') . '...' : $item->title }}">
                                                {{ strlen($item->title) > 37 ? mb_substr($item->title, 0, 37, 'UTF-8') . '...' : $item->title }}
                                            </a>
                                        </h6>
                                        <ul class="blog_list no-border list-unstyled">
                                            <li class="icon-start font-xsm">
                                                <a target="_self"
                                                    title="{{ $keywords['By'] ?? 'By' }} {{ $item->author }}">
                                                    <i class="fal fa-user-circle"></i> {{ $keywords['By'] ?? 'By' }}
                                                    {{ $item->author }}
                                                </a>
                                            </li>
                                            @php
                                                // first, convert the string into date object
                                                $date = Carbon\Carbon::parse($firstItem->created_at);
                                            @endphp
                                            <li class="icon-start font-xsm">
                                                <i class="fal fa-calendar-alt"></i>{{ date_format($date, 'M d, Y') }}
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="info-text">
                <div class="wrapper p-30 bg-light">
                    <h5 class="text-center mb-0">
                        {{ $keywords['No_Featured_Posts_Found'] ?? __('No Featured Posts Found !') }}
                    </h5>
                </div>
            </div>
        @endif
    </div>
</div>
