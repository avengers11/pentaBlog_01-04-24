@extends('user-front.common.layout')
@section('meta-description', !empty($seo) ? $seo->meta_description_contact : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_contact : '')
@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->contact_me_title }}
    @else
     {{$keywords['Contact'] ?? 'Contact' }}
    @endif
@endsection

@section('content')
    <!-- Start Olima Breadcrumb Section -->
    <section class="olima_breadcrumb bg_imag lazy"
        @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
        <div class="bg_overlay"
            style="background: #{{ $websiteInfo->breadcrumb_overlay_color }}; opacity: {{ $websiteInfo->breadcrumb_overlay_opacity }}">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="breadcrumb-title">
                        <h1>{{ !empty($pageHeading) ? $pageHeading->contact_me_title : 'Contact' }}</h1>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">
                                {{ !empty($pageHeading) ? $pageHeading->contact_me_title : 'Contact' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Breadcrumb Section -->

    <!-- Start Olima Map Section -->
    @if (!empty($mapInfo->latitude) && !empty($mapInfo->longitude))
        <section class="contact_map pt-150">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="map_box">
                            <iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0"
                                marginwidth="0"
                                src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $mapInfo->latitude }},%20{{ $mapInfo->longitude }}+({{ $websiteInfo->website_title }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- End Olima Map Section -->

    <!-- Start Olima Contact Section -->
    <section class="olima_contact contact_v1 pt-140 pb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="contact_information">
                        <div class="title">
                            <h3>{{ $keywords['Get_In_Touch'] ?? __('Get In Touch') }}</h3>
                        </div>

                        @isset($websiteInfo->address)
                            <div class="info_box">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $keywords['Address'] ?? __('Address') }}</span>
                                <h5>{{ $websiteInfo->address }}</h5>
                            </div>
                        @endisset

                        @isset($websiteInfo->support_email)
                            <div class="info_box">
                                <span><i class="fas fa-envelope"></i> {{ $keywords['Email'] ?? __('Email') }}</span>
                                <h5>
                                    <a href="{{ 'mailto:' . $websiteInfo->support_email }}">
                                        {{ $websiteInfo->support_email }}
                                    </a>
                                </h5>
                            </div>
                        @endisset

                        @isset($websiteInfo->support_contact)
                            <div class="info_box">
                                <span><i class="fas fa-phone"></i> {{ $keywords['Phone'] ?? __('Phone') }}</span>
                                <h5>
                                    <a href="{{ 'tel:' . $websiteInfo->support_contact }}">
                                        {{ $websiteInfo->support_contact }}
                                    </a>
                                </h5>
                            </div>
                        @endisset

                        @if (count($socialLinkInfos) > 0)
                            <div class="social_box">
                                <ul>
                                    @foreach ($socialLinkInfos as $socialLink)
                                        <li><a href="{{ $socialLink->url }}" target="_blank"><i
                                                    class="{{ $socialLink->icon }}"></i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="contact-form">
                        <h3>{{ $keywords['Send_Message'] ?? __('Send Message') }}</h3>
                        <form action="{{ route('front.contact.message', getParam()) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form_group">
                                        <label>{{ $keywords['First_Name'] ?? __('First Name') }}</label>
                                        <input type="text" class="form_control" name="first_name"
                                            value="{{ old('first_name') }}">
                                        @error('first_name')
                                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form_group">
                                        <label>{{ $keywords['Last_Name'] ?? __('Last Name') }}</label>
                                        <input type="text" class="form_control" name="last_name"
                                            value="{{ old('last_name') }}">
                                        @error('last_name')
                                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form_group">
                                        <label>{{ $keywords['Email_Address'] ?? __('Email Address') }}</label>
                                        <input type="email" class="form_control" name="email"
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form_group">
                                        <label>{{ $keywords['Email_Subject'] ?? __('Email Subject') }}</label>
                                        <input type="text" class="form_control" name="subject"
                                            value="{{ old('subject') }}">
                                        @error('subject')
                                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form_group">
                                        <label>{{ $keywords['Message'] ?? __('Message') }}</label>
                                        <textarea class="form_control" name="message">{{ old('message') }}</textarea>
                                        @error('message')
                                            <p class="mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @if ($userBs->is_recaptcha == 1)
                                    <div class="col-lg-12">
                                        <div class="form_group">
                                            {!! NoCaptcha::renderJs() !!}
                                            {!! NoCaptcha::display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                @php
                                                    $errmsg = $errors->first('g-recaptcha-response');
                                                @endphp
                                                <p class="text-danger mb-0 mt-2">{{ __("$errmsg") }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12">
                                    <div class="form_button">
                                        <button class="olima_btn">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Contact Section -->

@endsection
