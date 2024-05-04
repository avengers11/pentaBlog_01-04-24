@isset($authorInfo)
    <div class="widget widget-author text-center bg-light radius-md mb-40">
        <div class="widget-author_img mx-auto rounded-circl">
            <div class="lazy-container ratio ratio-1-1">
                <img class="lazyload" data-src="{{ $authorInfo->image != null ? Storage::url($authorInfo->image) : asset('assets/admin/img/noimage.jpg') }}"
                    alt="Image">
            </div>
        </div>
        <div class="widget-author_info mt-20">
            <span class="h4 color-primary mb-0">
                {{ $keywords['Hi'] ?? __('Hi') }}
            </span>
            <h4 class="mb-15">
                {{ $keywords['I_Am'] ?? __('I Am') }} {{ $authorInfo->name }}
            </h4>

            <p class="text mb-15">
                {!! strlen(strip_tags($authorInfo->about)) > 100
                    ? mb_substr(strip_tags($authorInfo->about), 0, 100, 'UTF-8') . '...'
                    : strip_tags($authorInfo->about) !!}
            </p>
            @if (count($socialLinkInfos) > 0)
                <div class="social-link icon-only justify-content-center">
                    @foreach ($socialLinkInfos as $socialLink)
                        <a href="{{ $socialLink->url }}" target="_blank" title=""><i
                                class="{{ $socialLink->icon }}"></i></a>
                    @endforeach
                </div>
            @endif
            <div class="cta-btn mt-20">
                <a href="{{ route('front.user.about', getParam()) }}" class="btn btn-lg btn-primary rounded-pill"
                    title="Read More" target="_self"> {{ $keywords['Learn_More'] ?? 'Learn More' }} </a>
            </div>
        </div>
    </div>
@else
<div class="bg-light py-2 mb-20 text-center" role="alert">
    {{ $keywords['No_author_info_found'] ?? 'No Author Info Found !' }}
</div>
@endisset
