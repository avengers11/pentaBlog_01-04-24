@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Languages'] ?? __('Languages') }}</h4>
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
                <a href="#">{{ $keywords['Language_Management'] ?? __('Language Management') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Languages'] ?? __('Languages') }}</div>
                    <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal">
                        <i class="fas fa-plus"></i>
                        {{ $keywords['Add_Language'] ?? __('Add Language') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if ($langCount > $langLimit)
                            <div class="col-12">
                                <div class="alert alert-danger" role="alert">
                                    {{ $keywords['Your_current_package_supports'] ?? __('Your current package supports') }}
                                    <strong>{{ $langLimit }} {{ $keywords['Languages'] ?? __('Languages') }}</strong>.
                                    <br>
                                    {{ $keywords['Currently_you_have'] ?? __('Currently, you have') }}
                                    <strong>{{ $langCount }} {{ $keywords['Languages'] ?? __('Languages') }}.</strong>
                                    <br>
                                    {{ $keywords['You_have_to_delete'] ?? __('You have to delete') }}
                                    <strong>{{ $langCount - $langLimit }}
                                        {{ $keywords['Languages'] ?? __('Languages') }}</strong>
                                    {{ $keywords['to_enable_editing_feature_of'] ?? __('to enable editing feature of') }}
                                    {{ $keywords['Languages'] ?? __('Languages') }}. <br>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-12">
                            @if (count($languages) == 0)
                                <h3 class="text-center">{{ $keywords['NO_LANGUAGE_FOUND'] ?? __('NO LANGUAGE FOUND') }}
                                </h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                <th scope="col">{{ $keywords['Code'] ?? __('Code') }}</th>
                                                <th scope="col">
                                                    {{ $keywords['Appearance_in_Website'] ?? __('Appearance in Website') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($languages as $key => $language)
                                                <tr>
                                                    <td>{{ $loop->iteration + 1 }}</td>
                                                    <td>{{ $language->name }}</td>
                                                    <td>{{ $language->code }}</td>
                                                    <td>
                                                        @if ($language->is_default == 1)
                                                            <strong
                                                                class="badge badge-success">{{ $keywords['Default'] ?? __('Default') }}</strong>
                                                        @else
                                                            <form class="d-inline-block"
                                                                action="{{ route('user.language.default', $language->id) }}"
                                                                method="post">
                                                                @csrf
                                                                <button class="btn btn-primary btn-sm" type="submit"
                                                                    name="button">{{ $keywords['Make_Default'] ?? __('Make Default') }}</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('user.language.editKeyword', [$language->id, 'language' => request('language')]) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit_Keyword'] ?? __('Edit Keyword') }}
                                                        </a>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('user.language.edit', [$language->id, 'language' => request('language')]) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.language.delete', $language->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ $keywords['Delete'] ?? __('Delete') }}
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

    <!-- Create Language Modal -->
    @includeif('user.language.create')
@endsection
