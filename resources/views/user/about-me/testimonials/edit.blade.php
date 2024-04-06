@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Testimonial'] ?? __('Edit Testimonial') }}</h4>
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
                <a href="#">{{ $keywords['Testimonials'] ?? __('Testimonials') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit_Testimonial'] ?? __('Edit Testimonial') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Testimonial'] ?? __('Edit Testimonial') }}
                    </div>
                    <a class="btn btn-info btn-sm float-right d-inline-block text-dark"
                        href="{{ route('user.about_me.testimonials', ['language' => request('language')]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>

                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="alert alert-danger pb-1 dis-none" id="testimonialErrors">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul></ul>
                            </div>

                            <form id="testimonialForm"
                                action="{{ route('user.about_me.update_testimonial', ['id' => $testimonial->id]) }}"
                                method="POST">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <div class="col-12 mb-2">
                                        <label
                                            for="">{{ $keywords['Clients_Image'] ?? __('Client\'s Image') }}</label>
                                    </div>
                                    <div class="col-md-12 showImage mb-3">
                                        <img src="{{ isset($testimonial->client_image) ? asset('assets/user/img/testimonials/' . $testimonial->client_image) : asset('assets/user/img/noimage.jpg') }}"
                                            alt="..." class="img-thumbnail">
                                    </div>
                                    <input type="file" name="client_image" id="image" class="form-control image">
                                    @if ($errors->has('client_image'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('client_image') }}</p>
                                    @endif
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}</label>
                                            <input type="number" class="form-control" name="serial_number"
                                                placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}"
                                                value="{{ $testimonial->serial_number }}">
                                            <p class="text-warning mt-2">
                                                {{ $keywords['Serial_Number_Text'] ?? __('The higher the serial number is, the later the item will be shown.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['Clients_Rating'] ?? __('Client\'s Rating') }}
                                                * </label>
                                            <input type="number" class="form-control" name="rating"
                                                placeholder="{{ $keywords['Enter_Client_Rating'] ?? __('Enter Client Rating') }}"
                                                value="{{ $testimonial->rating }}">
                                        </div>
                                    </div>
                                </div>

                                <div id="accordion" class="mt-4">
                                    @foreach ($languages as $language)
                                        @php
                                            $testimonialData = $language
                                                ->testimonialDetails()
                                                ->where('testimonial_id', $testimonial->id)
                                                ->first();
                                        @endphp

                                        <div class="version">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                                        data-target="#collapse{{ $language->id }}"
                                                        aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . __(' Language') }}
                                                        {{ $language->is_default == 1 ? '(Default)' : '' }}
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse{{ $language->id }}"
                                                class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div class="version-body">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Clients_Name'] ?? __('Client\'s Name') }}*</label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $language->code }}_client_name"
                                                                    placeholder="{{ $keywords['Enter_Client_Name'] ?? __('Enter Client Name') }}"
                                                                    value="{{ is_null($testimonialData) ? '' : $testimonialData->client_name }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Clients_Comment'] ?? __('Client\'s Comment') }}*</label>
                                                                <textarea class="form-control" name="{{ $language->code }}_comment"
                                                                    placeholder="{{ $keywords['Enter_Client_Comment'] ?? __('Enter Client Comment') }}" rows="5">{{ is_null($testimonialData) ? '' : $testimonialData->comment }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" id="testForm" class="btn btn-success">
                                {{ $keywords['Update'] ?? __('Update') }}
                            </button>
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
        var currUrl = "{{ url()->current() }}";
    </script>
    <script src="{{ asset('assets/user/dashboard/js/testimonial.js') }}"></script>
@endsection
