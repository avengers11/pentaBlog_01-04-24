@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Customer_Details'] ?? __('Customer Details') }}</h4>
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
                <a href="#">{{ $keywords['Registered_Users'] ?? __('Registered User') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Customer_Details'] ?? __('Customer Details') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="h4 card-title">{{ $keywords['Profile_Picture'] ?? __('Profile Picture') }}</div>
                        </div>

                        <div class="card-body text-center py-4">
                            <img src="{{ is_null($userInfo->image) ? asset('assets/user/img/profile.jpg') : asset('assets/user/img/users/' . $userInfo->image) }}"
                                alt="user image" width="150">
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="h4 card-title">{{ $keywords['Customer_Details'] ?? __('Customer Details') }}</div>
                        </div>

                        <div class="card-body">
                            <div class="payment-information">
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ $keywords['Name'] ?? __('Name') }}:</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->first_name . ' ' . $userInfo->last_name }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ $keywords['Username'] ?? __('Username') }}:</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->username }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ $keywords['Email'] ?? __('Email') }}:</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->email }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ $keywords['Phone'] ?? __('Phone') }}:</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->contact_number }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ $keywords['Address'] ?? __('Address') }}:</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->address }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ $keywords['City'] ?? __('City') }}:</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->city }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ $keywords['State'] ?? __('State') }}:</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->state }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <strong>{{ $keywords['Country'] ?? __('Country') }}:</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->country }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
