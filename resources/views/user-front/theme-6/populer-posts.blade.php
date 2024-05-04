<div class="widget widget-posts mb-40">
    <h4 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#blogPost">
            {{ $keywords['Popular_Posts'] ?? 'Popular_Posts' }}
        </button>
        <span class="line"></span>
    </h4>
    <div id="blogPost" class="collapse show">
        <div class="accordion-body mt-20 scroll-y">
            @if (count($mostViewedPosts) > 0)
                @foreach ($mostViewedPosts as $mostViewedPost)
                    <article class="blog blog-inline blog-inline_v2">
                        <div class="blog_img">
                            <a href="{{ route('front.user.post_details', ['slug' => $mostViewedPost->slug, getParam()]) }}"
                                target="_self"
                                title="{{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}"
                                class="lazy-container ratio ratio-1-1 rounded-circle">
                                <img class="lazyload"
                                    data-src="{{ $mostViewedPost->thumbnail_image != null ? Storage::url($mostViewedPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}"
                                    alt="{{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}">
                            </a>
                        </div>

                        <div class="blog_content">
                            @php
                                $ItemPostCategory = App\Models\User\PostCategory::where('id', $mostViewedPost->post_category_id)->first();
                            @endphp
                            <a href="{{ route('front.user.posts', ['category' => $ItemPostCategory->id, getParam()]) }}"
                                target="_self" title="{{ $ItemPostCategory->name }}"
                                class="blog_tag font-xsm mb-1">{{ $ItemPostCategory->name }}</a>
                            <h6 class="blog_title lc-2 mb-1">
                                @php
                                    // first, convert the string into date object
                                    $date = Carbon\Carbon::parse($mostViewedPost->created_at);
                                @endphp
                                <a href="{{ route('front.user.post_details', ['slug' => $mostViewedPost->slug, getParam()]) }}"
                                    target="_self" title="Link">
                                    {{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}
                                </a>
                            </h6>
                            <span class="blog_date font-sm"> {{ date_format($date, 'd F') }} </span>
                        </div>
                    </article>
                @endforeach
            @else
                <div class="row text-center">
                    <div class="alert alert-secondary py-1 mt-20" role="alert">
                        {{ $keywords['No_Popular_Posts_Found'] ?? __('No Popular Posts Found !') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
