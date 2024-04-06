@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ $keywords['Add_Post'] ?? __('Add Post') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('user-dashboard')}}">
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
        <a href="#">{{ $keywords['Add_Post'] ?? __('Add Post') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ $keywords['Add_Post'] ?? __('Add Post') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block text-dark" href="{{ route('user.post_management.posts',['language' => request('language')]) }}">
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
                    <label for="" class="mb-2"><strong>{{ $keywords['Slider_Images'] ?? __('Slider Images') }} **</strong></label>
                    <form action="{{route('user.post_management.slider')}}" id="my-dropzone" enctype="multipart/form-data" class="dropzone create">
                        @csrf
                        <div class="fallback">
                        </div>
                    </form>
                    <p class="em text-danger mb-0" id="err_slider_images"></p>
                </div>
                {{-- Slider images upload end --}}
              <form id="postForm" action="{{ route('user.post_management.store_post') }}" method="POST">
                @csrf
                {{-- thumbnail image start --}}
                  <div id="sliders"></div>

                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group">
                              <div class="col-12 mb-2">
                                  <label for="image"><strong>{{ $keywords['Thumbnail_Image'] ?? __('Thumbnail Image*') }} **</strong></label>
                              </div>
                              <div class="col-md-12 showImage mb-3">
                                  <img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="..." class="img-thumbnail">
                              </div>
                              <input type="file" name="thumbnail_image" id="image" class="form-control">
                              <p id="errthumbnail_image" class="mb-0 text-danger em"></p>
                          </div>
                      </div>
                  </div>
                {{-- thumbnail image end --}}

                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }} **</label>
                      <input type="number" class="form-control" name="serial_number" placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                      <p class="text-warning mt-2">
                        <small>{{  $keywords['Serial_Number_Text'] ?? __('The higher the serial number is, the later the item will be shown.') }}</small>
                      </p>
                    </div>
                  </div>
                </div>

                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $language->id }}" aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}" aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }} {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $language->id }}" class="collapse {{ $language->is_default == 1 ? 'show' : '' }}" aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ $keywords['Title'] ?? __('Title') }} **</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title" placeholder="{{ $keywords['Enter_Title'] ?? __('Enter Title') }}">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ $keywords['Category'] ?? __('Category') }} ** </label>
                                <select name="{{ $language->code }}_category" class="form-control">
                                  <option selected disabled>{{ $keywords['Select_a_category'] ?? __('Select a Category') }}</option>
                                  @php
                                    $categories = App\Models\User\PostCategory::where('language_id', $language->id)->where('user_id',Auth::id())->where('status', 1)->get();
                                  @endphp
                                  @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ $keywords['Author'] ?? __('Author') }} **</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_author" placeholder="{{ $keywords['Enter_Author_Name'] ?? __('Enter Author Name') }}">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ $keywords['Content'] ?? __('Content') }} ** </label>
                                <textarea id="{{ $language->code }}_PostContent" class="form-control summernote" name="{{ $language->code }}_content" placeholder="{{ $keywords['Enter_Content'] ?? __('Enter Content') }}" data-height="300"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ $keywords['Meta_Keywords'] ?? __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords" placeholder="{{ $keywords['Enter_Meta_Keywords'] ?? __('Enter Meta Keywords') }}" data-role="tagsinput">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ $keywords['Meta_Description'] ?? __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5" placeholder="{{ $keywords['Enter_Meta_Description'] ?? __('Enter Meta Description') }}"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              @php $currLang = $language; @endphp
                              @foreach ($languages as $language)
                                @continue($language->id == $currLang->id)
                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                    <span class="form-check-sign">{{ $keywords['Clone_for'] ?? __('Clone for') }} <strong class="text-capitalize text-secondary">{{ $language->name }}</strong> {{ __('language') }}</span>
                                  </label>
                                </div>
                              @endforeach
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
                {{ $keywords['Save'] ??  __('Save') }}
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
    const uploadSliderImage = "{{route('user.post_management.slider')}}";
    const rmvSliderImage = "{{route('user.post_management.slider-remove')}}";
    const rmvDbSliderImage = "{{route('user.post_management.db-slider-remove')}}";
  </script>
  <script src="{{asset('assets/user/dashboard/js/post.js')}}"></script>
  <script src="{{asset('assets/user/dashboard/js/dropzone-slider.js')}}"></script>
@endsection
