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
        <h4 class="page-title">{{ $keywords['Page_Headings'] ?? __('Page Headings') }}</h4>
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
                <a href="#">{{ $keywords['Page_Headings'] ?? __('Page Headings') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="ajaxForm"
                    action="{{ route('user.basic_settings.update_page_headings', ['language' => request()->input('language')]) }}"
                    method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">
                                    {{ $keywords['Update_Page_Headings'] ?? __('Update Page Headings') }}</div>
                            </div>

                            <div class="col-lg-2">

                            </div>
                        </div>
                    </div>

                    <div class="card-body py-5">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label>{{ $keywords['About_Me_Title'] ?? __('About Me Title') }} *</label>
                                    <input class="form-control" name="about_me_title"
                                        value="{{ isset($data) ? $data->about_me_title : '' }}">
                                    <p id="errabout_me_title" class="mb-0 text-danger em"></p>
                                </div>

                                @if (!empty($permissions) && in_array('Gallery', $permissions))
                                    <div class="form-group">
                                        <label>{{ $keywords['Gallery_Title'] ?? __('Gallery Title') }} *</label>
                                        <input class="form-control" name="gallery_title"
                                            value="{{ isset($data) ? $data->gallery_title : '' }}">
                                        <p id="errgallery_title" class="mb-0 text-danger em"></p>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>{{ $keywords['Posts_Title'] ?? __('Posts Title') }}*</label>
                                    <input class="form-control" name="posts_title"
                                        value="{{ isset($data) ? $data->posts_title : '' }}">
                                    <p id="errposts_title" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Post_Details_Title'] ?? __('Post Details Title') }}*</label>
                                    <input class="form-control" name="post_details_title"
                                        value="{{ isset($data) ? $data->post_details_title : '' }}">
                                    <p id="errpost_details_title" class="mb-0 text-danger em"></p>
                                </div>


                                @if (!empty($permissions) && in_array('FAQ', $permissions))
                                    <div class="form-group">
                                        <label>{{ $keywords['FAQ_Title'] ?? __('FAQ Title') }}*</label>
                                        <input class="form-control" name="faq_title"
                                            value="{{ isset($data) ? $data->faq_title : '' }}">
                                        <p id="errfaq_title" class="mb-0 text-danger em"></p>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>{{ $keywords['Contact_Me_Title'] ?? __('Contact Me Title') }}*</label>
                                    <input class="form-control" name="contact_me_title"
                                        value="{{ isset($data) ? $data->contact_me_title : '' }}">
                                    <p id="errcontact_me_title" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Error_Page_Title'] ?? __('Error Page Title') }}*</label>
                                    <input class="form-control" name="error_page_title"
                                        value="{{ isset($data) ? $data->error_page_title : '' }}">
                                    <p id="errerror_page_title" class="mb-0 text-danger em"></p>
                                </div>
                                @if (!empty($permissions) && in_array('Ecommerce', $permissions))
                                    <div class="form-group">
                                        <label>{{ $keywords['Shop_Page_Title'] ?? __('Shop Page Title') }}*</label>
                                        <input class="form-control" name="shop"
                                            value="{{ isset($data) ? $data->shop : '' }}">
                                        <p id="errshop" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ $keywords['Shop_Details_Page_Title'] ?? __('Shop Details Page Title') }}*</label>
                                        <input class="form-control" name="shop_details"
                                            value="{{ isset($data) ? $data->shop_details : '' }}">
                                        <p id="errshop_details" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ $keywords['Cart_Page_Title'] ?? __('Cart Page Title') }}*</label>
                                        <input class="form-control" name="cart"
                                            value="{{ isset($data) ? $data->cart : '' }}">
                                        <p id="errcart" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ $keywords['Checkout_Page_Title'] ?? __('Checkout Page Title') }}*</label>
                                        <input class="form-control" name="checkout"
                                            value="{{ isset($data) ? $data->checkout : '' }}">
                                        <p id="errcheckout" class="mb-0 text-danger em"></p>
                                    </div>
                                @endif
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
