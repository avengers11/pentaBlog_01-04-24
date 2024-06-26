@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['vCard_Information'] ?? __('vCard Information') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['vCards'] ?? __('vCards') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $vcard->vcard_name }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Infromation'] ?? __('Infromation') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['vCard_Information'] ?? __('vCard Information') }}
                    </div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.vcard', ['language' => request('language')]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>

                    <a class="btn btn-primary btn-sm float-right mr-2"
                        href="{{ route('front.user.vcard', [Auth::user()->username, $vcard->id]) }}" target="_blank">
                        <span class="btn-label">
                            <i class="fas fa-eye"></i>
                        </span>
                        {{ $keywords['Preview'] ?? __('Preview') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-12">
                            {{-- Featured image upload end --}}
                            <form id="ajaxForm" class="" action="{{ route('user.vcard.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="vcard_id" value="{{ $vcard->id }}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label
                                                class="form-label">{{ $keywords['Choose_a_Template'] ?? __('Choose a Template') }}</label>
                                            <div class="row">
                                                <div class="col-2">
                                                    <label class="imagecheck mb-4">
                                                        <input name="template" type="radio" value="1"
                                                            class="imagecheck-input"
                                                            {{ $vcard->template == 1 ? 'checked' : '' }}>
                                                        <figure class="imagecheck-figure">
                                                            <img src="{{ asset('assets/user/img/vcard-templates/1.jpg') }}"
                                                                alt="title" class="imagecheck-image">
                                                        </figure>
                                                    </label>
                                                </div>
                                                <div class="col-2">
                                                    <label class="imagecheck mb-4">
                                                        <input name="template" type="radio" value="2"
                                                            class="imagecheck-input"
                                                            {{ $vcard->template == 2 ? 'checked' : '' }}>
                                                        <figure class="imagecheck-figure">
                                                            <img src="{{ asset('assets/user/img/vcard-templates/2.jpg') }}"
                                                                alt="title" class="imagecheck-image">
                                                        </figure>
                                                    </label>
                                                </div>
                                                <div class="col-2">
                                                    <label class="imagecheck mb-4">
                                                        <input name="template" type="radio" value="3"
                                                            class="imagecheck-input"
                                                            {{ $vcard->template == 3 ? 'checked' : '' }}>
                                                        <figure class="imagecheck-figure">
                                                            <img src="{{ asset('assets/user/img/vcard-templates/3.jpg') }}"
                                                                alt="title" class="imagecheck-image">
                                                        </figure>
                                                    </label>
                                                </div>
                                                <div class="col-2">
                                                    <label class="imagecheck mb-4">
                                                        <input name="template" type="radio" value="4"
                                                            class="imagecheck-input"
                                                            {{ $vcard->template == 4 ? 'checked' : '' }}>
                                                        <figure class="imagecheck-figure">
                                                            <img src="{{ asset('assets/user/img/vcard-templates/4.jpg') }}"
                                                                alt="title" class="imagecheck-image">
                                                        </figure>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="em text-danger em-0" id="errtemplate"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label for="image"><strong>{{ $keywords['Profile_Image'] ?? __('Profile Image') }}
                                                        **</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ !empty($vcard->profile_image) ? asset('assets/user/img/vcard/' . $vcard->profile_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                            </div>
                                            <input type="file" name="profile_image" class="image"
                                                class="form-control image">
                                            <p id="errprofile_image" class="mb-0 text-danger em"></p>
                                            <p class="text-warning">
                                                {{ $keywords['Image_size_can_be_maximum_200_KB'] ?? _('Image size can be maximum 200 KB') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label
                                                    for="image"><strong>{{ $keywords['Cover_Image'] ?? __('Cover Image') }}</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ !empty($vcard->cover_image) ? asset('assets/user/img/vcard/' . $vcard->cover_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                            </div>
                                            <input type="file" name="cover_image" class="image"
                                                class="form-control image">
                                            <p id="errcover_image" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['vCard_Name'] ?? __('vCard Name') }}
                                                **</label>
                                            <input type="text"
                                                class="form-control {{ $vcard->direction == 2 ? 'rtl' : '' }}"
                                                name="vcard_name" value="{{ $vcard->vcard_name }}"
                                                placeholder="{{ __('Enter vcard name') }}">
                                            <p class="text-warning mb-0">
                                                {{ $keywords['vCard_Name_text'] ?? __('Use this name to identify sepcific vcard from your vcards list') }}.
                                            </p>
                                            <p id="errvcard_name" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Direction'] ?? __('Direction') }}
                                                **</label>
                                            <select name="direction" class="form-control" id="direction">
                                                <option value="" selected disabled>
                                                    {{ $keywords['Select_a_Direction'] ?? __('Select a Direction') }}
                                                </option>
                                                <option value="1" {{ $vcard->direction == 1 ? 'selected' : '' }}>
                                                    {{ $keywords['LTR'] ?? __('LTR') }}
                                                    ({{ $keywords['Left_to_Right'] ?? __('Left to Right') }})</option>
                                                <option value="2" {{ $vcard->direction == 2 ? 'selected' : '' }}>
                                                    {{ $keywords['RTL'] ?? __('RTL') }}
                                                    ({{ $keywords['Right_to_Left'] ?? __('Right to Left') }})</option>
                                            </select>
                                            <p id="errdirection" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Name'] ?? __('Name') }}</label>
                                            <input type="text"
                                                class="form-control {{ $vcard->direction == 2 ? 'rtl' : '' }}"
                                                name="name" value="{{ $vcard->name }}"
                                                placeholder="{{ $keywords['Enter_Name'] ?? __('Enter name') }}">
                                            <p id="errname" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['Company_Name'] ?? __('Company Name') }}</label>
                                            <input type="text"
                                                class="form-control {{ $vcard->direction == 2 ? 'rtl' : '' }}"
                                                name="company" value="{{ $vcard->company }}"
                                                placeholder="{{ $keywords['Enter_company'] ?? __('Enter company') }}">
                                            <p id="errcompany" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['Occupation'] ?? __('Occupation') }}</label>
                                            <input type="text"
                                                class="form-control {{ $vcard->direction == 2 ? 'rtl' : '' }}"
                                                name="occupation" value="{{ $vcard->occupation }}"
                                                placeholder="{{ $keywords['Enter_occupation'] ?? __('Enter occupation') }}">
                                            <p id="erroccupation" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Email'] ?? __('Email') }}</label>
                                            <input type="text" class="form-control ltr" name="email"
                                                value="{{ $vcard->email }}"
                                                placeholder="{{ $keywords['Enter_email'] ?? __('Enter email') }}">
                                            <p id="erremail" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Phone'] ?? __('Phone') }}</label>
                                            <input type="text" class="form-control ltr" name="phone"
                                                value="{{ $vcard->phone }}"
                                                placeholder="{{ $keywords['Enter_phone'] ?? __('Enter phone') }}">
                                            <p class="text-warning mb-0">
                                                {{ $keywords['Enter_Phone_Number_with'] ?? __('Enter Phone Number with') }}
                                                <strong
                                                    class="text-danger">{{ $keywords['Country_Code'] ?? __('Country Code') }}</strong>
                                            </p>
                                            <p id="errphone" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Address'] ?? __('Address') }}</label>
                                            <input type="text"
                                                class="form-control {{ $vcard->direction == 2 ? 'rtl' : '' }}"
                                                name="address" value="{{ $vcard->address }}"
                                                placeholder="{{ $keywords['Enter_address'] ?? __('Enter address') }}">
                                            <p id="erraddress" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['Website_URL'] ?? __('Website URL') }}</label>
                                            <input type="text" class="form-control ltr" name="website_url"
                                                value="{{ $vcard->website_url }}"
                                                placeholder="{{ $keywords['Enter_website_url'] ?? __('Enter website url') }}">
                                            <p id="errwebsite_url" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label
                                                for="summary">{{ $keywords['Introduction'] ?? __('Introduction') }}</label>
                                            <textarea name="introduction" id="introduction" class="form-control {{ $vcard->direction == 2 ? 'rtl' : '' }}"
                                                rows="4" placeholder="{{ $keywords['Enter_Introduction'] ?? __('Enter Introduction') }}">{{ $vcard->introduction }}</textarea>
                                        </div>
                                    </div>
                                </div>


                                <div id="app">
                                    {{-- Infromation Start --}}
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for=""
                                                    class="d-block mb-2">{{ $keywords['Other_Infromation'] ?? __('Other Infromation') }}</label>
                                                <button class="btn btn-primary"
                                                    @click="addInformation">{{ $keywords['Add_Information'] ?? __('Add Information') }}</button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row no-gutters" v-for="(information, index) in infromations"
                                        :key="information.uniqid">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Icon'] ?? __('Icon') }} **</label>
                                                <div class="btn-group d-block">
                                                    <button type="button" class="btn btn-primary iconpicker-component"><i
                                                            :id="'vcard-icp-icon' + index"
                                                            :class="information.icon"></i></button>
                                                    <button type="button" :id="'vcard-icp' + information.uniqid"
                                                        class="vcard-icp vcard-icp-dd btn btn-primary dropdown-toggle"
                                                        data-selected="fa-car" data-toggle="dropdown"
                                                        :data-vue_index="index">
                                                    </button>
                                                    <div class="dropdown-menu"></div>
                                                </div>
                                                <input type="hidden" name="icons[]" v-model="information.icon">
                                                <p class="em text-danger mb-0" :id="'erricons.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-1">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Link'] ?? __('Link') }}</label><br>
                                                <input name="links[]" :value="index" type="checkbox"
                                                    :checked="information.link == 1 ? true : false">
                                                <p class="em text-danger mb-0" :id="'errlinks.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Icon_Color'] ?? __('Icon Color') }}</label>
                                                <input name="colors[]"
                                                    :class="'jscolor jscolor' + information.uniqid + ' ltr form-control'"
                                                    :value="information.color" type="text"
                                                    @change="setColor($event, index)">
                                                <p class="em text-danger mb-0" :id="'errcolors.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Label'] ?? __('Label') }}</label>
                                                <input name="labels[]" class="form-control"
                                                    :class="{ rtl: information.dir == 2 }" v-model="information.label"
                                                    type="text">
                                                <p class="em text-danger mb-0" :id="'errlabels.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Value'] ?? __('Value') }}</label>
                                                <input name="values[]" class="form-control"
                                                    :class="{ rtl: information.dir == 2 }" v-model="information.value"
                                                    type="text">
                                                <p class="em text-danger mb-0" :id="'errvalues.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-1">
                                            <button class="btn btn-danger text-white mt-4"
                                                @click="removeInformation(index)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    {{-- Infromation End --}}
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
                                    class="btn btn-success">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        "use strict";
        var vcardInfoUrl = "{{ route('user.vcard.information', $vcard->id) }}";
        var direction = {{ $vcard->direction }};
    </script>
    <script src="{{ asset('assets/user/vcard/edit-vcard.js') }}"></script>
@endsection
