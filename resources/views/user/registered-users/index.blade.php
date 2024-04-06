@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Registered_Users'] ?? __('Registered User') }}</h4>
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
                <a href="#">{{ $keywords['Registered_Users'] ?? __('Registered User') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card-title">{{ $keywords['Registered_Users'] ?? __('Registered User') }}</div>
                        </div>

                        <div class="col-lg-7 offset-lg-2">
                            <button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"
                                data-href="{{ route('user.bulk_delete_user') }}">
                                <i class="flaticon-interface-5"></i> {{ $keywords['Delete'] ?? __('Delete') }}
                            </button>

                            <form class="float-right" action="{{ route('user.registered_users') }}" method="GET">
                                <input name="info" type="text" class="form-control min-w-230"
                                    placeholder="{{ $keywords['Search_By_Username_or_Email_ID'] ?? __('Search By Username or Email ID') }}"
                                    value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($users) == 0)
                                <h3 class="text-center">{{ $keywords['NO_USER_FOUND'] ?? __('NO USER FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['Username'] ?? __('Username') }}</th>
                                                <th scope="col">{{ $keywords['Email_ID'] ?? __('Email ID') }}</th>
                                                <th scope="col">{{ $keywords['Email_Status'] ?? __('Email Status') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Phone'] ?? __('Phone') }}</th>
                                                <th scope="col">
                                                    {{ $keywords['Account_Status'] ?? __('Account Status') }}</th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $user->id }}">
                                                    </td>
                                                    <td>{{ $user->username }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <form id="emailForm{{ $user->id }}" class="d-inline-block"
                                                            action="{{ route('user.email') }}" method="post">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ !empty($user->email_verified_at) ? 'bg-success' : 'bg-danger' }}"
                                                                name="email_verified"
                                                                onchange="document.getElementById('emailForm{{ $user->id }}').submit();">
                                                                <option value="1"
                                                                    {{ !empty($user->email_verified_at) ? 'selected' : '' }}>
                                                                    {{ $keywords['Verified'] ?? __('Verified') }}</option>
                                                                <option value="0"
                                                                    {{ empty($user->email_verified_at) ? 'selected' : '' }}>
                                                                    {{ $keywords['Unverified'] ?? __('Unverified') }}
                                                                </option>
                                                            </select>
                                                            <input type="hidden" name="user_id"
                                                                value="{{ $user->id }}">
                                                        </form>
                                                    </td>
                                                    <td>{{ empty($user->contact_number) ? '-' : $user->contact_number }}
                                                    </td>
                                                    <td>
                                                        <form id="accountStatusForm-{{ $user->id }}"
                                                            class="d-inline-block"
                                                            action="{{ route('user.user.update_account_status', ['id' => $user->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                                                name="account_status"
                                                                onchange="document.getElementById('accountStatusForm-{{ $user->id }}').submit()">
                                                                <option value="1"
                                                                    {{ $user->status == 1 ? 'selected' : '' }}>
                                                                    {{ $keywords['Active'] ?? __('Active') }}
                                                                </option>
                                                                <option value="2"
                                                                    {{ $user->status == 0 ? 'selected' : '' }}>
                                                                    {{ $keywords['Deactive'] ?? __('Deactive') }}
                                                                </option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle btn-sm"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ $keywords['Select'] ?? __('Select') }}
                                                            </button>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a href="{{ route('user.user_details', ['id' => $user->id, 'language' => request('language')]) }}"
                                                                    class="dropdown-item">
                                                                    {{ $keywords['Details'] ?? __('Details') }}
                                                                </a>

                                                                <a href="{{ route('user.user.change_password', ['id' => $user->id, 'language' => request('language')]) }}"
                                                                    class="dropdown-item">
                                                                    {{ $keywords['Change_Password'] ?? __('Change Password') }}
                                                                </a>
                                                            <form class="d-block"
                                                                action="{{ route('customer.secretUserLogin') }}"
                                                                method="post" target="_blank">
                                                                @csrf
                                                                <input type="hidden" name="user_id"
                                                                    value="{{ $user->id }}">
                                                                <button class="dropdown-item" role="button">Secret
                                                                    Login</button>
                                                            </form>

                                                                <form class="deleteform d-block"
                                                                    action="{{ route('user.user.delete', ['id' => $user->id]) }}"
                                                                    method="post">
                                                                    @csrf
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

                <div class="card-footer">
                    <div class="row">
                        <div class="d-inline-block mx-auto">
                            {{ $users->appends(['info' => request()->input('info')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
