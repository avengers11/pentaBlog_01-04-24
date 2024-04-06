@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Coupon'] ?? __('Edit Coupon') }}</h4>
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
                <a href="#">{{ $keywords['Coupons'] ?? __('Coupons') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit'] ?? __('Edit') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Coupon'] ?? __('Edit Coupon') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('user.coupon.index',['language' => request('language')]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">

                            <form id="ajaxForm" class="modal-form" action="{{ route('user.coupon.update') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                <div class="row no-gutters">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $coupon->name }}"
                                                placeholder="{{ $keywords['Enter_name'] ?? __('Enter name') }}">
                                            <p id="errname" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Code'] ?? __('Code') }} **</label>
                                            <input type="text" class="form-control" name="code"
                                                value="{{ $coupon->code }}"
                                                placeholder="{{ $keywords['Enter_code'] ?? __('Enter code') }}">
                                            <p id="errcode" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-gutters">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Type'] ?? __('Type') }} **</label>
                                            <select name="type" id="" class="form-control">
                                                <option value="percentage"
                                                    {{ $coupon->type == 'percentage' ? 'selected' : '' }}>
                                                    {{ $keywords['Percentage'] ?? __('Percentage') }}</option>
                                                <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>
                                                    {{ $keywords['Fixed'] ?? __('Fixed') }}</option>
                                            </select>
                                            <p id="errtype" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Value'] ?? __('Value') }} **</label>
                                            <input type="text" class="form-control" name="value"
                                                value="{{ $coupon->value }}"
                                                placeholder="{{ $keywords['Enter_value'] ?? __('Enter value') }}"
                                                autocomplete="off">
                                            <p id="errvalue" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Start_Date'] ?? __('Start Date') }}
                                                **</label>
                                            <input type="text" class="form-control datepicker" name="start_date"
                                                value="{{ $coupon->start_date }}"
                                                placeholder="{{ $keywords['Enter_start_date'] ?? __('Enter start date') }}"
                                                autocomplete="off">
                                            <p id="errstart_date" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['End_Date'] ?? __('End Date') }} **</label>
                                            <input type="text" class="form-control datepicker" name="end_date"
                                                value="{{ $coupon->end_date }}"
                                                placeholder="{{ $keywords['Enter_end_date'] ?? __('Enter end date') }}"
                                                autocomplete="off">
                                            <p id="errend_date" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-gutters">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Minimum_Spend'] ?? __('Minimum Spend') }}
                                                ({{ $bs->base_currency_text }})</label>
                                            <input type="text" class="form-control" name="minimum_spend"
                                                value="{{ $coupon->minimum_spend }}"
                                                placeholder="{{ $keywords['Enter_minimum_spend'] ?? __('Enter minimum spend') }}"
                                                autocomplete="off">
                                            <p class="mb-0 text-warning">
                                                {{ $keywords['Minimum_Spend_text'] ?? __('Keep it blank, if you do not want to keep any minimum spend limit') }}
                                            </p>
                                            <p id="errminimum_spend" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
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
