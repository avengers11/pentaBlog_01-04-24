<div class="widget widget-gallery mb-40">
    <h4 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse"
            data-bs-target="#gallery">
            {{$keywords['Gallery'] ?? __('Gallery') }}
            <span class="icons">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
    </h4>
    @if (count($galleryItems) > 0)
    <div id="gallery" class="collapse show">
        <div class="accordion-body mt-20 scroll-y">
            <div class="widget-gallery_img gallery-popup">
                @foreach ($galleryItems as $item)
                @if ($item->item_type == 'image')
                <a href="{{ asset('assets/user/img/gallery/' . $item->image) }}" target="_self"
                    title="Image">
                    <div class="lazy-container ratio ratio-1-1 radius-sm">
                        <img class="lazyload"
                            data-src="{{ $item->image != null ? Storage::url($item->image) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="Image">
                    </div>
                </a>
                @else
                <a href="{{ $item->video_link }}" target="_self" title="video" class="video-link">
                    <div class="lazy-container ratio ratio-1-1 radius-sm">
                        <img class="lazyload" data-src="{{ $item->image != null ? Storage::url($item->image) : asset('assets/admin/img/noimage.jpg') }}" alt="Image">
                    </div>
                    <span class="icon">
                        <i class="fas fa-play"></i>
                    </span>
                </a>
                @endif
                @endforeach

            </div>
        </div>
    </div>
    @else
    <div class="bg-light py-2 mt-20 text-center" role="alert">
        {{$keywords['No_Gallery_Items_Found'] ?? __('No Gallery Items Found !')}}
      </div>
    @endif
</div>
