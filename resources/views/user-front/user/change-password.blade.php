@extends('user-front.common.layout')

@section('pageHeading')
  {{$keywords['Change_Password'] ?? __('Change Password') }}
@endsection

@section('content')
  <!-- Start Olima Breadcrumb Section -->
  <section class="olima_breadcrumb bg_image lazy" @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
    <div class="bg_overlay" style="background: #{{$websiteInfo->breadcrumb_overlay_color}}; opacity: {{$websiteInfo->breadcrumb_overlay_opacity}}"></div>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="breadcrumb-title">
            <h1>{{$keywords['Change_Password'] ?? __('Change Password') }}</h1>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="breadcrumb-link">
            <ul>
              <li class="text-uppercase"><a href="{{route('front.user.detail.view', getParam())}}">{{$keywords['Home'] ?? __('Home') }}</a></li>
              <li class="active text-uppercase">{{$keywords['Change_Password'] ?? __('Change Password') }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Olima Breadcrumb Section -->

  <!-- Start Change Password Section -->
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
                    <h4>{{$keywords['Change_Password'] ?? __('Change Password') }}</h4>
                  </div>

                  <div class="edit-info-area">
                    <form action="{{ route('customer.update_password', getParam()) }}" method="POST">
                      @csrf
                      <div class="row">
                        <div class="col-lg-12">
                          <input type="password" class="form_control" placeholder="{{$keywords['Current_Password'] ?? __('Current Password') }}" name="current_password">
                          @error('current_password')
                            <p class="mb-3 text-danger">{{ $message }}</p>
                          @enderror
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-12">
                          <input type="password" class="form_control" placeholder="{{$keywords['New_Password'] ?? __('New Password') }}" name="new_password">
                          @error('new_password')
                            <p class="mb-3 text-danger">{{ $message }}</p>
                          @enderror
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-12">
                          <input type="password" class="form_control" placeholder="{{$keywords['Confirm_New_Password'] ?? __('Confirm New Password') }}" name="new_password_confirmation">
                          @error('new_password_confirmation')
                            <p class="mb-3 text-danger">{{ $message }}</p>
                          @enderror
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-button">
                            <button class="btn">{{$keywords['Submit'] ?? __('Submit') }}</button>
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
  <!-- End Change Password Section -->
@endsection
