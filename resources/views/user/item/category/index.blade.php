@extends('user.layout')

@php
$userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id], ['is_default', 1]])->first();
$userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id)->get();
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
        <h4 class="page-title">{{ $keywords['Item_Categories'] ?? __('Item Categories') }}</h4>
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
                <a href="#">{{ $keywords['Shop_Management'] ?? __('Shop Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Manage_Items'] ?? __('Manage Items') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Item_Categories'] ?? __('Item Categories') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Categories'] ?? __('Categories') }}</div>
                        </div>
                        <div class="col-lg-3">

                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                data-target="#createModal"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_Category'] ?? __('Add Category') }}</a>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('user.itemcategory.bulk.delete') }}"><i
                                    class="flaticon-interface-5"></i> {{ $keywords['Delete'] ?? __('Delete') }}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($itemcategories) == 0)
                                <h3 class="text-center">
                                    {{ $keywords['NO_ITEM_CATEGORY_FOUND'] ?? __('NO ITEM CATEGORY FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                <th scope="col">{{ $keywords['Image'] ?? __('Image') }}</th>

                                                <th scope="col">{{ $keywords['Featured'] ?? __('Featured') }}</th>

                                                <th scope="col">{{ $keywords['Status'] ?? __('Status') }}</th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($itemcategories as $key => $category)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $category->id }}">
                                                    </td>
                                                    <td>{{ convertUtf8($category->name) }}</td>
                                                    <td>
                                                        <img style="height: 5rem" src="{{ $category->image ? Storage::url($category->image) : asset('assets/admin/img/noimage.jpg') }}"
                                                            alt="..." class="img-thumbnail">
                                                    </td>
                                                    <td>
                                                        <form class="d-inline-block"
                                                            action="{{ route('user.itemcategory.feature') }}"
                                                            id="featureForm{{ $category->id }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="category_id"
                                                                value="{{ $category->id }}">
                                                            <select name="is_feature" id=""
                                                                class="form-control form-control-sm
                                                                        @if ($category->is_feature == 1) bg-success
                                                                        @else
                                                                        bg-danger @endif
                                                                        "
                                                                onchange="document.getElementById('featureForm{{ $category->id }}').submit();">
                                                                <option value="1"
                                                                    {{ $category->is_feature == 1 ? 'selected' : '' }}>
                                                                    {{ $keywords['Yes'] ?? __('Yes') }}</option>
                                                                <option value="0"
                                                                    {{ $category->is_feature == 0 ? 'selected' : '' }}>
                                                                    {{ $keywords['No'] ?? __('No') }}</option>
                                                            </select>
                                                        </form>
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
                                                        <a class="btn btn-secondary btn-sm editbtn"
                                                            href="{{ route('user.itemcategory.edit', $category->id) . '?language=' . request()->input('language') }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.itemcategory.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="category_id"
                                                                value="{{ $category->id }}">
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
                <div class="card-footer">
                    <div class="row">
                        <div class="d-inline-block mx-auto">
                            {{ $itemcategories->appends(['language' => request()->input('language')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Create Service Category Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ $keywords['Add_Item_Category'] ?? __('Add Item Category') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxForm" class="modal-form" enctype="multipart/form-data"
                        action="{{ route('user.itemcategory.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">{{ $keywords['Language'] ?? __('Language') }} **</label>
                            <select id="language" name="user_language_id" class="form-control">
                                <option value="" selected disabled>
                                    {{ $keywords['Select_a_Language'] ?? __('Select a Language') }}</option>
                                @foreach ($userLanguages as $lang)
                                    <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                            <p id="erruser_language_id" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <div class="col-12 mb-2">
                                <label for="image"><strong>{{ $keywords['Category_Image'] ?? __('Category Image') }}
                                        </strong></label>
                            </div>
                            <div class="col-md-12 showImage mb-3">
                                <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                    class="img-thumbnail">
                            </div>
                            <input type="file" name="image" id="image" class="form-control">
                            <p id="errimage" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                            <input type="text" class="form-control" name="name" value=""
                                placeholder="{{ $keywords['Enter_name'] ?? __('Enter name') }}">
                            <p id="errname" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label for="">{{ $keywords['Status'] ?? __('Status') }} **</label>
                            <select class="form-control ltr" name="status">
                                <option value="" selected disabled>
                                    {{ $keywords['Select_a_status'] ?? __('Select a status') }}</option>
                                <option value="1">{{ $keywords['Active'] ?? __('Active') }}</option>
                                <option value="0">{{ $keywords['Deactive'] ?? __('Deactive') }}</option>
                            </select>
                            <p id="errstatus" class="mb-0 text-danger em"></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                    <button id="submitBtn" type="button"
                        class="btn btn-primary">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // make input fields RTL
            $("select[name='language_id']").on('change', function() {
                $(".request-loader").addClass("show");
                let url = "{{ url('/') }}/admin/rtlcheck/" + $(this).val();
                console.log(url);
                $.get(url, function(data) {
                    $(".request-loader").removeClass("show");
                    if (data == 1) {
                        $("form.modal-form input").each(function() {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form.modal-form select").each(function() {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form.modal-form textarea").each(function() {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form.modal-form .nicEdit-main").each(function() {
                            $(this).addClass('rtl text-right');
                        });

                    } else {
                        $("form.modal-form input, form.modal-form select, form.modal-form textarea")
                            .removeClass('rtl');
                        $("form.modal-form .nicEdit-main").removeClass('rtl text-right');
                    }
                })
            });
        });
    </script>
@endsection
