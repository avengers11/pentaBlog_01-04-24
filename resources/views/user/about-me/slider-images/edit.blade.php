<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{ $keywords['Edit_Slider_Image'] ?? __('Edit Slider Image') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form" action="{{ route('user.about_me.update_slider_image') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="in_id" name="id">
                    <div class="form-group">
                        <div class="col-12 mb-2">
                            <label for="">{{ $keywords['Image'] ?? __('Image') }}*</label>
                        </div>
                        <div class="col-md-12 showImage mb-3">
                            <img src="#" alt="..." class="in_image img-thumbnail">
                        </div>
                        <input type="file" name="image" id="image" class="form-control image">
                        <p id="eerrimage" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }} * </label>
                        <input type="number" id="in_serial_number" class="form-control ltr" name="serial_number"
                            placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                        <p id="eerrserial_number" class="mt-2 mb-0 text-danger em"></p>
                        <p class="text-warning mt-2">
                            <small>{{ $keywords['Serial_Number_Text'] ?? __('The higher the serial number is, the later the item will be shown.') }}</small>
                        </p>
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
