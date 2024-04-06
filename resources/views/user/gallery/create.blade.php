<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Add_Item'] ?? __('Add Item') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form" action="{{ route('user.gallery_management.store_item') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="d-sm-inline mr-5">
                            <input type="radio" checked class="mr-1 createItemRadioBtn" name="item_type"
                                value="image">
                            <label for="">{{ $keywords['Image'] ?? __('Image') }}</label>
                        </div>

                        <div class="d-sm-inline">
                            <input type="radio" class="mr-1 createItemRadioBtn" name="item_type" value="video">
                            <label for="">{{ $keywords['Video'] ?? __('Video') }}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-12 mb-2">
                                    <label for="image"><strong>{{ $keywords['Image'] ?? __('Image') }}
                                            **</strong></label>
                                </div>
                                <div class="col-md-12 showImage mb-3">
                                    <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                        class="img-thumbnail">
                                </div>
                                <input type="file" name="image" id="image" class="form-control">
                                <p id="errimage" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group d-none" id="video-input">
                        <label for="">{{ $keywords['Video_Link'] ?? __('Video Link*') }}</label>
                        <input type="url" class="form-control ltr" name="video_link"
                            placeholder="{{ $keywords['Enter_Video_Link'] ?? __('Enter Video Link') }}">
                        <p id="err_video_link" class="mt-1 mb-0 text-danger em"></p>
                        <p class="text-warning mt-2 mb-0">
                            <small>{{$keywords['Video_Link_Text'] ?? __('Link will be formatted automatically after submitting the form.') }}</small>
                        </p>
                    </div>

                    <div class="row no-gutters">
                        <div class="{{ $userBs->gallery_category_status == 1 ? 'col-lg-6' : 'col-sm-12' }}">
                            <div class="form-group">
                                <label for="">{{ $keywords['Language'] ?? __('Language*') }}</label>
                                <select name="user_language_id" class="form-control" id="gallery_language">
                                    <option selected disabled>
                                        {{ $keywords['Select_a_Language'] ?? __('Select a Language') }}</option>
                                    @foreach ($langs as $lang)
                                        <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                                <p id="erruser_language_id" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            @if ($userBs->gallery_category_status == 1)
                                <div class="form-group">
                                    <label>{{ $keywords['Category'] ?? __('Category*') }}</label>
                                    <select name="gallery_category_id" class="form-control" disabled id="gcategory">
                                        <option selected disabled>
                                            {{ $keywords['Select_a_Category'] ?? __('Select a Category') }}</option>
                                    </select>
                                    <p id="errgallery_category_id" class="mt-1 mb-0 text-danger em"></p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ $keywords['Title'] ?? __('Title*') }}</label>
                                <input type="text" class="form-control" name="title"
                                    placeholder="{{$keywords['Enter_Title'] ?? __('Enter Title') }}">
                                <p id="errtitle" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number*') }}</label>
                                <input type="number" class="form-control ltr" name="serial_number"
                                    placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                <p id="errserial_number" class="mt-1 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <p class="text-warning ml-2 mb-0">
                        <small>{{ $keywords['Serial_Number_Text'] ?? __('*The higher the serial number is, the later the  item will be shown.') }}</small>
                    </p>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ $keywords['Close'] ?? __('Close') }}
                </button>
                <button id="submitBtn" type="button" class="btn btn-primary">
                    {{ $keywords['Save'] ?? __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
