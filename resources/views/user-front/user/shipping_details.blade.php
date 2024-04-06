@extends('user-front.common.layout')

@section('pageHeading')
    {{ $keywords['shipping_details'] ?? __('Shipping details') }}
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
                        <h1>{{ $keywords['shipping_details'] ?? __('Shipping details') }}</h1>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">{{ $keywords['shipping_details'] ?? __('Shipping details') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Breadcrumb Section -->


    <!--====== CHECKOUT PART START ======-->
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
                                        <h4>{{ $keywords['shipping_details'] ?? __('Shipping details') }}</h4>
                                    </div>
                                    <div class="edit-info-area">
                                        <form action="{{ route('customer.shipping-update', getParam()) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf

                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}"
                                                        name="shpping_fname"
                                                        value="{{ convertUtf8(Auth::guard('customer')->user()->shpping_fname) }}"
                                                        value="{{ Request::old('fname') }}">
                                                    @error('shpping_fname')
                                                        <p class="text-danger mb-4">{{ convertUtf8($message) }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}"
                                                        name="shpping_lname"
                                                        value="{{ convertUtf8(Auth::guard('customer')->user()->shpping_lname) }}"
                                                        value="{{ Request::old('fname') }}">
                                                    @error('shpping_lname')
                                                        <p class="text-danger mb-4">{{ convertUtf8($message) }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <input type="email" class="form_control"
                                                        placeholder="{{ $keywords['Email'] ?? __('Email') }}"
                                                        name="shpping_email"
                                                        value="{{ convertUtf8(Auth::guard('customer')->user()->shpping_email) }}">
                                                    @error('shpping_email')
                                                        <p class="text-danger mb-4">{{ convertUtf8($message) }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['Phone'] ?? __('Phone') }}"
                                                        name="shpping_number"
                                                        value="{{ convertUtf8(Auth::guard('customer')->user()->shpping_number) }}">
                                                    @error('shpping_number')
                                                        <p class="text-danger mb-4">{{ convertUtf8($message) }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['City'] ?? __('City') }}"
                                                        name="shpping_city"
                                                        value="{{ convertUtf8(Auth::guard('customer')->user()->shpping_city) }}">
                                                    @error('shpping_city')
                                                        <p class="text-danger mb-4">{{ convertUtf8($message) }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['State'] ?? __('State') }}"
                                                        name="shpping_state"
                                                        value="{{ convertUtf8(Auth::guard('customer')->user()->shpping_state) }}">
                                                    @error('shpping_state')
                                                        <p class="text-danger mb-4">{{ convertUtf8($message) }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['Country'] ?? __('Country') }}"
                                                        name="shpping_country"
                                                        value="{{ convertUtf8(Auth::guard('customer')->user()->shpping_country) }}">
                                                    @error('shpping_country')
                                                        <p class="text-danger mb-4">{{ convertUtf8($message) }}</p>
                                                    @enderror
                                                </div>


                                                <div class="col-lg-6 mb-3">
                                                    <textarea name="shpping_address" class="form_control" placeholder="{{ $keywords['Address'] ?? __('Address') }}">{{ convertUtf8(Auth::guard('customer')->user()->shpping_address) }}</textarea>
                                                    @error('shpping_address')
                                                        <p class="text-danger">{{ convertUtf8($message) }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-button">
                                                        <button type="submit"
                                                            class="btn btn-form">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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
