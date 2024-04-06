@extends('user-front.common.layout')

@section('pageHeading')
    {{ $keywords['Signup'] ?? __('Signup') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_signup : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_signup : '')

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
                        <h1>{{ $keywords['Signup'] ?? __('Signup') }}</h1>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">{{ $keywords['Signup'] ?? __('Signup') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Breadcrumb Section -->

    <!-- Start User Signup Section -->
    <section class="user-dashboard">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-content">
                        @if (Session::has('sendmail'))
                            <div class="alert alert-success mb-4">
                                <p>{{ __(Session::get('sendmail')) }}</p>
                            </div>
                        @endif
                        <form action="{{ route('customer.signup.submit', getParam()) }}" method="POST">
                            @csrf
                            <div class="form_group">
                                <label>{{ $keywords['Username'] ?? 'Username' }} **</label>
                                <input type="text" class="form_control" name="username" value="{{ old('username') }}">
                                @error('username')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form_group">
                                <label>{{ $keywords['Email_Address'] ?? 'Email Address' }} **</label>
                                <input type="email" class="form_control" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form_group">
                                <label>{{ $keywords['Password'] ?? 'Password' }} **</label>
                                <input type="password" class="form_control" name="password" value="{{ old('password') }}">
                                @error('password')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form_group">
                                <label>{{ $keywords['Confirm_Password'] ?? 'Confirm Password' }} **</label>
                                <input type="password" class="form_control" name="password_confirmation"
                                    value="{{ old('password_confirmation') }}">
                                @error('password_confirmation')
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
                                <button type="submit" class="btn">{{ $keywords['Signup'] ?? __('Signup') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End User Signup Section -->
@endsection
