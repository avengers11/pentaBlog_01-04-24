<div class="widget widget-posts mb-40">
    <h4 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#blogPost">
            {{ $keywords['Popular_Posts'] ?? 'Popular Posts' }}
        </button>
    </h4>
    <div id="blogPost" class="collapse show">
        <div class="accordion-body mt-20 scroll-y">
            @if (count($mostViewedPosts) > 0)
                @foreach ($mostViewedPosts as $mostViewedPost)
                    <article class="blog blog-inline">
                        <div class="blog_img">
                            <a href="{{ route('front.user.post_details', ['slug' => $mostViewedPost->slug, getParam()]) }}"
                                target="_self"
                                title="{{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}"
                                class="lazy-container ratio ratio-1-1 radius-md">
                                <img class="lazyload"
                                    data-src="{{ $mostViewedPost->thumbnail_image != null ? Storage::url($mostViewedPost->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}"
                                    alt="{{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}">
                            </a>
                        </div>
                        <div class="blog_content">
                            @php
                                $ItemPostCategory = App\Models\User\PostCategory::where('id', $mostViewedPost->post_category_id)->first();
                            @endphp
                            <h6 class="blog_title lc-2 mb-10">
                                <a href="{{ route('front.user.posts', ['category' => $ItemPostCategory->id, getParam()]) }}"
                                    target="_self"
                                    title=" {{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}">
                                    {{ strlen($mostViewedPost->title) > 40 ? mb_substr($mostViewedPost->title, 0, 40, 'UTF-8') . '...' : $mostViewedPost->title }}
                                </a>
                            </h6>

                            <ul class="blog_list no-border list-unstyled">
                                <li class="icon-start font-sm">
                                    <a href="author-details" target="_self" title="Link">
                                        <i class="fal fa-user-circle"></i>{{ $keywords['By'] ?? 'By' }}
                                        {{ $mostViewedPost->author }}
                                    </a>
                                </li>
                                @php
                                    // first, convert the string into date object
                                    $date = Carbon\Carbon::parse($mostViewedPost->created_at);
                                @endphp
                                <li class="icon-start font-sm">
                                    <i class="fal fa-calendar-alt"></i> {{ date_format($date, 'M d, Y') }}
                                </li>
                            </ul>

                        </div>
                    </article>
                @endforeach
            @else
            <div class="info-text">
                <div class="wrapper p-30 bg-light">
                    <h5 class="text-center mb-0">
                        {{ $keywords['No_Popular_Posts_Found'] ?? 'No Popular Posts Found !' }}
                    </h5>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
