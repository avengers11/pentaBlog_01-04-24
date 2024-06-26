@extends('user.layout')

@php
$userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
@endphp
@if (!empty($userDefaultLang) && $userDefaultLang->rtl == 1)
    @section('styles')
        <style>
            form:not(.modal-form) input,
            form:not(.modal-form) textarea,
            form:not(.modal-form) select,
            select[name='userLanguage'] {
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
        <h4 class="page-title">{{ $keywords['Follower_List'] ?? __('Follower List') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user.follower.list') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Follower_Page'] ?? __('Follower Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Follower'] ?? __('Follower') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Follower'] ?? __('Follower') }}</div>
                        </div>
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (is_null($userDefaultLang))
                                <h3 class="text-center">{{ $keywords['NO_LANGUAGE_FOUND'] ?? __('NO LANGUAGE FOUND') }}</h3>
                            @else
                                @if (count($users) == 0)
                                    <h3 class="text-center">{{ $keywords['NO_FOLLOWER_FOUND'] ?? __('NO FOLLOWER FOUND') }}
                                    </h3>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped mt-3">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <input type="checkbox" class="bulk-check" data-val="all">
                                                    </th>
                                                    <th scope="col">{{ $keywords['Image'] ?? __('Image') }}</th>
                                                    <th scope="col">{{ $keywords['Username'] ?? __('User name') }}</th>
                                                    <th scope="col">{{ $keywords['Email'] ?? __('Email') }}</th>
                                                    <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $key => $user)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="bulk-check"
                                                                data-val="{{ $user->id }}">
                                                        </td>
                                                        <td><img src="{{ asset('assets/user/img/' . $user->photo) }}"
                                                                alt="" width="80"></td>
                                                        <td>{{ strlen($user->username) > 30 ? mb_substr($user->username, 0, 30, 'UTF-8') . '...' : $user->username }}
                                                        </td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>
                                                            <a target="_blank" class="btn btn-secondary btn-sm"
                                                                href="{{ route('front.user.detail.view', [$user->id, 'language' => request('language')]) }}">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-eye"></i>
                                                                </span>
                                                                {{ $keywords['View'] ?? __('View') }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-inline-block mx-auto">
                            @if (count($users) > 0)
                                {{ $users->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
