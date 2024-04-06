@extends('admin.layout')

@php
use App\Models\Language;
$selLang = Language::where('code', request()->input('language'))->first();
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
        <h4 class="page-title">{{ __('Packages') }}</h4>
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
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('Package Page') }}</div>
                        </div>
                        <div class="col-lg-4 offset-lg-4 mt-2 mt-lg-0">
                            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                data-target="#createModal"><i class="fas fa-plus"></i>
                                {{ __('Add Package') }}
                            </a>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('admin.package.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($packages) == 0)
                                <h3 class="text-center">{{ __('NO PACKAGE FOUND YET') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Cost') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($packages as $key => $package)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $package->id }}">
                                                    </td>
                                                    <td>{{ strlen($package->title) > 30 ? mb_substr($package->title, 0, 30, 'UTF-8') . '...' : $package->title }}
                                                    </td>
                                                    <td>
                                                        @if ($package->price == 0)
                                                            {{ __('Free') }}
                                                        @else
                                                            {{ format_price($package->price) }}
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($package->status == 1)
                                                            <h2 class="d-inline-block">
                                                                <span
                                                                    class="badge badge-success">{{ __('Active') }}</span>
                                                            </h2>
                                                        @else
                                                            <h2 class="d-inline-block">
                                                                <span
                                                                    class="badge badge-danger">{{ __('Deactive') }}</span>
                                                            </h2>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('admin.package.edit', $package->id) . '?language=' . request()->input('language') }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('admin.package.delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="package_id"
                                                                value="{{ $package->id }}">
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ __('Delete') }}
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
            </div>
        </div>
    </div>
    <!-- Create Blog Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Package') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
                        action="{{ route('admin.package.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title">{{ __('Package title') }} *</label>
                                    <input id="title" type="text" class="form-control" name="title"
                                        placeholder="{{ __('Enter Package title') }}" value="">
                                    <p id="errtitle" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="price">{{ __('Price') }} ({{ $bex->base_currency_text }})*</label>
                                    <input id="price" type="number" class="form-control" name="price"
                                        placeholder="{{ __('Enter Package price') }}" value="">
                                    <p class="text-warning mb-0">
                                        <small>{{ __('If price is 0 , then it will appear as free') }}</small>
                                    </p>
                                    <p id="errprice" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="term">{{ __('Package Duration') }} *</label>
                            <select id="term" name="term" class="form-control" required>
                                <option value="" selected disabled>{{ __('Choose Package Duration') }}</option>
                                <option value="monthly">{{ __('Monthly') }}</option>
                                <option value="yearly">{{ __('Yearly') }}</option>
                                <option value="lifetime">{{ __('Lifetime') }}</option>
                            </select>
                            <p id="errterm" class="mb-0 text-danger em"></p>
                        </div>


                        <div class="form-group">
                            <label class="form-label">{{ __('Package Features') }}</label>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Custom Domain"
                                        class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Custom Domain') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Subdomain"
                                        class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Subdomain') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="QR Builder"
                                        class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('QR Builder') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="vCard" class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('vCard') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Gallery" class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Gallery') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="FAQ" class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('FAQ') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Custom Pages"
                                        class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Custom Pages') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Advertisement"
                                        class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Advertisement') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Ecommerce"
                                        class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Ecommerce') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Google Analytics"
                                        class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Google Analytics') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Google Recaptcha"
                                        class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Google Recaptcha') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Disqus" class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Disqus') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="WhatsApp" class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('WhatsApp') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Facebook Pixel"
                                        class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Facebook Pixel') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="features[]" value="Tawk.to" class="selectgroup-input">
                                    <span class="selectgroup-button">{{ __('Tawk.to') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="feature_posts">{{ __('Featured Posts Limit') }} *</label>
                                    <input id="feature_posts" type="number" class="form-control"
                                        name="feature_posts_limit" placeholder="{{ __('Enter featured posts limit') }}"
                                        value="">
                                    <p class="text-warning mb-0">
                                        <small>{{ __('Enter 999999 , then it will appear as unlimited') }}</small>
                                    </p>
                                    <p id="errfeature_posts_limit" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="post_categories">{{ __('Post Categories Limit') }} *</label>
                                    <input id="post_categories" type="number" class="form-control"
                                        name="post_categories_limit"
                                        placeholder="{{ __('Enter post categories limit') }}" value="">
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
                                    <label for="posts">{{ __('Posts Limit') }} *</label>
                                    <input id="posts" type="number" class="form-control" name="posts_limit"
                                        placeholder="{{ __('Enter posts limit') }}" value="">
                                    <p class="text-warning mb-0">
                                        <small>{{ __('Enter 999999 , then it will appear as unlimited') }}</small>
                                    </p>
                                    <p id="errposts_limit" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="languages">{{ __('Language Limit') }} *</label>
                                    <input id="languages" type="number" class="form-control" name="language_limit"
                                        placeholder="{{ __('Enter language limit') }}" value="">
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
                                                class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="featured" value="0"
                                                class="selectgroup-input" checked>
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
                                                class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_trial" value="0"
                                                class="selectgroup-input" checked>
                                            <span class="selectgroup-button">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group dis-none" id="trial_day">
                            <label for="trial_days">{{ __('Trial days') }} *</label>
                            <input id="trial_days" type="number" class="form-control" name="trial_days"
                                placeholder="{{ __('Enter trial days') }}" value="">
                            <p id="errtrial_days" class="mb-0 text-danger em"></p>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="status">{{ __('Status') }} *</label>
                                    <select id="status" class="form-control ltr" name="status">
                                        <option value="" selected disabled>{{ __('Select a status') }}</option>
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactive') }}</option>
                                    </select>
                                    <p id="errstatus" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group v-card-box vcrd-none">
                                <label for="">{{ __('Number of vcards') }} * </label>
                                <input type="number" class="form-control" name="number_of_vcards" value="">
                                <p class="text-warning">{{ __('Enter 999999 , then it will appear as unlimited') }}</p>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ __('Serial Number') }} **</label>
                                    <input type="number" class="form-control ltr" name="serial_number" value=""
                                        placeholder="{{ __('Enter Serial Number') }}">
                                    <p id="errserial_number" class="mb-0 text-danger em"></p>
                                    <p class="text-warning mb-0">
                                        <small>{{ __('The higher the serial number is, the later the package will be shown.') }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="">{{ __('Meta Keywords') }}</label>
                            <input type="text" class="form-control" name="meta_keywords" value=""
                                data-role="tagsinput" placeholder="{{ __('Enter meta keywords') }}">
                        </div>
                        <div class="form-group">
                            <label for="meta_description">{{ __('Meta Description') }}</label>
                            <textarea id="meta_description" type="text" class="form-control" name="meta_description" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/packages.js') }}"></script>
@endsection
