@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Theme_and_Home'] ?? __('Theme & Home') }}</h4>
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
                <a href="#">{{ $keywords['Basic_Settings'] ?? __('Basic Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Theme_and_Home'] ?? __('Theme & Home') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('user.basic_settings.update_theme_and_home') }}" method="post">
                    @csrf

                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">
                                    {{ $keywords['Update_Theme_and_Home'] ?? __('Update Theme & Home') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>{{ $keywords['Theme_Version'] ?? __('Theme Version') . '*' }}</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="1"
                                                    class="imagecheck-input"
                                                    {{ $data->theme_version == 1 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/user/img/themes/1.jpg') }}" alt="theme 1"
                                                        class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <p class="text-center font-weight-bold">
                                                {{ $keywords['Theme_1'] ?? __('Theme 1') }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="2"
                                                    class="imagecheck-input"
                                                    {{ $data->theme_version == 2 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/user/img/themes/2.jpg') }}" alt="theme 2"
                                                        class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <p class="text-center font-weight-bold">
                                                {{ $keywords['Theme_2'] ?? __('Theme 2') }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="3"
                                                    class="imagecheck-input"
                                                    {{ $data->theme_version == 3 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/user/img/themes/3.jpg') }}" alt="theme 3"
                                                        class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <p class="text-center font-weight-bold">
                                                {{ $keywords['Theme_3'] ?? __('Theme 3') }}</p>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="4"
                                                    class="imagecheck-input"
                                                    {{ $data->theme_version == 4 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/user/img/themes/4.jpg') }}" alt="theme 4"
                                                        class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <p class="text-center font-weight-bold">
                                                {{ $keywords['Theme_4'] ?? __('Theme 4') }}</p>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="5"
                                                    class="imagecheck-input"
                                                    {{ $data->theme_version == 5 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/user/img/themes/5.jpg') }}" alt="theme 5"
                                                        class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <p class="text-center font-weight-bold">
                                                {{ $keywords['Theme_5'] ?? __('Theme 5') }}</p>

                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="6"
                                                    class="imagecheck-input"
                                                    {{ $data->theme_version == 6 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/user/img/themes/6.jpg') }}" alt="theme 6"
                                                        class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <p class="text-center font-weight-bold">
                                                {{ $keywords['Theme_6'] ?? __('Theme 6') }}</p>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label class="imagecheck">
                                                <input name="theme_version" type="radio" value="7"
                                                    class="imagecheck-input"
                                                    {{ $data->theme_version == 7 ? 'checked' : '' }}>
                                                <figure class="imagecheck-figure">
                                                    <img src="{{ asset('assets/user/img/themes/7.jpg') }}" alt="theme 7"
                                                        class="imagecheck-image">
                                                </figure>
                                            </label>
                                            <p class="text-center font-weight-bold">
                                                {{ $keywords['Theme_7'] ?? __('Theme 7') }}</p>
                                        </div>
                                        @if ($errors->has('theme_version'))
                                            <p class="mb-0 ml-3 text-danger">{{ $errors->first('theme_version') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ $keywords['Update'] ?? __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
