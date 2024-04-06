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
        <h4 class="page-title">{{ $keywords['Information'] ?? __('Information') }}</h4>
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
                <a href="#">{{ $keywords['About_Me'] ?? __('About Me') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Information'] ?? __('Information') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ $keywords['Update_Information'] ?? __('Update Information') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form id="informationForm" enctype="multipart/form-data"
                                action="{{ route('user.about_me.update_information', ['language' => request()->input('language')]) }}"
                                method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label
                                                    for="image"><strong>{{ $keywords['Your_Image'] ?? __('Your Image') }}*</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ isset($data) && $data->image ? asset('assets/user/img/authors/' . $data->image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                            </div>
                                            <input type="file" name="image" id="image" class="form-control">
                                            @if ($errors->has('image'))
                                                <p class="mt-2 mb-0 text-danger">{{ $errors->first('image') }}</p>
                                            @endif
                                            <p class="text-warning mb-0 mt-2">
                                                {{ $keywords['img_validation_msg'] ?? __('** Only JPG, PNG, JPEG, SVG Images are allowed') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Your_Name'] ?? __('Your Name') }} *</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ is_null($data) ? '' : $data->name }}">
                                    @if ($errors->has('name'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['About_You'] ?? __('About You') }}*</label>
                                    <textarea class="form-control summernote" name="about" data-height="200" id="aboutYou">{{ is_null($data) ? '' : replaceBaseUrl($data->about, 'summernote') }}</textarea>
                                    @if ($errors->has('about'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('about') }}</p>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label
                                                    for="image"><strong>{{ $keywords['Video_Background_Image'] ?? __('Video Background Image') }}</strong></label>
                                            </div>
                                            <div class="col-md-12 showEditImage mb-3">
                                                <img src="{{ isset($data) && $data->video_background_image ? asset('assets/user/img/' . $data->video_background_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                            </div>
                                            <input type="file" name="video_background_image" id="edit_image"
                                                class="form-control">
                                            <p id="errvideo_background_image" class="mb-0 text-danger em"></p>
                                            <p class="text-warning mb-0 mt-2">
                                                {{ $keywords['img_validation_msg'] ?? __('** Only JPG, PNG, JPEG, SVG Images are allowed') }}
                                            </p>
                                            @if ($errors->has('video_background_image'))
                                                <p class="mb-0 text-danger">{{ $errors->first('video_background_image') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Video_URL'] ?? __('Video URL') }}</label>
                                    <input type="url" class="form-control ltr" name="link"
                                        value="{{ is_null($data) ? '' : $data->link }}">
                                    @if ($errors->has('link'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('link') }}</p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="informationForm" class="btn btn-success">
                                {{ $keywords['Update'] ?? __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/user/dashboard/js/edit-image-modal.js') }}"></script>
@endsection
