@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Social_Links'] ?? __('Social Links') }}</h4>
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
                <a href="#">{{ $keywords['About_Me'] ?? __('About Me') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Social_Links'] ?? __('Social Links') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="socialForm" action="{{ route('user.about_me.social.store') }}" method="post">
                    <div class="card-header">
                        <div class="card-title">{{ $keywords['Add_Social_Link'] ?? __('Add Social Link') }}</div>
                    </div>
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ $keywords['Social_Icon'] ?? __('Social Icon') }} **</label>
                                    <div class="btn-group d-block">
                                        <button type="button" class="btn btn-primary iconpicker-component"><i class="fa fa-fw fa-heart"></i></button>
                                        <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car" data-toggle="dropdown"> </button>
                                        <div class="dropdown-menu"></div>
                                    </div>
                                    <input id="inputIcon" type="hidden" name="icon" value="">
                                    @if ($errors->has('icon'))
                                        <p class="mb-0 text-danger">{{ $errors->first('icon') }}</p>
                                    @endif
                                    <div class="mt-2">
                                        <small>{{ $keywords['Social_Icon_text'] ?? __('NB: click on the dropdown icon to select a social link icon.') }}</small>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="">{{ $keywords['URL'] ?? __('URL') }} **</label>
                                    <input type="text" class="form-control" name="url" value=""
                                        placeholder="{{ $keywords['Enter_URL_of_social_media_account'] ?? __('Enter URL of social media account') }}">
                                    @if ($errors->has('url'))
                                        <p class="mb-0 text-danger">{{ $errors->first('url') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                        **</label>
                                    <input type="number" class="form-control ltr" name="serial_number" value=""
                                        placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                    @if ($errors->has('serial_number'))
                                        <p class="mb-0 text-danger">{{ $errors->first('serial_number') }}</p>
                                    @endif
                                    <p class="text-warning">
                                        <small>{{ $keywords['Serial_Number_Text'] ?? __('The higher the serial number is, the later the item will be shown.') }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pt-3">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-lg-3 col-md-3 col-sm-12">

                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" id="displayNotif"
                                        class="btn btn-success">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ $keywords['Social_Links'] ?? __('Social Links') }}</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($socials) == 0)
                                <h2 class="text-center">{{ $keywords['NO_LINK_ADDED'] ?? __('NO LINK ADDED') }}</h2>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ $keywords['Icon'] ?? __('Icon') }}</th>
                                                <th scope="col">{{ $keywords['URL'] ?? __('URL') }}</th>
                                                <th scope="col">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($socials as $key => $social)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><i class="{{ $social->icon }}"></i></td>
                                                    <td>{{ $social->url }}</td>
                                                    <td>{{ $social->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('user.about_me.social.edit', [$social->id, 'language' => request('language')]) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>
                                                        <form class="d-inline-block deleteform"
                                                            action="{{ route('user.about_me.social.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="social_id"
                                                                value="{{ $social->id }}">
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
@endsection
