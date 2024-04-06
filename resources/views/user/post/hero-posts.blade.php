<div class="modal fade" id="hero-post-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ $keywords['Make_Hero_Post'] ?? __('Make hero Post') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form id="ajaxForm_2" class="modal-form" action="{{ route('user.post_management.update_hero_post') }}" method="POST">
            @csrf
            <input type="hidden" id="in_id_hero" name="id">
            <input type="hidden" id="in_is_hero" name="is_hero_post">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="form-group">
                          <div class="col-md-12 showImage mb-3">
                              <img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="..." class="img-thumbnail">
                          </div>
                          <input type="file" name="hero_post_image" id="image" class="form-control">
                          <p class="text-warning mb-0 mt-2">** {{ $keywords['img_validation_msg'] ?? __('Only JPG, PNG, JPEG, SVG Images are allowed')}}</p>
                          <p id="errhero_post_image" class="mt-2 mb-0 text-danger em"></p>

                          <div>
                            <select name="image_size_type" id="" class="form-control">
                                <option value="" disabled selected>{{ $keywords['select_option'] ?? __('Select Option') }}</option>
                                <option value="side_post">{{ $keywords['side_post'] ?? 'Side Post' }}</option></option>
                                <option value="middle_post"> {{ $keywords['middle_post'] ?? 'Middle Post' }}</option>
                            </select>
                            <p></p>
                            <p id="errimage_size_type" class="mt-2 mb-0 text-danger em">
                                {{$keywords['Select_750x945_will_show_the_hero_section_in_the_middle_and_750x422_will_show_on_the_left_and_right.'] ?? __('Select 750x945 will show the hero section in the middle, and 750x422 will show on the left and right.')}}
                            </p>
                          </div>

                              <p class="text-warning mt-2 mb-0">{{ $keywords['Upload_750x945_pixel_size_image_for_best_quality'] ?? __('Upload 750x945 pixel size image for best quality') }}.</p>
                              <small> {{ $keywords['OR'] ?? __('OR') }} </small>
                              <p class="text-warning mb-0">{{ $keywords['Upload_750x422_pixel_size_image_for_best_quality'] ?? __('Upload 750x422 pixel size image for best quality') }}.</p>
                      </div>
                  </div>
              </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            {{ $keywords['Close'] ?? __('Close') }}
          </button>
          <button type="button" class="btn btn-primary" id="submitBtn_2">
            {{ $keywords['Save'] ?? __('Save') }}
          </button>
        </div>
      </div>
    </div>
  </div>
