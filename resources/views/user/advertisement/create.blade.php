<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{ $keywords['Add_Advertisement'] ?? __('Add Advertisement') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxForm" class="modal-form" action="{{ route('user.store_advertisement') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label
                            for="">{{ $keywords['Advertisement_Type'] ?? __('Advertisement Type') . '*' }}</label>
                        <select name="ad_type" class="form-control create-ad-type">
                            <option value="" selected disabled>
                                {{ $keywords['Select_a_Type'] ?? __('Select a Type') }}</option>
                            <option value="banner">{{ $keywords['Banner'] ?? __('Banner') }}</option>
                            <option value="script">{{ $keywords['Google_Adsense'] ?? __('Google Adsense') }}</option>
                        </select>
                        <p id="errad_type" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label
                            for="">{{ $keywords['Advertisement_Size'] ?? __('Advertisement Size') . '*' }}</label>
                        <select name="resolution_type" class="form-control">
                            <option value="" selected disabled>
                                {{ $keywords['Select_a_Size'] ?? __('Select a Size') }}</option>
                            <option value="1">{{ __('300 x 250') }}</option>
                            <option value="2">{{ __('300 x 600') }}</option>
                            <option value="3">{{ __('728 x 90') }}</option>
                        </select>
                        <p id="errresolution_type" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group d-none" id="image-input">
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
                                    <p class="text-warning mb-0 mt-2">
                                        {{ $keywords['img_validation_msg'] ?? __('** Only JPG, PNG, JPEG, SVG Images are allowed') }}
                                    </p>
                                    <p id="errimage" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group d-none" id="url-input">
                        <label for="">{{ $keywords['Redirect_URL'] ?? __('Redirect URL') . '*' }}</label>
                        <input type="url" class="form-control" name="url"
                            placeholder="{{ $keywords['Enter_Redirect_URL'] ?? __('Enter Redirect URL') }}">
                        <p id="errurl" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group d-none" id="script-input">
                        <label for="">{{ $keywords['Ad_Slot'] ?? __('Ad Slot') . '*' }}</label>
                        <input type="text" class="form-control" name="ad_slot" placeholder="{{ $keywords['Enter_Ad_Slot'] ?? __('Enter Ad Slot') . '*' }}">
                        <p class="mb-0">
                            <a href="//prnt.sc/1uwa420"
                                target="_blank">{{ $keywords['Click_here'] ?? __('Click here') }}</a>
                            {{ $keywords['to_see_where_to_find_the_Ad_Slot'] ?? __('to see where to find the Ad Slot.') }}
                        </p>
                        <p id="errad_slot" class="mt-2 mb-0 text-danger em"></p>
                    </div>
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
