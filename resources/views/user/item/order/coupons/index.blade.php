@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Coupons'] ?? __('Coupons') }}</h4>
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
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-title d-inline-block">{{ $keywords['Coupons'] ?? __('Coupons') }}</div>
                        </div>
                        <div class="col-lg-4 mt-2 mt-lg-0">
                            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                data-target="#createModal"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_New'] ?? __('Add New') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($coupons) == 0)
                                <h3 class="text-center">{{ $keywords['NO_COUPON_FOUND'] ?? __('NO COUPON FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                <th scope="col">{{ $keywords['Code'] ?? __('Code') }}</th>
                                                <th scope="col">{{ $keywords['Discount'] ?? __('Discount') }}</th>
                                                <th scope="col">{{ $keywords['Status'] ?? __('Status') }}</th>
                                                <th scope="col">{{ $keywords['Created'] ?? __('Created') }}</th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($coupons as $coupon)
                                                <tr>
                                                    <td>{{ $coupon->name }}</td>
                                                    <td>{{ $coupon->code }}</td>
                                                    <td>{{ $coupon->type == 'fixed' && $be->base_currency_symbol_position == 'left' ? $be->base_currency_symbol : '' }}{{ $coupon->value }}{{ $coupon->type == 'percentage' ? '%' : '' }}{{ $coupon->type == 'fixed' && $be->base_currency_symbol_position == 'right' ? $be->base_currency_symbol : '' }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $end = Carbon\Carbon::parse($coupon->end_date);
                                                            $start = Carbon\Carbon::parse($coupon->start_date);
                                                            $now = Carbon\Carbon::now();
                                                            $diff = $end->diffInDays($now);
                                                        @endphp
                                                        @if ($start->greaterThan($now))
                                                            <h3 class="d-inline-block badge badge-warning">
                                                                {{ $keywords['Pending'] ?? __('Pending') }}</h3>
                                                        @else
                                                            @if ($now->lessThan($end))
                                                                <h3 class="d-inline-block badge badge-success">
                                                                    {{ $keywords['Active'] ?? __('Active') }}</h3>
                                                            @else
                                                                <h3 class="d-inline-block badge badge-danger">
                                                                    {{ $keywords['Expired'] ?? __('Expired') }}</h3>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $created = Carbon\Carbon::parse($coupon->created_at);
                                                            $diff = $created->diffInDays($now);
                                                        @endphp
                                                        {{ $created->subDays($diff)->diffForHumans() }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('user.coupon.edit', [$coupon->id, 'language' => request('language')]) }}"
                                                            class="btn btn-warning btn-sm">{{ $keywords['Edit'] ?? __('Edit') }}</a>
                                                        <form class="d-inline-block deleteform"
                                                            action="{{ route('user.coupon.delete') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="coupon_id"
                                                                value="{{ $coupon->id }}">
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm deletebtn">{{ $keywords['Delete'] ?? __('Delete') }}</button>
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
                            {{ $coupons->links() }}
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
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Add_Coupon'] ?? __('Add Coupon') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="ajaxForm" class="modal-form" action="{{ route('user.coupon.store') }}" method="POST">
                        @csrf
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                                    <input type="text" class="form-control" name="name" value=""
                                        placeholder="{{ $keywords['Enter_name'] ?? __('Enter name') }}">
                                    <p id="errname" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Code'] ?? __('Code') }} **</label>
                                    <input type="text" class="form-control" name="code" value=""
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
                                        <option value="percentage">{{ $keywords['Percentage'] ?? __('Percentage') }}
                                        </option>
                                        <option value="fixed">{{ $keywords['Fixed'] ?? __('Fixed') }}</option>
                                    </select>
                                    <p id="errtype" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Value'] ?? __('Value') }} **</label>
                                    <input type="text" class="form-control" name="value" value=""
                                        placeholder="{{ $keywords['Enter_value'] ?? __('Enter value') }}"
                                        autocomplete="off">
                                    <p id="errvalue" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>

                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Start_Date'] ?? __('Start Date') }} **</label>
                                    <input type="text" class="form-control datepicker" name="start_date"
                                        value=""
                                        placeholder="{{ $keywords['Enter_start_date'] ?? __('Enter start date') }}"
                                        autocomplete="off">
                                    <p id="errstart_date" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ $keywords['End_Date'] ?? __('End Date') }} **</label>
                                    <input type="text" class="form-control datepicker" name="end_date" value=""
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
                                        ({{ $be->base_currency_text }})</label>
                                    <input type="text" class="form-control" name="minimum_spend" value=""
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
