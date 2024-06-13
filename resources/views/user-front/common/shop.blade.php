@extends('user-front.common.layout')
@section('meta-description', !empty($seo) ? $seo->meta_description_shop : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_shop : '')
@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->shop ?? $keywords['Shop'] ?? 'Shop' }}
    @else
    {{$keywords['Shop'] ?? 'Shop' }}
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
                        <h1> {{ !empty($pageHeading) ? $pageHeading->shop : $keywords['Shop'] ?? 'Shop' }}</h1>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase">
                                <a href="{{ route('front.user.detail.view', getParam()) }}">
                                    {{ $keywords['Home'] ?? __('Home') }}
                                </a>
                            </li>
                            <li class="active text-uppercase">
                                {{ !empty($pageHeading) ? $pageHeading->shop : $keywords['Shop'] ?? 'Shop' }} </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End olima_ breadcrumb section -->
    <!-- Start olima_shop section -->
    <section class="olima_shop pt-140 pb-140">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="olima_sidebar sidebar_v1">
                        <div class="widget_box category_widget_2 mb-50">
                            <div class="product-search input-group">
                                <input type="search" class="input-search" name="search"
                                    value="{{ request()->input('search') ? request()->input('search') : '' }}"
                                    placeholder="{{ $keywords['Search_Keywords'] ?? 'Search Keywords' }}....">
                                <button type="submit"><i class="far fa-search"></i></button>
                            </div>
                        </div>
                        <div class="widget_box category_widget_2 mb-20">
                            <select name="type" id="type_sort" class="form-control">
                                <option value="0" data-display="Sort By" selected>
                                    {{ $keywords['Sort_By'] ?? 'Sort By' }}</option>
                                <option value="new" {{ request('type') == 'new' ? 'selected' : '' }}>
                                    {{ $keywords['Latest'] ?? 'Latest' }}
                                </option>
                                <option value="old" {{ request('type') == 'old' ? 'selected' : '' }}>
                                    {{ $keywords['Oldest'] ?? 'Oldest' }}
                                </option>
                                <option value="high-to-low" {{ request('type') == 'high-to-low' ? 'selected' : '' }}>
                                    {{ $keywords['Price_Hight_to_Low'] ?? 'Price:Hight to Low' }}</option>
                                <option value="low-to-high" {{ request('type') == 'low-to-high' ? 'selected' : '' }}>
                                    {{ $keywords['Price_Low_to_High'] ?? 'Price:Low to High' }}</option>
                            </select>
                        </div>
                        <div class="widget_box category_widget_2 mb-50">
                            <h4 class="widget-title">{{ $keywords['Categories'] ?? 'Categories' }}</h4>
                            <ul>
                                <li class="">
                                    <a data-href="0" class="category-id cursor-pointer">
                                        <div class="single-list-category d-flex justify-content-between align-items-center">
                                            <div class="category-text">
                                                <h6
                                                    class="title {{ request()->input('category_id') == '' ? 'active-search' : '' }} ">
                                                    {{ $keywords['All'] ?? 'All' }} </h6>
                                                {{-- ({{ $all_items }}) --}}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                @foreach ($categories as $category)
                                    <li>
                                        <a class="category-id {{ request()->input('category_id') == $category->id ? 'active-search' : '' }} "
                                            data-href="{{ $category->id }}">{{ $category->name }} <span><i
                                                    class="fas fa-plus"></i></span></a>
                                        @if (request()->input('category_id') == $category->id)
                                            @if ($category->subcategories->count() > 0)
                                                <ul class="ml-20">
                                                    @foreach ($category->subcategories as $sub)
                                                        <li>
                                                            <a data-href="{{ $sub->id }}"
                                                                class="subcategory-id cursor-pointer {{ request('subcategory_id') == $sub->id ? 'active-search' : '' }}"><i
                                                                    class="fa fa-angle-right"></i>
                                                                {{ $sub->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="widget_box price_ranger_widget mb-50">
                            <div class="widget product-filter-widget">
                                <h4 class="widget-title">{{ $keywords['Filter_By_Price'] ?? 'Filter By Price' }}</h4>
                                <div id="slider-range" class="slider-range"></div>
                                <div class="range">
                                    <input type="text" min="0"
                                        value="{{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ request()->input('minprice') ?: $min_price ?? 0 }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}"
                                        name="minprice" id="amount1" readonly />
                                    <input type="text" min="0"
                                        value="{{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ request()->input('maxprice') ?: $max_price ?? 0 }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}"
                                        name="maxprice" id="amount2" readonly />
                                    <button class="filter-button olima_btn mt-3"
                                        type="submit">{{ $keywords['Filter'] ?? 'Filter' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        @if (count($items) == 0)
                            <h3 class="text-muted">{{ $keywords['NO_ITEMS_FOUND'] ?? 'No Items Found' }}!</h3>
                        @endif
                        @foreach ($items as $item)
                            <div class="col-lg-6 col-md-6">
                                <div class="product_box mb-30">
                                    <div class="product_img">
                                        <a
                                            href="{{ route('front.user.item_details', ['slug' => $item->slug, getParam()]) }}">
                                            <img data-src="{{ $item->thumbnail != null ? Storage::url($item->thumbnail) : asset('assets/admin/img/noimage.jpg') }}"
                                                class="img-fluid lazy" alt="">
                                        </a>
                                        @php
                                            $variations = App\Models\User\UserItemVariation::where('item_id', $item->item_id)
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
                                                            class="fas fa-cart-arrow-down"></i></a>
                                                @endif
                                                <a
                                                    href="{{ route('front.user.item_details', ['slug' => $item->slug, getParam()]) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="add-to-wish pointer" data-item_id="{{ $item->item_id }}"
                                                    data-href="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ $keywords['Add_to_wishlist'] ?? __('Add to wishlist') }}">
                                                    @if (!empty($myWishlist) && in_array($item->item_id, $myWishlist))
                                                        <i class="fa fa-heart"></i>
                                                    @else
                                                        <i class="far fa-heart"></i>
                                                    @endif
                                                    {{-- <i class="far fa-heart"></i> --}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product_info product-desc">
                                        @if (!empty($userShopSetting) && $userShopSetting->item_rating_system)
                                            <div class="rate">
                                                <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                                            </div>
                                        @endif
                                        <h3><a
                                                href="{{ route('front.user.item_details', ['slug' => $item->slug, getParam()]) }}">
                                                {{ strlen($item->title) > 40 ? mb_substr($item->title, 0, 40, 'UTF-8') . '...' : $item->title }}
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
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="pagination-wrap text-center">
                                <ul class="pagination justify-content-center">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <nav class="pagination-nav {{ $items->count() > 2 ? 'mb-4' : '' }}">
                                                {{ $items->appends(['minprice' => request()->input('minprice'), 'maxprice' => request()->input('maxprice'), 'category_id' => request()->input('category_id'), 'type' => request()->input('type')])->links() }}
                                            </nav>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End olima_shop section -->
    <!--====== Shop Section End ======-->
    <form id="searchForm" class="d-none" action="{{ route('front.user.shop', getParam()) }}" method="get">
        <input type="hidden" id="search" name="search"
            value="{{ !empty(request()->input('search')) ? request()->input('search') : '' }}">
        <input type="hidden" id="minprice" name="minprice"
            value="{{ !empty(request()->input('minprice')) ? request()->input('minprice') : '' }}">
        <input type="hidden" id="maxprice" name="maxprice"
            value="{{ !empty(request()->input('maxprice')) ? request()->input('maxprice') : '' }}">
        <input type="hidden" name="category_id" id="category_id"
            value="{{ !empty(request()->input('category_id')) ? request()->input('category_id') : null }}">
        <input type="hidden" name="subcategory_id" id="subcategory_id"
            value="{{ !empty(request()->input('subcategory_id')) ? request()->input('subcategory_id') : null }}">
        <input type="hidden" name="type" id="type"
            value="{{ !empty(request()->input('type')) ? request()->input('type') : 'new' }}">
        <button id="search-button" type="submit"></button>
    </form>


    {{-- Variation Modal Starts --}}
    @includeIf('front.partials.variation-modal')
    {{-- Variation Modal Ends --}}
@endsection

@section('script')
    <script>
        let maxprice = 0;
        let minprice = 0;
        let typeSort = '';
        let category = '';
        let attributes = '';
        let review = '';
        let search = '';

        let countryId = '';
        let stateId = '';
        let cityId = '';


        $(document).on('click', '.filter-button', function() {
            let filterval1 = $('#amount1').val();
            let filterval2 = $('#amount2').val();
            minprice = filterval1.replace('$', '');
            maxprice = filterval2.replace('$', '');
            $('#maxprice').val(maxprice);
            $('#minprice').val(minprice);
            $('#search-button').click();
        });


        $(document).on('change', '#type_sort', function() {
            typeSort = $(this).val();
            $('#type').val(typeSort);
            $('#search-button').click();
        })
        // $(document).ready(function() {
        //     typeSort = $('#type_sort').val();
        //     $('#type').val(typeSort);
        // })

        $(document).on('click', '.category-id', function(e) {
            e.preventDefault();
            category = '';
            if ($(this).attr('data-href') != 0) {
                category = $(this).attr('data-href');
            }
            $('#category_id').val(category);
            $('#subcategory_id').val('');
            $('#search-button').click();
        })
        $(document).on('click', '.subcategory-id', function(e) {
            e.preventDefault();
            category = '';
            if ($(this).attr('data-href') != 0) {
                subcategory = $(this).attr('data-href');
            }
            $('#subcategory_id').val(subcategory);
            $('#search-button').trigger('click');
        })

        $(document).on('click', '.review_val', function() {
            review = $(".review_val:checked").val();
            $('#review').val(review);
            $('#search-button').click();
        })

        $(document).on('change', '.input-search', function(e) {
            var key = e.which;
            search = $('.input-search').val();
            $('#search').val(search);
            $('#search-button').click();
            return false;
        })
    </script>
    @php
        $selMinPrice = request()->input('minprice') ? request()->input('minprice') : $min_price;
        $selMaxPrice = request()->input('maxprice') ? request()->input('maxprice') : $max_price;
    @endphp
    <script>
        $("#slider-range").slider({
            range: true,
            min: {{ $min_price }},
            max: {{ $max_price }},
            values: [{{ $selMinPrice }}, {{ $selMaxPrice }}],
            slide: function(event, ui) {
                $("#amount1").val(
                    `{{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}` +
                    ui.values[0] + ".00" +
                    `{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}`
                );
                $("#amount2").val(
                    `{{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}` +
                    ui.values[1] + ".00" +
                    `{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}`
                );
            }
        });
    </script>
@endsection
