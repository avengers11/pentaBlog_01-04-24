@extends('user-front.common.layout')

@section('pageHeading')
    {{ $keywords['Login'] ?? __('Login') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_login : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_login : '')

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
                        <h1>{{ $keywords['Login'] ?? __('Login') }}</h1>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">{{ $keywords['Login'] ?? __('Login') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Breadcrumb Section -->

    <!-- Start User Login Section -->
    <section class="user-dashboard">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-content">
                        <form action="{{ route('customer.login_submit', getParam()) }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div class="form_group">
                                <label>{{ $keywords['Email_Address'] ? $keywords['Email_Address'] . '*' : __('Email Address') . '*' }}</label>
                                <input type="email" class="form_control" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form_group">
                                <label>{{ $keywords['Password'] ? $keywords['Password'] . '*' : __('Password') . '*' }}</label>
                                <input type="password" class="form_control" name="password" value="{{ old('password') }}">
                                @error('password')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            @if ($userBs->is_recaptcha == 1)
                                <div class="form-group">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                    @if ($errors->has('g-recaptcha-response'))
                                        @php
                                            $errmsg = $errors->first('g-recaptcha-response');
                                        @endphp
                                        <p class="text-danger mb-0 mt-2">{{ __("$errmsg") }}</p>
                                    @endif
                                </div>
                            @endif
                            <div class="form_group">
                                <button type="submit" class="btn">{{ $keywords['Login'] ?? __('Login') }}</button>
                                <a href="{{ route('customer.forget_password', getParam()) }}"
                                    class="link">{{ $keywords['Lost_your_password'] ? $keywords['Lost_your_password'] . '?' : __('Lost your password') . '?' }}</a>

                                <a href="{{ route('customer.signup', getParam()) }}"
                                    class="link float-right"><u>{{ $keywords['Signup'] ? $keywords['Signup'] : __('Signup') }}</u></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End User Login Section -->
@endsection
