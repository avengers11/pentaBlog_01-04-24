<div class="widget widget-newsletter mb-40">
    <h4 class="title">
        {{ $keywords['Newsletter'] ?? __('Newsletter') }}
    </h4>
    <div class="newsletter-form mt-20 mb-10">
        <form id="newsletterForm" action="{{ route('front.user.subscriber', getParam()) }}" method="POST">
            @csrf
            <div class="input-inline">
                <input class="form-control" placeholder="{{ $keywords['Email_Address'] ?? __('Email Address') }}"
                    type="email" name="email" required="">

                <button class="btn btn-sm btn-primary radius-sm" type="submit" aria-label="button">
                    <span class="d-none d-sm-inline">
                        {{ $keywords['Subscribe'] ?? __('Subscribe') }}
                        &nbsp;
                    </span>
                    <i class="fas fa-paper-plane"></i>
                </button>

            </div>
        </form>
    </div>
    <p class="text">
        {{ $keywords['Subscribe_to_Our_Newsletter_and_Stay_Updated'] ?? __('Subscribe to Our Newsletter and Stay Updated') }}
    </p>
</div>
