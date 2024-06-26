@extends('user.layout')
@php
$default = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
@endphp

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Choose_Item_Type'] ?? __('Choose Item Type') }}</h4>
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
                <a href="#">{{ $keywords['Item_Management'] ?? __('Item Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Item_Type'] ?? __('Item Type') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h3>{{ $keywords['Choose_Item_Type'] ?? __('Choose Item Type') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <a href="{{ route('user.item.create') . '?language=' . $default->code . '&type=digital' }}"
                                class="d-block">
                                <div class="card card-stats card-round">
                                    <div class="card-body ">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="col-icon mx-auto">
                                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                                        <i class="icon-screen-desktop"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-stats ml-3 ml-sm-0">
                                                <div class="numbers mx-auto text-center">
                                                    <h2 class="card-title mt-2 mb-4 text-uppercase">{{ $keywords['Digital_Product'] ?? __('Digital Product') }}</h2>
                                                    <p class="card-category"><strong>{{ $keywords['Total'] ?? __('Total') }}:</strong> {{ $digitalCount }}
                                                        {{ $keywords['Items'] ?? __('Items') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('user.item.create') . '?language=' . $default->code . '&type=physical' }}"
                                class="d-block">
                                <div class="card card-stats card-round">
                                    <div class="card-body ">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="col-icon mx-auto">
                                                    <div class="icon-big text-center icon-warning bubble-shadow-small">
                                                        <i class="icon-present"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-stats ml-3 ml-sm-0">
                                                <div class="numbers mx-auto text-center">
                                                    <h2 class="card-title mt-2 mb-4 text-uppercase">{{ $keywords['Physical_Product'] ?? __('Physical Product') }}</h2>
                                                    <p class="card-category"><strong>{{ $keywords['Total'] ?? __('Total') }}:</strong> {{ $physicalCount }}
                                                        {{ $keywords['Items'] ?? __('Items') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
