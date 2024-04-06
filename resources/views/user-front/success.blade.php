<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $bs->website_title }} - {{ $keywords['payment_success'] ?? 'Payment Success' }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/plugin.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/front/css/success.css') }}" />
    @if ($userBs->theme_version == 5)
        <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/theme-dark.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/user/css/theme567/assets/css/dark-success.css') }}">
    @endif
    <!-- base color change -->
    <link href="{{ asset('assets/front/css/style-base-color.php') . '?color=' . $bs->base_color }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto" id="mt">
                <div class="payment">
                    <div class="payment_header">
                        <div class="check">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="content">
                        <h1> {{ $keywords['payment_success'] ?? 'Payment Success' }}</h1>
                        <p class="paragraph-text">
                            {{ $keywords['payment_success_msg'] ?? 'Your order successfully received. Thanks for trust us' }}
                        </p>
                        <a href="{{ route('front.user.detail.view', getParam()) }}">
                            {{ $keywords['Go_To_Home'] ?? 'Go to Home' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
