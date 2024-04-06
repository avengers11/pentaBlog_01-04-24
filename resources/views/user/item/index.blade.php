@extends('user.layout')

@php
$selLang = \App\Models\User\Language::where('code', request()->input('language'))->first();
$userLanguages = \App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)->get();
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
        <h4 class="page-title">{{ $keywords['Items'] ?? __('Items') }}</h4>
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
                <a href="#">{{ $keywords['Items'] ?? __('Items') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Items'] ?? __('Items') }}</div>
                        </div>
                        <div class="col-lg-3">

                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="{{ route('user.item.type') }}" class="btn btn-primary float-right btn-sm"><i
                                    class="fas fa-plus"></i> {{ $keywords['Add_Item'] ?? __('Add Item') }}</a>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('user.item.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                                {{ $keywords['Delete'] ?? __('Delete') }}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($items) == 0)
                                <h3 class="text-center">{{ $keywords['NO_ITEMS_FOUND'] ?? __('NO ITEMS FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['Title'] ?? __('Title') }}</th>
                                                <th scope="col">{{ $keywords['Price'] ?? __('Price') }}
                                                    ({{ $userBs->base_currency_symbol }})</th>
                                                <th scope="col">{{ $keywords['Type'] ?? __('Type') }}</th>
                                                <th scope="col">{{ $keywords['Variations'] ?? __('Variations') }}</th>
                                                <th scope="col">{{ $keywords['Category'] ?? __('Category') }}</th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $key => $item)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $item->item_id }}">
                                                    </td>
                                                    <td>
                                                        {{ strlen($item->title) > 30 ? mb_substr($item->title, 0, 30, 'utf-8') . '...' : $item->title }}
                                                    </td>

                                                    <td>{{ $item->current_price }}</td>

                                                    <td class="text-capitalize">{{ $item->type }}</td>
                                                    <td class="">
                                                        @if ($item->type == 'physical')
                                                            <a class="btn btn-secondary btn-sm"
                                                                href="{{ route('user.item.variations', $item->item_id) . '?language=' . request()->input('language') }}">
                                                                <span class="btn-label">
                                                                    {{ $keywords['Manage'] ?? __('Manage') }}
                                                                </span>
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ convertUtf8($item->category ? $item->category : '') }}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-info btn-sm dropdown-toggle"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ $keywords['Actions'] ?? __('Actions') }}
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('user.item.edit', $item->item_id) . '?language=' . request()->input('language') }}"
                                                                    target="_blank">{{ $keywords['Edit'] ?? __('Edit') }}</a>
                                                                <form class="deleteform d-block"
                                                                    action="{{ route('user.item.delete') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="item_id"
                                                                        value="{{ $item->item_id }}">
                                                                    <button type="submit" class="deletebtn">
                                                                        {{ $keywords['Delete'] ?? __('Delete') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>

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
@endsection
@section('scripts')
    {{-- <script>
        $(function() {
            $(".datepicker").datepicker({
                format: 'YYYY-MM-DD'
            });
        });
    </script> --}}
@endsection
