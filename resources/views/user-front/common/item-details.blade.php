@extends('user-front.common.layout')

@section('meta-description', !empty($seo) ? $seo->meta_description_shop_details : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_shop_details : '')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ !empty($pageHeading) ? $pageHeading->shop_details : $keywords['Shop_Details'] ?? 'Shop Details' }}
    @else

    {{$keywords['Shop_Details'] ?? 'Shop Details' }}
    @endif
@endsection

@section('content')
    <!-- Start olima_breadcrumb section -->
    <section class="olima_breadcrumb bg_imag lazy"
        @if (!empty($bgImg)) data-bg="{{ $bgImg->breadcrumb != null ? Storage::url($bgImg->breadcrumb) : asset('assets/admin/img/noimage.jpg') }}" @endif>
        <div class="bg_overlay"
            style="background: #{{ $websiteInfo->breadcrumb_overlay_color }}; opacity: {{ $websiteInfo->breadcrumb_overlay_opacity }}">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="breadcrumb-title">
                        <h1>
                            @isset($ad_details->title)
                            {{strlen($ad_details->title) > 50 ? mb_substr($ad_details->title,0,50,'utf-8') . '...' : $ad_details->title}}
                            @else
                            {{ !empty($pageHeading) ? $pageHeading->shop_details : $keywords['Shop_Details'] ?? 'Shop Details' }}
                            @endisset
                        </h1>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li>
                                <a href="{{ route('front.user.detail.view', getParam()) }}">
                                    {{ $keywords['Home'] ?? 'HOME' }}
                                </a>
                            </li>
                            <li class="active">
                                {{ !empty($pageHeading) ? $pageHeading->shop_details : $keywords['Shop_Details'] ?? 'Shop Details' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End olima_ breadcrumb section -->


    <!-- Start olima_shop_details section -->
    <section class="olima_shop_details pt-140 pb-140">
        <div class="container">
            <div class="row mb-70">
                <div class="col-lg-6">
                    @if ($ad_details->item->sliders)
                        <div class="shop_big_slide">
                            @foreach ($ad_details->item->sliders as $slider)
                                <div class="olima_img">
                                    <a href="{{ asset('assets/front/img/user/items/slider-images/' . $slider->image) }}"
                                        class="gallery-single">
                                        <img data-src="{{ $slider->image != null ? Storage::url($slider->image) : asset('assets/admin/img/noimage.jpg') }}"
                                            class="img-fluid lazy" alt="image">
                                    </a>
                                    {{-- <span class="new">new</span> --}}
                                </div>
                            @endforeach
                        </div>

                        <div class="shop_thumb_slide">
                            @foreach ($ad_details->item->sliders as $slider)
                                <div class="olima_img">
                                    <img src="{{ $slider->image != null ? Storage::url($slider->image) : asset('assets/admin/img/noimage.jpg') }}"
                                        class="img-fluid" alt="image">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="shop_details_box">
                        <h2>{{ $ad_details->title }}</h2>
                        <div class="rate mt-1">
                            <div class="rating" style="width:{{ $ad_details->item->rating * 20 }}%"></div>
                        </div>

                        @php
                            $variations = \App\Models\User\UserItemVariation::where('item_id', $ad_details->item_id)
                                ->where('language_id', $currentLanguageInfo->id)
                                ->get();
                            if (count($variations) == 0) {
                                $variations = null;
                            }
                        @endphp
                        @if ($ad_details->item->previous_price > 0)
                            <span class="previous-price">
                                {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                {{ $ad_details->item->previous_price }}
                                {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                            </span>
                        @endif
                        <span class="price">
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                            {{ $ad_details->item->current_price }}
                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                        </span>
                        <p>{{ $ad_details->summary }}</p>
                        @php
                            $variations = \App\Models\User\UserItemVariation::where('item_id', $ad_details->item_id)
                                ->where('language_id', $currentLanguageInfo->id)
                                ->get();
                            if (count($variations) == 0) {
                                $variations = null;
                            }
                        @endphp

                        @if (!empty($userShopSetting) &&  $userShopSetting->is_shop)
                            <div class="button_box pt-30">
                                @if (empty($variations))
                                    <input type="number" class="nice-input" value="1" id="detailsQuantity">
                                @endif
                                <a class="cart-link olima_btn cursor-pointer" data-title="{{ $ad_details->title }}"
                                    data-current_price="{{ $ad_details->item->current_price }}"
                                    data-item_id="{{ $ad_details->item->id }}"
                                    data-variations="{{ json_encode($variations) }}"
                                    data-href="{{ route('front.user.add.cart', ['id' => $ad_details->item_id, getParam()]) }}"
                                    data-toggle="tooltip" data-placement="top"
                                    title="{{ $keywords['Add_to_cart'] ?? 'Add To Cart' }}">
                                    <i class="far fa-shopping-cart"></i>
                                    {{ $keywords['Add_to_cart'] ?? 'Add To Cart' }}
                                </a>
                            </div>
                        @endif
                        <ul class="pt-40">
                            <li><span>{{ $keywords['Share_Now'] ?? 'Share Now' }}</span></li>
                            <li><a
                                    href="https://www.facebook.com/sharer/sharer.php?u={{ route('front.user.item_details', ['slug' => $ad_details->slug, getParam()]) }}"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li><a
                                    href="https://twitter.com/intent/tweet?url={{ route('front.user.item_details', ['slug' => $ad_details->slug, getParam()]) }}"><i
                                        class="fab fa-twitter"></i></a></li>
                            <li><a
                                    href="https://plus.google.com/share?url={{ route('front.user.item_details', ['slug' => $ad_details->slug, getParam()]) }}"><i
                                        class="fab fa-google-plus-g"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="discription_area">
                        <div class="discription_tabs">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab"
                                        href="#description">{{ $keywords['Description'] ?? 'Description' }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab"
                                        href="#reviews">{{ $keywords['Reviews'] ?? 'Reviews' }}
                                        ({{ count($reviews) }})</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div id="description" class="tab-pane active"><br>
                                <div class="olima_content_box">
                                    <p>{!! $ad_details->description !!}</p>
                                </div>
                            </div>

                            <div id="reviews" class="tab-pane fade"><br>
                                <div class="shop_review_area">
                                    <h4 class="title">({{ count($reviews) }}) {{ $keywords['Reviews'] ?? 'Reviews' }}
                                        {{ $keywords['for'] ?? 'for' }}
                                        {{ $ad_details->title }}</h4>
                                    @foreach ($reviews as $review)
                                        <div class="review_user review-content">
                                            <img data-src="{{ is_null($review->customer->image) ? asset('assets/user/img/profile.jpg') : Storage::url($review->customer->image)) }}"
                                                class="lazy">
                                            <ul>
                                                <div class="rate">
                                                    <div class="rating" style="width:{{ $review->review * 20 }}%"></div>
                                                </div>
                                            </ul>
                                            <span><span>
                                                    {{ !empty(convertUtf8($review->customer)) ? convertUtf8($review->customer->username) : '' }}</span>
                                                – {{ $review->created_at->format('F j, Y') }}</span>
                                            <p>{{ convertUtf8($review->comment) }}</p>
                                        </div>
                                    @endforeach
                                    @if (Auth::guard('customer')->user())
                                        @if (\App\Models\User\UserOrderItem::where('customer_id', Auth::guard('customer')->user()->id)->where('item_id', $ad_details->item_id)->exists())
                                            <div class="review_form">
                                                <form action="{{ route('item.review.submit', getParam()) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="form_group">
                                                        <label>{{ $keywords['Your_review'] ?? 'Your review' }} *</label>
                                                        <textarea class="form_control @error('comment') is-invalid @enderror " name="comment" placeholder="Your review *"></textarea>
                                                        @error('comment')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <input type="hidden" value="" id="reviewValue" name="review">
                                                    <input type="hidden" value="{{ $ad_details->item->id }}"
                                                        name="item_id">
                                                    <div class="form_group">
                                                        <span>{{ $keywords['Rating'] ?? __('Rating') }} *</span>
                                                        <div class="review-content ">
                                                            <ul class="review-value review-1">
                                                                <li><a class="cursor-pointer" data-href="1"><i
                                                                            class="far fa-star"></i></a></li>
                                                            </ul>
                                                            <ul class="review-value review-2">
                                                                <li><a class="cursor-pointer" data-href="2"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="2"><i
                                                                            class="far fa-star"></i></a></li>
                                                            </ul>
                                                            <ul class="review-value review-3">
                                                                <li><a class="cursor-pointer" data-href="3"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="3"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="3"><i
                                                                            class="far fa-star"></i></a></li>
                                                            </ul>
                                                            <ul class="review-value review-4">
                                                                <li><a class="cursor-pointer" data-href="4"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="4"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="4"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="4"><i
                                                                            class="far fa-star"></i></a></li>
                                                            </ul>
                                                            <ul class="review-value review-5">
                                                                <li><a class="cursor-pointer" data-href="5"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="5"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="5"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="5"><i
                                                                            class="far fa-star"></i></a></li>
                                                                <li><a class="cursor-pointer" data-href="5"><i
                                                                            class="far fa-star"></i></a></li>
                                                            </ul>
                                                        </div>
                                                        @error('review')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="form_button">
                                                        <button type="submit"
                                                            class="olima_btn">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        <div class="review-login mt-5">
                                            <a class="boxed-btn d-inline-block mr-2"
                                                href="{{ route('customer.login', getParam()) }}">{{ $keywords['Login'] ?? __('Login') }}</a>
                                            {{ $keywords['to_leave_a_rating'] ?? __('to leave a rating') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End olima_shop_details section -->
    <!-- Start olima_shop section -->
    <section class="olima_shop pb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section_title mb-70 text-center">
                        <span>{{ $keywords['Related_Items'] ?? 'Related Items' }}</span>
                        <h2>{{ $keywords['Related_Items'] ?? 'Related Items' }}</h2>
                    </div>
                </div>
            </div>
            <div class="releted_post_slide">
                @foreach ($relateditems as $item)
                    <div class="product_box">
                        <div class="product_img">
                            <a href="{{ route('front.user.item_details', ['slug' => $item->slug, getParam()]) }}">
                                <img data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                                    class="img-fluid lazy" alt="">
                            </a>
                            @php
                                $variations = \App\Models\User\UserItemVariation::where('item_id', $ad_details->item_id)
                                    ->where('language_id', $currentLanguageInfo->id)
                                    ->get();
                                if (count($variations) == 0) {
                                    $variations = null;
                                }
                            @endphp
                            <div class="product_overlay">
                                <div class="product_link">
                                    @if (!empty($userShopSetting))
                                        <a class="cart-link pointer" data-title="{{ $item->title }}"
                                            data-current_price="{{ $item->current_price }}"
                                            data-item_id="{{ $item->item_id }}"
                                            data-variations="{{ json_encode($variations) }}"
                                            data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                                            data-toggle="tooltip" data-placement="top"
                                            title="{{ $keywords['Add_to_cart'] ?? __('Add to Cart') }}"><i
                                                class="fas fa-cart-arrow-down "></i></a>
                                    @endif
                                    <a href="{{ route('front.user.item_details', ['slug' => $item->slug, getParam()]) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="add-to-wish pointer" data-item_id="{{ $item->item_id }}"
                                        data-href="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                                        data-toggle="tooltip" data-placement="top"
                                        title="{{ $keywords['Add_to_wishlist'] ?? __('Add to wishlist') }}"><i
                                            class="fas fa-heart"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="product_info">
                            @if (!empty($userShopSetting) && $userShopSetting->item_rating_system)
                                <div class="rate">
                                    <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                                </div>
                            @endif
                            <h3><a href="{{ route('front.user.item_details', ['slug' => $item->slug, getParam()]) }}">
                                    {{ strlen($item->title) > 35 ? mb_substr($item->title, 0, 35, 'UTF-8') . '...' : $item->title }}
                                </a>
                            </h3>
                            @if ($item->previous_price > 0)
                                <span class="previous-price">
                                    {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                    {{ $item->previous_price }}
                                    {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                </span>
                            @endif
                            <span class="price">
                                {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                {{ $item->current_price }}
                                {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End olima_shop section -->
    {{-- Variation Modal Starts --}}
    @includeIf('front.partials.variation-modal')
    {{-- Variation Modal Ends --}}
@endsection
@section('script')
@endsection
