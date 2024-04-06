<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{ $keywords['Edit_Quick_Links'] ?? __('Edit Quick Links') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form" action="{{ route('user.footer.update_quick_link') }}"
                    method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="link_id">

                    <div class="form-group">
                        <label for="">{{ $keywords['Title'] ?? __('Title') }}*</label>
                        <input type="text" id="in_title" class="form-control" name="title">
                        <p id="eerrtitle" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ $keywords['URL'] ?? __('URL') }} *</label>
                        <input type="url" id="in_url" class="form-control ltr" name="url">
                        <p id="eerrurl" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }} *</label>
                        <input type="number" id="in_serial_number" class="form-control ltr"
                            placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}"
                            name="serial_number">
                        <p id="eerrserial_number" class="mt-1 mb-0 text-danger em"></p>
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
