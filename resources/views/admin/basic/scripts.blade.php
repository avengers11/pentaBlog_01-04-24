@extends('admin.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Plugins') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Basic Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Plugins') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form id="scriptForm" class="" action="{{ route('admin.script.update') }}" method="post">
                @csrf
                <div class="row">

                    <div class="col-lg-4">
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
                                            <input type="radio" name="is_recaptcha" value="1"
                                                class="selectgroup-input" {{ $data->is_recaptcha == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_recaptcha" value="0"
                                                class="selectgroup-input" {{ $data->is_recaptcha == 0 ? 'checked' : '' }}>
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
                                    @if ($errors->has('google_recaptcha_site_key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('google_recaptcha_site_key') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Google Recaptcha Secret key') }}</label>
                                    <input class="form-control" name="google_recaptcha_secret_key"
                                        value="{{ $data->google_recaptcha_secret_key }}">
                                    @if ($errors->has('google_recaptcha_secret_key'))
                                        <p class="mb-0 text-danger">{{ $errors->first('google_recaptcha_secret_key') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">{{ __('Disqus') }}</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ __('Disqus Status (Website Blog Details)') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_disqus" value="1" class="selectgroup-input"
                                                {{ $data->is_disqus == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_disqus" value="0" class="selectgroup-input"
                                                {{ $data->is_disqus == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    @if ($errors->has('is_disqus'))
                                        <p class="mb-0 text-danger">{{ $errors->first('is_disqus') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Disqus Status (User Profile Blog Details)') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_user_disqus" value="1"
                                                class="selectgroup-input"
                                                {{ $data->is_user_disqus == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_user_disqus" value="0"
                                                class="selectgroup-input"
                                                {{ $data->is_user_disqus == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    @if ($errors->has('is_user_disqus'))
                                        <p class="mb-0 text-danger">{{ $errors->first('is_user_disqus') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Disqus Shortname') }}</label>
                                    <input class="form-control" name="disqus_shortname"
                                        value="{{ $data->disqus_shortname }}">
                                    @if ($errors->has('disqus_shortname'))
                                        <p class="mb-0 text-danger">{{ $errors->first('disqus_shortname') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">{{ __('Tawk.to') }}</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ __('Tawk.to Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_tawkto" value="1"
                                                class="selectgroup-input" {{ $data->is_tawkto == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_tawkto" value="0"
                                                class="selectgroup-input" {{ $data->is_tawkto == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    <p class="mb-0 text-warning">
                                        {{ __('If you enable Tawk.to, then WhatsApp must be disabled.') }}</p>
                                    @if ($errors->has('is_tawkto'))
                                        <p class="mb-0 text-danger">{{ $errors->first('is_tawkto') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Tawk.to Direct Chat Link') }}</label>
                                    <input class="form-control" name="tawkto_chat_link"
                                        value="{{ $data->tawkto_chat_link }}">
                                    @if ($errors->has('tawkto_chat_link'))
                                        <p class="mb-0 text-danger">{{ $errors->first('tawkto_chat_link') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">{{ __('WhatsApp Chat Button') }}</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ __('Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_whatsapp" value="1"
                                                class="selectgroup-input" {{ $data->is_whatsapp == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_whatsapp" value="0"
                                                class="selectgroup-input" {{ $data->is_whatsapp == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    <p class="text-warning mb-0">
                                        {{ __('If you enable WhatsApp, then Tawk.to must be disabled.') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('WhatsApp Number') }}</label>
                                    <input class="form-control" type="text" name="whatsapp_number"
                                        value="{{ $data->whatsapp_number }}">
                                    <p class="text-warning mb-0">{{ __('Enter Phone number with Country Code') }}</p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('WhatsApp Header Title') }}</label>
                                    <input class="form-control" type="text" name="whatsapp_header_title"
                                        value="{{ $data->whatsapp_header_title }}">
                                    @if ($errors->has('whatsapp_header_title'))
                                        <p class="mb-0 text-danger">{{ $errors->first('whatsapp_header_title') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('WhatsApp Popup Message') }}</label>
                                    <textarea class="form-control" name="whatsapp_popup_message" rows="2">{{ $data->whatsapp_popup_message }}</textarea>
                                    @if ($errors->has('whatsapp_popup_message'))
                                        <p class="mb-0 text-danger">{{ $errors->first('whatsapp_popup_message') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Popup') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_popup" value="1"
                                                class="selectgroup-input"
                                                {{ $data->whatsapp_popup == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="whatsapp_popup" value="0"
                                                class="selectgroup-input"
                                                {{ $data->whatsapp_popup == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    @if ($errors->has('whatsapp_popup'))
                                        <p class="mb-0 text-danger">{{ $errors->first('whatsapp_popup') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-title">
                                            {{ __('Google Analytics') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>{{ __('Google Analytics Status') }}*</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="analytics_status" value="1"
                                                        class="selectgroup-input"
                                                        {{ isset($data) && $data->analytics_status == 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Active') }}</span>
                                                </label>

                                                <label class="selectgroup-item">
                                                    <input type="radio" name="analytics_status" value="0"
                                                        class="selectgroup-input"
                                                        {{ !isset($data) || $data->analytics_status != 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                                </label>
                                            </div>

                                            @if ($errors->has('analytics_status'))
                                                <p class="mt-1 mb-0 text-danger">{{ $errors->first('analytics_status') }}
                                                </p>
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
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-title">{{ __('Facebook Pixel') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>{{ __('Facebook Pixel Status') }}
                                                *</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="pixel_status" value="1"
                                                        class="selectgroup-input"
                                                        {{ isset($data) && $data->pixel_status == 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Active') }}</span>
                                                </label>

                                                <label class="selectgroup-item">
                                                    <input type="radio" name="pixel_status" value="0"
                                                        class="selectgroup-input"
                                                        {{ !isset($data) || $data->pixel_status != 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                                </label>
                                            </div>
                                            <p id="errpixel_status" class="mb-0 text-danger em"></p>
                                            <p class="text text-warning">
                                                <strong>{{ $keywords['Hint'] ?? __('Hint') }}:</strong> <a
                                                    class="text-primary" href="https://prnt.sc/5u1ZP6YjAw5O"
                                                    target="_blank">{{ $keywords['fb_pixel_hint_text'] ?? __('Click Here to see where to get the Facebook Pixel ID') }}</a>
                                            </p>
                                            @if ($errors->has('pixel_status'))
                                                <p class="text-danger">{{ $errors->first('pixel_status') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Facebook Pixel ID') }}
                                                *</label>
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
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit" form="scriptForm"
                                        class="btn btn-success">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
