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
        <h4 class="page-title">{{ $keywords['Announcement_Popups'] ?? __('Announcement Popups') }}</h4>
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
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Popups'] ?? __('Popups') }}</div>
                        </div>

                        <div class="col-lg-3">

                        </div>

                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="{{ route('user.announcement_popups.select_popup_type', ['language' => request('language')]) }}"
                                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_Popup'] ?? __('Add Popup') }}</a>

                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                data-href="{{ route('user.announcement_popups.bulk_delete_popup') }}">
                                <i class="flaticon-interface-5"></i> {{ $keywords['Delete'] ?? __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($popups) == 0)
                                <h3 class="text-center mt-3">{{ $keywords['NO_POPUP_FOUND'] ?? __('NO POPUP FOUND') . '!' }}
                                </h3>
                            @else
                                <div class="alert alert-warning text-center" role="alert">
                                    <strong
                                        class="text-dark">{{ $keywords['POPUP_MESSAGE'] ?? __('All activated popups will be appear in UI according to serial number.') }}</strong>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['Image'] ?? __('Image') }}</th>
                                                <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                <th scope="col">{{ $keywords['Type'] ?? __('Type') }}</th>
                                                <th scope="col">{{ $keywords['Status'] ?? __('Status') }}</th>
                                                <th scope="col">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($popups as $popup)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $popup->id }}">
                                                    </td>
                                                    <td>
                                                        <img src="{{ asset('assets/user/img/popups/' . $popup->image) }}"
                                                            alt="popup image" width="55">
                                                    </td>
                                                    <td>{{ $popup->name }}</td>
                                                    <td>
                                                        <img src="{{ asset('assets/user/dashboard/img/popup-samples/' . $popup->type . '.jpg') }}"
                                                            alt="popup type image" class="pt-4" width="55">
                                                        <p class="mt-1 text-muted">
                                                            {{ $keywords['Type'] ?? __('Type') . ' - ' . $popup->type }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <form id="statusForm-{{ $popup->id }}" class="d-inline-block"
                                                            action="{{ route('user.announcement_popups.update_popup_status', ['id' => $popup->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ $popup->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                                                name="status"
                                                                onchange="document.getElementById('statusForm-{{ $popup->id }}').submit()">
                                                                <option value="1"
                                                                    {{ $popup->status == 1 ? 'selected' : '' }}>
                                                                    {{ $keywords['Active'] ?? __('Active') }}
                                                                </option>
                                                                <option value="0"
                                                                    {{ $popup->status == 0 ? 'selected' : '' }}>
                                                                    {{ $keywords['Deactive'] ?? __('Deactive') }}
                                                                </option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td>{{ $popup->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-xs mr-1"
                                                            href="{{ route('user.announcement_popups.edit_popup', ['id' => $popup->id, 'language' => request('language')]) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                        </a>

                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.announcement_popups.delete_popup', ['id' => $popup->id]) }}"
                                                            method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-xs deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>
@endsection
