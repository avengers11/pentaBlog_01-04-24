<div class="trending-blog pb-25">
    <div class="section-title section-title_v1 title-inline mb-30">
        <h4 class="title mt-0">
            {{ $keywords['Featured_Posts'] ?? 'Featured Posts' }}
            <span class="icons">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </h4>
        <span class="line"></span>
    </div>
    <div class="row">
        @if (count($featuredPosts) > 0)
            @php
                $items = $featuredPosts; // Replace with your actual model query
                $chunkSize = 4;
            @endphp
            @foreach ($items->chunk($chunkSize) as $chunk)
                @php
                    $firstItem = $chunk->shift();
                @endphp
                @if ($firstItem)
                    <!--- First Item here---->
                    <div class="col-xl-7">
                        <article class="blog blog-lg_v1 radius-md mb-25">
                            <div class="blog_img">
                                <div class="lazy-container ratio ratio-5-4">
                                    <img class="lazyload"
                                        data-src="{{ $firstItem->featured_post_image != null ? Storage::url($firstItem->featured_post_image) : asset('assets/admin/img/noimage.jpg') }}"
                                        alt="{{ $firstItem->title }}">
                                </div>
                            </div>
                            <div class="blog_content p-3 p-lg-4">
                                <div class="content-inner">
                                    @php
                                        $firstItemPostCategory = App\Models\User\PostCategory::where('id', $firstItem->post_category_id)->first();
                                    @endphp

                                    <a href="{{ route('front.user.posts', ['category' => $firstItemPostCategory->id, getParam()]) }}"
                                        target="_self" title="{{ $firstItemPostCategory->name }}"
                                        class="blog_tag rounded-pill position-static font-xsm ms-0 mb-15">
                                        {{ $firstItemPostCategory->name }}
                                    </a>

                                    <h4 class="blog_title lc-2 mb-15">
                                        <a href="{{ route('front.user.post_details', ['slug' => $firstItem->slug, getParam()]) }}"
                                            target="_self"
                                            title="{{ strlen($firstItem->title) > 50 ? mb_substr($firstItem->title, 0, 50, 'UTF-8') . '...' : $firstItem->title }}">
                                            {{ strlen($firstItem->title) > 50 ? mb_substr($firstItem->title, 0, 50, 'UTF-8') . '...' : $firstItem->title }}
                                        </a>
                                    </h4>

                                    <ul class="blog_list list-unstyled">
                                        @php
                                            $bookmarkCount = \App\Models\User\BookmarkPost::where('post_id', $firstItem->post_id)->count();
                                        @endphp
                                        <li class="icon-start">
                                            <i class="fal fa-heart"></i>{{ $bookmarkCount }}
                                        </li>
                                        <li class="icon-start">
                                            <i class="fal fa-eye"></i> {{ $firstItem->views }}
                                        </li>
                                        @php
                                            // first, convert the string into date object
                                            $date = Carbon\Carbon::parse($firstItem->created_at);
                                        @endphp

                                        <li class="icon-start">
                                            <a target="_self" title="{{ date_format($date, 'd M') }}">
                                                <i class="fal fa-clock"></i>{{ date_format($date, 'd M') }}
                                            </a>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </article>
                    </div>
                @endif
                <div class="col-xl-5">
                    <div class="row">
                        @foreach ($chunk as $item)
                            <div class="col-sm-6 col-xl-12">
                                <article class="blog blog-inline blog-inline_v2 mb-25">

                                    <div class="blog_img">
                                        <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                            target="_self"
                                            title="{{ strlen($item->title) > 30 ? mb_substr($item->title, 0, 30, 'UTF-8') . '...' : $item->title }}"
                                            class="lazy-container ratio ratio-1-1 radius-sm">
                                            <img class="lazyload"
                                                data-src="{{ $item->featured_post_image != null ? Storage::url($item->featured_post_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                alt="{{ strlen($item->title) > 15 ? mb_substr($item->title, 0, 15, 'UTF-8') . '...' : $item->title }}">
                                        </a>
                                    </div>

                                    <div class="blog_content">
                                        @php
                                            $ItemPostCategory = App\Models\User\PostCategory::where('id', $item->post_category_id)->first();
                                        @endphp

                                        <a href="{{ route('front.user.posts', ['category' => $ItemPostCategory->id, getParam()]) }}"
                                            target="_self" title=" {{ $ItemPostCategory->name }}"
                                            class="blog_tag font-xsm mb-10">
                                            {{ $ItemPostCategory->name }}
                                        </a>

                                        <h6 class="blog_title lc-2 mb-10">
                                            <a href="{{ route('front.user.post_details', ['slug' => $item->slug, getParam()]) }}"
                                                target="_self"
                                                title="{{ strlen($item->title) > 40 ? mb_substr($item->title, 0, 40, 'UTF-8') . '...' : $item->title }}">
                                                {{ strlen($item->title) > 40 ? mb_substr($item->title, 0, 40, 'UTF-8') . '...' : $item->title }}
                                            </a>
                                        </h6>

                                        @php
                                            // first, convert the string into date object
                                            $date = Carbon\Carbon::parse($item->created_at);
                                        @endphp
                                        <span class="blog_date font-sm">
                                            {{ date_format($date, 'd F') }}
                                        </span>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-light py-3 text-center" role="alert">
                {{ $keywords['No_Featured_Posts_Found'] ?? __('No Featured Post Found !') }}
            </div>
        @endif
    </div>
</div>
