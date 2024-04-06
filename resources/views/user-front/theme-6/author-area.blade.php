@isset($authorInfo)
<div class="widget widget-author text-center bg-light">
    <div class="widget-author_img mx-auto">
        <div class="lazy-container ratio ratio-1-1 rounded-circle">
            <img class="lazyload" data-src="{{ asset('assets/user/img/authors/' . $authorInfo->image) }}" alt="{{ $authorInfo->name }}">
        </div>
    </div>
    <div class="widget-author_info mt-20">
        <span class="h4 color-primary mb-0">
            {{ $keywords['Hi'] ?? __('Hi')}}
        </span>
        <h4 class="mb-15">
            {{ $keywords['I_Am'] ?? __('I Am') }} {{$authorInfo->name}}
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
            <a href="{{ route('front.user.about', getParam()) }}" class="btn-icon rounded-circle" title="{{ $keywords['More'] ?? 'More' }} " target="_self">
                {{ $keywords['More'] ?? 'More' }}
            </a>
        </div>
    </div>
</div>
@else
<div class="alert alert-secondary py-1 mb-30 text-center" role="alert">
    {{ $keywords['No_author_info_found'] ?? 'No Author Info Found !' }}
</div>
@endisset

