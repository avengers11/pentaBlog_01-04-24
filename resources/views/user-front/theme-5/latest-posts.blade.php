<div class="latest-blog pt-90 pb-20">
    <div class="section-title section-title_v1 title-inline mb-30">
        <h4 class="title mt-0">
            {{ $keywords['Latest_Posts'] ?? __(' Latest Posts') }}
            <span class="icons">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </h4>
        <span class="line"></span>
    </div>
    <div class="row">
        @if (count($latestPosts) > 0)
            @foreach ($latestPosts->take(4) as $latestPost)
                @auth('customer')
                    @php
                        $postBookmarked = 0;
                        foreach ($bookmarkPosts as $bookmarkPost) {
                            if ($bookmarkPost->post_id == $latestPost->post_id) {
                                $postBookmarked = 1;
                                break;
                            }
                        }
                    @endphp
                @endauth
                <div class="col-md-6">
                    <article class="blog blog_v2 text-center mb-30">
                        <div class="blog_img">
                            <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}"
                                target="_self"
                                title="{{ strlen($latestPost->title) > 30 ? mb_substr($latestPost->title, 0, 30, 'UTF-8') . '...' : $latestPost->title }}"
                                class="lazy-container ratio ratio-5-3 radius-md">
                                <img class="lazyload"
                                    data-src="{{ asset('assets/user/img/posts/' . $latestPost->thumbnail_image) }}"
                                    alt="Image">
                            </a>
                        </div>
                        <div class="blog_content p-25 radius-md">
                            @php
                                $latestPostCategory = App\Models\User\PostCategory::where('id', $latestPost->post_category_id)->first();
                            @endphp
                            <a href="{{ route('front.user.posts', ['category' => $latestPostCategory->id, getParam()]) }}"
                                target="_self" title="{{ $latestPostCategory->name }}"
                                class="blog_tag rounded-pill font-xsm mb-15">{{ $latestPostCategory->name }}</a>

                            <h5 class="blog_title lc-2 mb-10">
                                <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}"
                                    target="_self"
                                    title="{{ strlen($latestPost->title) > 30 ? mb_substr($latestPost->title, 0, 30, 'UTF-8') . '...' : $latestPost->title }}">
                                    {{ strlen($latestPost->title) > 55 ? mb_substr($latestPost->title, 0, 55, 'UTF-8') . '...' : $latestPost->title }}
                                </a>
                            </h5>

                            <ul class="blog_list justify-content-center list-unstyled">
                                <li class="icon-start font-sm">
                                    <a href="">
                                        <i
                                            class="fal fa-heart post-bookmarked"></i>{{ formatNumberWithK($latestPost->bookmarks) }}
                                    </a>
                                </li>
                                <li class="icon-start font-sm">
                                    <i class="fal fa-eye"></i>{{ formatNumberWithK($latestPost->views) }}
                                </li>
                                <li class="icon-start font-sm">
                                    @php
                                        $date = Carbon\Carbon::parse($latestPost->created_at);
                                    @endphp
                                    <a href="" target="_self" title="{{ date_format($date, 'd M') }}">
                                        <i class="fal fa-clock"></i>{{ date_format($date, 'd M') }}
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </article>
                </div>
            @endforeach
        @else
                <div class="bg-light py-3 text-center" role="alert">
                    {{ $keywords['No_Latest_Posts_Found'] ?? __('No Latest Post Found !') }}
                </div>
        @endif
    </div>
</div>
