@extends('user.layout')
@php
$user = Auth::guard('web')->user();
$package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
if (!empty($user)) {
    $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
    $permissions = json_decode($permissions, true);
}
@endphp
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Plugins'] ?? __('Plugins') }}</h4>
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
                <a href="#">{{ $keywords['Plugins'] ?? __('Plugins') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        @if (!empty($permissions) && in_array('Google Analytics', $permissions))
        <div class="col-lg-4">
            <div class="card">
                <form id="ajaxForm" action="{{ route('user.basic_settings.update_analytics') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Google_Analytics'] ?? __('Google Analytics') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ $keywords['Google_Analytics_Status'] ?? __('Google Analytics Status') }}*</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="analytics_status" value="1"
                                                class="selectgroup-input"
                                                {{ isset($data) && $data->analytics_status == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="analytics_status" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($data) || $data->analytics_status != 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>

                                    @if ($errors->has('analytics_status'))
                                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('analytics_status') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Measurement_ID'] ?? __('Measurement ID') }} *</label>
                                    <input type="text" class="form-control" name="measurement_id"
                                        value="{{ isset($data) && $data->measurement_id ? $data->measurement_id : null }}">
                                    <p id="errmeasurement_id" class="mb-0 text-danger em"></p>
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
        @endif
        @if (!empty($permissions) && in_array('Google Recaptcha', $permissions))
        <div class="col-lg-4">
            <form id="ajaxFormRecaptcha" action="{{ route('user.basic_settings.update_recaptcha') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            {{ __('Google Recaptcha') }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ __('Google Recaptcha Status') }}</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="is_recaptcha" value="1" class="selectgroup-input"
                                        {{ $data->is_recaptcha == 1 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ __('Active') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="is_recaptcha" value="0" class="selectgroup-input"
                                        {{ $data->is_recaptcha == 0 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                </label>
                            </div>
                            @if ($errors->has('is_recaptcha'))
                                <p class="mb-0 text-danger">{{ $errors->first('is_recaptcha') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>{{ __('Google Recaptcha Site key') }}</label>
                            <input class="form-control" name="google_recaptcha_site_key"
                                value="{{ $data->google_recaptcha_site_key }}">
                            <p id="errgoogle_recaptcha_site_key" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Google Recaptcha Secret key') }}</label>
                            <input class="form-control" name="google_recaptcha_secret_key"
                                value="{{ $data->google_recaptcha_secret_key }}">
                                <p id="errgoogle_recaptcha_secret_key" class="mb-0 text-danger em"></p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="button" id="recaptchaSubmitBtn" class="btn btn-success">
                                    {{ $keywords['Update'] ?? __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif
        @if (!empty($permissions) && in_array('Disqus', $permissions))
        <div class="col-lg-4">
            <div class="card">
                <form id="ajaxFormDisqus" action="{{ route('user.basic_settings.update_disqus') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Disqus'] ?? __('Disqus') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ $keywords['Disqus_Status'] ?? __('Disqus Status*') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="disqus_status" value="1"
                                                class="selectgroup-input"
                                                {{ isset($data) && $data->disqus_status == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="disqus_status" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($data) || $data->disqus_status != 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    <p id="errdisqus_status" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Disqus_Short_Name'] ?? __('Disqus Short Name') }}</label>
                                    <input type="text" class="form-control" name="disqus_short_name"
                                        value="{{ isset($data) ? $data->disqus_short_name : null }}">
                                    <p id="errdisqus_short_name" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="button" id="disqusSubmitBtn" class="btn btn-success">
                                    {{ $keywords['Update'] ?? __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @if (!empty($permissions) && in_array('WhatsApp', $permissions))
        <div class="col-lg-4">
            <div class="card">
                <form id="ajaxEditForm" action="{{ route('user.basic_settings.update_whatsapp') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['WhatsApp'] ?? __('WhatsApp') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ $keywords['WhatsApp_Status'] ?? __('WhatsApp Status') }} *</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_status" value="1"
                                                class="selectgroup-input"
                                                {{ isset($data) && $data->whatsapp_status == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_status" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($data) || $data->whatsapp_status != 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    <p id="eerrwhatsapp_status" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['WhatsApp_Number'] ?? __('WhatsApp Number') }} *</label>
                                    <input type="text" class="form-control" name="whatsapp_number"
                                        value="{{ isset($data) && $data->whatsapp_number ? $data->whatsapp_number : null }}">

                                    <p id="eerrwhatsapp_number" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['WhatsApp_Header_Title'] ?? __('WhatsApp Header Title') }} *
                                    </label>
                                    <input type="text" class="form-control" name="whatsapp_header_title"
                                        value="{{ isset($data) && $data->whatsapp_header_title ? $data->whatsapp_header_title : null }}">
                                    <p id="eerrwhatsapp_header_title" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['WhatsApp_Popup_Status'] ?? __('WhatsApp Popup Status') }} *
                                    </label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_popup_status" value="1"
                                                class="selectgroup-input"
                                                {{ isset($data) && $data->whatsapp_popup_status == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_popup_status" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($data) || $data->whatsapp_popup_status != 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    <p id="eerrwhatsapp_popup_status" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['WhatsApp_Popup_Message'] ?? __('WhatsApp Popup Message') }}*</label>
                                    <textarea class="form-control" name="whatsapp_popup_message" rows="2">{{ isset($data) && $data->whatsapp_popup_message ? $data->whatsapp_popup_message : null }}</textarea>
                                    <p id="eerrwhatsapp_popup_message" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" id="updateBtn" class="btn btn-success">
                                    {{ $keywords['Update'] ?? __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @if (!empty($permissions) && in_array('Facebook Pixel', $permissions))
        <div class="col-lg-4">
            <div class="card">
                <form id="ajaxFormDisqus" action="{{ route('user.update_pixel') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-title">{{ $keywords['Facebook_Pixel'] ?? __('Facebook Pixel') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ $keywords['Facebook_Pixel_Status'] ?? __('Facebook Pixel Status') }}
                                        *</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="pixel_status" value="1"
                                                class="selectgroup-input"
                                                {{ isset($data) && $data->pixel_status == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="pixel_status" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($data) || $data->pixel_status != 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    <p id="errpixel_status" class="mb-0 text-danger em"></p>
                                    <p class="text text-warning">
                                        <strong>{{ $keywords['Hint'] ?? __('Hint') }}:</strong> <a class="text-primary"
                                            href="https://prnt.sc/5u1ZP6YjAw5O"
                                            target="_blank">{{ $keywords['fb_pixel_hint_text'] ?? __('Click Here to see where to get the Facebook Pixel ID') }}</a>
                                    </p>
                                    @if ($errors->has('pixel_status'))
                                        <p class="text-danger">{{ $errors->first('pixel_status') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Facebook_Pixel_ID'] ?? __('Facebook Pixel ID') }} *</label>
                                    <input type="text" class="form-control" name="pixel_id"
                                        value="{{ isset($data) ? $data->pixel_id : null }}">
                                    <p id="errpixel_id" class="mb-0 text-danger em"></p>
                                    @if ($errors->has('pixel_id'))
                                        <p class="text-danger">{{ $errors->first('pixel_id') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ $keywords['Update'] ?? __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @if (!empty($permissions) && in_array('Tawk.to', $permissions))
        <div class="col-lg-4">
            <div class="card">
                <form action="{{ route('user.update_tawkto') }}" method="POST">
                    @csrf
                    <div class="card-header">
                        <div class="card-title">{{ $keywords['Tawk_to'] ?? __('Tawk.to') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ $keywords['Tawk_to_Status'] ?? __('Tawk.to Status') }} </label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="tawkto_status" value="1" class="selectgroup-input"
                                        {{ isset($data) && $data->tawkto_status == 1 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="tawkto_status" value="0" class="selectgroup-input"
                                        {{ isset($data) && $data->tawkto_status == 0 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                </label>
                            </div>
                            @if ($errors->has('tawkto_status'))
                                <p class="mb-0 text-danger">{{ $errors->first('tawkto_status') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>{{ $keywords['Tawk_to_Chat_Link'] ?? __('Tawk.to Direct Chat Link') }}</label>
                            <input class="form-control" name="tawkto_direct_chat_link"
                                value="{{ isset($data) ? $data->tawkto_direct_chat_link : '' }}">
                            @if ($errors->has('tawkto_direct_chat_link'))
                                <p class="mb-0 text-danger">{{ $errors->first('tawkto_direct_chat_link') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-success" type="submit">{{ $keywords['Update'] ?? __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
@endsection
