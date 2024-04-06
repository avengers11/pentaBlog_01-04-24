@extends('user.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@php
$selLang = \App\Models\User\Language::where([['code', request()->input('language')], ['user_id', \Illuminate\Support\Facades\Auth::id()]])->first();
$userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
$userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
    @section('styles')
        <style>
            form:not(.modal-form) input,
            form:not(.modal-form) textarea,
            form:not(.modal-form) select,
            select[name='userLanguage'] {
                direction: rtl;
            }

            form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Cookie_Alert'] ?? __('Cookie Alert') }}</h4>
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
                <a href="#">{{ $keywords['Cookie_Alert'] ?? __('Basic Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Cookie_Alert'] ?? __('Cookie Alert') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ $keywords['Update_Cookie_Alert'] ?? __('Update Cookie Alert') }}
                            </div>
                        </div>

                        <div class="col-lg-2">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm"
                                action="{{ route('user.basic_settings.update_cookie_alert', ['language' => request()->input('language')]) }}"
                                method="post">
                                @csrf
                                <div class="form-group">
                                    <label>{{ $keywords['Cookie_Alert_Status'] ?? __('Cookie Alert Status') }}*</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="cookie_alert_status" value="1"
                                                class="selectgroup-input"
                                                {{ isset($data) && $data->cookie_alert_status == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="cookie_alert_status" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($data) || $data->cookie_alert_status == 0 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    <p id="errcookie_alert_status" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Cookie_Alert_Button_Text'] ?? __('Cookie Alert Button Text') }}*
                                    </label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ $keywords['Enter_Button_Text'] ?? __('Enter Button Text') }}"
                                        name="cookie_alert_btn_text"
                                        value="{{ isset($data) ? $data->cookie_alert_btn_text : '' }}">
                                    <p id="errcookie_alert_btn_text" class="em text-danger mb-0"></p>
                                </div>

                                <div class="form-group">
                                    <label
                                        for="">{{ $keywords['Cookie_Alert_Text'] ?? __('Cookie Alert Text') }}*</label>
                                    <textarea class="form-control summernote" name="cookie_alert_text" data-height="120">{!! isset($data) ? replaceBaseUrl($data->cookie_alert_text, 'summernote') : '' !!}</textarea>
                                    <p id="errcookie_alert_text" class="em text-danger mb-0"></p>
                                </div>
                            </form>
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
            </div>
        </div>
    </div>
@endsection
