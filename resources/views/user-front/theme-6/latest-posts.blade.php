<div class="latest-blog pb-20">
    <div class="section-title section-title_v2 title-inline mb-30">
        <h4 class="title mt-0">
            {{ $keywords['Latest_Posts'] ?? __('Latest Posts') }}
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
        <div class="col-sm-6">
            <article class="blog blog_v4 text-center mb-30">
                <div class="blog_img">
                    <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}" target="_self" title="{{ strlen($latestPost->title) > 50 ? mb_substr($latestPost->title, 0, 50, 'UTF-8') . '...' : $latestPost->title }}" class="lazy-container ratio ratio-5-3">
                        <img class="lazyload" data-src="{{ asset('assets/user/img/posts/' . $latestPost->thumbnail_image) }}" alt="{{ strlen($latestPost->title) > 50 ? mb_substr($latestPost->title, 0, 50, 'UTF-8') . '...' : $latestPost->title }}">
                    </a>
                </div>
                <div class="blog_content px-3">
                    <ul class="blog_list list-unstyled mb-20">
                        <li class="icon-start">
                            <i class="fal fa-heart"></i>{{ formatNumberWithK($latestPost->bookmarks) }}
                        </li>
                        <li class="icon-start">
                            <i class="fal fa-eye"></i>{{ formatNumberWithK($latestPost->views) }}
                        </li>
                        <li class="icon-start">
                            @php
                              // first, convert the string into date object
                              $date = Carbon\Carbon::parse($latestPost->created_at);
                            @endphp
                             <a  target="_self" title="Link">
                                <i class="fal fa-clock"></i> {{ date_format($date, 'd M') }}
                            </a>
                        </li>
                    </ul>
                    @php
                    $latestPostCategory = App\Models\User\PostCategory::where('id', $latestPost->post_category_id)->first();
                    @endphp
                    <a href="{{ route('front.user.posts', ['category' => $latestPostCategory->id, getParam()]) }}" target="_self" title="{{ $latestPostCategory->name }}" class="blog_tag rounded-pill font-xsm mb-15">{{ $latestPostCategory->name }}</a>

                    <h5 class="blog_title lc-2 mb-0">
                        <a href="{{ route('front.user.post_details', ['slug' => $latestPost->slug, getParam()]) }}" target="_self" title="{{ strlen($latestPost->title) > 50 ? mb_substr($latestPost->title, 0, 50, 'UTF-8') . '...' : $latestPost->title }}">
                            {{ strlen($latestPost->title) > 50 ? mb_substr($latestPost->title, 0, 50, 'UTF-8') . '...' : $latestPost->title }}
                        </a>
                    </h5>
                </div>
            </article>
        </div>
        @endforeach
        @else
        <div class="col-lg-12">
            <div class="alert alert-secondary text-center" role="alert">
                {{ $keywords['No_Latest_Posts_Found'] ?? __('No Latest Post Found !') }}
              </div>
        </div>
       @endif
    </div>
</div>
