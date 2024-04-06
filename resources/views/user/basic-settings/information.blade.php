@extends('user.layout')
@php

    $user = Auth::guard('web')->user();
    $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
    if (!empty($user)) {
        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
        $permissions = json_decode($permissions, true);
        $userBs = \App\Models\User\BasicSetting::where('user_id', $user->id)->first();
    }
@endphp
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['General_Settings'] ?? __('General Settings') }}</h4>
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
                <a href="#">{{ $keywords['General_Settings'] ?? __('General Settings') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="ajaxForm" action="{{ route('user.basic_settings.update_info') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">
                                    {{ $keywords['Update_General_Settings'] ?? __('Update General Settings') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body py-5">
                        <div class="row">
                            <div class="col-lg-10 mx-auto">
                                <h3 class="text-warning">
                                    {{ $keywords['Information'] ?? __('Information') }}</h3>
                                <hr class="divider">
                            </div>
                            <div class="col-lg-10 mx-auto">
                                <div class="row ">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ $keywords['Website_Title'] ?? __('Website Title') }}</label>
                                            <input type="text" class="form-control" name="website_title"
                                                value="{{ isset($data->website_title) ? $data->website_title : '' }}"
                                                placeholder="{{ $keywords['Enter_Website_Title'] ?? __('Enter Website Title') }}">
                                            <p id="errwebsite_title" class="em text-danger mb-0"></p>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ $keywords['Email'] ?? __('Email') }}</label>
                                            <input type="email" class="form-control ltr" name="support_email"
                                                value="{{ isset($data->support_email) ? $data->support_email : '' }}"
                                                placeholder="{{ $keywords['Enter_Email_Address'] ?? __('Enter Email Address') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{ $keywords['Phone'] ?? __('Phone') }}</label>
                                            <input type="text" class="form-control" name="support_contact"
                                                value="{{ isset($data->support_contact) ? $data->support_contact : '' }}"
                                                placeholder="{{ $keywords['Enter_Contact_Number'] ?? __('Enter Contact Number') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{ $keywords['Address'] ?? __('Address') }}</label>
                                            <input type="text" class="form-control" name="address"
                                                value="{{ isset($data->address) ? $data->address : '' }}"
                                                placeholder="{{ $keywords['Enter_Address'] ?? __('Enter Address') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ $keywords['Latitude'] ?? __('Latitude') }}</label>
                                            <input type="text" class="form-control" name="latitude"
                                                value="{{ isset($data->latitude) ? $data->latitude : '' }}"
                                                placeholder="{{ $keywords['Enter_Latitude'] ?? __('Enter Latitude') }}">
                                            <p class="mt-2 mb-0 text-warning">
                                                {{ $keywords['Latitude_text'] ?? __('The value of the latitude will be helpful to show your location in the map.') }}
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ $keywords['Longitude'] ?? __('Longitude') }}</label>
                                            <input type="text" class="form-control" name="longitude"
                                                value="{{ isset($data->longitude) ? $data->longitude : '' }}"
                                                placeholder="{{ $keywords['Enter_longitude'] ?? __('Enter longitude') }}">
                                            <p class="mt-2 mb-0 text-warning">
                                                {{ $keywords['Longitude_text'] ?? __('The value of the longitude will be helpful to show your location in the map.') }}
                                            </p>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        @if (!empty($permissions) && in_array('Ecommerce', $permissions))
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="form-group">
                                        <br>
                                        <h3 class="text-warning">
                                            {{ $keywords['Currency_Settings'] ?? __('Currency Settings') }}</h3>
                                        <hr class="divider">
                                    </div>
                                </div>
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">

                                                <label>{{ $keywords['Base_Currency_Symbol'] ?? __('Base Currency Symbol') }}
                                                    **</label>
                                                <input type="text" class="form-control ltr" name="base_currency_symbol"
                                                    value="{{ $data->base_currency_symbol }}">
                                                <p id="errbase_currency_symbol" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $keywords['Base_Currency_Symbol_Position'] ?? __('Base Currency Symbol Position') }}
                                                    **</label>
                                                <select name="base_currency_symbol_position" class="form-control ltr">
                                                    <option value="left"
                                                        {{ $data->base_currency_symbol_position == 'left' ? 'selected' : '' }}>
                                                        {{ $keywords['Left'] ?? __('Left') }}
                                                    </option>
                                                    <option value="right"
                                                        {{ $data->base_currency_symbol_position == 'right' ? 'selected' : '' }}>
                                                        {{ $keywords['Right'] ?? __('Right') }}
                                                    </option>
                                                </select>
                                                <p id="errbase_currency_symbol_position" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>{{ $keywords['Base_Currency_Text'] ?? __('Base Currency Text') }}
                                                    **</label>
                                                <input type="text" class="form-control ltr" name="base_currency_text"
                                                    value="{{ $data->base_currency_text }}">
                                                <p id="errbase_currency_text" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>{{ $keywords['Base_Currency_Text_Position'] ?? __('Base Currency Text Position') }}
                                                    **</label>
                                                <select name="base_currency_text_position" class="form-control ltr">
                                                    <option value="left"
                                                        {{ $data->base_currency_text_position == 'left' ? 'selected' : '' }}>
                                                        {{ $keywords['Left'] ?? __('Left') }}
                                                    </option>
                                                    <option value="right"
                                                        {{ $data->base_currency_text_position == 'right' ? 'selected' : '' }}>
                                                        {{ $keywords['Right'] ?? __('Right') }}
                                                    </option>
                                                </select>
                                                <p id="errbase_currency_text_position" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>{{ $keywords['Base_Currency_Rate'] ?? __('Base Currency Rate') }}
                                                    **</label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ __('1 USD') }} =</span>
                                                    </div>
                                                    <input type="text" name="base_currency_rate"
                                                        class="form-control ltr" value="{{ $data->base_currency_rate }}">
                                                    <div class="input-group-append">
                                                        <span
                                                            class="input-group-text">{{ $data->base_currency_text }}</span>
                                                    </div>
                                                </div>
                                                <p id="errbase_currency_rate" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
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
