@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Settings'] ?? __('Settings') }}</h4>
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
                <a href="#">{{ $keywords['Post_Management'] ?? __('Post Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Settings'] ?? __('Settings') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Post_Settings'] ?? __('Post Settings') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="settingsForm" action="{{ route('user.post_management.update_settings') }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="mb-3">{{ $keywords['View_Type'] ?? __('View Type') }} **</label>
                                    <div>
                                        <div class="d-sm-inline mr-5">
                                            <input type="radio" {{ $data->post_view_type == 'standard' ? 'checked' : '' }}
                                                class="mr-1" name="post_view_type" value="standard">
                                            <label
                                                for="">{{ $keywords['Standard_View'] ?? __('Standard View') }}</label>
                                        </div>

                                        <div class="d-sm-inline mr-5">
                                            <input type="radio" {{ $data->post_view_type == 'grid' ? 'checked' : '' }}
                                                class="mr-1" name="post_view_type" value="grid">
                                            <label for="">{{ $keywords['Grid_View'] ?? __('Grid View') }}</label>
                                        </div>

                                        <div class="d-sm-inline">
                                            <input type="radio" {{ $data->post_view_type == 'masonry' ? 'checked' : '' }}
                                                class="mr-1" name="post_view_type" value="masonry">
                                            <label
                                                for="">{{ $keywords['Masonry_View'] ?? __('Masonry View') }}</label>
                                        </div>
                                    </div>
                                    @if ($errors->has('post_view_type'))
                                        <p class="text-danger">{{ $errors->first('post_view_type') }}</p>
                                    @endif

                                    <p class="mt-2 text-warning">
                                        {{ $keywords['Specify_how_the_posts_will_be_displayed_in_Posts_page'] ?? __('Specify how the posts will be displayed in Posts page.') }}
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="settingsForm" class="btn btn-success">
                                {{ $keywords['Update'] ?? __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
