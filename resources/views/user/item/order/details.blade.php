@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Order_Details'] ?? __('Order Details') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user-dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="{{ url()->previous() }}">{{ $keywords['Order'] ?? __('Order') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Order_Details'] ?? __('Order Details') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Order'] ?? __('Order') }} [
                        {{ $order->order_number }} ]</div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="payment-information">
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Payment_Status'] ?? __('Payment Status') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                @if ($order->payment_status == 'Pending' || $order->payment_status == 'pending')
                                    <span class="badge badge-danger">{{ convertUtf8($order->payment_status) }} </span>
                                @else
                                    <span class="badge badge-success">{{ convertUtf8($order->payment_status) }} </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Order_Status'] ?? __('Order Status') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                @if ($order->order_status == 'Pending')
                                    <span class="badge badge-warning">{{ convertUtf8($order->order_status) }} </span>
                                @elseif ($order->order_status == 'processing')
                                    <span class="badge badge-primary">{{ convertUtf8($order->order_status) }} </span>
                                @elseif ($order->order_status == 'completed')
                                    <span class="badge badge-success">{{ convertUtf8($order->order_status) }} </span>
                                @elseif ($order->order_status == 'rejected')
                                    <span class="badge badge-danger">{{ convertUtf8($order->order_status) }} </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Shipping_Method'] ?? __('Shipping Method') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $order->shipping_method }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Cart_Total'] ?? __('Cart Total') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                {{ $order->cart_total }}
                                {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong class="text-success">{{ $keywords['Discount'] ?? __('Discount') }}
                                    <span style="font-size: 10px;">(<i class="fas fa-minus"></i>)</span> :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                {{ $order->discount }}
                                {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Subtotal'] ?? __('Subtotal') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                {{ round($order->cart_total - $order->discount, 2) }}
                                {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong class="text-danger">{{ $keywords['Shipping_Charge'] ?? __('Shipping Charge') }}
                                    <span style="font-size: 10px;">(<i class="fas fa-plus"></i>)</span> :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                {{ $order->shipping_charge }}
                                {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong class="text-danger">{{ $keywords['Tax'] ?? __('Tax') }} ({{ @$userBs->tax }}%)
                                    <span style="font-size: 10px;">(<i class="fas fa-plus"></i>)</span> :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                {{ $order->tax }}
                                {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Total'] ?? __('Total') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                {{ $order->total }}
                                {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Payment_Method'] ?? __('Payment Method') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->method) }}
                            </div>
                        </div>


                        <div class="row mb-0">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Order_Date'] ?? __('Order Date') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->created_at->format('d-m-Y')) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Shipping_Details'] ?? __('Shipping Details') }}
                    </div>

                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="payment-information">
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Email'] ?? __('Email') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->shpping_email) }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Phone'] ?? __('Phone') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $order->shpping_number }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['City'] ?? __('City') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->shpping_city) }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Address'] ?? __('Address') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->shpping_address) }}
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Country'] ?? __('Country') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->billing_country) }}
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Billing_Details'] ?? __('Billing Details') }}
                    </div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="payment-information">
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Email'] ?? __('Email') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->billing_email) }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Phone'] ?? __('Phone') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $order->billing_number }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['City'] ?? __('City') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->billing_city) }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Address'] ?? __('Address') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->billing_address) }}
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-lg-6">
                                <strong>{{ $keywords['Country'] ?? __('Country') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ convertUtf8($order->billing_country) }}
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Order Item(s)</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive product-list">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Details') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderitems as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><img src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->image) }}"
                                                alt="product" width="100"></td>
                                        <td>{{ convertUtf8($item->title) }}</td>
                                        <td>
                                            <b>{{ __('Quantity') }}:</b> <span>{{ $item->qty }}</span><br>
                                        </td>
                                        <td>{{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ $item->price }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                        </td>
                                        <td>{{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ $item->price * $item->qty }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
