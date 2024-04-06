@extends('user-front.common.layout')
@section('pageHeading')
    {{ $keywords['myOrders'] ?? __('My Orders') }}
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
                        <h1>{{ $keywords['myOrders'] ?? __('My Orders') }}</h1>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">{{ $keywords['myOrders'] ?? __('My Orders') }}</li>
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
                                    <div class="title mb-2">
                                        <h4>{{ $keywords['myOrders'] ?? __('My Orders') }}</h4>
                                    </div>
                                    <div class="main-info">
                                        <div class="main-table">
                                            <div class="table-responsiv">
                                                <table id="order_table"
                                                    class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ $keywords['Order_Number'] ?? __('Order Number') }}</th>
                                                            <th>{{ $keywords['Date'] ?? __('Date') }}</th>
                                                            <th>{{ $keywords['Total'] ?? __('Total') }}</th>
                                                            <th>{{ $keywords['Status'] ?? __('Status') }}</th>
                                                            <th>{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($orders)
                                                            @foreach ($orders as $order)
                                                                <tr>
                                                                    <td>{{ $order->order_number }}</td>
                                                                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                                                    <td>{{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                                                                        {{ $order->total }}
                                                                        {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                                                                    </td>
                                                                    <td><span
                                                                            class="{{ $order->order_status }}">{{ $order->order_status }}</span>
                                                                    </td>
                                                                    <td><a href="{{ route('customer.orders-details', ['id' => $order->id, getParam()]) }}"
                                                                            class="btn base-bg">{{ $keywords['Details'] ?? __('Details') }}</a>
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
