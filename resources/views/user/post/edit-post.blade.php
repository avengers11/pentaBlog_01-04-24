@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Post'] ?? __('Edit Post') }}</h4>
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
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit_Post'] ?? __('Edit Post') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Post'] ?? __('Edit Post') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block text-dark"
                        href="{{ route('user.post_management.posts', ['language' => request('language')]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="alert alert-danger pb-1 dis-none" id="postErrors">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul></ul>
                            </div>
                            {{-- Slider images upload start --}}
                            <div class="px-2">
                                <label for=""
                                    class="mb-2"><strong>{{ $keywords['Slider_Images'] ?? __('Slider Images') }}
                                        **</strong></label>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped" id="imgtable">
                                            @if (!is_null($post->slider_images))
                                                @foreach (json_decode($post->slider_images) as $key => $img)
                                                    <tr class="trdb" id="trdb{{ $key }}">
                                                        <td>
                                                            <div class="thumbnail">
                                                                <img class="w-150"
                                                                    src="{{ asset('assets/user/img/posts/slider-images/' . $img) }}"
                                                                    alt="Ad Image">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger pull-right rmvbtndb"
                                                                onclick="rmvdbimg({{ $key }},{{ $post->id }})">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                <form action="{{ route('user.post_management.slider') }}" id="my-dropzone"
                                    enctype="multipart/form-data" class="dropzone create">
                                    @csrf
                                    <div class="fallback">
                                    </div>
                                </form>
                                @if ($errors->has('image'))
                                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('image') }}</p>
                                @endif
                            </div>
                            {{-- Slider images upload end --}}
                            <form id="postForm"
                                action="{{ route('user.post_management.update_post', ['id' => $post->id]) }}"
                                method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div id="sliders"></div>
                                {{-- thumbnail image start --}}
                                <div class="form-group">
                                    <div class="col-12 mb-2">
                                        <label for="">{{ $keywords['Thumbnail_Image'] ?? __('Thumbnail Image') }}
                                            **</label>
                                    </div>
                                    <div class="col-md-12 showImage mb-3">
                                        <img src="{{ isset($post->thumbnail_image) ? asset('assets/user/img/posts/' . $post->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}"
                                            alt="..." class="img-thumbnail">
                                    </div>
                                    <input type="file" name="thumbnail_image" id="image" class="form-control image">
                                    @if ($errors->has('thumbnail_image'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('thumbnail_image') }}</p>
                                    @endif
                                </div>
                                {{-- thumbnail image end --}}

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                                **</label>
                                            <input type="number" class="form-control" name="serial_number"
                                                placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}"
                                                value="{{ $post->serial_number }}">
                                            <p class="text-warning mt-2">
                                                <small>{{ $keywords['Serial_Number_Text'] ?? __('The higher the serial number is, the later the item will be shown.') }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div id="accordion" class="mt-3">
                                    @foreach ($languages as $language)
                                        @php
                                            $postData = $language
                                                ->postInfo()
                                                ->where('post_id', $post->id)
                                                ->first();
                                        @endphp

                                        <div class="version">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                                        data-target="#collapse{{ $language->id }}"
                                                        aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . __(' Language') }}
                                                        {{ $language->is_default == 1 ? '(Default)' : '' }}
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse{{ $language->id }}"
                                                class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div class="version-body">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Title'] ?? __('Title') }} **</label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $language->code }}_title"
                                                                    placeholder="{{ $keywords['Enter_Title'] ?? __('Enter Title') }}"
                                                                    value="{{ is_null($postData) ? '' : $postData->title }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Category'] ?? __('Category') }}
                                                                    **</label>
                                                                <select name="{{ $language->code }}_category"
                                                                    class="form-control">
                                                                    @php
                                                                        $categories = App\Models\User\PostCategory::where('language_id', $language->id)
                                                                            ->where('user_id', Auth::id())
                                                                            ->where('status', 1)
                                                                            ->get();
                                                                    @endphp

                                                                    @if (is_null($categories))
                                                                        <option selected disabled>
                                                                            {{ $keywords['Select_a_category'] ?? __('Select a Category') }}
                                                                        </option>
                                                                    @else
                                                                        <option selected="" disabled>
                                                                            {{ $keywords['Select_a_category'] ?? __('Select a Category') }}
                                                                        </option>

                                                                        @foreach ($categories as $category)
                                                                            <option value="{{ $category->id }}"
                                                                                {{ !empty($postData) && $postData->post_category_id == $category->id ? 'selected' : '' }}>
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Author'] ?? __('Author') }} **</label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $language->code }}_author"
                                                                    placeholder="{{ $keywords['Enter_Author_Name'] ?? __('Enter Author Name') }}"
                                                                    value="{{ is_null($postData) ? '' : $postData->author }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Content'] ?? __('Content') }}
                                                                    **</label>
                                                                <textarea class="form-control summernote" name="{{ $language->code }}_content"
                                                                    placeholder="{{ $keywords['Enter_Content'] ?? __('Enter Content') }}" data-height="300">{{ is_null($postData) ? '' : replaceBaseUrl($postData->content, 'summernote') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Meta_Keywords'] ?? __('Meta Keywords') }}</label>
                                                                <input class="form-control"
                                                                    name="{{ $language->code }}_meta_keywords"
                                                                    placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}"
                                                                    data-role="tagsinput"
                                                                    value="{{ is_null($postData) ? '' : $postData->meta_keywords }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Meta_Description'] ?? __('Meta Description') }}</label>
                                                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                                                    placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}">{{ is_null($postData) ? '' : $postData->meta_description }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="postForm" class="btn btn-success">
                                {{ $keywords['Update'] ?? __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        "use strict";
        const currUrl = "{{ url()->current() }}";
        const uploadSliderImage = "{{ route('user.post_management.slider') }}";
        const rmvSliderImage = "{{ route('user.post_management.slider-remove') }}";
        const rmvDbSliderImage = "{{ route('user.post_management.db-slider-remove') }}";
    </script>
    <script src="{{ asset('assets/user/dashboard/js/post.js') }}"></script>
    <script src="{{ asset('assets/user/dashboard/js/dropzone-slider.js') }}"></script>
@endsection
