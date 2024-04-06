<div class="featured-blog pb-20">
    <div class="section-title title-inline mb-30">
        <h3 class="title">
            {{ $keywords['Featured_Categories'] ?? __('Featured Categories') }}
        </h3>
        <div class="tabs-navigation tabs-navigation_v3">
            <ul class="nav nav-tabs">
                @foreach ($featPostCategories as $featPostCategory)
                    <li class="nav-item">
                        <button class="nav-link btn-sm radius-sm {{ $loop->index == 0 ? 'active' : '' }}"
                            data-bs-toggle="tab" data-bs-target="#tab_{{ $featPostCategory->id }}"
                            type="button">{{ $featPostCategory->name }}</button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @if (count($featPostCategories) == 0)
        <div class="info-text mb-30">
            <div class="wrapper p-30 bg-light">
                <h5 class="text-center mb-0">
                    {{ $keywords['No_Featured_Categories_Found'] ?? __('No Featured Categories Found !') }}
                </h5>
            </div>
        </div>
    @else
        <div class="tab-content">
            @php $langId = $currentLanguageInfo->id; @endphp
            @foreach ($featPostCategories as $featPostCategory)
                <div class="tab-pane slide show {{ $loop->index == 0 ? 'active' : '' }}"
                    id="tab_{{ $featPostCategory->id }}">
                    <div class="row">
                        @php
                            $featCatPosts = DB::table('posts')
                                ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
                                ->where('post_contents.language_id', '=', $langId)
                                ->where('post_contents.post_category_id', '=', $featPostCategory->id)
                                ->where('posts.user_id', $user->id)
                                ->orderBy('posts.serial_number', 'ASC')
                                ->limit(6)
                                ->get();
                        @endphp
                        @if (count($featCatPosts) > 0)
                            @foreach ($featCatPosts as $index => $featCatPost)
                                @if ($index < 2)
                                    <div class="col-sm-6">
                                        <article class="blog mb-30">
                                            <div class="blog_img mb-20">
                                                <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}"
                                                    target="_self"
                                                    title="{{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}"
                                                    class="lazy-container ratio radius-md">
                                                    <img class="lazyload"
                                                        data-src="{{ asset('assets/user/img/posts/' . $featCatPost->thumbnail_image) }}"
                                                        alt="{{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}">
                                                </a>
                                            </div>
                                            <div class="blog_content">
                                                <h5 class="blog_title lc-2 mb-15">
                                                    <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}"
                                                        target="_self"
                                                        title="{{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}">
                                                        {{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}
                                                    </a>
                                                </h5>
                                                <ul class="blog_list no-border list-unstyled">
                                                    <li class="icon-start">
                                                        <a target="_self"
                                                            title="{{ $keywords['By'] ?? __('By') }} {{ $featCatPost->author }}">
                                                            <i
                                                                class="fal fa-user-circle"></i>{{ $keywords['By'] ?? __('By') }}
                                                            {{ $featCatPost->author }}
                                                        </a>
                                                    </li>
                                                    @php
                                                        // first, convert the string into date object
                                                        $date = Carbon\Carbon::parse($featCatPost->created_at);
                                                    @endphp
                                                    <li class="icon-start">
                                                        <i
                                                            class="fal fa-calendar-alt"></i>{{ date_format($date, 'M d,Y') }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </article>
                                    </div>
                                @else
                                    <div class="col-xl-3 col-sm-6">
                                        <article class="blog mb-30">
                                            <div class="blog_img mb-15">
                                                <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}"
                                                    target="_self"
                                                    title="{{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}"
                                                    class="lazy-container ratio ratio-1-3 radius-md">
                                                    <img class="lazyload"
                                                        data-src="{{ asset('assets/user/img/posts/' . $featCatPost->thumbnail_image) }}"
                                                        alt="{{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}">
                                                </a>
                                            </div>
                                            <div class="blog_content">
                                                <h6 class="blog_title lc-2 mb-10">
                                                    <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}"
                                                        target="_self"
                                                        title="{{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}">
                                                        {{ strlen($featCatPost->title) > 70 ? mb_substr($featCatPost->title, 0, 70, 'UTF-8') . '...' : $featCatPost->title }}
                                                    </a>
                                                </h6>
                                                <ul class="blog_list no-border list-unstyled">
                                                    <li class="icon-start font-xsm">
                                                        <a target="_self"
                                                            title="{{ $keywords['By'] ?? __('By') }} {{ $featCatPost->author }}">
                                                            <i
                                                                class="fal fa-user-circle"></i>{{ $keywords['By'] ?? __('By') }}
                                                            {{ $featCatPost->author }}
                                                        </a>
                                                    </li>
                                                    @php
                                                        // first, convert the string into date object
                                                        $date = Carbon\Carbon::parse($featCatPost->created_at);
                                                    @endphp
                                                    <li class="icon-start font-xsm">
                                                        <i
                                                            class="fal fa-calendar-alt"></i>{{ date_format($date, 'M d,y') }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </article>
                                    </div>
                                @endif
                            @endforeach
                            @if (count($featCatPosts) > 5)
                                <div class="d-flex justify-content-center align-items-center mt-1 mb-2">
                                    <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}"
                                        class="btn btn-md btn-primary radius-sm">{{ $keywords['Show_More'] ?? __('Show More') }}</a>
                                </div>
                            @endif
                        @else
                            <div class="col pt-5 pb-3 mb-4">
                                <h5 class="mb-5">
                                    {{ $keywords['No_Post_Found_Of_This_Category'] ?? __('No Post Found Of This Category !') }}
                                </h5>
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
