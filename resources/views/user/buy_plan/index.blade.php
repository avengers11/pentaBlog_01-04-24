@extends('user.layout')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buy_plan.css') }}">
@endsection

@php
use App\Http\Helpers\UserPermissionHelper;
$user = Auth::guard('web')->user();
$package = UserPermissionHelper::currentPackagePermission($user->id);
@endphp

@section('content')
    @if (is_null($package))
        <div class="alert alert-warning">
            {{ $keywords['membership_expired_text'] ?? __('Your membership is expired. Please purchase a new package / extend the current package') }}
        </div>
    @else
        <div class="row justify-content-center align-items-center mb-1">
            <div class="col-12">
                <div class="alert border-left border-primary text-dark">
                    @if ($package_count >= 2)
                        @if ($next_membership->status == 0)
                            <strong
                                class="text-danger">{{ $keywords['buy_plan_approve_reject_text'] ?? __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken') }}</strong><br>
                        @elseif ($next_membership->status == 1)
                            <strong
                                class="text-danger">{{ $keywords['another_package_activate_msg'] ?? __('You have another package to activate after the current package expires. You cannot purchase / extend any package, until the next package is activated') }}</strong><br>
                        @endif
                    @endif

                    <strong>{{ $keywords['Current_Package'] ?? __('Current_Package') }}: </strong>
                    {{ $current_package->title }}
                    <span class="badge badge-secondary">{{ $current_package->term }}</span>
                    @if ($current_membership->is_trial == 1)
                        ({{ __('Expire Date') }}:
                        {{ Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
                        <span class="badge badge-primary">{{ $keywords['Trial'] ?? __('Trial') }}</span>
                    @else
                        ({{ __('Expire Date') }}:
                        {{ $current_package->term === 'lifetime' ? __('Lifetime') : Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
                    @endif

                    @if ($package_count >= 2)
                        <div>
                            <strong>{{ $keywords['Next_Package_To_Activate'] ?? __('Next Package To Activate') }} :
                            </strong> {{ $next_package->title }} <span
                                class="badge badge-secondary">{{ $next_package->term }}</span>
                            @if ($current_package->term != 'lifetime' && $current_membership->is_trial != 1)
                                (
                                {{ $keywords['Activation_Date'] ?? __('Activation Date') }}:
                                {{ Carbon\Carbon::parse($next_membership->start_date)->format('M-d-Y') }},
                                {{ $keywords['Expire_Date'] ?? __('Expire Date') }}:
                                {{ $next_package->term === 'lifetime' ? __('Lifetime') : Carbon\Carbon::parse($next_membership->expire_date)->format('M-d-Y') }})
                            @endif
                            @if ($next_membership->status == 0)
                                <span
                                    class="badge badge-warning">{{ $keywords['Decision_Pending'] ?? __('Decision Pending') }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    <div class="row mb-5 justify-content-center">
        @foreach ($packages as $key => $package)
            <div class="col-md-3 pr-md-0 mb-5">
                <div class="card-pricing2 @if (isset($current_package->id) && $current_package->id === $package->id) card-success @else card-primary @endif">
                    <div class="pricing-header">
                        <h3 class="fw-bold d-inline-block">
                            {{ $package->title }}
                        </h3>
                        @if (isset($current_package->id) && $current_package->id === $package->id)
                            <h3 class="badge badge-danger d-inline-block float-right ml-2">{{ __('Current') }}</h3>
                        @endif
                        @if ($package_count >= 2 && $next_package->id == $package->id)
                            <h3 class="badge badge-warning d-inline-block float-right ml-2">
                                {{ $keywords['Next'] ?? __('Next') }}</h3>
                        @endif
                        <span class="sub-title"></span>
                    </div>
                    <div class="price-value">
                        <div class="value">
                            <span
                                class="amount">{{ $package->price == 0 ? __('Free') : format_price($package->price) }}</span>
                            <span class="month">/{{ $package->term }}</span>
                        </div>
                    </div>
                    <ul class="pricing-content">
                        @foreach (json_decode($package->features) as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                        <li>{{ $package->feature_posts_limit === 999999 ? __('Unlimited') : $package->feature_posts_limit }}
                            {{ $keywords['features_posts'] ?? __('features posts') }}</li>
                        <li>{{ $package->posts_limit === 999999 ? __('Unlimited') : $package->posts_limit }}
                            {{ $keywords['Posts'] ?? __('posts') }}</li>
                        <li>{{ $package->post_categories_limit === 999999 ? __('Unlimited') : $package->post_categories_limit }}
                            {{ $keywords['Post_Categories'] ?? __('Post Categories') }}
                        </li>
                        <li>{{ $package->language_limit === 999999 ? __('Unlimited') : $package->language_limit }}
                            {{ $keywords['Languages'] ?? __('languages') }}</li>
                    </ul>

                    @if ($package_count < 2)
                        <div class="px-4">
                            @if (isset($current_package->id) && $current_package->id === $package->id)
                                @if ($package->term != 'lifetime' || $current_membership->is_trial == 1)
                                    <a href="{{ route('user.plan.extend.checkout', $package->id) }}"
                                        class="btn btn-success btn-lg w-75 fw-bold mb-3">{{ $keywords['Extend'] ?? __('Extend') }}</a>
                                @endif
                            @else
                                <a href="{{ route('user.plan.extend.checkout', $package->id) }}"
                                    class="btn btn-primary btn-block btn-lg fw-bold mb-3">{{ $keywords['Buy_Now'] ?? __('Buy Now') }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
