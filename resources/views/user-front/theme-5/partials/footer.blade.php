<footer class="footer-area bg-light">
    <div class="container">
        @if ($hs->footer === 1)
        <div class="footer-top pt-90 pb-60">
            <div class="row gx-xl-5 justify-content-between">
                <div class="col-lg-3 col-sm-6 col-sm-12">
                    <div class="footer-widget" data-aos-delay="100">
                        @if (!is_null($footerInfo))
                            <div class="logo mb-20">
                                <a class="navbar-brand" href="{{ route('front.user.detail.view', getParam()) }}"
                                    target="_self" title="{{ $websiteInfo->website_title }}">
                                    @if($footerInfo->logo)
                                    <img class="lazyload"
                                        src="{{ $footerInfo->logo != null ? Storage::url($footerInfo->logo) : asset('assets/admin/img/noimage.jpg') }}"
                                        data-src="{{ $footerInfo->logo != null ? Storage::url($footerInfo->logo) : asset('assets/admin/img/noimage.jpg') }}"
                                        alt="{{ $websiteInfo->website_title }}">
                                    @else
                                        <img data-src="{{ asset('assets/user/img/themes/default_dark.png') }}" src="{{ asset('assets/user/img/themes/default_dark.png') }}" alt="Logo">
                                    @endif
                                </a>
                            </div>
                        @else
                            <div class="logo mb-20">
                                <a class="navbar-brand" href="{{ route('front.user.detail.view', getParam()) }}"
                                    target="_self">
                                    <img class="lazyload" src="{{ asset('assets/user/img/themes/default_dark.png') }}" data-src="{{ asset('assets/user/img/themes/default_dark.png') }}" alt="Logo">
                                </a>
                            </div>
                        @endif
                        <p>
                            {{ !is_null($footerInfo) ? $footerInfo->about_company : '' }}
                        </p>
                        @if (count($socialLinkInfos) > 0)
                            <div class="social-link rounded">
                                @foreach ($socialLinkInfos as $socialLink)
                                    <a href="{{ $socialLink->url }}" target="_blank" title=""><i
                                            class="{{ $socialLink->icon }}"></i></a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4">
                    <div class="footer-widget" data-aos-delay="200">
                        <h5 class="title">{{ $keywords['Quick_Links'] ?? __('Quick Links') }} </h5>
                        @if (count($quickLinkInfos) > 0)
                            <ul class="footer-links">
                                @foreach ($quickLinkInfos as $quickLink)
                                    <li>
                                        <a href="{{ $quickLink->url }}" target="_self"
                                            title="{{ $quickLink->title }}">{{ $quickLink->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                        <p>{{ $keywords['No_Links_Found'] ?? __('No Links Found!') }}</p>
                        @endif
                    </div>
                </div>
                @isset($postCategories)
                    <div class="col-lg-2 col-sm-4">
                        <div class="footer-widget" data-aos-delay="200">
                            <h5 class="title">{{ $keywords['Categories'] ?? 'Categories' }}</h5>
                            @if (count($postCategories) > 0)
                                <ul class="footer-links">
                                    @foreach ($postCategories->take(4) as $postCategory)
                                        <li>
                                            <a href="{{ route('front.user.posts', ['category' => $postCategory->id, getParam()]) }}"
                                                target="_self" title="link">{{ $postCategory->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>{{ $keywords['No_Categories_Found'] ?? __('No Categories Found !') }}</p>
                            @endif

                        </div>
                    </div>
                @endisset
                <div class="col-xl-3 col-sm-6">
                    <div class="footer-widget" data-aos-delay="200">
                        <h5 class="title">{{ $keywords['Contact_Us'] ?? 'Contact Us' }}</h5>
                        <ul class="info-list">
                            @isset($websiteInfo->address)
                                <li>
                                    <i class="fal fa-map-marker-alt"></i>
                                    <span>{{ $websiteInfo->address }}</span>
                                </li>
                            @endisset
                            @isset($websiteInfo->support_email)
                                <li>
                                    <i class="fal fa-envelope"></i>
                                    <a href="{{ 'mailto:' . $websiteInfo->support_email }}" target="_self"
                                        title="{{ $websiteInfo->support_email }}">{{ $websiteInfo->support_email }}</a>
                                </li>
                            @endisset
                            @isset($websiteInfo->support_contact)
                                <li>
                                    <i class="fal fa-phone"></i>
                                    <a href="{{ 'tel:' . $websiteInfo->support_contact }}" target="_self"
                                        title="{{ $websiteInfo->support_contact }}">
                                        {{ $websiteInfo->support_contact }}
                                    </a>
                                </li>
                            @endisset
                            @if( !$websiteInfo->address && !$websiteInfo->support_email && !$websiteInfo->support_contact )
                            <p>{{ $keywords['No_Contact_Information_Added '] ?? __('No Contact Information Added !') }}</p>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($hs->copyright_text == 1 && (!is_null($footerInfo) ? $footerInfo->copyright_text : false)  )
            <div class="copy-right-area border-top ptb-30">
                <div class="container">
                    <div class="copy-right-content">
                        <span>
                            {!! $footerInfo->copyright_text !!}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</footer>
