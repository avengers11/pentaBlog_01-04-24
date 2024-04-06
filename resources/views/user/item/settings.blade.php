@extends('user.layout')
@section('content')
    @php
        $type = request()->input('type');
    @endphp
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Settings'] ?? __('Settings') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
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
                <a href="#">{{ $keywords['Settings'] ?? __('Settings') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Settings'] ?? __('Settings') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.item.index') . '?language=' . request()->input('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward" style="font-size: 12px;"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm" class="" action="{{ route('user.item.settings') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>{{ $keywords['Shop'] ?? __('Shop') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_shop" value="1" class="selectgroup-input"
                                                @if ($shopsettings) {{ $shopsettings->is_shop == 1 ? 'checked' : '' }} @endif>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_shop" value="0" class="selectgroup-input"
                                                @if ($shopsettings) {{ $shopsettings->is_shop == 0 ? 'checked' : '' }} @endif>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                    <p id="erris_shop" class="mb-0 text-danger em"></p>
                                    <p class="text-warning mb-0">
                                        {{ $keywords['Shop_active_inactive_msg'] ??
                                            __('By enabling / disabling, you can completely enable / disable the relevant pages of your shop in this system') }}.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label>{{ $keywords['Rating_System'] ?? __('Rating System') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="item_rating_system" value="1"
                                                class="selectgroup-input"
                                                @if ($shopsettings) {{ $shopsettings->item_rating_system == 1 ? 'checked' : '' }} @endif>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="item_rating_system" value="0"
                                                class="selectgroup-input"
                                                @if ($shopsettings) {{ $shopsettings->item_rating_system == 0 ? 'checked' : '' }} @endif>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                        <p id="erritem_rating_system" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Tax'] ?? __('Tax') }} **</label>
                                    <input type="text" class="form-control" name="tax"
                                        value="{{ $shopsettings ? $shopsettings->tax : '' }}"
                                        placeholder="{{ $keywords['Enter_tax'] ?? __('Enter tax') }}">
                                    <p id="errtax" class="mb-0 text-danger em"></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" form="ajaxForm"  id="submitBtn"
                                    class="btn btn-success">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
