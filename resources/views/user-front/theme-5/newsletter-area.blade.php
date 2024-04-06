<section class="newsletter-area newsletter-area_v1 bg-img bg-cover"
    data-bg-image="{{ asset('assets/user/img/' . $userBs->news_letter_section_bg_image) }}">
    <div class="overlay opacity-75"></div>
    <div class="container">
        <div class="newsletter-inner ptb-60">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="content-title text-center">
                        <h3 class="title mb-10 color-white">
                            {{ $keywords['Newsletter'] ?? __('Newsletter') }}
                        </h3>
                        <p class="text mx-auto color-light">
                            {{ $keywords['Subscribe_to_Our_Newsletter_and_Stay_Updated'] ?? __('Subscribe to Our Newsletter and Stay Updated') }}
                        </p>
                        <div class="newsletter-form mx-auto mt-20">
                            <form id="newsletterForm" action="{{ route('front.user.subscriber', getParam()) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-inline p-1 border radius-sm">
                                    <input class="form-control border-0 color-light"
                                        placeholder="{{ $keywords['Email_Address'] ?? __('Email Address') }}"
                                        type="email" name="email" required="">
                                    <button class="btn-icon radius-sm" type="submit" aria-label="button">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
