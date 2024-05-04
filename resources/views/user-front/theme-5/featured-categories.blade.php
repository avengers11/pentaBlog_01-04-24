<div class="featured-blog pb-20">
    <div class="section-title section-title_v1 title-inline mb-30">
        <h4 class="title">
            {{ $keywords['Featured_Categories'] ?? __('Featured Categories') }}
            <span class="icons">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </h4>
        <div class="tabs-navigation tabs-navigation_v1">
            <ul class="nav nav-tabs">
                @foreach ($featPostCategories as $featPostCategory)
                    <li class="nav-item">
                        <button class="nav-link {{ $loop->index == 0 ? 'active' : '' }}" data-bs-toggle="tab"
                            data-bs-target="#tab_{{ $featPostCategory->id }}" type="button">
                            {{ $featPostCategory->name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="tab-content">
        @if(count($featPostCategories) > 0 )
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
                            ->limit(3)
                            ->get();
                    @endphp

                    @if (count($featCatPosts) > 0)
                        @foreach ($featCatPosts as $featCatPost)
                            <div class="col-sm-6 col-xl-4">
                                <article class="blog blog_v3 radius-md mb-30">
                                    <div class="blog_img">
                                        <div class="lazy-container ratio ratio-1-3">
                                            <img class="lazyload"
                                                data-src="{{ $featCatPost->thumbnail_image != null ? Storage::url($featCatPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                alt="Blog Image">
                                        </div>
                                    </div>
                                    <div class="blog_content p-15">
                                        <div class="content-inner">
                                            <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}"
                                                target="_self" title="Link"
                                                class="blog_tag rounded-pill font-xsm mb-2">{{ $featPostCategory->name }}</a>
                                            @php
                                                // first, convert the string into date object
                                                $date = Carbon\Carbon::parse($featCatPost->created_at);
                                            @endphp
                                            <span class="blog_date font-sm mb-2">{{ date_format($date, 'd F') }} </span>
                                            <h6 class="blog_title lc-2 mb-15">
                                                <a href="{{ route('front.user.post_details', ['slug' => $featCatPost->slug, getParam()]) }}"
                                                    target="_self"
                                                    title="{{ strlen($featCatPost->title) > 30 ? mb_substr($featCatPost->title, 0, 30, 'UTF-8') . '...' : $featCatPost->title }}">
                                                    {{ strlen($featCatPost->title) > 50 ? mb_substr($featCatPost->title, 0, 50, 'UTF-8') . '...' : $featCatPost->title }}
                                                </a>
                                            </h6>

                                            <ul class="blog_list list-unstyled">
                                                <li class="icon-start">
                                                    <i class="fal fa-heart post-bookmarked"></i>
                                                    {{ formatNumberWithK($featCatPost->bookmarks) }}
                                                </li>
                                                <li class="icon-start">
                                                    <i class="fal fa-eye"></i> {{ formatNumberWithK($featCatPost->views) }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    @else
                        <div class="col pt-5 pb-3 mb-4">
                            <h5 class="mb-5">
                                {{ $keywords['No_Post_Found_Of_This_Category'] ?? __('No Post Found Of This Category !') }}
                            </h5>
                        </div>
                    @endif
                    @if (count($featCatPosts) > 0)
                    <div class="d-flex justify-content-center align-items-center mt-1 mb-2">
                        <a href="{{ route('front.user.posts', ['category' => $featPostCategory->id, getParam()]) }}" class="btn btn-md btn-primary radius-sm">{{ $keywords['Show_More'] ?? __('Show More') }}</a>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
        @else
        <div class="row text-center">
            <div class="bg-light py-3 text-center" role="alert">
                {{ $keywords['No_Featured_Categories_Found'] ?? __('No Featured Categories Found !') }}
            </div>
        </div>

        @endif
    </div>
</div>
