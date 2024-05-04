@extends('user-front.common.layout')

@section('pageHeading')
    {{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}
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
                        <h1>{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</h1>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="breadcrumb-link">
                        <ul>
                            <li class="text-uppercase"><a
                                    href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a>
                            </li>
                            <li class="active text-uppercase">{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Olima Breadcrumb Section -->

    <!-- Start User Edit-Profile Section -->
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
                                        <h4>{{ $keywords['Edit_Your_Profile'] ?? __('Edit Your Profile') }}</h4>
                                    </div>

                                    <div class="edit-info-area">
                                        <form action="{{ route('customer.update_profile', getParam()) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="upload-img">
                                                <div class="img-box">
                                                    <img data-src="{{ $authUser->image != null ? Storage::url($authUser->image) : asset('assets/user/img/profile.jpg') }}"
                                                        alt="user image" class="user-photo lazy">
                                                </div>

                                                <div class="file-upload-area">
                                                    <div class="upload-file">
                                                        <input type="file" accept=".jpg, .jpeg, .png" name="image"
                                                            class="upload">
                                                        <span>{{ $keywords['Upload'] ?? __('Upload') }}</span>
                                                    </div>
                                                </div>
                                                @error('image')
                                                    <p class="mb-3 text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}"
                                                        name="first_name" value="{{ $authUser->first_name }}">
                                                    @error('first_name')
                                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}"
                                                        name="last_name" value="{{ $authUser->last_name }}">
                                                    @error('last_name')
                                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6">
                                                    <input type="email" class="form_control"
                                                        placeholder="{{ $keywords['Email'] ?? __('Email') }}"
                                                        value="{{ $authUser->email }}" readonly>
                                                </div>

                                                <div class="col-lg-6">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['Phone'] ?? __('Phone') }}"
                                                        name="contact_number" value="{{ $authUser->contact_number }}">
                                                    @error('contact_number')
                                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6">
                                                    <textarea class="form_control" placeholder="{{ $keywords['Address'] ?? __('Address') }}" name="address">{{ $authUser->address }}</textarea>
                                                    @error('address')
                                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['City'] ?? __('City') }}" name="city"
                                                        value="{{ $authUser->city }}">
                                                    @error('city')
                                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['State'] ?? __('State') }}"
                                                        name="state" value="{{ $authUser->state }}">
                                                    @error('state')
                                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form_control"
                                                        placeholder="{{ $keywords['Country'] ?? __('Country') }}"
                                                        name="country" value="{{ $authUser->country }}">
                                                    @error('country')
                                                        <p class="mb-3 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-button">
                                                        <button
                                                            class="btn form-btn">{{ $keywords['Update_Profile'] ?? 'Update profile' }}</button>
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
    <!-- End User Edit-Profile Section -->
@endsection
