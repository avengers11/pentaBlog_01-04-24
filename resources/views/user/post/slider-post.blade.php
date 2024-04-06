<div class="modal fade" id="slider-post-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ $keywords['Make_Slider_Post'] ?? __('Make Slider Post') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form" action="{{ route('user.post_management.update_slider_post') }}" method="POST">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <input type="hidden" id="in_is_slider" name="is_slider">

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="col-md-12 showImage mb-3">
                            <img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="..." class="img-thumbnail">
                        </div>
                        <input type="file" name="slider_post_image" id="image" class="form-control">
                        <p class="text-warning mb-0 mt-2">** {{ $keywords['img_validation_msg'] ?? __('Only JPG, PNG, JPEG, SVG Images are allowed')}}</p>
                        <p id="errslider_post_image" class="mt-2 mb-0 text-danger em"></p>
                        @if ($themeInfo->theme_version == 1)
                            <p class="text-warning mt-2 mb-0">{{ $keywords['Upload_1030x700_pixel_size_image_for_best_quality'] ?? __('Upload 1030x700 pixel size image for best quality') }}.</p>
                        @elseif ($themeInfo->theme_version == 2)
                            <p class="text-warning mt-2 mb-0">{{ $keywords['Upload_500x600_pixel_size_image_for_best_quality'] ?? __('Upload 500x600 pixel size image for best quality') }}.</p>
                        @elseif ($themeInfo->theme_version == 3)
                            <p class="text-warning mt-2 mb-0">{{ $keywords['Upload_570X450_pixel_size_image_for_best_quality'] ?? __('Upload 570X450 pixel size image for best quality') }}.</p>
                        @elseif($themeInfo->theme_version == 4)
                            <p class="text-warning mt-2 mb-0">{{ $keywords['Upload_770x450_pixel_size_image_for_best_quality'] ?? __('Upload 770x450 pixel size image for best quality') }}.</p>
                        @elseif($themeInfo->theme_version == 5)
                        <p class="text-warning mt-2 mb-0">{{ $keywords['Upload_750x945_pixel_size_image_for_best_quality'] ?? __('Upload 750x945 pixel size image for best quality') }}.</p>
                        @elseif($themeInfo->theme_version == 6)
                        <p class="text-warning mt-2 mb-0">{{ $keywords['Upload_770x450_pixel_size_image_for_best_quality'] ?? __('Upload 770x450 pixel size image for best quality') }}.</p>
                        @else
                        <p class="text-warning mt-2 mb-0">{{ $keywords['Upload_770x450_pixel_size_image_for_best_quality'] ?? __('Upload 770x450 pixel size image for best quality') }}.</p>
                        @endif
                    </div>
                </div>
            </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          {{ $keywords['Close'] ?? __('Close') }}
        </button>
        <button type="button" class="btn btn-primary" id="submitBtn">
          {{ $keywords['Save'] ?? __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
