@extends('user.layout')

@if (!empty($data->language) && $data->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }

            .nicEdit-main {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Subcategory'] ?? __('Edit Subcategory') }}</h4>
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
                <a href="#">{{ $keywords['Edit_Subcategory'] ?? __('Edit Subcategory') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Subcategory'] ?? __('Edit Subcategory') }}
                    </div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.itemsubcategory.index') . '?language=' . request()->input('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward" style="font-size: 12px;"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm" action="{{ route('user.itemsubcategory.update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ $keywords['Category'] ?? __('Category') }} **</label>
                                    <select id="language" name="category_id" class="form-control">
                                        <option value="" selected disabled>
                                            {{ $keywords['Select_a_category'] ?? __('Select a category') }}</option>
                                        @foreach ($categories as $category)
                                            <option {{ $data->category_id == $category->id ? 'selected' : '' }}
                                                value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <p id="errcategory_id" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                                    <input type="text" class="form-control" name="name" value="{{ $data->name }}"
                                        placeholder="{{ $keywords['Enter_name'] ?? __('Enter name') }}">
                                    <p id="errname" class="mb-0 text-danger em"></p>
                                </div>
                                <input type="hidden" name="subcategory_id" value="{{ $data->id }}">

                                <div class="form-group">
                                    <label for="">{{ $keywords['Status'] ?? __('Status') }} **</label>
                                    <select class="form-control ltr" name="status">
                                        <option value="" selected disabled>
                                            {{ $keywords['Select_a_status'] ?? __('Select a status') }}</option>
                                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>
                                            {{ $keywords['Active'] ?? __('Active') }}</option>
                                        <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>
                                            {{ $keywords['Deactive'] ?? __('Deactive') }}</option>
                                    </select>
                                    <p id="errstatus" class="mb-0 text-danger em"></p>
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
