@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Testimonials'] ?? __('Testimonials') }}</h4>
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
                <a href="#">{{ $keywords['Testimonials'] ?? __('Testimonials') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-title d-inline-block">{{ $keywords['Testimonials'] ?? __('Testimonials') }}
                            </div>
                        </div>

                        <div class="col-lg-4 mt-2 mt-lg-0">
                            <a href="{{ route('user.about_me.create_testimonial', ['language' => request('language')]) }}"
                                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_Testimonial'] ?? __('Add Testimonial') }}</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($testimonialContents) == 0)
                                <h3 class="text-center">
                                    {{ $keywords['NO_TESTIMONIAL_FOUND'] ?? __('NO TESTIMONIAL FOUND!') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('#') }}</th>
                                                <th scope="col">{{ $keywords['Image'] ?? __('Image') }}</th>
                                                <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                <th scope="col">{{ $keywords['Rating'] ?? __('Rating') }}</th>
                                                <th scope="col">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($testimonialContents as $testimonialContent)
                                            @if($testimonialContent->testimonial)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <img src="{{ asset('assets/user/img/testimonials/' . $testimonialContent->testimonial->client_image) }}"
                                                            alt="client" width="40">
                                                    </td>
                                                    <td>{{ $testimonialContent->client_name }}</td>
                                                    <td>{{ $testimonialContent->testimonial->rating }}</td>
                                                    <td>{{ $testimonialContent->testimonial->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm mr-1"
                                                            href="{{ route('user.about_me.edit_testimonial', ['id' => $testimonialContent->testimonial_id, 'language' => request('language')]) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.about_me.delete_testimonial', ['id' => $testimonialContent->testimonial_id]) }}"
                                                            method="post">
                                                            @method('DELETE')
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
                                                @endif
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
