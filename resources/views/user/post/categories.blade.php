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
        <h4 class="page-title">{{ $keywords['Categories'] ?? __('Categories') }}</h4>
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
                <a href="#">{{ $keywords['Post_Management'] ?? __('Post Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Categories'] ?? __('Categories') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">
                                {{ $keywords['Post_Categories'] ?? __('Post Categories') }}</div>
                        </div>

                        <div class="col-lg-3">

                        </div>

                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" data-toggle="modal" data-target="#createModal"
                                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_Category'] ?? __('Add Category') }}</a>

                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                data-href="{{ route('user.post_management.bulk_delete_category') }}">
                                <i class="flaticon-interface-5"></i> {{ $keywords['Delete'] ?? __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        @if ($count > $category_limit)
                            <div class="col-12">
                                <div class="alert alert-danger" role="alert">
                                    {{ $keywords['Your_current_package_supports'] ?? __('Your current package supports') }}
                                    <strong>{{ $category_limit }}
                                        {{ $keywords['Post_Categories'] ?? __('Post Categories') }}</strong>. <br>
                                    {{ $keywords['Currently_you_have'] ?? __('Currently, you have') }}
                                    <strong>{{ $count }}
                                        {{ $keywords['Post_Categories'] ?? __('Post Categories') }}.</strong> <br>
                                    {{ $keywords['You_have_to_delete'] ?? __('You have to delete') }}
                                    <strong>{{ $count - $category_limit }}
                                        {{ $keywords['Post_Categories'] ?? __('Post Categories') }}</strong>
                                    {{ $keywords['to_enable_editing_feature_of'] ?? __('to enable editing feature of') }}
                                    {{ $keywords['Post_Categories'] ?? __('Post Categories') }}. <br>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-12">
                            @if (count($categories) == 0)
                                <h3 class="text-center">
                                    {{ $keywords['No_Post_Category_Found'] ?? __('NO POST CATEGORY FOUND!') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                @if ($userBs->theme_version == 1 ||$userBs->theme_version == 6 || $userBs->theme_version == 7)
                                                    <th scope="col">{{ $keywords['Image'] ?? __('Image') }}</th>
                                                @endif
                                                <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                <th scope="col">{{ $keywords['Status'] ?? __('Status') }}</th>
                                                <th scope="col">{{ $keywords['Featured'] ?? __('Featured') }}</th>
                                                <th scope="col">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $category->id }}">
                                                    </td>
                                                    @if ($userBs->theme_version == 1 ||$userBs->theme_version == 6 || $userBs->theme_version == 7)
                                                        <td>
                                                            <img src="{{ asset('assets/user/img/post-categories/' . $category->image) }}"
                                                                alt="category image" width="50">
                                                        </td>
                                                    @endif
                                                    <td>
                                                        {{ strlen($category->name) > 100 ? convertUtf8(substr($category->name, 0, 100)) . '...' : convertUtf8($category->name) }}
                                                    </td>
                                                    <td>
                                                        @if ($category->status == 1)
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-success">{{ $keywords['Active'] ?? __('Active') }}</span>
                                                            </h2>
                                                        @else
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-danger">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                                            </h2>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form id="featuredForm-{{ $category->id }}" class="d-inline-block"
                                                            action="{{ route('user.post_management.update_featured', ['id' => $category->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ $category->is_featured == 1 ? 'bg-success' : 'bg-danger' }}"
                                                                name="is_featured"
                                                                onchange="document.getElementById('featuredForm-{{ $category->id }}').submit();">
                                                                <option value="1"
                                                                    {{ $category->is_featured == 1 ? 'selected' : '' }}>
                                                                    {{ $keywords['Yes'] ?? __('Yes') }}
                                                                </option>
                                                                <option value="0"
                                                                    {{ $category->is_featured == 0 ? 'selected' : '' }}>
                                                                    {{ $keywords['No'] ?? __('No') }}
                                                                </option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td>{{ $category->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm mr-1 newEditBtn" href="#"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $category->id }}"
                                                            data-image="{{ asset('assets/user/img/post-categories/' . $category->image) }}"
                                                            data-name="{{ $category->name }}"
                                                            data-status="{{ $category->status }}"
                                                            data-serial_number="{{ $category->serial_number }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>

                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.post_management.delete_category', ['id' => $category->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ $keywords['Delete'] ?? __('Delete') }}
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
    @include('user.post.create-category')

    {{-- edit modal --}}
    @include('user.post.edit-category')
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/user/dashboard/js/rtl.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/dashboard/js/edit-image-modal.js') }}"></script>
@endsection
