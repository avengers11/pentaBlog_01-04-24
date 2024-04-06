<div class="widget widget-newsletter mb-40">
    <h4 class="title">
        {{ $keywords['Newsletter'] ?? __('Newsletter') }}
        <span class="line"></span>
    </h4>
    <div class="newsletter-form mt-20 mb-10">
        <form id="newsletterForm" action="{{ route('front.user.subscriber', getParam()) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-inline p-1 border radius-sm">
                <input class="form-control border-0"
                    placeholder="{{ $keywords['Email_Address'] ?? __('Email Address') }}" type="email" name="email"
                    required="">
                <button class="btn-icon radius-sm" type="submit" aria-label="button">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
    <p class="text">
        {{ $keywords['Subscribe_to_Our_Newsletter_and_Stay_Updated'] ?? __('Subscribe to Our Newsletter and Stay Updated') }}
    </p>
</div>
