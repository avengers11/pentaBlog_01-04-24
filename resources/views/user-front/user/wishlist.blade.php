@extends('user-front.common.layout')
@section('pageHeading')
    {{ $keywords['mywishlist'] ?? __('My Wishlist') }}
@endsection
@section('content')

    <!-- Start Olima Breadcrumb Section -->
    <section class="olima_breadcrumb bg_image lazy"
        @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
        <div class="bg_overlay"
            style="background: #{{ $websiteInfo->breadcrumb_overlay_color }}; opacity: {{ $websiteInfo->breadcrumb_overlay_opacity }}">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="breadcrumb-title">
                        <h1>{{ $keywords['mywishlist'] ?? __('My Wishlist') }}</h1>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">{{ $keywords['mywishlist'] ?? __('My Wishlist') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Breadcrumb Section -->

    <section class="user-dashboard">
        <div class="container">
            <div class="row">
                @includeIf('user-front.user.side-navbar')
                <div class="col-lg-9">
                    <div class="row mb-5">
                        <div class="col-lg-12">
                            <div class="user-profile-details mb-40">
                                <div class="account-info">
                                    <div class="title mb-3">
                                        <h4>{{ $keywords['mywishlist'] ?? __('My Wishlist') }}</h4>
                                    </div>
                                    <div class="main-info" id="refreshDiv">
                                        <div class="main-table">
                                            <div class="table-responsiv">
                                                <table id="order_table"
                                                    class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ $keywords['Item'] ?? __('item') }}</th>
                                                            <th>{{ $keywords['Title'] ?? __('title') }}</th>
                                                            <th>{{ $keywords['Price'] ?? __('price') }}</th>
                                                            <th>{{ $keywords['Action'] ?? __('action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($wishlist)
                                                            @foreach ($wishlist as $item)
                                                                @php
                                                                    $content = $item->item
                                                                        ->itemContents()
                                                                        ->where('language_id', $language->id)
                                                                        ->first();
                                                                @endphp
                                                                <tr>
                                                                    <td width="15%"> <a
                                                                            href="{{ route('front.user.item_details', ['slug' => $content->slug, getParam()]) }}">
                                                                            <img src="{{ $item->item->thumbnail != null ? Storage::url($item->item->thumbnail) : asset('assets/admin/img/noimage.jpg') }}"
                                                                                class="img-fluid" alt="image">
                                                                        </a>
                                                                    </td>
                                                                    <td width="50%" class="px-4"> <a
                                                                            href="{{ route('front.user.item_details', ['slug' => $content->slug, getParam()]) }}">
                                                                            {{ $content->title }}
                                                                        </a>
                                                                    </td>
                                                                    <td>{{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                                                        {{ $item->item->current_price }}
                                                                        {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="remove">
                                                                            <div class="checkbox">
                                                                                <span
                                                                                    class="fas fa-times cursor-pointer item-remove"
                                                                                    rel="{{ $item->id }}"
                                                                                    data-pg="wish"
                                                                                    data-href="{{ route('customer.removefromWish', ['id' => $item->id, getParam()]) }} "></span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr class="text-center">
                                                                <td colspan="4">
                                                                    {{ $keywords['no_items'] ?? __('No Items found!') }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#order_table').DataTable({
                responsive: true
            });
        });
    </script>
@endsection
