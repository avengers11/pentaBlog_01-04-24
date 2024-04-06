@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Add_Popup'] ?? __('Add Popup') }}</h4>
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
                <a href="#">{{ $keywords['Announcement_Popups'] ?? __('Announcement Popups') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Add_Popup'] ?? __('Add Popup') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">
                        {{ $keywords['Add_Popup'] ?? __('Add Popup') . ' (' . __('Type') . ' - ' . $popupType . ')' }}
                    </div>
                    <a class="btn btn-info btn-sm float-right d-inline-block text-dark"
                        href="{{ route('user.announcement_popups.select_popup_type', ['language' => request('language')]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>

                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form id="ajaxForm" class="create" action="{{ route('user.announcement_popups.store_popup') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="{{ $popupType }}">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label for="image"><strong>{{ $keywords['Image'] ?? __('Image') }}
                                                        **</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                                    class="img-thumbnail">
                                            </div>
                                            <input type="file" name="image" id="image" class="form-control">
                                            <p id="errimage" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ $keywords['Language'] ?? __('Language') . '*' }}</label>
                                            <select name="user_language_id" class="form-control">
                                                <option selected disabled>
                                                    {{ $keywords['Select_a_Language'] ?? __('Select a Language') }}
                                                </option>
                                                @foreach ($languages as $language)
                                                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="erruser_language_id" class="mt-2 mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ $keywords['Name'] ?? __('Name') . '*' }}</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="{{ $keywords['Enter_Popup_Name'] ?? __('Enter Popup Name') }}">
                                            <p id="errname" class="mt-2 mb-0 text-danger em"></p>
                                            <p class="text-warning mt-2 mb-0">
                                                <small>{{ $keywords['Popup_Name_text'] ?? __('This name will not appear in UI. Rather then, it will help the admin to identify the popup.') }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if ($popupType == 2 || $popupType == 3 || $popupType == 7)
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Background_Color_Code'] ?? __('Background Color Code') . '*' }}</label>
                                                <input class="jscolor form-control ltr" name="background_color">
                                                <p id="err_background_color" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($popupType == 2 || $popupType == 3)
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Background_Color_Opacity'] ?? __('Background Color Opacity') . '*' }}</label>
                                                <input type="number" class="form-control ltr" step="0.01"
                                                    name="background_color_opacity">
                                                <p id="errbackground_color_opacity" class="mt-2 mb-0 text-danger em"></p>
                                                <p class="mt-2 mb-0 text-warning">
                                                    {{ $keywords['This_will_decide_the_transparency_level_of_the_color'] ?? __('This will decide the transparency level of the color.') }}<br>
                                                    {{ $keywords['Value_must_be_between_0_to_1'] ?? __('Value must be between 0 to 1.') }}<br>
                                                    {{ $keywords['Transparency_level_will_be_lower_with_the_increment_of_the_value'] ?? __('Transparency level will be lower with the increment of the value.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($popupType == 2 ||
                                    $popupType == 3 ||
                                    $popupType == 4 ||
                                    $popupType == 5 ||
                                    $popupType == 6 ||
                                    $popupType == 7)
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Title'] ?? __('Title') . '*' }}</label>
                                                <input type="text" class="form-control" name="title"
                                                    placeholder="{{  $keywords['Enter_Popup_Title'] ?? __('Enter Popup Title') }}">
                                                <p id="errtitle" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Text'] ?? __('Text') . '*' }}</label>
                                                <textarea class="form-control" name="text" placeholder="{{ $keywords['Enter_Popup_Text'] ?? __('Enter Popup Text') }}" rows="5"></textarea>
                                                <p id="errtext" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $keywords['Button_Text'] ?? __('Button Text') . '*' }}</label>
                                                <input type="text" class="form-control" name="button_text"
                                                    placeholder="{{ $keywords['Enter_Button_Text'] ?? __('Enter Button Text') }}">
                                                <p id="errbutton_text" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $keywords['Button_Color_Code'] ?? __('Button Color Code') . '*' }}</label>
                                                <input class="jscolor form-control ltr" name="button_color">
                                                <p id="errbutton_color" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($popupType == 2 || $popupType == 4 || $popupType == 6 || $popupType == 7)
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Button_URL'] ?? __('Button URL') . '*' }}</label>
                                                <input type="url" class="form-control ltr" name="button_url"
                                                    placeholder="{{ $keywords['Enter_Button_URL'] ?? __('Enter Button URL') }}">
                                                <p id="errbutton_url" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($popupType == 6 || $popupType == 7)
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $keywords['End_Date'] ?? __('End Date') . '*' }}</label>
                                                <input type="text" class="form-control datepicker ltr" name="end_date"
                                                    placeholder="{{ $keywords['Enter_End_Date'] ?? __('Enter End Date') }}"
                                                    readonly autocomplete="off">
                                                <p id="errend_date" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $keywords['End_Time'] ?? __('End Time') . '*' }}</label>
                                                <input type="text" class="form-control timepicker ltr" name="end_time"
                                                    placeholder="{{ $keywords['Enter_End_Time'] ?? __('Enter End Time') }}"
                                                    readonly autocomplete="off">
                                                <p id="errend_time" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ $keywords['Delay'] ?? __('Delay') . ' (' . __('milliseconds') . ')*' }}</label>
                                            <input type="number" class="form-control ltr" name="delay"
                                                placeholder="{{ $keywords['Enter_Popup_Delay'] ?? __('Enter Popup Delay') }}">
                                            <p id="errdelay" class="mt-2 mb-0 text-danger em"></p>
                                            <p class="text-warning mt-2 mb-0">
                                                <small>{{ $keywords['Popup_Delay_text'] ?? __('Popup will appear in UI after this delay time.') }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ $keywords['Serial_Number'] ?? __('Serial Number') . '*' }}</label>
                                            <input type="number" class="form-control ltr" name="serial_number"
                                                placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                            <p id="errserial_number" class="mt-2 mb-0 text-danger em"></p>
                                            <p class="mt-2 mb-0 text-warning">
                                                {{ $keywords['Popup_Serial_Number_1'] ?? __('If there are multiple active popups, then popups will be shown in UI according to serial number.') }}<br>
                                                {{ $keywords['Popup_Serial_Number_2'] ?? __('The higher the serial number is, the later the Popup will be shown.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                {{ $keywords['Save'] ?? __('Save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
