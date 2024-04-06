<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{ $keywords['Edit_Gallery_Category'] ?? __('Edit Gallery Category') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="ajaxEditForm" class="modal-form"
                    action="{{ route('user.gallery_management.update_category') }}" method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <div class="form-group">
                        <label for="">{{ $keywords['Category_Name'] ?? __('Category Name*') }}</label>
                        <input type="text" id="in_name" class="form-control" name="name"
                            placeholder="{{ $keywords['Enter_Category_Name'] ?? __('Enter Category Name') }}">
                        <p id="eerrname" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ $keywords['Category_Status'] ?? __('Category Status*') }}</label>
                        <select name="status" id="in_status" class="form-control">
                            <option disabled>{{ __('Select a Status') }}</option>
                            <option value="1">{{ $keywords['Active'] ?? __('Active') }}</option>
                            <option value="0">{{ $keywords['Deactive'] ?? __('Deactive') }}</option>
                        </select>
                        <p id="eerrstatus" class="mt-1 mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number*') }}</label>
                        <input type="number" id="in_serial_number" class="form-control ltr" name="serial_number"
                            placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                        <p id="eerrserial_number" class="mt-1 mb-0 text-danger em"></p>
                        <p class="text-warning mt-2">
                            <small>{{ $keywords['Serial_Number_Text'] ?? __('The higher the serial number is, the later the category will be shown.') }}</small>
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
