@extends('user.layout')

@php
    $default = \App\Models\User\Language::where('is_default', 1)->where('user_id', Auth::user()->id)->first();
@endphp

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ $keywords['Popup_Type'] ?? __('Popup Type') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('user-dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $keywords['Announcement_Popups'] ?? __('Announcement Popups') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $keywords['Popup_Type'] ?? __('Popup Type') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">{{ $keywords['Select_Popup_Type'] ?? __('Select Popup Type') }}</div>
            </div>

            <div class="col-lg-4 mt-2 mt-lg-0">
              <a class="btn btn-info btn-sm float-right d-inline-block text-dark" href="{{ route('user.announcement_popups',['language' => request('language')]) }}">
                <span class="btn-label">
                  <i class="fas fa-backward"></i>
                </span>
                {{ $keywords['Back'] ?? __('Back') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="popup-type">
    <div class="row">
      <div class="col-lg-3">
        <a href="{{ route('user.announcement_popups.create_popup', ['type' => 1, 'language' => request('language')]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/user/dashboard/img/popup-samples/1.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ $keywords['Type_1'] ?? __('Type - 1') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('user.announcement_popups.create_popup', ['type' => 2, 'language' => request('language')]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/user/dashboard/img/popup-samples/2.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ $keywords['Type_2'] ?? __('Type - 2') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('user.announcement_popups.create_popup', ['type' => 3, 'language' => request('language')]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/user/dashboard/img/popup-samples/3.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ $keywords['Type_3'] ?? __('Type - 3') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('user.announcement_popups.create_popup', ['type' => 4, 'language' => request('language')]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/user/dashboard/img/popup-samples/4.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ $keywords['Type_4'] ?? __('Type - 4') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('user.announcement_popups.create_popup', ['type' => 5, 'language' => request('language')]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/user/dashboard/img/popup-samples/5.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ $keywords['Type_5'] ?? __('Type - 5') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('user.announcement_popups.create_popup', ['type' => 6, 'language' => request('language')]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/user/dashboard/img/popup-samples/6.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ $keywords['Type_6'] ?? __('Type - 6') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('user.announcement_popups.create_popup', ['type' => 7, 'language' => request('language')]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/user/dashboard/img/popup-samples/7.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ $keywords['Type_7'] ?? __('Type - 7') }}</h5>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
@endsection
