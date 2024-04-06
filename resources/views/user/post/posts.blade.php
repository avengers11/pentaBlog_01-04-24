@extends('user.layout')
@php
    $selLang = \App\Models\User\Language::where([['code', request()->input('language')], ['user_id', \Illuminate\Support\Facades\Auth::id()]])->first();
    $userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
    $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
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
        <h4 class="page-title">{{ $keywords['Posts'] ?? __('Posts') }}</h4>
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
                <a href="#">{{ $keywords['Posts'] ?? __('Posts') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Posts'] ?? __('Posts') }}</div>
                        </div>

                        <div class="col-lg-4 offset-lg-4 mt-2 mt-lg-0">
                            <a href="{{ route('user.post_management.create_post', ['language' => request('language')]) }}"
                                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i>
                                {{ $keywords['Add_Post'] ?? __('Add Post') }}</a>
                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                data-href="{{ route('user.post_management.bulk_delete_post') }}">
                                <i class="flaticon-interface-5"></i> {{ $keywords['Delete'] ?? __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        @if ($count > $limit)
                            <div class="col-12">
                                <div class="alert alert-danger" role="alert">
                                    {{ $keywords['Your_current_package_supports'] ?? __('Your current package supports') }}
                                    <strong>{{ $limit }} {{ $keywords['Posts'] ?? __('Posts') }}</strong>.
                                    {{ $keywords['Currently_you_have'] ?? __('Currently, you have') }}
                                    <strong>{{ $count }} {{ $keywords['Posts'] ?? __('Posts') }}.</strong>
                                    {{ $keywords['You_have_to_delete'] ?? __('You have to delete') }}
                                    <strong>{{ $count - $limit }} {{ $keywords['Posts'] ?? __('Posts') }}</strong>
                                    {{ $keywords['to_enable_editing_feature_of'] ?? __('to enable editing feature of') }}
                                    {{ $keywords['Posts'] ?? __('Posts') }}.
                                </div>
                            </div>
                        @endif
                        @if ($featuredCount > $featuredLimit)
                            <div class="col-12">
                                <div class="alert alert-danger" role="alert">
                                    {{ $keywords['Your_current_package_supports'] ?? __('Your current package supports') }}
                                    <strong>{{ $featuredLimit }}
                                        {{ $keywords['Featured_Posts'] ?? __('Featured Posts') }}</strong>.
                                    {{ $keywords['Currently_you_have'] ?? __('Currently, you have') }}
                                    <strong>{{ $featuredCount }}
                                        {{ $keywords['Featured_Posts'] ?? __('Featured Posts') }}.</strong>
                                    {{ $keywords['You_have_to_unfeature'] ?? __('You have to unfeature') }}
                                    <strong>{{ $featuredCount - $featuredLimit }}
                                        {{ $keywords['Posts'] ?? __('Posts') }}</strong>
                                    {{ $keywords['to_enable_editing_feature_of'] ?? __('to enable editing feature of') }}
                                    {{ $keywords['Posts'] ?? __('Posts') }}.
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-12">
                            @if (count($posts) == 0)
                                <h3 class="text-center">{{ $keywords['NO_POST_FOUND'] ?? __('NO POST FOUND!') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['Title'] ?? __('Title') }}</th>
                                                <th scope="col">{{ $keywords['Category'] ?? __('Category') }}</th>

                                                @if ($themeInfo->theme_version != 7)
                                                    <th scope="col">{{ $keywords['Slider'] ?? __('Slider') }}</th>
                                                @else
                                                    <th scope="col">
                                                        {{ $keywords['Hero_section_posts'] ?? __('Hero Section Posts') }}
                                                    </th>
                                                @endif

                                                @if ($themeInfo->theme_version != 3)
                                                    <th scope="col">{{ $keywords['Featured'] ?? __('Featured') }}</th>
                                                @endif

                                                <th scope="col">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($posts as $post)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $post->post_id }}">
                                                    </td>
                                                    <td>
                                                        {{ strlen($post->title) > 30 ? mb_substr($post->title, 0, 30) . '...' : $post->title }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $category = App\Models\User\PostCategory::where('id', $post->post_category_id)->first();
                                                        @endphp
                                                        {{ $category->name }}
                                                    </td>
                                                    <td>
                                                        @if ($themeInfo->theme_version != 7)
                                                            <form class="d-inline-block">
                                                                <select
                                                                    class="form-control form-control-sm {{ $post->is_slider == 1 ? 'bg-success' : 'bg-danger' }} slider-post"
                                                                    data-id="{{ $post->post_id }}">
                                                                    <option value="1"
                                                                        {{ $post->is_slider == 1 ? 'selected' : '' }}>
                                                                        {{ $keywords['Yes'] ?? __('Yes') }}
                                                                    </option>
                                                                    <option value="0"
                                                                        {{ $post->is_slider == 0 ? 'selected' : '' }}>
                                                                        {{ $keywords['No'] ?? __('No') }}
                                                                    </option>
                                                                </select>
                                                            </form>
                                                            @if ($post->is_slider == 1)
                                                                <a href="#" class="btn btn-primary btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#sliderImage{{ $post->id }}">{{ $keywords['Image'] ?? __('Image') }}</a>
                                                            @endif
                                                        @else
                                                            <form class="d-inline-block">
                                                                <select
                                                                    class="form-control form-control-sm {{ $post->is_hero_post == 1 ? 'bg-success' : 'bg-danger' }} hero-post"
                                                                    data-id="{{ $post->post_id }}">
                                                                    <option value="1"
                                                                        {{ $post->is_hero_post == 1 ? 'selected' : '' }}>
                                                                        {{ $keywords['Yes'] ?? __('Yes') }}
                                                                    </option>
                                                                    <option value="0"
                                                                        {{ $post->is_hero_post == 0 ? 'selected' : '' }}>
                                                                        {{ $keywords['No'] ?? __('No') }}
                                                                    </option>
                                                                </select>
                                                            </form>

                                                            @if ($post->is_hero_post == 1)
                                                                <a href="#" class="btn btn-primary btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#heroImage{{ $post->id }}">{{ $keywords['Image'] ?? __('Image') }}</a>
                                                            @endif
                                                        @endif


                                                    </td>

                                                    @if ($themeInfo->theme_version != 7)
                                                        @if ($post->is_slider == 1)
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="sliderImage{{ $post->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="exampleModalCenterTitle"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLongTitle">
                                                                                {{ $post->title }}</h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body text-center">
                                                                            <img src="{{ asset('assets/user/img/posts/' . $post->slider_post_image) }}"
                                                                                class="img-fluid" alt="">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="modal fade" id="heroImage{{ $post->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="exampleModalLongTitle">
                                                                            {{ $post->title }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <img src="{{ asset('assets/user/img/posts/' . $post->hero_post_image) }}"
                                                                            class="img-fluid" alt="">

                                                                        <p class="text-warning mt-1 text-left"> {{ $post->image_size_type }}</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif


                                                    @if ($themeInfo->theme_version != 3)
                                                        <td>
                                                            <form class="d-inline-block">
                                                                <select
                                                                    class="form-control form-control-sm {{ $post->is_featured == 1 ? 'bg-success' : 'bg-danger' }} featured-post"
                                                                    data-id="{{ $post->post_id }}">
                                                                    <option value="1"
                                                                        {{ $post->is_featured == 1 ? 'selected' : '' }}>
                                                                        {{ $keywords['Yes'] ?? __('Yes') }}
                                                                    </option>
                                                                    <option value="0"
                                                                        {{ $post->is_featured == 0 ? 'selected' : '' }}>
                                                                        {{ $keywords['No'] ?? __('No') }}
                                                                    </option>
                                                                </select>
                                                            </form>
                                                            @if ($post->is_featured == 1)
                                                                <a href="#" class="btn btn-primary btn-sm"
                                                                    data-toggle="modal"
                                                                    data-target="#featuredImage{{ $post->id }}">{{ $keywords['Image'] ?? __('Image') }}</a>
                                                            @endif
                                                        </td>

                                                        @if ($post->is_featured == 1)
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="featuredImage{{ $post->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="exampleModalCenterTitle"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLongTitle">
                                                                                {{ $post->title }}</h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body text-center">
                                                                            <img src="{{ asset('assets/user/img/posts/' . $post->featured_post_image) }}"
                                                                                class="img-fluid" alt="">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    <td>{{ $post->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-xs mr-1"
                                                            href="{{ route('user.post_management.edit_post', ['id' => $post->post_id, 'language' => request('language')]) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                        </a>

                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.post_management.delete_post', ['id' => $post->post_id]) }}"
                                                            method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger btn-xs deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
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
                <div class="card-footer"></div>
            </div>
        </div>
    </div>

    {{-- slider-post modal --}}
    @include('user.post.slider-post')

    {{--- hero posts modal ---}}
    @include('user.post.hero-posts')

    {{-- featured-post modal --}}
    @include('user.post.featured-post')
@endsection

@section('scripts')
    <script>
        "use strict";
        const currUrl = "{{ url()->current() }}";
        const mainURL = "{{ url('/') }}";
    </script>
    <script type="text/javascript" src="{{ asset('assets/user/dashboard/js/post.js') }}"></script>
@endsection
