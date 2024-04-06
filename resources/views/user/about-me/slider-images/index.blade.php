@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Slider_Images'] ?? __('Slider Images') }}</h4>
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
                <a href="#">{{ $keywords['Slider_Images'] ?? __('Slider Images') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-title d-inline-block">{{ $keywords['Slider_Images'] ?? __('Slider Images') }}
                            </div>
                        </div>

                        <div class="col-lg-4 mt-2 mt-lg-0">
                            <a href="#" data-toggle="modal" data-target="#createModal"
                                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_Image'] ?? __('Add Image') }}</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if (count($sliders) == 0)
                                <h3 class="text-center">{{ $keywords['NO_IMAGE_FOUND'] ?? __('NO IMAGE FOUND!') }}</h3>
                            @else
                                <div class="row">
                                    @foreach ($sliders as $slider)
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img class="w-100"
                                                        src="{{ asset('assets/user/img/authors/slider-images/' . $slider->image) }}"
                                                        alt="image">
                                                </div>

                                                <div class="card-footer text-center">
                                                    <a class="newEditBtn btn btn-secondary btn-sm mr-2" href="#"
                                                        data-toggle="modal" data-target="#editModal"
                                                        data-id="{{ $slider->id }}"
                                                        data-image="{{ asset('assets/user/img/authors/slider-images/' . $slider->image) }}"
                                                        data-serial_number="{{ $slider->serial_number }}">
                                                        <span class="btn-label">
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                        {{ $keywords['Edit'] ?? __('Edit') }}
                                                    </a>

                                                    <form class="deleteform d-inline-block"
                                                        action="{{ route('user.about_me.delete_slider_image') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="slider_id" value="{{ $slider->id }}">
                                                        <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                            <span class="btn-label">
                                                                <i class="fas fa-trash"></i>
                                                            </span>
                                                            {{ $keywords['Delete'] ?? __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- create modal --}}
    @include('user.about-me.slider-images.create')

    {{-- edit modal --}}
    @include('user.about-me.slider-images.edit')
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/user/dashboard/js/rtl.js') }}"></script>
@endsection
