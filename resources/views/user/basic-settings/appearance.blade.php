@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Website_Appearance'] ?? __('Website Appearance') }}</h4>
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
                <a href="#">{{ $keywords['Website_Appearance'] ?? __('Website Appearance') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="ajaxForm" action="{{ route('user.basic_settings.update_appearance') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">
                                    {{ $keywords['Update_Website_Appearance'] ?? __('Update Website Appearance') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label>{{ $keywords['Primary_Color'] ?? __('Primary Color') }}*</label>
                                    <input class="jscolor form-control ltr" name="primary_color"
                                        value="{{ isset($data) ? $data->primary_color : '' }}">
                                    <p id="errprimary_color" class="em text-danger mb-0"></p>
                                </div>
                                 <input type="hidden" name="theme_version" value="{{$userBs->theme_version}}">

                                <div class="form-group">
                                    <label>{{ $keywords['Breadcrumb_Section_Overlay_Color'] ?? __('Breadcrumb Section Overlay Color') }}*</label>
                                    <input class="jscolor form-control ltr" name="breadcrumb_overlay_color"
                                        value="{{ isset($data) ? $data->breadcrumb_overlay_color : '' }}">
                                    <p id="errbreadcrumb_overlay_color" class="em text-danger mb-0"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Breadcrumb_Section_Overlay_Opacity'] ?? __('Breadcrumb Section Overlay Opacity') }}*</label>
                                    <input class="form-control ltr" type="number" step="0.01" min="0"
                                        max="1" name="breadcrumb_overlay_opacity"
                                        value="{{ $data->breadcrumb_overlay_opacity }}">
                                    <p id="errbreadcrumb_overlay_opacity" class="em text-danger mb-0"></p>
                                    <p class="mt-2 mb-0 text-warning">
                                        {{ $keywords['This_will_decide_the_transparency_level_of_the_overlay_color'] ?? __('This will decide the transparency level of the overlay color.') }}<br>
                                        {{ $keywords['Value_must_be_between_0_to_1'] ?? __('Value must be between 0 to 1.') }}<br>
                                        {{ $keywords['Transparency_level_will_be_lower_with_the_increment_of_the_value'] ?? __('Transparency level will be lower with the increment of the value.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" id="submitBtn" class="btn btn-success">
                                    {{ $keywords['Update'] ?? __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
