<?php
$domain = env('WEBSITE_HOST');

if (!app()->runningInConsole()) {
    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
        $domain = 'www.' . env('WEBSITE_HOST');
    }
}

$parsedUrl = parse_url(url()->current());
$host = str_replace("www.", "", $parsedUrl['host']);
if (array_key_exists('host', $parsedUrl)) {
    // if it is a path based URL
    if ($host == env('WEBSITE_HOST')) {
        $domain = $domain;
        $prefix = '/{username}';
    }
    // if it is a subdomain / custom domain
    else {
        if (!app()->runningInConsole()) {
            if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
                $domain = 'www.{domain}';
            } else {
                $domain = '{domain}';
            }
        }
        $prefix = '';
    }
}
Route::group(['domain' => $domain, 'prefix' => $prefix], function () {
    Route::get('/', 'Front\FrontendController@userDetailView')->name('front.user.detail.view');
    Route::post('/advertisement/{id}/total-view', 'Front\FrontendController@countAdView');

    Route::group(['middleware' => ['routeAccess:Gallery']], function () {
        Route::get('/gallery', 'Front\FrontendController@gallery')->name('front.user.gallery');
    });
    Route::get('/about', 'Front\FrontendController@about')->name('front.user.about');

    Route::get('/posts', 'Front\PostController@posts')->name('front.user.posts');
    Route::get('/post/{slug}', 'Front\PostController@postDetails')->name('front.user.post_details');
    Route::post('/post/{id}/make-bookmark', 'Front\PostController@makeBookmark')->name('front.user.make_bookmark');

    Route::post('/subscribe', 'User\SubscriberController@store')->name('front.user.subscriber');
    Route::get('/contact', 'Front\CustomerController@contact')->name('front.user.contact');
    Route::post('/contact/message', 'Front\CustomerController@contactMessage')->name('front.contact.message');
    Route::get('/team', 'Front\FrontendController@userTeam')->name('front.user.team');

    Route::group(['middleware' => ['routeAccess:FAQ']], function () {
        Route::get('/faqs', 'Front\FrontendController@userFaqs')->name('front.user.faq');
    });

    Route::post('/item/payment/submit', 'Front\UsercheckoutController@checkout')->name('item.payment.submit');
    Route::group(['middleware' => ['routeAccess:Ecommerce']], function () {
        Route::get('/shop', 'Front\ShopController@shop')->name('front.user.shop');
        Route::get('/add-to-cart/{id}', 'Front\ItemController@addToCart')->name('front.user.add.cart');
        Route::get('/add-to-wishlist/{id}', 'Front\ItemController@addToWishlist')->name('front.user.add.wishlist');
        Route::get('/item/{slug}', 'Front\ShopController@adDetails')->name('front.user.item_details');
        Route::post('item/review/submit', 'Front\ReviewController@reviewsubmit')->name('item.review.submit');
        Route::get('/cart', 'Front\ItemController@cart')->name('front.user.cart');
        Route::get('/cart/item/remove/{uid}', 'Front\ItemController@cartitemremove')->name('front.cart.item.remove');
        Route::post('/cart/update', 'Front\ItemController@updatecart')->name('front.user.cart.update');
        Route::get('/customer-checkout', 'Front\ItemController@checkout')->name('front.user.checkout');
        Route::post('/coupon', 'Front\ItemController@coupon')->name('front.coupon');
        Route::get('/customer-success', 'Front\CustomerController@onlineSuccess')->name('customer.success.page');
    });
    Route::prefix('item-checkout')->group(function () {
        Route::get('paypal/success', "User\Payment\PaypalController@successPayment")->name('customer.itemcheckout.paypal.success');
        Route::get('paypal/cancel', "User\Payment\PaypalController@cancelPayment")->name('customer.itemcheckout.paypal.cancel');
        Route::get('stripe/cancel', "User\Payment\StripeController@cancelPayment")->name('customer.itemcheckout.stripe.cancel');
        Route::get('paystack/success', 'User\Payment\PaystackController@successPayment')->name('customer.itemcheckout.paystack.success');
        Route::post('mercadopago/cancel', 'User\Payment\paymenMercadopagoController@cancelPayment')->name('customer.itemcheckout.mercadopago.cancel');
        Route::post('mercadopago/success', 'User\Payment\MercadopagoController@successPayment')->name('customer.itemcheckout.mercadopago.success');
        Route::post('razorpay/success', 'User\Payment\RazorpayController@successPayment')->name('customer.itemcheckout.razorpay.success');
        Route::post('razorpay/cancel', 'User\Payment\RazorpayController@cancelPayment')->name('customer.itemcheckout.razorpay.cancel');
        Route::get('instamojo/success', 'User\Payment\InstamojoController@successPayment')->name('customer.itemcheckout.instamojo.success');
        Route::post('instamojo/cancel', 'User\Payment\InstamojoController@cancelPayment')->name('customer.itemcheckout.instamojo.cancel');
        Route::post('flutterwave/success', 'User\Payment\FlutterWaveController@successPayment')->name('customer.itemcheckout.flutterwave.success');
        Route::post('flutterwave/cancel', 'User\Payment\FlutterWaveController@cancelPayment')->name('customer.itemcheckout.flutterwave.cancel');
        Route::get('/mollie/success', 'User\Payment\MollieController@successPayment')->name('customer.itemcheckout.mollie.success');
        Route::post('mollie/cancel', 'User\Payment\MollieController@cancelPayment')->name('customer.itemcheckout.mollie.cancel');
        Route::get('anet/cancel', 'User\Payment\AuthorizenetController@cancelPayment')->name('customer.itemcheckout.anet.cancel');
        Route::get('/offline/success', 'Front\UsercheckoutController@offlineSuccess')->name('customer.itemcheckout.offline.success');
        Route::get('/trial/success', 'Front\CheckoutController@trialSuccess')->name('customer.itemcheckout.trial.success');
        Route::post('paytm/payment-status', "User\Payment\PaytmController@paymentStatus")->name('customer.itemcheckout.paytm.status');
         Route::post('/payment/instructions', 'Front\CustomerController@paymentInstruction')->name('user.front.payment.instructions');
    });

    Route::get('/vcard/{id}', 'Front\FrontendController@vcard')->name('front.user.vcard');
    Route::get('/vcard-import/{id}', 'Front\FrontendController@vcardImport')->name('front.user.vcardImport');
    Route::get('/user/changelanguage', 'Front\FrontendController@changeUserLanguage')->name('changeUserLanguage');

    Route::group(['middleware' => ['routeAccess:Custom Pages']], function () {
        Route::get('/{slug}', 'Front\FrontendController@userCPage')->name('front.user.cpage');
    });

    Route::prefix('/user')->middleware(['guest:customer'])->group(function () {
        // user redirect to login page route
        Route::get('/login',  'Front\CustomerController@login')->name('customer.login');
        // user login submit route
        Route::post('/login-submit', 'Front\CustomerController@loginSubmit')->name('customer.login_submit');
        // user forget password route
        Route::get('/forget-password', 'Front\CustomerController@forgetPassword')->name('customer.forget_password');
        // send mail to user for forget password route
        Route::post('/send-forget-password-mail', 'Front\CustomerController@sendMail')->name('customer.send_forget_password_mail');
        // reset password route
        Route::get('/reset-password', 'Front\CustomerController@resetPassword')->name('customer.reset_password');
        // user reset password submit route
        Route::post('/reset-password-submit', 'Front\CustomerController@resetPasswordSubmit')->name('customer.reset_password_submit');
        // user redirect to signup page route
        Route::get('/signup', 'Front\CustomerController@signup')->name('customer.signup');
        // user signup submit route
        Route::post('/signup-submit', 'Front\CustomerController@signupSubmit')->name('customer.signup.submit');
        // signup verify route
        Route::get('/signup-verify/{token}', 'Front\CustomerController@signupVerify')->name('customer.signup.verify');
    });
    Route::prefix('/user')->middleware(['auth:customer', 'accountStatus', 'checkWebsiteOwner'])->group(function () {
        // user redirect to dashboard route
        Route::get('/dashboard', 'Front\CustomerController@redirectToDashboard')->name('customer.dashboard');


        Route::get('/order/{id}', 'Front\CustomerController@orderdetails')->name('customer.orders-details');
        Route::get('/orders', 'Front\CustomerController@customerOrders')->name('customer.orders');
        Route::get('/wishlist', 'Front\CustomerController@customerWishlist')->name('customer.wishlist');
        Route::get('/remove-from-wishlist/{id}', 'Front\CustomerController@removefromWish')->name('customer.removefromWish');
        //user order
        Route::get('/shipping/details', 'Front\CustomerController@shippingdetails')->name('customer.shpping-details');
        Route::post('/shipping/details/update', 'Front\CustomerController@shippingupdate')->name('customer.shipping-update');
        Route::get('/billing/details', 'Front\CustomerController@billingdetails')->name('customer.billing-details');
        Route::post('/billing/details/update', 'Front\CustomerController@billingupdate')->name('customer.billing-update');
        Route::post('/digital/download', 'Front\CustomerController@digitalDownload')->name('customer.digital-download');
        // edit profile route
        Route::get('/edit-profile', 'Front\CustomerController@editProfile')->name('customer.edit_profile');
        // update profile route
        Route::post('/update-profile', 'Front\CustomerController@updateProfile')->name('customer.update_profile');
        // all bookmarks route
        Route::get('/my-bookmarks', 'Front\CustomerController@myBookmarks')->name('customer.my_bookmarks');
        // change password route
        Route::get('/change-password',  'Front\CustomerController@changePassword')->name('customer.change_password');
        // update password route
        Route::post('/update-password',  'Front\CustomerController@updatePassword')->name('customer.update_password');
        // user logout attempt route
        Route::get('/logout',  'Front\CustomerController@logoutSubmit')->name('customer.logout');
    });
});
