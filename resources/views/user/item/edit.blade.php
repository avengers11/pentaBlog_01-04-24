@extends('user.layout')

@if (!empty($item->language) && $item->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }

            .nicEdit-main {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Item'] ?? __('Edit Item') }}</h4>
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
                <a href="#">{{ $keywords['Shop_Management'] ?? __('Shop Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Manage_Items'] ?? __('Manage Items') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit_Item'] ?? __('Edit Item') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Item'] ?? __('Edit Item') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.item.index') . '?language=' . request()->input('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward" style="font-size: 12px;"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="alert alert-danger pb-1" id="itemErrors" style="display: none;">
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
                                            @if (!is_null($item->sliders))
                                                @foreach ($item->sliders as $key => $img)
                                                    <tr class="trdb" id="trdb{{ $key }}">
                                                        <td>
                                                            <div class="thumbnail">
                                                                <img style="width:150px;"
                                                                    src="{{ asset('assets/front/img/user/items/slider-images/' . $img->image) }}"
                                                                    alt="Item Image">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger pull-right rmvbtndb"
                                                                onclick="rmvdbimg({{ $key }},{{ $img->id }})">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                <form action="{{ route('user.item.slider') }}" id="my-dropzone"
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

                            <form id="itemForm" class="" action="{{ route('user.item.update') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                {{-- thumbnail image start --}}
                                <div class="form-group">
                                    <div class="col-12 mb-2">
                                        <label for="">{{ $keywords['Thumbnail'] ?? __('Thumbnail') }}</label>
                                    </div>
                                    <div class="col-md-12 showImage mb-3">
                                        <img src="{{ isset($item->thumbnail) ? asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) : asset('assets/admin/img/noimage.jpg') }}"
                                            alt="..." class="img-thumbnail">
                                    </div>
                                    <input type="file" name="thumbnail" id="image" class="form-control">
                                    <p id="errthumbnail" class="mb-0 text-danger em"></p>
                                </div>
                                {{-- thumbnail image end --}}
                                {{-- START: slider Part --}}
                                <div id="sliders"></div>
                                {{-- END: slider Part --}}
                                <div class="row">
                                    <input type="hidden" name="type" value="{{ $item->type }}">
                                    <div class="col-lg-6">
                                        @if ($item->type == 'physical')
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Stock_Item'] ?? __('Stock Item') }}
                                                    **</label>
                                                <input type="number" class="form-control ltr" name="stock"
                                                    placeholder="{{ $keywords['Enter_Item_Stock'] ?? __('Enter Item Stock') }}"
                                                    value="{{ $item->stock }}">
                                                <p id="errstock" class="mb-0 text-danger em"></p>
                                            </div>
                                        @endif
                                        @if ($item->type == 'digital')
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Type'] ?? __('Type') }} **</label>
                                                <select name="file_type" class="form-control" id="fileType"
                                                    onchange="toggleFileUpload();">
                                                    <option value="upload"
                                                        {{ !empty($item->download_file) ? 'selected' : '' }}>
                                                        {{ $keywords['File_Upload'] ?? __('File Upload') }}
                                                    </option>
                                                    <option value="link"
                                                        {{ !empty($item->download_link) ? 'selected' : '' }}>
                                                        {{ $keywords['File_Download_Link'] ?? __('File Download Link') }}
                                                    </option>
                                                </select>
                                                <p id="errfile_type" class="mb-0 text-danger em"></p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Status'] ?? __('Status') }} **</label>
                                            <select class="form-control ltr" name="status">
                                                <option value="" selected disabled>
                                                    {{ $keywords['Select_a_status'] ?? __('Select a status') }}</option>
                                                <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>
                                                    {{ $keywords['Show'] ?? __('Show') }}
                                                </option>
                                                <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>
                                                    {{ $keywords['Hide'] ?? __('Hide') }}
                                                </option>
                                            </select>
                                            <p id="errstatus" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                @if ($item->type == 'digital')
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="downloadFile" class="form-group">
                                                <label
                                                    for="">{{ $keywords['Downloadable_File'] ?? __('Downloadable File') }}
                                                    **</label>
                                                <br>
                                                <input name="download_file" type="file">
                                                <p class="mb-0 text-warning">
                                                    {{ $keywords['Only_zip_file_is_allowed'] ?? __('Only zip file is allowed') }}.
                                                </p>
                                                <p id="errdownload_file" class="mb-0 text-danger em"></p>
                                            </div>
                                            <div id="downloadLink" class="form-group" style="display: none">
                                                <label
                                                    for="">{{ $keywords['Downloadable_Link'] ?? __('Downloadable Link') }}
                                                    **</label>
                                                <input name="download_link" type="text" class="form-control"
                                                    value="{{ $item->download_link }}">
                                                <p id="errdownload_link" class="mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    @if ($item->type == 'physical')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for=""> {{ $keywords['Product_Sku'] ?? __('Product Sku') }}
                                                    **</label>
                                                <input type="text" class="form-control ltr" name="sku"
                                                    placeholder="{{ $keywords['Enter_Product_sku'] ?? __('Enter Product sku') }}"
                                                    value="{{ $item->sku }}">
                                                <p id="errsku" class="mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for=""> {{ $keywords['Current_Price'] ?? __('Current Price') }}
                                                ({{ $userBs->base_currency_symbol }} )
                                                **</label>
                                            <input type="number" class="form-control ltr" name="current_price"
                                                value="{{ $item->current_price }}"
                                                placeholder="{{ $keywords['Enter_Current_Price'] ?? __('Enter Current Price') }}">
                                            <p id="errcurrent_price" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['Previous_Price'] ?? __('Previous Price') }}
                                                ({{ $userBs->base_currency_symbol }} )</label>
                                            <input type="number" class="form-control ltr" name="previous_price"
                                                value="{{ $item->previous_price }}"
                                                placeholder="{{ $keywords['Enter_Previous_Price'] ?? __('Enter Previous Price') }}">
                                            <p id="errprevious_price" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div id="accordion" class="mt-3">
                                    @foreach ($languages as $language)
                                        @php
                                            $postData = $language
                                                ->itemInfo()
                                                ->where('item_id', $item->id)
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
                                                <div class="version-body" id="app{{ $language->code }}">
                                                    <div class="row">
                                                        @php
                                                            $categories = App\Models\User\UserItemCategory::where('language_id', $language->id)
                                                                ->where('user_id', Auth::guard('web')->user()->id)
                                                                ->orderBy('name', 'asc')
                                                                ->get();
                                                        @endphp
                                                        <input hidden id="subcatGetterForItem"
                                                            value="{{ route('user.item.subcatGetter') }}">
                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Select_Category'] ?? __('Select Category') }}
                                                                    * </label>
                                                                <select data-code="{{ $language->code }}"
                                                                    name="{{ $language->code }}_category"
                                                                    class="form-control getSubCategory">
                                                                    <option value="" disabled>
                                                                        -{{ $keywords['Select_Category'] ?? __('Select Category') }}-
                                                                    </option>
                                                                    @foreach ($categories as $category)
                                                                        <option
                                                                            <?= $postData->category_id == $category->id ? 'selected' : '' ?>
                                                                            value="{{ $category->id }}">
                                                                            {{ $category->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Select_Subcategory'] ?? __('Select Subcategory') }}*</label>
                                                                <select data-code="{{ $language->code }}"
                                                                    name="{{ $language->code }}_subcategory"
                                                                    id="{{ $language->code }}_subcategory"
                                                                    class="form-control">
                                                                    <option value="" disabled>
                                                                        -{{ $keywords['Select_Subcategory'] ?? __('Select Subcategory') }}-
                                                                    </option>
                                                                    @foreach (App\Models\User\UserItemSubCategory::where('language_id', $language->id)->where('user_id', Auth::guard('web')->user()->id)->where('category_id', $postData->category_id ?? '')->get() as $sub)
                                                                        <option
                                                                            <?= $postData->subcategory_id == $sub->id ? 'selected' : '' ?>
                                                                            value="{{ $sub->id }}">
                                                                            {{ $sub->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Title'] ?? __('Title') }} * </label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $language->code }}_title"
                                                                    value="{{ is_null($postData) ? '' : $postData->title }}"
                                                                    placeholder="{{ $keywords['Enter_Title'] ?? __('Enter Title') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 ">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label
                                                                    for="">{{ $keywords['Tags'] ?? __('Tags') }}
                                                                </label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $language->code }}_tags"
                                                                    data-role="tagsinput"
                                                                    placeholder="{{ $keywords['Enter_tags'] ?? __('Enter tags') }}"
                                                                    value="{{ is_null($postData) ? '' : $postData->tags }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Summary'] ?? __('Summary') }}
                                                                    *</label>
                                                                <textarea class="form-control" name="{{ $language->code }}_summary"
                                                                    placeholder="{{ $keywords['Enter_Summary'] ?? __('Enter Summary') }}">{{ is_null($postData) ? '' : $postData->summary }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Description'] ?? __('Description') }}
                                                                    * </label>
                                                                <textarea id="{{ $language->code }}_PostContent" class="form-control summernote"
                                                                    name="{{ $language->code }}_description" placeholder="{{ $keywords['Enter_Content'] ?? __('Enter Content') }}"
                                                                    data-height="300">{{ is_null($postData) ? '' : $postData->description }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Keywords'] ?? __('Keywords') }}</label>
                                                                <input class="form-control"
                                                                    name="{{ $language->code }}_keyword"
                                                                    placeholder="{{ $keywords['Enter_Keywords'] ?? __('Enter  Keywords') }}"
                                                                    value="{{ is_null($postData) ? '' : $postData->meta_keywords }}"
                                                                    data-role="tagsinput">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ $keywords['Meta_keyword'] ?? __('Meta keyword') }}</label>
                                                                <textarea class="form-control" name="{{ $language->code }}_meta_keyword" rows="5"
                                                                    placeholder="{{ $keywords['Enter_Meta_keyword'] ?? __('Enter Meta keyword') }}">{{ is_null($postData) ? '' : $postData->meta_description }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            @php $currLang = $language; @endphp
                                                            @foreach ($languages as $lang)
                                                                @continue($lang->id == $currLang->id)
                                                                <div class="form-check py-0">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $lang->id }}', event)">
                                                                        <span
                                                                            class="form-check-sign">{{ $keywords['Clone_for'] ?? __('Clone for') }}
                                                                            <strong
                                                                                class="text-capitalize text-secondary">{{ $lang->name }}</strong>
                                                                            {{ $keywords['Language'] ?? __('Language') }}</span>
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
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" form="itemForm"
                                    class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if ($item->type == 'digital')
        <script>
            function toggleFileUpload() {
                let type = $("select[name='file_type']").val();
                if (type == 'link') {
                    $("#downloadFile input").attr('disabled', true);
                    $("#downloadFile").hide();
                    $("#downloadLink").show();
                    $("#downloadLink input").removeAttr('disabled');
                } else {
                    $("#downloadLink input").attr('disabled', true);
                    $("#downloadLink").hide();
                    $("#downloadFile").show();
                    $("#downloadFile input").removeAttr('disabled');
                }
            }

            $(document).ready(function() {
                toggleFileUpload();
            });
        </script>
    @endif

    {{-- dropzone --}}
    <script>
        // myDropzone is the configuration for the element that has an id attribute
        // with the value my-dropzone (or myDropzone)
        Dropzone.options.myDropzone = {
            acceptedFiles: '.png, .jpg, .jpeg',
            url: "",
            success: function(file, response) {
                console.log(response.file_id);

                // Create the remove button
                var removeButton = Dropzone.createElement(
                    "<button class='rmv-btn'><i class='fa fa-times'></i></button>");


                // Capture the Dropzone instance as closure.
                var _this = this;

                // Listen to the click event
                removeButton.addEventListener("click", function(e) {
                    // Make sure the button click doesn't submit the form:
                    e.preventDefault();
                    e.stopPropagation();

                    _this.removeFile(file);

                    rmvimg(response.file_id);
                });

                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);

                var content = {};

                content.message = 'Slider images added successfully!';
                content.title = 'Success';
                content.icon = 'fa fa-bell';

                $.notify(content, {
                    type: 'success',
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    time: 1000,
                    delay: 0,
                });
            }
        };

        function rmvimg(fileid) {
            // If you want to the delete the file on the server as well,
            // you can do the AJAX request here.

            $.ajax({
                url: "",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    fileid: fileid
                },
                success: function(data) {
                    var content = {};

                    content.message = 'Slider image deleted successfully!';
                    content.title = 'Success';
                    content.icon = 'fa fa-bell';

                    $.notify(content, {
                        type: 'success',
                        placement: {
                            from: 'top',
                            align: 'right'
                        },
                        time: 1000,
                        delay: 0,
                    });
                }
            });

        }
    </script>


    <script>
        var el = 0;

        function rmvdbimg(indb) {
            $(".request-loader").addClass("show");
            $.ajax({
                url: "",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    fileid: indb
                },
                success: function(data) {
                    $(".request-loader").removeClass("show");
                    $("#trdb" + indb).remove();
                    var content = {};

                    content.message = 'Slider image deleted successfully!';
                    content.title = 'Success';
                    content.icon = 'fa fa-bell';

                    $.notify(content, {
                        type: 'success',
                        placement: {
                            from: 'top',
                            align: 'right'
                        },
                        time: 1000,
                        delay: 0,
                    });
                }
            });

        }
    </script>
    @php
        $test = $languages->pluck('code')->toArray();
        // dump($test);
    @endphp

    <script>
        "use strict";
        const currUrl = "{{ url()->current() }}";
        const fullUrl = "{!! url()->full() !!}";
        const uploadSliderImage = "{{ route('user.item.slider') }}";
        const rmvSliderImage = "{{ route('user.item.slider-remove') }}";
        const rmvDbSliderImage = "{{ route('user.item.db-slider-remove') }}";
    </script>
    <script>
        //shop item
        $(document).ready(function() {
            $('#itemForm').on('submit', function(e) {
                $('.request-loader').addClass('show');
                e.preventDefault();

                let action = $('#itemForm').attr('action');
                let fd = new FormData(document.querySelector('#itemForm'));

                $.ajax({
                    url: action,
                    method: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log('Item Form', data);
                        $('.request-loader').removeClass('show');

                        if (data == 'success') {
                            window.location = currUrl;
                        }
                    },
                    error: function(error) {
                        $('#itemErrors').show();
                        let errors = ``;

                        for (let x in error.responseJSON.errors) {
                            errors += `<li>
              <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
            </li>`;
                        }

                        $('#itemErrors ul').html(errors);

                        $('.request-loader').removeClass('show');

                        $('html, body').animate({
                            scrollTop: $('#itemErrors').offset().top - 100
                        }, 1000);
                    }
                });
            });
        })
    </script>
    < <script src="{{ asset('assets/user/dashboard/js/dropzone-slider.js') }}"></script>
@endsection
