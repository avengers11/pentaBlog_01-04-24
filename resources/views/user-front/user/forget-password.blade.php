@extends('user-front.common.layout')

@section('pageHeading')
  {{$keywords['Forget_Password'] ?? __('Forget Password') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->meta_description_forget_password	 : '')
@section('meta-keywords', !empty($seo) ? $seo->meta_keyword_forget_password	 : '')

@section('content')
  <!-- Start Olima Breadcrumb Section -->
  <section class="olima_breadcrumb bg_image lazy" @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
    <div class="bg_overlay" style="background: #{{$websiteInfo->breadcrumb_overlay_color}}; opacity: {{$websiteInfo->breadcrumb_overlay_opacity}}"></div>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="breadcrumb-title">
            <h1>{{$keywords['Forget_Password'] ?? __('Forget Password') }}</h1>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="breadcrumb-link">
            <ul>
              <li class="text-uppercase"><a href="{{route('front.user.detail.view', getParam())}}">{{$keywords['Home'] ?? __('Home') }}</a></li>
              <li class="active text-uppercase">{{$keywords['Forget_Password'] ?? __('Forget Password') }}</li>
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
            <form action="{{ route('customer.send_forget_password_mail', getParam()) }}" method="POST">
              @csrf
              <div class="form_group">
                <label>{{$keywords['Email_Address'] ? $keywords['Email_Address'] . '*' : __('Email Address') . '*' }}</label>
                <input type="email" class="form_control" name="email" value="{{ old('email') }}">
                @error('email')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div class="form_group">
                <button type="submit" class="btn">{{$keywords['Proceed'] ?? __('Proceed') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End User Login Section -->
@endsection
