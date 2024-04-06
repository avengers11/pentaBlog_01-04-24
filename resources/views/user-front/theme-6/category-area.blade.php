@if($postCategories->count() > 0)
<div class="category-area category-area_v1 mb-50">
    <div class="swiper category-slider" id="category-slider-1" data-slides-per-view="3" data-swiper-loop="true">
        <div class="swiper-wrapper">
            @foreach ($postCategories as $postCategory)
            <div class="swiper-slide">
                <div class="card">
                    <div class="card-img">
                        <div class="lazy-container ratio ratio-2-3">
                            <img class="lazyload" data-src="{{ asset('assets/user/img/post-categories/' . $postCategory->image) }}" alt="{{ $postCategory->name }}">
                        </div>
                    </div>
                    <div class="card-content text-center">
                        <h6 class="card-title lc-1 mb-0">
                            <a href="{{ route('front.user.posts', ['category' => $postCategory->id, getParam()]) }}" target="_self" title="{{ $postCategory->name }}">
                                {{ $postCategory->name }}
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="swiper-pagination position-static mt-20" id="category-slider-1-pagination"></div>
    </div>
</div>
@endif
