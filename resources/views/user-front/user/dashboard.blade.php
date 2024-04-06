@extends('user-front.common.layout')

@section('pageHeading')
    {{ $keywords['Dashboard'] ?? __('Dashboard') }}
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
                        <h1>{{ $keywords['Dashboard'] ?? __('Dashboard') }}</h1>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">{{ $keywords['Dashboard'] ?? __('Dashboard') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Breadcrumb Section -->

    <!-- Start User Dashboard Section -->
    <section class="user-dashboard">
        <div class="container">
            <div class="row">
                @includeIf('user-front.user.side-navbar')
                <div class="col-lg-9">
                    <div class="row mb-5">
                        <div class="col-lg-12">
                            <div class="user-profile-details">
                                <div class="account-info">
                                    <div class="title">
                                        <h4>{{ $keywords['Account_Information'] ?? __('Account Information') }}</h4>
                                    </div>
                                    <div class="main-info">
                                        <ul class="list">
                                            @if ($authUser->first_name != null && $authUser->last_name != null)
                                                <li><span>{{ $keywords['Name'] ?? __('Name') }}:</span>
                                                </li>
                                            @endif
                                            <li><span>{{ $keywords['Username'] ?? __('Username') }}:</span>
                                            </li>
                                            <li><span>{{ $keywords['Email'] ?? __('Email') }}:</span>
                                            </li>
                                            @if ($authUser->contact_number != null)
                                                <li><span>{{ $keywords['Phone'] ?? __('Phone') }}:</span>
                                                </li>
                                            @endif
                                            @if ($authUser->address != null)
                                                <li><span>{{ $keywords['Address'] ?? __('Address') }}:</span>
                                                </li>
                                            @endif
                                            @if ($authUser->city != null)
                                                <li><span>{{ $keywords['City'] ?? __('City') }}:</span>
                                                </li>
                                            @endif
                                            @if ($authUser->state != null)
                                                <li><span>{{ $keywords['State'] ?? __('State') }}:</span>
                                                </li>
                                            @endif
                                            @if ($authUser->country != null)
                                                <li><span>{{ $keywords['Country'] ?? __('Country') }}:</span>
                                                </li>
                                            @endif
                                        </ul>
                                        <ul class="list">
                                            @if ($authUser->first_name != null && $authUser->last_name != null)
                                                <li>{{ $authUser->first_name . ' ' . $authUser->last_name }}</li>
                                            @endif
                                            <li>{{ $authUser->username }}</li>
                                            <li>{{ $authUser->email }}</li>
                                            @if ($authUser->contact_number != null)
                                                <li>{{ $authUser->contact_number }}</li>
                                            @endif
                                            @if ($authUser->address != null)
                                                <li>{{ $authUser->address }}</li>
                                            @endif
                                            @if ($authUser->city != null)
                                                <li>{{ $authUser->city }}</li>
                                            @endif
                                            @if ($authUser->state != null)
                                                <li>{{ $authUser->state }}</li>
                                            @endif
                                            @if ($authUser->country != null)
                                                <li>{{ $authUser->country }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('customer.my_bookmarks', getParam()) }}">
                                <div class="card card-box box-1 mb-5">
                                    <div class="card-info">
                                        <h5>{{ $keywords['Total_Bookmarks'] ?? __('Total Bookmarks') }}</h5>
                                        <p>{{ $totalBookmarks }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @php
                            $shopSettings = App\Models\User\UserShopSetting::where('user_id', $user->id)->first();
                        @endphp
                        @if (isset($shopSettings) && $shopSettings->is_shop == 1)
                            <div class="col-md-6">
                                <a href="{{ route('customer.orders', getParam()) }}">
                                    <div class="card card-box box-2 mb-5">
                                        <div class="card-info">
                                            <h5>{{ $keywords['Orders'] ?? __('Orders') }}</h5>
                                            @php
                                                try {
                                                    $orders = \App\Models\User\UserOrder::where('customer_id', Auth::guard('customer')->user()->id)
                                                        ->orderBy('id', 'DESC')
                                                        ->count();
                                                } catch (\Throwable $th) {
                                                    $orders = 0;
                                                }
                                            @endphp
                                            <p>{{ @$orders }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('customer.wishlist', getParam()) }}">
                                    <div class="card card-box box-3 mb-5">
                                        @php
                                        try {
                                            $wishLists = \App\Models\User\CustomerWishList::where('customer_id', Auth::guard('customer')->user()->id)
                                                ->with('item.itemContents')
                                                ->orderBy('id', 'DESC')
                                                ->count();
                                        } catch (\Throwable $th) {
                                            $wishLists = 0;
                                        }

                                        @endphp
                                        <div class="card-info">
                                            <h5>{{ $keywords['Wishlists'] ?? __('Wishlists') }}</h5>
                                            <p>{{ $wishLists }}</p>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End User Dashboard Section -->
@endsection
