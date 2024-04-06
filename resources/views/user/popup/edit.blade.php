@extends('user.layout')

@php
use App\Models\User\Language;
$default = Language::where('is_default', 1)
    ->where('user_id', Auth::user()->id)
    ->first();
@endphp


@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Popup'] ?? __('Edit Popup') }} </h4>
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
                <a href="#">{{ $keywords['Edit_Popup'] ?? __('Edit Popup') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">
                        {{ $keywords['Edit_Popup'] ?? __('Edit Popup') . ' (' . __('Type') . ' - ' . $popup->type . ')' }}
                    </div>
                    <a class="btn btn-info btn-sm float-right d-inline-block text-dark"
                        href="{{ route('user.announcement_popups', ['language' => request('language')]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>

                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <form id="ajaxEditForm"
                                action="{{ route('user.announcement_popups.update_popup', ['id' => $popup->id]) }}"
                                method="POST">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="type" value="{{ $popup->type }}">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label for="image"><strong>{{ $keywords['Image'] ?? __('Image') }}
                                                        **</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ asset('assets/user/img/popups/' . $popup->image) }}"
                                                    alt="..." class="img-thumbnail">
                                            </div>
                                            <input type="file" name="image" id="image" class="form-control">
                                            <p id="eerrimage" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ $keywords['Name'] ?? __('Name') . '*' }}</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="{{ $keywords['Enter_Popup_Name'] ?? __('Enter Popup Name') }}"
                                                value="{{ $popup->name }}">
                                            <p id="eerrname" class="mt-2 mb-0 text-danger em"></p>
                                            <p class="text-warning mt-2 mb-0">
                                                <small>{{ $keywords['Popup_Name_text'] ?? __('This name will not appear in UI. Rather then, it will help the admin to identify the popup.') }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if ($popup->type == 2 || $popup->type == 3 || $popup->type == 7)
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Background_Color_Code'] ?? __('Background Color Code') . '*' }}</label>
                                                <input class="jscolor form-control ltr" name="background_color"
                                                    value="{{ $popup->background_color }}">
                                                <p id="eerrbackground_color" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($popup->type == 2 || $popup->type == 3)
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Background_Color_Opacity'] ?? __('Background Color Opacity') . '*' }}</label>
                                                <input type="number" class="form-control ltr" step="0.01"
                                                    name="background_color_opacity"
                                                    value="{{ $popup->background_color_opacity }}">
                                                <p id="eerrbackground_color_opacity" class="mt-2 mb-0 text-danger em"></p>
                                                <p class="mt-2 mb-0 text-warning">
                                                    {{ $keywords['This_will_decide_the_transparency_level_of_the_color'] ?? __('This will decide the transparency level of the color.') }}<br>
                                                    {{ $keywords['Value_must_be_between_0_to_1'] ?? __('Value must be between 0 to 1.') }}<br>
                                                    {{ $keywords['Transparency_level_will_be_lower_with_the_increment_of_the_value'] ?? __('Transparency level will be lower with the increment of the value.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($popup->type == 2 ||
                                    $popup->type == 3 ||
                                    $popup->type == 4 ||
                                    $popup->type == 5 ||
                                    $popup->type == 6 ||
                                    $popup->type == 7)
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Title'] ?? __('Title') . '*' }}</label>
                                                <input type="text" class="form-control" name="title"
                                                    placeholder="{{ $keywords['Enter_Popup_Title'] ?? __('Enter Popup Title') }}"
                                                    value="{{ $popup->title }}">
                                                <p id="eerrtitle" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Text'] ?? __('Text') . '*' }}</label>
                                                <textarea class="form-control" name="text" placeholder="{{ __('Enter Popup Text') }}" rows="5">{{ $popup->text }}</textarea>
                                                <p id="eerrtext" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $keywords['Button_Text'] ?? __('Button Text') . '*' }}</label>
                                                <input type="text" class="form-control" name="button_text"
                                                    placeholder="{{ $keywords['Enter_Button_Text'] ?? __('Enter Button Text') }}"
                                                    value="{{ $popup->button_text }}">
                                                <p id="eerrbutton_text" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $keywords['Button_Color_Code'] ?? __('Button Color Code') . '*' }}</label>
                                                <input class="jscolor form-control ltr" name="button_color"
                                                    value="{{ $popup->button_color }}">
                                                <p id="eerrbutton_color" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($popup->type == 2 || $popup->type == 4 || $popup->type == 6 || $popup->type == 7)
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{ $keywords['Button_URL'] ?? __('Button URL') . '*' }}</label>
                                                <input type="url" class="form-control ltr" name="button_url"
                                                    placeholder="{{ $keywords['Enter_Button_URL'] ?? __('Enter Button URL') }}"
                                                    value="{{ $popup->button_url }}">
                                                <p id="eerrbutton_url" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($popup->type == 6 || $popup->type == 7)
                                    @php
                                        $endDate = Carbon\Carbon::parse($popup->end_date);
                                        $endDate = date_format($endDate, 'm/d/Y');
                                        $endTime = date('h:i A', strtotime($popup->end_time));
                                    @endphp
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $keywords['End_Date'] ?? __('End Date') . '*' }}</label>
                                                <input type="text" class="form-control datepicker ltr" name="end_date"
                                                    placeholder="{{ $keywords['Enter_End_Date'] ?? __('Enter End Date') }}"
                                                    readonly autocomplete="off" value="{{ $endDate }}">
                                                <p id="eerrend_date" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ $keywords['End_Time'] ?? __('End Time') . '*' }}</label>
                                                <input type="text" class="form-control timepicker ltr" name="end_time"
                                                    placeholder="{{ $keywords['Enter_End_Time'] ?? __('Enter End Time') }}"
                                                    readonly autocomplete="off" value="{{ $endTime }}">
                                                <p id="eerrend_time" class="mt-2 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>{{ $keywords['Delay'] ?? __('Delay') . ' (' . __('milliseconds') . ')*' }}</label>
                                            <input type="number" class="form-control ltr" name="delay"
                                                placeholder="{{ $keywords['Enter_Popup_Delay'] ?? __('Enter Popup Delay') }}"
                                                value="{{ $popup->delay }}">
                                            <p id="eerrdelay" class="mt-2 mb-0 text-danger em"></p>
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
                                                placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}"
                                                value="{{ $popup->serial_number }}">
                                            <p id="eerrserial_number" class="mt-2 mb-0 text-danger em"></p>
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
                            <button type="submit" class="btn btn-success" id="updateBtn">
                                {{ $keywords['Update'] ?? __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
