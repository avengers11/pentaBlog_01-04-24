@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Preferences'] ?? __('Preferences') }}</h4>
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
                <a href="#">{{ $keywords['Basic_Settings'] ?? __('Basic Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Preferences'] ?? __('Preferences') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ $keywords['Update_Preferences'] ?? __('Update Preferences') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-5 pb-4">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm" action="{{ route('user.basic_settings.update_preferences') }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>{{ $keywords['Hide_Profile'] ?? __('Hide Profile') }}
                                        ({{ $keywords['from_Everywhere'] ?? __('from Everywhere') }})</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="online_status" value="1"
                                                class="selectgroup-input" {{ $data->online_status == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="online_status" value="0"
                                                class="selectgroup-input" {{ $data->online_status != 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Hide_Profile'] ?? __('Hide Profile') }}
                                        ({{ $keywords['from_Listing_Page'] ?? __('from Listing Page') }})</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="listing_page" value="1"
                                                class="selectgroup-input" {{ $data->listing_page == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="listing_page" value="0"
                                                class="selectgroup-input" {{ $data->listing_page != 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" id="submitBtn" form="ajaxForm" class="btn btn-success">
                                {{ $keywords['Update'] ?? __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
