<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Edit_Post_Category'] ??  __('Edit Post Category') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('user.post_management.update_category') }}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="in_id" name="id">

          @if ($userBs->theme_version == 1 || $userBs->theme_version == 6 || $userBs->theme_version == 7)
          <div class="form-group">
              <div class="col-12 mb-2">
                  <label for="image"><strong>{{ $keywords['Thumbnail_Image'] ?? __('Thumbnail Image') }} **</strong></label>
              </div>
              <div class="col-md-12 showEditImage mb-3">
                  <img
                      src=""
                      alt="..." class="in_image img-thumbnail">
              </div>
              <input type="file" name="image" id="edit_image"
                     class="form-control image">
              <p id="eerrimage" class="mb-0 text-danger em"></p>
          </div>
          @endif

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ $keywords['Category_Name'] ?? __('Category Name') }} **</label>
                <input type="text" id="in_name" class="form-control" name="name" placeholder="{{ $keywords['Enter_Name'] ?? __('Enter Name') }}">
                <p id="eerrname" class="mt-1 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ $keywords['Category_Status'] ?? __('Category Status') }} **</label>
                <select name="status" id="in_status" class="form-control">
                  <option disabled>{{ $keywords['Select_a_status'] ?? __('Select a Status') }}</option>
                  <option value="1">{{ $keywords['Active'] ?? __('Active') }}</option>
                  <option value="0">{{ $keywords['Deactive'] ?? __('Deactive') }}</option>
                </select>
                <p id="eerrstatus" class="mt-1 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }} **</label>
            <input type="number" id="in_serial_number" class="form-control ltr" name="serial_number" placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
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

