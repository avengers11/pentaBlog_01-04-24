@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Mail_Subscribers'] ?? __('Mail Subscribers') }}</h4>
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
                <a href="#">{{ $keywords['Subscribers'] ?? __('Subscribers') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Mail_Subscribers'] ?? __('Mail Subscribers') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <form action="{{ route('user.subscribers.sendmail') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="card-title">{{ $keywords['Send_Mail'] ?? __('Send Mail') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Subject'] ?? __('Subject') }} **</label>
                                    <input type="text" class="form-control" name="subject" value=""
                                        placeholder="{{ $keywords['Enter_subject_of_Email'] ?? __('Enter subject of E-mail') }}">
                                    @if ($errors->has('subject'))
                                        <p class="text-danger mb-0">{{ $errors->first('subject') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Message'] ?? __('Message') }} **</label>
                                    <textarea name="message" class="summernote form-control" data-height="150"></textarea>
                                    @if ($errors->has('message'))
                                        <p class="text-danger mb-0">{{ $errors->first('message') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success">
                            <span class="btn-label">
                                <i class="fa fa-check"></i>
                            </span>
                            {{ $keywords['Send_Mail'] ?? __('Send Mail') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
