<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{ $keywords['Edit_Advertisement'] ?? __('Edit Advertisement') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form" action="{{ route('user.update_advertisement') }}"
                    method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <div class="form-group">
                        <label
                            for="">{{ $keywords['Advertisement_Type'] ?? __('Advertisement Type') . '*' }}</label>
                        <select name="ad_type" class="form-control edit-ad-type" id="in_ad_type">
                            <option disabled>{{ $keywords['Select_a_Type'] ?? __('Select a Type') }}</option>
                            <option value="banner">{{ $keywords['Banner'] ?? __('Banner') }}</option>
                            <option value="script">{{ $keywords['Google_Adsense'] ?? __('Google Adsense') }}</option>
                        </select>
                        <p id="eerrad_type" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label
                            for="">{{ $keywords['Advertisement_Resolution'] ?? __('Advertisement Resolution') . '*' }}</label>
                        <select name="resolution_type" class="form-control" id="in_resolution_type">
                            <option disabled>{{ $keywords['Select_a_Size'] ?? __('Select a Size') }}</option>
                            <option value="1">{{ __('300 x 250') }}</option>
                            <option value="2">{{ __('300 x 600') }}</option>
                            <option value="3">{{ __('728 x 90') }}</option>
                        </select>
                        <p id="eerrresolution_type" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group d-none" id="edit-image-input">
                        <div class="form-group">
                            <div class="col-12 mb-2">
                                <label for="">{{ $keywords['Image'] ?? __('Image') }} **</label>
                            </div>
                            <div class="col-md-12 showImage mb-3">
                                <img src="#" alt="..." class="in_image img-thumbnail">
                            </div>
                            <input type="file" name="image" id="image" class="form-control image">
                            @if ($errors->has('image'))
                                <p class="mt-2 mb-0 text-danger">{{ $errors->first('image') }}</p>
                            @endif
                        </div>
                        <p class="text-warning mb-0 mt-2">
                            {{ $keywords['img_validation_msg'] ?? __('** Only JPG, PNG, JPEG, SVG Images are allowed') }}
                        </p>
                        <p id="eerrimage" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group d-none" id="edit-url-input">
                        <label for="">{{ $keywords['Redirect_URL'] ?? __('Redirect URL') . '*' }}</label>
                        <input type="url" class="form-control" name="url"
                            placeholder="{{ $keywords['Enter_Redirect_URL'] ?? __('Enter Redirect URL') }}"
                            id="in_url">
                        <p id="eerrurl" class="mt-2 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group d-none" id="edit-script-input">
                        <label for="">{{ $keywords['Ad_Slot'] ?? __('Ad Slot') . '*' }}</label>
                        <input type="text" class="form-control" name="ad_slot"
                            placeholder="{{ $keywords['Enter_Ad_Slot'] ?? __('Enter Ad Slot') . '*' }}"
                            id="in_ad_slot">
                        <p class="mb-0">
                            <a href="//prnt.sc/1uwa420"
                                target="_blank">{{ $keywords['Click_here'] ?? __('Click here') }}</a>
                            {{ $keywords['to_see_where_to_find_the_Ad_Slot'] ?? __('to see where to find the Ad Slot.') }}
                        </p>
                        <p id="eerrad_slot" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ $keywords['Close'] ?? __('Close') }}
                </button>
                <button id="updateBtn" type="button" class="btn btn-primary">
                    {{ $keywords['Update'] ?? __('Update') }}
                </button>
            </div>
        </div>
    </div>
</div>
