@extends('user.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@php
$selLang = \App\Models\User\Language::where([['code', request()->input('language')], ['user_id', \Illuminate\Support\Facades\Auth::id()]])->first();
$userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
$userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();

$user = Auth::guard('web')->user();
if (!empty($user)) {
    $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
    $permissions = json_decode($permissions, true);
}
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
        <h4 class="page-title">{{ $keywords['SEO_Informations'] ?? __('SEO Informations') }}</h4>
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
                <a href="#">{{ $keywords['SEO_Informations'] ?? __('SEO Informations') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="ajaxForm"
                    action="{{ route('user.basic_settings.update_seo', ['language' => request()->input('language')]) }}"
                    method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">
                                    {{ $keywords['Update_SEO_Information'] ?? __('Update SEO Informations') }}</div>
                            </div>

                            <div class="col-lg-2">

                            </div>
                        </div>
                    </div>

                    <div class="card-body py-5">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Home_Page'] ?? __('Meta Keywords For Home Page') }}</label>
                                    <input class="form-control" name="meta_keyword_home"
                                        value="{{ isset($data) ? $data->meta_keyword_home : '' }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Home_Page'] ?? __('Meta Description For Home Page') }}</label>
                                    <textarea class="form-control" name="meta_description_home" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_home : '' }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_About_Page'] ?? __('Meta Keywords For About Page') }}</label>
                                    <input class="form-control" name="meta_keyword_about"
                                        value="{{ isset($data) ? $data->meta_keyword_about : '' }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_About_Page'] ?? __('Meta Description For About Page') }}</label>
                                    <textarea class="form-control" name="meta_description_about" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_about : '' }}</textarea>
                                </div>
                            </div>

                            @if (!empty($permissions) && in_array('Gallery', $permissions))
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Keywords_For_Gallery_Page'] ?? __('Meta Keywords For Gallery Page') }}</label>
                                        <input class="form-control" name="meta_keyword_gallery"
                                            value="{{ isset($data) ? $data->meta_keyword_gallery : '' }}"
                                            placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                            data-role="tagsinput">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Description_For_Gallery_Page'] ?? __('Meta Description For Gallery Page') }}</label>
                                        <textarea class="form-control" name="meta_description_gallery" rows="5"
                                            placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_gallery : '' }}</textarea>
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Posts_Page'] ?? __('Meta Keywords For Posts Page') }}</label>
                                    <input class="form-control" name="meta_keyword_posts"
                                        value="{{ isset($data) ? $data->meta_keyword_posts : '' }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Posts_Page'] ?? __('Meta Description For Posts Page') }}</label>
                                    <textarea class="form-control" name="meta_description_posts" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_posts : '' }}</textarea>
                                </div>
                            </div>

                            @if (!empty($permissions) && in_array('FAQ', $permissions))
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Keywords_For_FAQ_Page'] ?? __('Meta Keywords For FAQ Page') }}</label>
                                        <input class="form-control" name="meta_keyword_faq"
                                            value="{{ isset($data) ? $data->meta_keyword_faq : '' }}"
                                            placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                            data-role="tagsinput">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ $keywords['Meta_Description_For_FAQ_Page'] ?? __('Meta Description For FAQ Page') }}</label>
                                        <textarea class="form-control" name="meta_description_faq" rows="5"
                                            placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_faq : '' }}</textarea>
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Contact_Page'] ?? __('Meta Keywords For Contact Page') }}</label>
                                    <input class="form-control" name="meta_keyword_contact"
                                        value="{{ isset($data) ? $data->meta_keyword_contact : '' }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Contact_Page'] ?? __('Meta Description For Contact Page') }}</label>
                                    <textarea class="form-control" name="meta_description_contact" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_contact : '' }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Login_Page'] ?? __('Meta Keywords For Login Page') }}</label>
                                    <input class="form-control" name="meta_keyword_login"
                                        value="{{ isset($data) ? $data->meta_keyword_login : '' }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Login_Page'] ?? __('Meta Description For Login Page') }}</label>
                                    <textarea class="form-control" name="meta_description_login" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_login : '' }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Signup_Page'] ?? __('Meta Keywords For Signup Page') }}</label>
                                    <input class="form-control" name="meta_keyword_signup"
                                        value="{{ isset($data) ? $data->meta_keyword_signup : '' }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Signup_Page'] ?? __('Meta Description For Signup Page') }}</label>
                                    <textarea class="form-control" name="meta_description_signup" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_signup : '' }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Keywords_For_Forget_Page'] ?? __('Meta Keywords For Forget Password Page') }}</label>
                                    <input class="form-control" name="meta_keyword_forget_password"
                                        value="{{ isset($data) ? $data->meta_keyword_forget_password : '' }}"
                                        placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                        data-role="tagsinput">
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Meta_Description_For_Forget_Password_Page'] ?? __('Meta Description For Forget Password Page') }}</label>
                                    <textarea class="form-control" name="meta_description_forget_password" rows="5"
                                        placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_forget_password : '' }}</textarea>
                                </div>
                            </div>

                            @if (!empty($permissions) && in_array('Ecommerce', $permissions))
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ $keywords['meta_keyword_shop'] ?? __('Meta Keywords For Shop Page') }}</label>
                                        <input class="form-control" name="meta_keyword_shop"
                                            value="{{ isset($data) ? $data->meta_keyword_shop : '' }}"
                                            placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                            data-role="tagsinput">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ $keywords['meta_description_shop'] ?? __('Meta Description For Shop Page') }}</label>
                                        <textarea class="form-control" name="meta_description_shop" rows="5"
                                            placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_shop : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ $keywords['meta_keyword_shop_details'] ?? __('Meta Keywords For Shop Details Page') }}</label>
                                        <input class="form-control" name="meta_keyword_shop_details"
                                            value="{{ isset($data) ? $data->meta_keyword_shop_details : '' }}"
                                            placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                            data-role="tagsinput">
                                    </div>

                                    <div class="form-group">
                                        <label>{{ $keywords['meta_description_shop_details'] ?? __('Meta Description For Shop Details Page') }}</label>
                                        <textarea class="form-control" name="meta_description_shop_details" rows="5"
                                            placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ isset($data) ? $data->meta_description_shop_details : '' }}</textarea>
                                    </div>
                                </div>
                            @endif
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
