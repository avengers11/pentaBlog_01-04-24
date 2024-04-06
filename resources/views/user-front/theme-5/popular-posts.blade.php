<div class="widget widget-posts mb-40">
    <h4 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#blogPost">
            {{ $keywords['Popular_Posts'] ?? 'Popular_Posts' }}
            <span class="icons">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
    </h4>
    @if (count($mostViewedPosts) > 0)
        <div id="blogPost" class="collapse show">
            <div class="accordion-body mt-20 scroll-y">
                @foreach ($mostViewedPosts as $mostViewedPost)
                    <article class="blog blog-inline blog-inline_v2">
                        <div class="blog_img">
                            <a href="{{ route('front.user.post_details', ['slug' => $mostViewedPost->slug, getParam()]) }}"
                                target="_self"
                                title="{{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}"
                                class="lazy-container ratio ratio-1-1 radius-sm">
                                <img class="lazyload"
                                    data-src="{{ asset('assets/user/img/posts/' . $mostViewedPost->thumbnail_image) }}"
                                    alt="{{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}">
                            </a>
                        </div>
                        <div class="blog_content">
                            @php
                                $ItemPostCategory = App\Models\User\PostCategory::where('id', $mostViewedPost->post_category_id)->first();
                            @endphp
                            <a href="{{ route('front.user.posts', ['category' => $ItemPostCategory->id, getParam()]) }}"
                                target="_self" title=" {{ $ItemPostCategory->name }}" class="blog_tag font-xsm mb-1">
                                {{ $ItemPostCategory->name }}
                            </a>

                            <h6 class="blog_title lc-2 mb-1">
                                <a href="{{ route('front.user.post_details', ['slug' => $mostViewedPost->slug, getParam()]) }}"
                                    target="_self"
                                    title=" {{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}">
                                    {{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}
                                </a>
                            </h6>
                            @php
                                // first, convert the string into date object
                                $date = Carbon\Carbon::parse($mostViewedPost->created_at);
                            @endphp
                            <span class="blog_date font-sm">
                                {{ date_format($date, 'd F') }}
                            </span>
                        </div>
                    </article>
                @endforeach

            </div>
        </div>
    @else
      <div class="bg-light py-2 mt-30 text-center" role="alert">
        {{ $keywords['No_Popular_Posts_Found'] ?? __('No Popular Posts Found !') }}
      </div>
    @endif
</div>
