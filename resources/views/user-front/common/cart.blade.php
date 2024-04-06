@extends('user-front.common.layout')
@section('pageHeading')
    @if (!empty($pageHeading))
        {{ !empty($pageHeading) ? $pageHeading->cart : $keywords['Cart'] ?? 'Cart' }}
    @else
    {{ $keywords['Cart'] ?? 'Cart' }}
    @endif
@endsection

@section('content')
    <!-- Start olima_breadcrumb section -->
    <section class="olima_breadcrumb bg_imag lazy"
        @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
        <div class="bg_overlay"
            style="background: #{{ $websiteInfo->breadcrumb_overlay_color }}; opacity: {{ $websiteInfo->breadcrumb_overlay_opacity }}">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="breadcrumb-title">
                        <h1>
                            {{ !empty($pageHeading) ? $pageHeading->cart : $keywords['Cart'] ?? 'Cart' }}
                        </h1>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? 'HOME' }}</a>
                            </li>
                            <li class="active">{{ !empty($pageHeading) ? $pageHeading->cart : $keywords['Cart'] ?? 'Cart' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End olima_ breadcrumb section -->
    <!-- Start olima_cart section -->
    <section class="olima_cart pt-140 pb-140" id="refreshDiv">
        <div class="container">

            @php
                $cartTotal = 0;
                $countitem = 0;
                if ($cart) {
                    foreach ($cart as $p) {
                        $cartTotal += $p['total'];
                        $countitem += $p['qty'];
                    }
                }
            @endphp
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between mb-20">
                        <div class="cart-total ">
                            <h4 class="">
                                <span>{{ $keywords['Total'] ?? __('Total') }}:</span>
                                <span>
                                    {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol: ''}}
                                    {{ $cartTotal }}
                                    {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol: '' }}
                                </span>
                            </h4>
                        </div>
                        <div class="cart-total justify-content-end">
                            <h4 class="">
                                <span>{{ $keywords['Total_Items'] ?? __('Total Items') }}:</span>
                                <span>
                                    {{$countitem}}
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="table_content table-responsive">
                        @if ($cart != null)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="prod-column">{{ $keywords['Items'] ?? 'item' }}</th>
                                        <th class="hide-column"></th>
                                        <th>{{ $keywords['Quantity'] ?? __('Quantity') }}</th>
                                        <th class="price">{{ $keywords['Price'] ?? __('Price') }}</th>
                                        <th>{{ $keywords['Total'] ?? __('total') }}</th>
                                        <th>{{ $keywords['Remove'] ?? __('Remove') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $key => $item)
                                        @php
                                            $id = $item['id'];
                                            $product = App\Models\User\UserItem::findOrFail($item['id']);
                                        @endphp
                                        <tr class="remove{{ $key }}">
                                            <td colspan="2" class="prod-column">
                                                <div class="column-box">
                                                    <div class="product-image">
                                                        <img src="{{ $item ? asset('assets/front/img/user/items/thumbnail/' . $product->thumbnail) : 'https://via.placeholder.com/350x350' }}"
                                                            class="">
                                                    </div>
                                                    <div class="title pl-0">
                                                        <a target="_blank"
                                                            href="{{ route('front.user.item_details', ['slug' => $item['slug'], getParam()]) }}">
                                                            <h5 class="prod-title">
                                                                {{ strlen($item['name']) > 40 ? mb_substr($item['name'], 0, 40, 'UTF-8') . '...' : $item['name'] }}
                                                            </h5>
                                                        </a>

                                                        @if (!empty($item['variations']))
                                                            <p><strong>{{ $keywords['Variations'] ?? __('Variations') }}
                                                                    :</strong> <br></p>
                                                            @foreach ($item['variations'] as $k => $itm)
                                                                <table class="variation-table">
                                                                    <tr>
                                                                        <td class="">
                                                                            <strong>{{ $k }} :
                                                                        </td>
                                                                        <td>{{ $itm['name'] }}: </td>
                                                                        <td>{{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                                                            {{ $itm['price'] }}
                                                                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="qty">
                                                <div class="quantity-input">
                                                    <button type="button" class="qtyMinus"><i
                                                            class="fas fa-angle-left"></i></button>
                                                    <input id="1" class="cart_qty" type="text"
                                                        value="{{ $item['qty'] }}" name="quantity">
                                                    <button type="button" class="qtyPlus"><i
                                                            class="fas fa-angle-right"></i></button>
                                                </div>
                                            </td>
                                            <input type="hidden" value="{{ $id }}" class="product_id">

                                            <td class="price cart_price">
                                                <p>
                                                    <strong>{{ $keywords['Item'] ?? __('Item') }}:</strong>
                                                    {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}<span>{{ $item['product_price'] * $item['qty'] }}</span>{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                                </p>
                                                @if (!empty($item['variations']))
                                                    <p>
                                                        <strong>{{ __('Variation') }}: </strong>
                                                        {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}<span>{{ $item['total'] - $item['product_price'] * $item['qty'] }}</span>{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="sub-total">
                                                {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                                {{ $item['total'] }}
                                                {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                            </td>
                                            <td>
                                                <div class="remove">
                                                    <div class="checkbox">
                                                        <span
                                                            class="fas fa-times cursor-pointer item-remove btn-danger btn-sm"
                                                            rel="{{ $id }}"
                                                            data-href="{{ route('front.cart.item.remove', ['uid' => $key, getParam()]) }}"></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="bg-light py-5 text-center">
                                <h3 class="text-uppercase">{{ $keywords['Cart_is_empty'] ?? __('Cart is empty') }}</h3>
                            </div>
                        @endif
                    </div>
                    <div class="btn-groups mt-30 justify-content-end">
                        <button id="cartUpdate" id="cartUpdate"
                            data-href="{{ route('front.user.cart.update', getParam()) }}"
                            class="olima_btn">{{ $keywords['Update'] ?? __('Update') }}
                            {{ $keywords['Cart'] ?? __('Cart') }}
                        </button>
                        <a href="{{ route('front.user.checkout', getParam()) }}"
                            class="olima_btn">{{ $keywords['Checkout'] ?? __('Checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End olima_cart section -->
@endsection
@section('script')
@endsection
