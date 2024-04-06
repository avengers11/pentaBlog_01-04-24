@extends('user.layout')

@php
$selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
    @section('styles')
        <style>
            form:not(.modal-form) input,
            form:not(.modal-form) textarea,
            form:not(.modal-form) select,
            select[name='language'] {
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
        <h4 class="page-title">{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</h4>
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
                <a href="#">{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Update_Profile'] ?? __('Update Profile') }}</div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm" class="" action="{{ route('user-profile-update') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label for="image"><strong>{{ $keywords['Profile_Image'] ?? __('Profile Image') }}
                                                        **</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ $user->photo ? Storage::url($user->photo) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                            </div>
                                            <input type="file" name="photo" id="image" class="form-control">
                                            <p id="errphoto" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['First_Name'] ?? __('First Name') }} **</label>
                                    <input type="text" class="form-control" name="first_name"
                                        value="{{ $user->first_name }}"
                                        placeholder="{{ $keywords['Enter_first_name'] ?? __('Enter first name') }}">
                                    <p id="errfirst_name" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Last_Name'] ?? __('Last Name') }} **</label>
                                    <input type="text" class="form-control" name="last_name"
                                        value="{{ $user->last_name }}"
                                        placeholder="{{ $keywords['Enter_last_name'] ?? __('Enter last name') }}">
                                    <p id="errlast_name" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['User_Name'] ?? __('User Name') }} **</label>
                                    <input type="text" class="form-control" name="username"
                                        value="{{ $user->username }}"
                                        placeholder="{{ $keywords['Enter_username'] ?? __('Enter username') }}">
                                    <p id="errusername" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Designation'] ?? __('Designation') }} **</label>
                                    <input type="text" class="form-control" name="designation"
                                        value="{{ $user->designation }}">
                                    <p id="errdesignation" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Phone'] ?? __('Phone') }} **</label>
                                    <input type="text" class="form-control" name="phone" value="{{ $user->phone }}"
                                        placeholder="{{ $keywords['Enter_phone'] ?? __('Enter phone') }}">
                                    <p id="errphone" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Address'] ?? __('Address') }} **</label>
                                    <textarea type="text" class="form-control" name="address" rows="5">{{ $user->address }}</textarea>
                                    <p id="erraddress" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['City'] ?? __('City') }} **</label>
                                    <input type="text" class="form-control" name="city" rows="5"
                                        value="{{ $user->city }}">
                                    <p id="errcity" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['State'] ?? __('State') }} **</label>
                                    <input type="text" class="form-control" name="state" rows="5"
                                        value="{{ $user->state }}">
                                    <p id="errstate" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Country'] ?? __('Country') }} **</label>
                                    <input type="text" class="form-control" name="country" rows="5"
                                        value="{{ $user->country }}">
                                    <p id="errcountry" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Keywords'] ?? __('Keywords') }}</label>
                                    <input type="text" class="form-control" name="keywords"
                                        value="{{ $user->keywords }}" data-role="tagsinput">
                                    <p id="errkeywords" class="mb-0 text-danger em"></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" id="submitBtn"
                                    class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
