<div class="featured-blog pb-20">
    <div class="section-title section-title_v2 title-inline mb-30">
        <h4 class="title">
            {{ $keywords['Featured_Categories'] ?? __('Featured Categories') }}
            <span class="icons">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </h4>
        <div class="tabs-navigation tabs-navigation_v2">
            <ul class="nav nav-tabs">
                @foreach ($featPostCategories as $featPostCategory)
                    <li class="nav-item">
                        <button class="nav-link btn-sm rounded-pill {{ $loop->index == 0 ? 'active' : '' }}"
                            data-bs-toggle="tab" data-bs-target="#tab_{{ $featPostCategory->id }}" type="button">
                            {{ $featPostCategory->name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="tab-content">
        @php $langId = $currentLanguageInfo->id; @endphp
        @if (count($featPostCategories) > 0)
            @foreach ($featPostCategories as $featPostCategory)
                <div class="tab-pane slide show {{ $loop->index == 0 ? 'active' : '' }}"
                    id="tab_{{ $featPostCategory->id }}">
                    @php
                        $featCatPosts = DB::table('posts')
                            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
                            ->where('post_contents.language_id', '=', $langId)
                            ->where('post_contents.post_category_id', '=', $featPostCategory->id)
                            ->where('posts.user_id', $user->id)
                            ->orderBy('posts.serial_number', 'ASC')
                            ->limit(3)
                            ->get();
                    @endphp
                    @foreach ($featCatPosts as $featCatPost)
                        <article class="blog blog-inline blog-inline_v3 border mb-30">
                            <div class="blog_img">
                                <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}"
                                    target="_self"
                                    title="{{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}"
                                    class="lazy-container ratio ratio-5-3">
                                    <img class="lazyload"
                                        data-src="{{ asset('assets/user/img/posts/' . $featCatPost->thumbnail_image) }}"
                                        alt="{{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}">
                                </a>
                            </div>
                            <div class="blog_content">
                                <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}"
                                    target="_self" title="{{ $featPostCategory->name }}"
                                    class="blog_tag font-sm mb-15">
                                    {{ $featPostCategory->name }}
                                </a>

                                <h5 class="blog_title lc-2 mb-15">
                                    <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}"
                                        target="_self"
                                        title="{{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}">
                                        {{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}
                                    </a>
                                </h5>

                                <ul class="blog_list list-unstyled mb-15">
                                    <li class="icon-start">
                                        <i class="fal fa-heart"></i> {{ formatNumberWithK($featCatPost->bookmarks) }}
                                    </li>
                                    <li class="icon-start">
                                        <i class="fal fa-eye"></i>{{ formatNumberWithK($featCatPost->views) }}
                                    </li>
                                    <li class="icon-start">
                                        @php
                                            // first, convert the string into date object
                                            $date = Carbon\Carbon::parse($featCatPost->created_at);
                                        @endphp

                                        <a target="_self" title="Link">
                                            <i class="fal fa-clock"></i>{{ date_format($date, 'd M') }}
                                        </a>
                                    </li>
                                </ul>

                                <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}"
                                    class="btn btn-md btn-secondary rounded-pill"
                                    title="{{ $keywords['Read_More'] ?? __('Read More') }}"
                                    target="_self">{{ $keywords['Read_More'] ?? __('Read More') }} -</a>
                            </div>
                        </article>
                    @endforeach

                    @if (count($featCatPosts) > 0)
                        <div class="d-flex justify-content-center align-items-center mt-1 mb-2">
                            <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}"
                                class="btn btn-md btn-primary rounded-pill">{{ $keywords['Show_More'] ?? __('Show More') }}</a>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="row text-center">
                <div class="alert alert-secondary" role="alert">
                    {{ $keywords['No_Featured_Categories_Found'] ?? __('No Featured Categories Found !') }}
                  </div>
            </div>
        @endif
    </div>
</div>
