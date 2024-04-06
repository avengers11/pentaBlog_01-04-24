@extends('user.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@php
use App\Models\User\Language;
$selLang = Language::where([['code', request()->input('language')], ['user_id', \Illuminate\Support\Facades\Auth::id()]])->first();
$userDefaultLang = Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
$userLanguages = Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
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
        <h4 class="page-title">{{ $keywords['Gallery'] ?? __('Gallery') }}</h4>
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
                <a href="#">{{ $keywords['Gallery_Management'] ?? __('Gallery Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Gallery'] ?? __('Gallery') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Gallery_Items'] ?? __('Gallery Items') }}
                            </div>
                        </div>

                        <div class="col-lg-3">
                            
                        </div>

                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" data-toggle="modal" data-target="#createModal"
                                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_Item'] ?? __('Add Item') }}</a>

                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('user.gallery_management.bulk_delete_item') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($items) == 0)
                                <h3 class="text-center">
                                    {{ $keywords['NO_GALLERY_ITEM_FOUND'] ?? __('NO GALLERY ITEM FOUND!') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['Title'] ?? __('Title') }}</th>
                                                <th scope="col">{{ $keywords['Category'] ?? __('Category') }}</th>
                                                <th scope="col">{{ $keywords['Item_Type'] ?? __('Item Type') }}</th>
                                                <th scope="col">{{ $keywords['Featured'] ?? __('Featured') }}</th>
                                                <th scope="col">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        {{ strlen($item->title) > 30 ? mb_substr($item->title, 0, 30, 'UTF-8') . '...' : $item->title }}
                                                    </td>
                                                    <td>
                                                        {{ !empty($item->itemCategory->name) ? $item->itemCategory->name : '-' }}
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ $item->item_type == 'image' ? 'image' : 'video' }}
                                                    </td>
                                                    <td>
                                                        <form id="featuredForm{{ $item->id }}" class="d-inline-block"
                                                            action="{{ route('user.gallery_management.update_featured_item', ['id' => $item->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ $item->is_featured == 1 ? 'bg-success' : 'bg-danger' }}"
                                                                name="is_featured"
                                                                onchange="document.getElementById('featuredForm{{ $item->id }}').submit()">
                                                                <option value="1"
                                                                    {{ $item->is_featured == 1 ? 'selected' : '' }}>
                                                                    {{ $keywords['Yes'] ?? __('Yes') }}
                                                                </option>
                                                                <option value="0"
                                                                    {{ $item->is_featured == 0 ? 'selected' : '' }}>
                                                                    {{ $keywords['No'] ?? __('No') }}
                                                                </option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td>{{ $item->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm mr-1 newEditBtn" href="#"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $item->id }}"
                                                            data-gallery_category_id="{{ $item->gallery_category_id }}"
                                                            data-item_type="{{ $item->item_type }}"
                                                            data-image="{{ asset('assets/user/img/gallery/' . $item->image) }}"
                                                            data-video_link="{{ $item->video_link }}"
                                                            data-title="{{ $item->title }}"
                                                            data-serial_number="{{ $item->serial_number }}"
                                                            data-edit="editGallery">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.gallery_management.delete_item', ['id' => $item->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <i class="fas fa-trash"></i>
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
    {{-- create modal --}}
    @include('user.gallery.create')

    {{-- edit modal --}}
    @include('user.gallery.edit')
@endsection

@section('scripts')
    <script>
        "use strict";
        const currUrl = "{{ url()->current() }}";
        const mainURL = "{{ url('/') }}";
    </script>
    <script src="{{ asset('assets/user/dashboard/js/gallery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/dashboard/js/rtl.js') }}"></script>
@endsection
