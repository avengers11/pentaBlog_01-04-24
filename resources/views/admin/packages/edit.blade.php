@extends('admin.layout')

@php
use App\Models\Language;
$selLang = Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang->language) && $selLang->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }

            form .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Edit package') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Packages') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit package') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.package.index') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm" class="" action="{{ route('admin.package.update') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{ __('Package title') }}*</label>
                                            <input id="title" type="text" class="form-control" name="title"
                                                value="{{ $package->title }}" placeholder="{{ __('Enter name') }}">
                                            <p id="errtitle" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group py-0">
                                            <label for="price">{{ __('Price') }}
                                                ({{ $bex->base_currency_text }})*</label>
                                            <input id="price" type="number" class="form-control" name="price"
                                                placeholder="{{ __('Enter Package price') }}"
                                                value="{{ $package->price }}">
                                            <p class="text-warning mb-0">
                                                <small>{{ __('If price is 0 , then it will appear as free') }}</small>
                                            </p>
                                            <p id="errprice" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="plan_term">{{ __('Package Duration') }}*</label>
                                    <select id="plan_term" name="term" class="form-control">
                                        <option value="" selected disabled>{{ __('Choose Package Duration') }}
                                        </option>
                                        <option value="monthly" {{ $package->term == 'monthly' ? 'selected' : '' }}>
                                            {{ __('monthly') }}</option>
                                        <option value="yearly" {{ $package->term == 'yearly' ? 'selected' : '' }}>
                                            {{ __('yearly') }}</option>
                                        <option value="lifetime" {{ $package->term == 'lifetime' ? 'selected' : '' }}>
                                            {{ __('lifetime') }}</option>
                                    </select>
                                    <p id="errterm" class="mb-0 text-danger em"></p>
                                </div>
                                @php
                                    $permissions = $package->features;
                                    if (!empty($package->features)) {
                                        $permissions = json_decode($permissions, true);
                                    }
                                @endphp
                               
                                <div class="form-group ">
                                    <label class="form-label">{{ __('Package Features') }}</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Custom Domain"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('Custom Domain', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Custom Domain') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Subdomain"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('Subdomain', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Subdomain') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="QR Builder"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('QR Builder', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('QR Builder') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="vCard"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('vCard', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('vCard') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Gallery"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('Gallery', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Gallery') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="FAQ"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('FAQ', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('FAQ') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Custom Pages"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('Custom Pages', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Custom Pages') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Advertisement"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('Advertisement', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Advertisement') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Ecommerce"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('Ecommerce', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Ecommerce') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Google Analytics"
                                                class="selectgroup-input"
                                                @if (is_array($permissions) && in_array('Google Analytics', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Google Analytics') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Google Recaptcha"
                                                class="selectgroup-input"
                                                @if (is_array($permissions) && in_array('Google Recaptcha', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Google Recaptcha') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Disqus"
                                                class="selectgroup-input"
                                                @if (is_array($permissions) && in_array('Disqus', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Disqus') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="WhatsApp"
                                                class="selectgroup-input"
                                                @if (is_array($permissions) && in_array('WhatsApp', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('WhatsApp') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Facebook Pixel"
                                                class="selectgroup-input"
                                                @if (is_array($permissions) && in_array('Facebook Pixel', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Facebook Pixel') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Tawk.to"
                                                class="selectgroup-input"
                                                @if (is_array($permissions) && in_array('Tawk.to', $permissions)) checked @endif>
                                            <span class="selectgroup-button">{{ __('Tawk.to') }}</span>
                                        </label>
                                    </div>
                                </div>
                                

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="feature_posts">{{ __('Featured Posts Limit') }}*</label>
                                            <input id="feature_posts" type="number" class="form-control"
                                                name="feature_posts_limit"
                                                placeholder="{{ __('Enter featured posts limit') }}"
                                                value="{{ $package->feature_posts_limit }}">
                                            <p class="text-warning mb-0">
                                                <small>{{ __('Enter 999999 , then it will appear as unlimited') }}</small>
                                            </p>
                                            <p id="errfeature_posts_limit" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="post_categories">{{ __('Post Categories Limit') }}*</label>
                                            <input id="post_categories" type="number" class="form-control"
                                                name="post_categories_limit"
                                                placeholder="{{ __('Enter post categories limit') }}"
                                                value="{{ $package->post_categories_limit }}">
                                            <p class="text-warning mb-0">
                                                <small>{{ __('Enter 999999 , then it will appear as unlimited') }}</small>
                                            </p>
                                            <p id="errpost_categories_limit" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="posts">{{ __('Posts Limit') }}*</label>
                                            <input id="posts" type="number" class="form-control" name="posts_limit"
                                                placeholder="{{ __('Enter posts limit') }}"
                                                value="{{ $package->posts_limit }}">
                                            <p class="text-warning mb-0">
                                                <small>{{ __('Enter 999999 , then it will appear as unlimited') }}</small>
                                            </p>
                                            <p id="errposts_limit" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="languages">{{ __('Language Limit') }}*</label>
                                            <input id="languages" type="number" class="form-control"
                                                name="language_limit" placeholder="{{ __('Enter language limit') }}"
                                                value="{{ $package->language_limit }}">
                                            <p class="text-warning mb-0">
                                                <small>{{ __('Enter 999999 , then it will appear as unlimited') }}</small>
                                            </p>
                                            <p id="errlanguage_limit" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Featured') }} *</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="featured" value="1"
                                                        class="selectgroup-input"
                                                        {{ $package->featured == 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Yes') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="featured" value="0"
                                                        class="selectgroup-input"
                                                        {{ $package->featured == 0 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('No') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Trial') }} *</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="is_trial" value="1"
                                                        class="selectgroup-input"
                                                        {{ $package->is_trial == 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Yes') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="is_trial" value="0"
                                                        class="selectgroup-input"
                                                        {{ $package->is_trial == 0 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('No') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($package->is_trial == 1)
                                    <div class="form-group dis-block" id="trial_day">
                                        <label for="trial_days_2">{{ __('Trial days') }}*</label>
                                        <input id="trial_days_2" type="number" class="form-control" name="trial_days"
                                            placeholder="{{ __('Enter trial days') }}"
                                            value="{{ $package->trial_days }}">
                                    </div>
                                @else
                                    <div class="form-group dis-none" id="trial_day">
                                        <label for="trial_days_1">{{ __('Trial days') }}*</label>
                                        <input id="trial_days_1" type="number" class="form-control" name="trial_days"
                                            placeholder="{{ __('Enter trial days') }}"
                                            value="{{ $package->trial_days }}">
                                    </div>
                                @endif
                                <p id="errtrial_days" class="mb-0 text-danger em"></p>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="status">{{ __('Status') }}*</label>
                                            <select id="status" class="form-control ltr" name="status">
                                                <option value="" selected disabled>{{ __('Select a status') }}
                                                </option>
                                                <option value="1" {{ $package->status == '1' ? 'selected' : '' }}>
                                                    {{ __('Active') }}</option>
                                                <option value="0" {{ $package->status == '0' ? 'selected' : '' }}>
                                                    {{ __('Deactive') }}</option>
                                            </select>
                                            <p id="errstatus" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div
                                        class="col-lg-6 form-group @if (is_array($permissions) && in_array('vCard', $permissions)) @else vcrd-none @endif  v-card-box ">
                                        <label for="">{{ __('Number of vcards') }} * </label>
                                        <input type="number" class="form-control" name="number_of_vcards"
                                            value="{{ $package->number_of_vcards }}">
                                        <p class="text-warning">
                                            {{ __('Enter 999999 , then it will appear as unlimited') }}</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Serial Number') }} **</label>
                                            <input type="number" class="form-control ltr" name="serial_number"
                                                value="{{ $package->serial_number }}"
                                                placeholder="{{ __('Enter Serial Number') }}">
                                            <p id="errserial_number" class="mb-0 text-danger em"></p>
                                            <p class="text-warning mb-0">
                                                <small>{{ __('The higher the serial number is, the later the package will be shown.') }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">{{ __('Meta Keywords') }}</label>
                                    <input id="meta_keywords" type="text" class="form-control" name="meta_keywords"
                                        value="{{ $package->meta_keywords }}" data-role="tagsinput">
                                </div>
                                <div class="form-group">
                                    <label for="meta_description">{{ __('Meta Description') }}</label>
                                    <textarea id="meta_description" type="text" class="form-control" name="meta_description" rows="5">{{ $package->meta_description }}</textarea>
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
                                    class="btn btn-success">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/packages.js') }}"></script>
    <script src="{{ asset('assets/admin/js/edit-package.js') }}"></script>
@endsection
