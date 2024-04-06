@extends('user-front.common.layout')

@section('pageHeading')
  {{$keywords['Reset_Password'] ?? __('Reset Password')}}
@endsection

@section('content')
  <!-- Start Olima Breadcrumb Section -->
  <section class="olima_breadcrumb bg_image lazy" @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
    <div class="bg_overlay" style="background: #{{$websiteInfo->breadcrumb_overlay_color}}; opacity: {{$websiteInfo->breadcrumb_overlay_opacity}}"></div>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="breadcrumb-title">
            <h1>{{$keywords['Reset_Password'] ?? __('Reset Password')}}</h1>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="breadcrumb-link">
            <ul>
              <li class="text-uppercase"><a href="{{route('front.user.detail.view', getParam())}}">{{$keywords['Home'] ?? __('Home') }}</a></li>
              <li class="active text-uppercase">{{$keywords['Reset_Password'] ?? __('Reset Password')}}</li>
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
            <form action="{{ route('customer.reset_password_submit', getParam()) }}" method="POST">
              @csrf
              <div class="form_group">
                <label>{{$keywords['New_Password'] ? $keywords['New_Password'] . '*' : __('New Password') . '*' }}</label>
                <input type="password" class="form_control" name="new_password">
                @error('new_password')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div class="form_group">
                <label>{{$keywords['Confirm_New_Password'] ? $keywords['Confirm_New_Password'] . '*' : __('Confirm New Password') . '*' }}</label>
                <input type="password" class="form_control" name="new_password_confirmation">
                @error('new_password_confirmation')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <div class="form_group">
                <button type="submit" class="btn">{{ $keywords['Submit'] ?? __('Submit') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End User Login Section -->
@endsection
