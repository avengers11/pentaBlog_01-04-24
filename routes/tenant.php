<?php

$domain = env('WEBSITE_HOST');

if (!app()->runningInConsole()) {
    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
        $domain = 'www.' . env('WEBSITE_HOST');
    }
}

/*=======================================================
    ******************** User Routes **********************
    =======================================================*/
Route::domain($domain)->group(function () {
    Route::group(['middleware' => ['guest', 'setlang']], function () {
        Route::get('/registration/final-step', 'Front\FrontendController@step2')->name('front.registration.step2');
        Route::post('/checkout', 'Front\FrontendController@checkout')->name('front.checkout.view');

        Route::get('/login', 'User\Auth\LoginController@showLoginForm')->name('user.login');
        Route::post('/login', 'User\Auth\LoginController@login')->name('user.login.submit');
        Route::get('/register', 'User\Auth\RegisterController@registerPage')->name('user-register');
        Route::post('/register/submit', 'User\Auth\RegisterController@register')->name('user-register-submit');
        Route::get('/register/mode/{mode}/verify/{token}', 'User\Auth\RegisterController@token')->name('user-register-token');

        Route::post('/password/email', 'User\Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.forgot.password.submit');
        Route::get('/password/reset', 'User\Auth\ForgotPasswordController@showLinkRequestForm')->name('user.forgot.password.form');
        Route::post('/password/reset', 'User\Auth\ResetPasswordController@reset')->name('user.reset.password.submit');
        Route::get('/password/reset/{token}/email/{email}', 'User\Auth\ResetPasswordController@showResetForm')->name('user.reset.password.form');

        Route::get('/forgot', 'User\ForgotController@showforgotform')->name('user-forgot');
        Route::post('/forgot', 'User\ForgotController@forgot')->name('user-forgot-submit');
    });

    Route::group(['prefix' => 'user', 'middleware' => ['auth', 'userstatus']], function () {
        // user theme change
        Route::get('/change-theme', 'User\UserController@changeTheme')->name('user.theme.change');
        // RTL check
        Route::get('/rtlcheck/{langid}', 'User\LanguageController@rtlcheck')->name('user.rtlcheck');
        Route::get('/dashboard', 'User\UserController@index')->name('user-dashboard');
        Route::get('/reset', 'User\UserController@resetform')->name('user-reset');
        Route::post('/reset', 'User\UserController@reset')->name('user-reset-submit');
        Route::get('/profile', 'User\UserController@profile')->name('user-profile');
        Route::post('/profile', 'User\UserController@profileupdate')->name('user-profile-update');
        Route::get('/logout', 'User\Auth\LoginController@logout')->name('user-logout');


        Route::group(['middleware' => 'checkUserPermission:Ecommerce'], function () {
            Route::get('/edit_mail_template/{id}', 'User\MailTemplateController@editMailTemplate')->name('user.basic_settings.edit_mail_template');
            Route::get('/mail_templates', 'User\MailTemplateController@mailTemplates')->name('user.basic_settings.mail_templates');
            Route::post('/update_mail_template/{id}', 'User\MailTemplateController@updateMailTemplate')->name('user.basic_settings.update_mail_template');
        });

        // Payment Log
        Route::get('/payment-log', 'User\PaymentLogController@index')->name('user.payment-log.index');

        // User Domains & URLs
        Route::group(['middleware' => 'checkUserPermission:Custom Domain'], function () {
            Route::get('/domains', 'User\DomainController@domains')->name('user-domains');
            Route::post('/request/domain', 'User\DomainController@domainrequest')->name('user-domain-request');
        });

        //START SHOP MANAGEMENT
        Route::group(['middleware' => 'checkUserPermission:Ecommerce'], function () {
            // Category
            Route::get('/category', 'User\ItemCategoryController@index')->name('user.itemcategory.index');
            Route::post('/category/store', 'User\ItemCategoryController@store')->name('user.itemcategory.store');
            Route::get('/category/{id}/edit', 'User\ItemCategoryController@edit')->name('user.itemcategory.edit');
            Route::post('/category/update', 'User\ItemCategoryController@update')->name('user.itemcategory.update');
            Route::post('/category/feature', 'User\ItemCategoryController@feature')->name('user.itemcategory.feature');
            Route::post('/category/delete', 'User\ItemCategoryController@delete')->name('user.itemcategory.delete');
            Route::post('/category/bulk-delete', 'User\ItemCategoryController@bulkDelete')->name('user.itemcategory.bulk.delete');
            //    SUb Category
            Route::get('/subcategory', 'User\ItemSubCategoryController@index')->name('user.itemsubcategory.index');
            Route::post('/subcategory/store', 'User\ItemSubCategoryController@store')->name('user.itemsubcategory.store');
            Route::get('/subcategory/{id}/edit', 'User\ItemSubCategoryController@edit')->name('user.itemsubcategory.edit');
            Route::post('/subcategory/update', 'User\ItemSubCategoryController@update')->name('user.itemsubcategory.update');
            Route::post('/subcategory/feature', 'User\ItemSubCategoryController@feature')->name('user.itemsubcategory.feature');
            Route::post('/subcategory/delete', 'User\ItemSubCategoryController@delete')->name('user.itemsubcategory.delete');
            Route::post('/subcategory/bulk-delete', 'User\ItemSubCategoryController@bulkDelete')->name('user.itemsubcategory.bulk.delete');

            Route::get('/shipping', 'User\ShopSettingController@index')->name('user.shipping.index');
            Route::post('/shipping/store', 'User\ShopSettingController@store')->name('user.shipping.store');
            Route::get('/shipping/{id}/edit', 'User\ShopSettingController@edit')->name('user.shipping.edit');
            Route::post('/shipping/update', 'User\ShopSettingController@update')->name('user.shipping.update');
            Route::post('/shipping/delete', 'User\ShopSettingController@delete')->name('user.shipping.delete');


            Route::get('/item', 'User\ItemController@index')->name('user.item.index');
            Route::get('/item/type', 'User\ItemController@type')->name('user.item.type');
            Route::get('/item/create', 'User\ItemController@create')->name('user.item.create');
            Route::post('/item/store', 'User\ItemController@store')->name('user.item.store');
            Route::get('/item/{id}/edit', 'User\ItemController@edit')->name('user.item.edit');
            Route::post('/item/update', 'User\ItemController@update')->name('user.item.update');
            Route::post('/item/delete', 'User\ItemController@delete')->name('user.item.delete');
            Route::get('/item/{id}/variations', 'User\ItemController@variations')->name('user.item.variations');
            Route::post('/item/variation/store', 'User\ItemController@variationStore')->name('user.item.variation.store');

            Route::post('/item/slider', 'User\ItemController@slider')->name('user.item.slider');
            Route::post('/item/slider/remove', 'User\ItemController@sliderRemove')->name('user.item.slider-remove');
            Route::post('/item/db/slider/remove', 'User\ItemController@dbSliderRemove')->name('user.item.db-slider-remove');

            Route::post('/item/sub-category-getter', 'User\ItemController@subcatGetter')->name('user.item.subcatGetter');

            Route::get('item/{id}/getcategory', 'User\ItemController@getCategory')->name('user.item.getcategory');
            Route::post('/item/delete', 'User\ItemController@delete')->name('user.item.delete');
            Route::post('/item/bulk-delete', 'User\ItemController@bulkDelete')->name('user.item.bulk.delete');
            Route::post('/item/sliderupdate', 'User\ItemController@sliderupdate')->name('user.item.sliderupdate');


            Route::get('/item/{id}/variants', 'User\ItemController@variants')->name('user.item.variants');
            // Route::post('/item/{id}/uploadUpdate', 'User\ItemController@uploadUpdate')->name('user.item.uploadUpdate');
            Route::post('/item/update', 'User\ItemController@update')->name('user.item.update');

            Route::get('/item/settings', 'User\ItemController@settings')->name('user.item.settings');
            Route::post('/item/settings', 'User\ItemController@updateSettings')->name('user.item.settings');

            // User Coupon Routes
            Route::get('/coupon', 'User\CouponController@index')->name('user.coupon.index');
            Route::post('/coupon/store', 'User\CouponController@store')->name('user.coupon.store');
            Route::get('/coupon/{id}/edit', 'User\CouponController@edit')->name('user.coupon.edit');
            Route::post('/coupon/update', 'User\CouponController@update')->name('user.coupon.update');
            Route::post('/coupon/delete', 'User\CouponController@delete')->name('user.coupon.delete');
            // User Coupon Routes End
            Route::post('/orders/mail', 'Admin\ItemOrderController@mail')->name('user.orders.mail');

            // Product Order
            Route::get('/item/all/orders', 'User\ItemOrderController@all')->name('user.all.item.orders');
            Route::get('/item/pending/orders', 'User\ItemOrderController@pending')->name('user.pending.item.orders');
            Route::get('/item/processing/orders', 'User\ItemOrderController@processing')->name('user.processing.item.orders');
            Route::get('/item/completed/orders', 'User\ItemOrderController@completed')->name('user.completed.item.orders');
            Route::get('/item/rejected/orders', 'User\ItemOrderController@rejected')->name('user.rejected.item.orders');
            Route::post('/item/orders/status', 'User\ItemOrderController@status')->name('user.item.orders.status');
            Route::get('/item/orders/details/{id}', 'User\ItemOrderController@details')->name('user.item.details');
            Route::post('/item/order/delete', 'User\ItemOrderController@orderDelete')->name('user.item.order.delete');
            Route::post('/item/order/bulk-delete', 'User\ItemOrderController@bulkOrderDelete')->name('user.item.order.bulk.delete');
            Route::get('/item/orders/report', 'User\ItemOrderController@report')->name('user.orders.report');
            Route::get('/item/export/report', 'User\ItemOrderController@exportReport')->name('user.orders.export');
            Route::post('/item/payment/status', 'User\ItemOrderController@paymentStatus')->name('user.item.paymentStatus');
            // Product Order end


            // user start register-user, ban user, details, reports
            Route::post('user/customer/ban', 'User\UserController@userban')->name('user.customer.ban');
            Route::get('register/customer/details/{id}', 'User\UserController@view')->name('register.customer.view');
            Route::post('register/customer/email', 'User\UserController@emailStatus')->name('register.customer.email');
            Route::get('/ads-reports', 'User\PostController@viewReports')->name('user.ads-report');
            Route::get('/register-user', 'User\UserController@registerUsers')->name('user.register-user');
            Route::get('register/customer/{id}/changePassword', 'User\UserController@changePassCstmr')->name('register.customer.changePass');
            Route::post('register/customer/updatePassword', 'User\UserController@updatePasswordCstmr')->name('register.customer.updatePassword');
            Route::post('register/customer/delete', 'User\UserController@delete')->name('register.customer.delete');
            Route::post('register/customer/bulk-delete', 'User\UserController@bulkDelete')->name('register.customer.bulk.delete');
            Route::post('/digital/download', 'User\ItemOrderController@digitalDownload')->name('user-digital-download');
            // user End register-user, ban user, details, reports
        });
        //END SHOP MANAGEMENT

        Route::group(['middleware' => 'checkUserPermission:Ecommerce'], function () {
            // User Online Gateways Routes
            Route::get('/gateways', 'User\GatewayController@index')->name('user.gateway.index');
            Route::post('/stripe/update', 'User\GatewayController@stripeUpdate')->name('user.stripe.update');
            Route::post('/anet/update', 'User\GatewayController@anetUpdate')->name('user.anet.update');
            Route::post('/paypal/update', 'User\GatewayController@paypalUpdate')->name('user.paypal.update');
            Route::post('/paystack/update', 'User\GatewayController@paystackUpdate')->name('user.paystack.update');
            Route::post('/paytm/update', 'User\GatewayController@paytmUpdate')->name('user.paytm.update');
            Route::post('/flutterwave/update', 'User\GatewayController@flutterwaveUpdate')->name('user.flutterwave.update');
            Route::post('/instamojo/update', 'User\GatewayController@instamojoUpdate')->name('user.instamojo.update');
            Route::post('/mollie/update', 'User\GatewayController@mollieUpdate')->name('user.mollie.update');
            Route::post('/razorpay/update', 'User\GatewayController@razorpayUpdate')->name('user.razorpay.update');
            Route::post('/mercadopago/update', 'User\GatewayController@mercadopagoUpdate')->name('user.mercadopago.update');

            // User Offline Gateway Routes
            Route::get('/offline/gateways', 'User\GatewayController@offline')->name('user.gateway.offline');
            Route::post('/offline/gateway/store', 'User\GatewayController@store')->name('user.gateway.offline.store');
            Route::post('/offline/gateway/update', 'User\GatewayController@update')->name('user.gateway.offline.update');
            Route::post('/offline/status', 'User\GatewayController@status')->name('user.offline.status');
            Route::post('/offline/gateway/delete', 'User\GatewayController@delete')->name('user.offline.gateway.delete');
        });


        // User Subdomains & URLs
        Route::get('/subdomain', 'User\SubdomainController@subdomain')->name('user-subdomain');

        //user follow and following list
        Route::group(['middleware' => 'checkPackage'], function () {
            Route::get('/follower-list', 'User\FollowerController@follower')->name('user.follower.list');
            Route::get('/following-list', 'User\FollowerController@following')->name('user.following.list');
            Route::get('/follow/{id}', 'User\FollowerController@follow')->name('user.follow');
            Route::get('/unfollow/{id}', 'User\FollowerController@unfollow')->name('user.unfollow');
        });

        Route::get('/change-password', 'User\UserController@changePass')->name('user.changePass');
        Route::post('/profile/updatePassword', 'User\UserController@updatePassword')->name('user.updatePassword');

        //user language
        Route::group(['middleware' => 'checkPackage'], function () {
            Route::get('/languages', 'User\LanguageController@index')->name('user.language.index');
            Route::get('/language/{id}/edit', 'User\LanguageController@edit')->name('user.language.edit');
            Route::get('/language/{id}/edit/keyword', 'User\LanguageController@editKeyword')->name('user.language.editKeyword');
            Route::post('/language/{id}/update/keyword', 'User\LanguageController@updateKeyword')->name('user.language.updateKeyword');
            Route::post('/language/store', 'User\LanguageController@store')->name('user.language.store');
            Route::post('/language/upload', 'User\LanguageController@upload')->name('user.language.upload');
            Route::post('/language/{id}/uploadUpdate', 'User\LanguageController@uploadUpdate')->name('user.language.uploadUpdate');
            Route::post('/language/{id}/default', 'User\LanguageController@default')->name('user.language.default');
            Route::post('/language/{id}/delete', 'User\LanguageController@delete')->name('user.language.delete');
            Route::post('/language/update', 'User\LanguageController@update')->name('user.language.update');
        });

        //user color
        Route::get('color', 'User\ColorController@index')->name('user.color.index');
        Route::post('color/update', 'User\ColorController@update')->name('user.color.update');

        Route::group(['middleware' => 'checkPackage'], function () {
            // basic settings favicon route
            Route::get('/basic-settings/favicon', 'User\BasicSettingController@favicon')->name('user.basic_settings.favicon');
            Route::post('/basic-settings/update-favicon', 'User\BasicSettingController@updateFavicon')->name('user.basic_settings.update_favicon');

            // basic settings logo route
            Route::get('/basic-settings/logo', 'User\BasicSettingController@logo')->name('user.basic_settings.logo');
            Route::post('/basic-settings/update-logo', 'User\BasicSettingController@updateLogo')->name('user.basic_settings.update_logo');

            // basic settings logo route
            Route::get('/basic-settings/preloader', 'User\BasicSettingController@preloader')->name('user.basic_settings.preloader');
            Route::post('/basic-settings/update-preloader', 'User\BasicSettingController@updatePreloader')->name('user.basic_settings.update_preloader');

            // basic settings logo route
            Route::get('/basic-settings/preferences', 'User\BasicSettingController@preferences')->name('user.basic_settings.preferences');
            Route::post('/basic-settings/update-preferences', 'User\BasicSettingController@updatePreferences')->name('user.basic_settings.update_preferences');

            // basic settings information route
            Route::get('/basic-settings/information', 'User\BasicSettingController@information')->name('user.basic_settings.information');
            Route::post('/basic-settings/update-info', 'User\BasicSettingController@updateInfo')->name('user.basic_settings.update_info');

            // basic settings (theme & home) route
            Route::get('/basic-settings/theme-and-home', 'User\BasicSettingController@themeAndHome')->name('user.basic_settings.theme_and_home');
            Route::post('/basic-settings/update-theme-and-home', 'User\BasicSettingController@updateThemeAndHome')->name('user.basic_settings.update_theme_and_home');

            // Background Image Settings
            Route::get('/basic-settings/background-image/theme', 'User\BackgroundImageController@index')->name('user.basic_settings.background_sections');
            Route::post('/basic-settings/background-image/theme/update', 'User\BackgroundImageController@update')->name('user.basic_settings.background_image');


            // basic settings (home) route
            Route::get('/basic-settings/home-sections', 'User\BasicSettingController@homeSections')->name('user.basic_settings.home_sections');
            Route::post('/basic-settings/update-home-sections', 'User\BasicSettingController@updateHomeSections')->name('user.basic_settings.update_home_sections');

            // basic settings appearance route
            Route::get('/basic-settings/appearance', 'User\BasicSettingController@appearance')->name('user.basic_settings.appearance');
            Route::post('/basic-settings/update-appearance', 'User\BasicSettingController@updateAppearance')->name('user.basic_settings.update_appearance');

            //background setting
            Route::get('/basic-settings/background-image', 'User\BackgroundSettingController@index')->name('user.basic_settings.background_index');
            Route::post('/basic-settings/update-background', 'User\BackgroundSettingController@update')->name('user.basic_settings.background_update');

            // basic settings breadcrumb route
            Route::get('/basic-settings/breadcrumb', 'User\BasicSettingController@breadcrumb')->name('user.basic_settings.breadcrumb');
            Route::post('/basic-settings/update-breadcrumb', 'User\BasicSettingController@updateBreadcrumb')->name('user.basic_settings.update_breadcrumb');

            // basic settings page-headings route
            Route::get('/basic-settings/page-headings', 'User\PageHeadingController@pageHeadings')->name('user.basic_settings.page_headings');
            Route::post('/basic-settings/update-page-headings', 'User\PageHeadingController@updatePageHeadings')->name('user.basic_settings.update_page_headings');

            // basic settings plugins route start
            Route::get('/basic-settings/plugins', 'User\BasicSettingController@plugins')->name('user.basic_settings.plugins');
            Route::post('/basic-settings/update-analytics', 'User\BasicSettingController@updateAnalytics')->name('user.basic_settings.update_analytics');
            Route::post('/basic-settings/update-whatsapp', 'User\BasicSettingController@updateWhatsApp')->name('user.basic_settings.update_whatsapp');
            Route::post('/basic-settings/update-disqus', 'User\BasicSettingController@updateDisqus')->name('user.basic_settings.update_disqus');
            Route::post('/update-pixel', 'User\BasicSettingController@updatePixel')->name('user.update_pixel');
            Route::post('/update-tawkto', 'User\BasicSettingController@updateTawkto')->name('user.update_tawkto');
            Route::post('/basic-settings/update-recaptcha', 'User\BasicSettingController@updateRecaptcha')->name('user.basic_settings.update_recaptcha');
            // basic settings plugins route end

            // basic settings seo route
            Route::get('/basic-settings/seo', 'User\SEOController@index')->name('user.basic_settings.seo');
            Route::post('/basic-settings/update-seo', 'User\SEOController@update')->name('user.basic_settings.update_seo');

            // basic settings cookie-alert route
            Route::get('/basic-settings/cookie-alert', 'User\CookieAlertController@cookieAlert')->name('user.basic_settings.cookie_alert');
            Route::post('/basic-settings/update-cookie-alert', 'User\CookieAlertController@updateCookieAlert')->name('user.basic_settings.update_cookie_alert');
        });

        Route::group(['middleware' => 'checkPackage'], function () {
            //menu builder routes
            Route::get('/menu-builder', 'User\MenuBuilderController@index')->name('user.menu_builder.index');
            Route::post('/menu-builder/update', 'User\MenuBuilderController@update')->name('user.menu_builder.update');

            // about me slider images section start
            Route::get('/about_me/slider-images', 'User\SliderImageController@index')->name('user.about_me.slider_images');
            Route::post('/about_me/store-slider-image', 'User\SliderImageController@store')->name('user.about_me.store_slider_image');
            Route::post('/about_me/update-slider-image', 'User\SliderImageController@update')->name('user.about_me.update_slider_image');
            Route::post('/about-me/delete-slider-image', 'User\SliderImageController@destroy')->name('user.about_me.delete_slider_image');
            // about me slider images section end
        });

        // about me social routes start
        Route::get('/about_me/social', 'User\SocialController@index')->name('user.about_me.social.index');
        Route::post('/about_me/social/store', 'User\SocialController@store')->name('user.about_me.social.store');
        Route::get('/about_me/social/{id}/edit', 'User\SocialController@edit')->name('user.about_me.social.edit');
        Route::post('/about_me/social/update', 'User\SocialController@update')->name('user.about_me.social.update');
        Route::post('/about_me/social/delete', 'User\SocialController@delete')->name('user.about_me.social.delete');
        // about me social routes end

        // about me (information) route start
        Route::get('/about-me/information', 'User\InformationController@index')->name('user.about_me.information');
        Route::post('about-me/update-information', 'User\InformationController@update')->name('user.about_me.update_information');
        //about me (information) route end

        // testimonials route start
        Route::get('/about-me/testimonials', 'User\TestimonialController@index')->name('user.about_me.testimonials');
        Route::get('/about-me/create-testimonial', 'User\TestimonialController@create')->name('user.about_me.create_testimonial');
        Route::post('/about-me/store-testimonial', 'User\TestimonialController@store')->name('user.about_me.store_testimonial');
        Route::get('/about-me/edit-testimonial/{id}', 'User\TestimonialController@edit')->name('user.about_me.edit_testimonial');
        Route::put('/about-me/update-testimonial/{id}', 'User\TestimonialController@update')->name('user.about_me.update_testimonial');
        Route::delete('/about-me/delete-testimonial/{id}', 'User\TestimonialController@destroy')->name('user.about_me.delete_testimonial');
        // testimonials route end

        //Partners route start
        Route::get('/about-me/partners', 'User\PartnerController@index')->name('user.about_me.partners');
        Route::post('/about-me/store-partner', 'User\PartnerController@store')->name('user.about_me.store_partner');
        Route::post('/about-me/brand_section/update_brand', 'User\PartnerController@update')->name('user.about_me.update_partner');
        Route::post('/about-me/brand_section/delete_brand', 'User\PartnerController@delete')->name('user.about_me.delete_partner');

        //FAQ route start
        Route::group(['middleware' => 'checkUserPermission:FAQ'], function () {
            Route::get('/faq_management', 'User\FAQController@index')->name('user.faq_management');
            Route::post('/faq_management/store_faq', 'User\FAQController@store')->name('user.faq_management.store_faq');
            Route::post('/faq_management/update_faq', 'User\FAQController@update')->name('user.faq_management.update_faq');
            Route::post('/faq_management/delete_faq', 'User\FAQController@delete')->name('user.faq_management.delete_faq');
            Route::post('/faq_management/bulk_delete_faq', 'User\FAQController@bulkDelete')->name('user.faq_management.bulk_delete_faq');
        });

        //user package extend route
        Route::get('/package-list', 'User\BuyPlanController@index')->name('user.plan.extend.index');
        Route::get('/package/checkout/{package_id}', 'User\BuyPlanController@checkout')->name('user.plan.extend.checkout');
        Route::post('/package/checkout', 'User\UserCheckoutController@checkout')->name('user.plan.checkout');

        //user footer route
        Route::group(['middleware' => 'checkPackage'], function () {
            Route::get('/footer/text', 'User\FooterController@footerText')->name('user.footer.text');
            Route::post('/footer/update_footer_info/{language}', 'User\FooterController@updateFooterInfo')->name('user.footer.update_footer_info');
            Route::get('/footer/quick_links', 'User\FooterController@quickLinks')->name('user.footer.quick_links');
            Route::post('/footer/store_quick_link/{language}', 'User\FooterController@storeQuickLink')->name('user.footer.store_quick_link');
            Route::post('/footer/update_quick_link', 'User\FooterController@updateQuickLink')->name('user.footer.update_quick_link');
            Route::post('/footer/delete_quick_link', 'User\FooterController@deleteQuickLink')->name('user.footer.delete_quick_link');

            //user subscriber routes
            Route::get('/subscribers', 'User\SubscriberController@index')->name('user.subscriber.index');
            Route::get('/mailsubscriber', 'User\SubscriberController@mailsubscriber')->name('user.mailsubscriber');
            Route::post('/subscribers/sendmail', 'User\SubscriberController@subscsendmail')->name('user.subscribers.sendmail');
            Route::post('/subscriber/delete', 'User\SubscriberController@delete')->name('user.subscriber.delete');
            Route::post('/subscriber/bulk-delete', 'User\SubscriberController@bulkDelete')->name('user.subscriber.bulk.delete');
            Route::get('/mail/information/subscriber', 'User\SubscriberController@getMailInformation')->name('user.mail.information');
            Route::post('/mail/information/subscriber', 'User\SubscriberController@storeMailInformation')->name('user.mail.subscriber');
        });


        // User vCard routes
        Route::group(['middleware' => 'checkUserPermission:vCard'], function () {
            Route::get('/vcard', 'User\VcardController@vcard')->name('user.vcard');
            Route::get('/vcard/create', 'User\VcardController@create')->name('user.vcard.create');
            Route::post('/vcard/store', 'User\VcardController@store')->name('user.vcard.store');
            Route::get('/vcard/{id}/edit', 'User\VcardController@edit')->name('user.vcard.edit');
            Route::post('/vcard/update', 'User\VcardController@update')->name('user.vcard.update');
            Route::post('/vcard/delete', 'User\VcardController@delete')->name('user.vcard.delete');
            Route::post('/vcard/bulk/delete', 'User\VcardController@bulkDelete')->name('user.vcard.bulk.delete');
            Route::get('/vcard/{id}/information', 'User\VcardController@information')->name('user.vcard.information');

            Route::get('/vcard/{id}/services', 'User\VcardController@services')->name('user.vcard.services');
            Route::post('/vcard/service/store', 'User\VcardController@serviceStore')->name('user.vcard.serviceStore');
            Route::post('/vcard/service/update', 'User\VcardController@serviceUpdate')->name('user.vcard.serviceUpdate');
            Route::post('/vcard/service/delete', 'User\VcardController@serviceDelete')->name('user.vcard.serviceDelete');
            Route::post('/vcard/bulk/service/delete', 'User\VcardController@bulkServiceDelete')->name('user.vcard.bulkServiceDelete');

            Route::get('/vcard/{id}/projects', 'User\VcardController@projects')->name('user.vcard.projects');
            Route::post('/vcard/project/store', 'User\VcardController@projectStore')->name('user.vcard.projectStore');
            Route::post('/vcard/project/update', 'User\VcardController@projectUpdate')->name('user.vcard.projectUpdate');
            Route::post('/vcard/project/delete', 'User\VcardController@projectDelete')->name('user.vcard.projectDelete');
            Route::post('/vcard/bulk/project/delete', 'User\VcardController@bulkProjectDelete')->name('user.vcard.bulkProjectDelete');

            Route::get('/vcard/{id}/testimonials', 'User\VcardController@testimonials')->name('user.vcard.testimonials');
            Route::post('/vcard/testimonial/store', 'User\VcardController@testimonialStore')->name('user.vcard.testimonialStore');
            Route::post('/vcard/testimonial/update', 'User\VcardController@testimonialUpdate')->name('user.vcard.testimonialUpdate');
            Route::post('/vcard/testimonial/delete', 'User\VcardController@testimonialDelete')->name('user.vcard.testimonialDelete');
            Route::post('/vcard/bulk/testimonial/delete', 'User\VcardController@bulkTestimonialDelete')->name('user.vcard.bulkTestimonialDelete');

            Route::get('/vcard/{id}/about', 'User\VcardController@about')->name('user.vcard.about');
            Route::post('/vcard/aboutUpdate', 'User\VcardController@aboutUpdate')->name('user.vcard.aboutUpdate');

            Route::get('/vcard/{id}/preferences', 'User\VcardController@preferences')->name('user.vcard.preferences');
            Route::post('/vcard/{id}/prefUpdate', 'User\VcardController@prefUpdate')->name('user.vcard.prefUpdate');

            Route::get('/vcard/{id}/color', 'User\VcardController@color')->name('user.vcard.color');
            Route::post('/vcard/{id}/colorUpdate', 'User\VcardController@colorUpdate')->name('user.vcard.colorUpdate');

            Route::get('/vcard/{id}/keywords', 'User\VcardController@keywords')->name('user.vcard.keywords');
            Route::post('/vcard/{id}/keywordsUpdate', 'User\VcardController@keywordsUpdate')->name('user.vcard.keywordsUpdate');
        });

        // user QR Builder
        Route::group(['middleware' => 'checkUserPermission:QR Builder'], function () {
            Route::get('/saved/qrs', 'User\QrController@index')->name('user.qrcode.index');
            Route::post('/saved/qr/delete', 'User\QrController@delete')->name('user.qrcode.delete');
            Route::post('/saved/qr/bulk-delete', 'User\QrController@bulkDelete')->name('user.qrcode.bulk.delete');
            Route::get('/qr-code', 'User\QrController@qrCode')->name('user.qrcode');
            Route::post('/qr-code/generate', 'User\QrController@generate')->name('user.qrcode.generate');
            Route::get('/qr-code/clear', 'User\QrController@clear')->name('user.qrcode.clear');
            Route::post('/qr-code/save', 'User\QrController@save')->name('user.qrcode.save');
        });

        // post route start
        Route::group(['middleware' => 'checkPackage'], function () {
            Route::get('/post-management/settings', 'User\BasicSettingController@postSettings')->name('user.post_management.settings');
            Route::post('/post-management/update-settings', 'User\BasicSettingController@updatePostSettings')->name('user.post_management.update_settings');
            Route::get('/post-management/categories', 'User\PostCategoryController@index')->name('user.post_management.categories');
            Route::post('/post-management/store-category', 'User\PostCategoryController@store')->name('user.post_management.store_category');
            Route::post('/post-management/update-featured/{id}', 'User\PostCategoryController@updateFeatured')->name('user.post_management.update_featured');
            Route::post('/post-management/update-category', 'User\PostCategoryController@update')->name('user.post_management.update_category');
            Route::post('/post-management/delete-category/{id}', 'User\PostCategoryController@destroy')->name('user.post_management.delete_category');
            Route::post('/post-management/bulk-delete-category', 'User\PostCategoryController@bulkDestroy')->name('user.post_management.bulk_delete_category');
            Route::get('/post-management/posts', 'User\PostController@index')->name('user.post_management.posts');
            Route::get('/post-management/create-post', 'User\PostController@create')->name('user.post_management.create_post');
            Route::post('/post-management/store-post', 'User\PostController@store')->name('user.post_management.store_post');
            Route::post('/post-management/update-slider-post', 'User\PostController@updateSliderPost')->name('user.post_management.update_slider_post');
            //update hero section posts
            Route::post('/post-management/update-hero-post', 'User\PostController@updateHeroPost')->name('user.post_management.update_hero_post');


            Route::post('/post-management/update-featured-post', 'User\PostController@updateFeaturedPost')->name('user.post_management.update_featured_post');
            Route::get('/post-management/edit-post/{id}', 'User\PostController@edit')->name('user.post_management.edit_post');
            Route::put('/post-management/update-post/{id}', 'User\PostController@update')->name('user.post_management.update_post');
            Route::delete('/post-management/delete-post/{id}', 'User\PostController@destroy')->name('user.post_management.delete_post');
            Route::post('/post-management/bulk-delete-post', 'User\PostController@bulkDestroy')->name('user.post_management.bulk_delete_post');
            Route::post('/post-management/slider', 'User\PostController@slider')->name('user.post_management.slider');
            Route::post('/post-management/slider/remove', 'User\PostController@sliderRemove')->name('user.post_management.slider-remove');
            Route::post('/post-management/db/slider/remove', 'User\PostController@dbSliderRemove')->name('user.post_management.db-slider-remove');
        });
        // post route end

        // gallery route start
        Route::group(['middleware' => 'checkUserPermission:Gallery'], function () {
            Route::get('/gallery-management/settings', 'User\BasicSettingController@gallerySettings')->name('user.gallery_management.settings');
            Route::post('/gallery-management/update-settings', 'User\BasicSettingController@updateGallerySettings')->name('user.gallery_management.update_settings');
            Route::get('/gallery-management/categories', 'User\GalleryCategoryController@index')->name('user.gallery_management.categories');
            Route::post('/gallery-management/store-category', 'User\GalleryCategoryController@store')->name('user.gallery_management.store_category');
            Route::post('/gallery-management/update-category', 'User\GalleryCategoryController@update')->name('user.gallery_management.update_category');
            Route::post('/gallery-management/delete-category/{id}', 'User\GalleryCategoryController@destroy')->name('user.gallery_management.delete_category');
            Route::post('/gallery-management/bulk-delete-category', 'User\GalleryCategoryController@bulkDestroy')->name('user.gallery_management.bulk_delete_category');
            Route::get('/gallery-management/get-categories/{id}', 'User\GalleryCategoryController@getCategories');
            Route::get('/gallery-management/gallery', 'User\GalleryItemController@index')->name('user.gallery_management.gallery');
            Route::post('/gallery-management/store-item', 'User\GalleryItemController@store')->name('user.gallery_management.store_item');
            Route::post('/gallery-management/update-featured-item/{id}', 'User\GalleryItemController@updateFeatured')->name('user.gallery_management.update_featured_item');
            Route::get('/gallery-management/edit-item/get-categories/{code}', 'User\GalleryItemController@getCategories');
            Route::post('/gallery-management/update-item', 'User\GalleryItemController@update')->name('user.gallery_management.update_item');
            Route::post('/gallery-management/delete-item/{id}', 'User\GalleryItemController@destroy')->name('user.gallery_management.delete_item');
            Route::post('/gallery-management/bulk-delete-item', 'User\GalleryItemController@bulkDestroy')->name('user.gallery_management.bulk_delete_item');
        });
        // gallery route end

        // advertisement route start
        Route::group(['middleware' => 'checkUserPermission:Advertisement'], function () {
            Route::get('/advertisements', 'User\AdvertisementController@index')->name('user.advertisements');
            Route::get('/advertisement/settings', 'User\AdvertisementController@settings')->name('user.advertisement.settings');
            Route::post('/advertisement/update/settings', 'User\AdvertisementController@updateSettings')->name('user.advertisement.update_settings');
            Route::post('/store-advertisement', 'User\AdvertisementController@store')->name('user.store_advertisement');
            Route::post('/update-advertisement', 'User\AdvertisementController@update')->name('user.update_advertisement');
            Route::post('/delete-advertisement/{id}', 'User\AdvertisementController@destroy')->name('user.delete_advertisement');
            Route::post('/bulk-delete-advertisement', 'User\AdvertisementController@bulkDestroy')->name('user.bulk_delete_advertisement');
        });
        // advertisement route end

        //custom pages route start
        Route::group(['middleware' => 'checkUserPermission:Custom Pages'], function () {
            Route::get('/custom-pages', 'User\CustomPageController@index')->name('user.custom_pages');
            Route::get('/custom-pages/create-page', 'User\CustomPageController@create')->name('user.custom_pages.create_page');
            Route::post('/custom-pages/store-page', 'User\CustomPageController@store')->name('user.custom_pages.store_page');
            Route::get('/custom-pages/edit-page/{id}', 'User\CustomPageController@edit')->name('user.custom_pages.edit_page');
            Route::put('/custom-pages/update-page/{id}', 'User\CustomPageController@update')->name('user.custom_pages.update_page');
            Route::delete('/custom-pages/delete-page/{id}', 'User\CustomPageController@destroy')->name('user.custom_pages.delete_page');
            Route::post('/custom-pages/bulk-delete-page', 'User\CustomPageController@bulkDestroy')->name('user.custom_pages.bulk_delete_page');
        });
        //custom pages route end

        // Announcement popups route start
        Route::group(['middleware' => 'checkPackage'], function () {
            Route::get('/announcement-popups', 'User\PopupController@index')->name('user.announcement_popups');
            Route::get('/announcement-popups/select-popup-type', 'User\PopupController@popupType')->name('user.announcement_popups.select_popup_type');
            Route::get('/announcement-popups/create-popup/{type}', 'User\PopupController@create')->name('user.announcement_popups.create_popup');
            Route::post('/announcement-popups/store-popup', 'User\PopupController@store')->name('user.announcement_popups.store_popup');
            Route::post('/announcement-popups/popup/{id}/update-status', 'User\PopupController@updateStatus')->name('user.announcement_popups.update_popup_status');
            Route::get('/announcement-popups/edit-popup/{id}', 'User\PopupController@edit')->name('user.announcement_popups.edit_popup');
            Route::put('/announcement-popups/update-popup/{id}', 'User\PopupController@update')->name('user.announcement_popups.update_popup');
            Route::delete('/announcement-popups/delete-popup/{id}', 'User\PopupController@destroy')->name('user.announcement_popups.delete_popup');
            Route::post('/announcement-popups/bulk-delete-popup', 'User\PopupController@bulkDestroy')->name('user.announcement_popups.bulk_delete_popup');
        });
        // Announcement popups route end


        // user management route start
        Route::middleware('checkPackage')->group(function () {
            Route::get('/registered-users', 'User\RegisteredUserController@index')->name('user.registered_users');

            Route::post('/user/{id}/update-account-status', 'User\RegisteredUserController@updateAccountStatus')->name('user.user.update_account_status');
            Route::post('register/users/email', 'User\RegisteredUserController@emailStatus')->name('user.email');
            Route::post('register/customer/secret-login', 'User\RegisteredUserController@customerSecretLogin')->name('customer.secretUserLogin');

            Route::get('/user/{id}/details', 'User\RegisteredUserController@show')->name('user.user_details');

            Route::get('/user/{id}/change-password', 'User\RegisteredUserController@changePassword')->name('user.user.change_password');

            Route::post('/user/{id}/update-password', 'User\RegisteredUserController@updatePassword')->name('user.user.update_password');

            Route::post('/user/{id}/delete', 'User\RegisteredUserController@destroy')->name('user.user.delete');

            Route::post('/bulk-delete-user', 'User\RegisteredUserController@bulkDestroy')->name('user.bulk_delete_user');
        });
        // user management route end
    });
});











// =============================================
Route::group(['prefix' => 'pentaforce'], function () {

    //dashboard
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/{crypt}', 'Pentaforce\DashboardApiController@getDashboardData');
        Route::get('test/{crypt}', 'Pentaforce\DashboardApiController@Test');
    });

    //profile
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/{crypt}', 'Pentaforce\ProfileApiController@getProfileData');
        Route::post('/update/{crypt}', 'Pentaforce\ProfileApiController@profileUpdate');
    });

    //MenuBuilder
    Route::group(['prefix' => 'menu-builder'], function () {
        Route::post('/show/{crypt}', 'Pentaforce\MenuBuilderApiController@MenuShow');
        Route::post('/menu-insert/{crypt}', 'Pentaforce\MenuBuilderApiController@MenuInsert');
    });

    //shop
    Route::group(['prefix' => 'shop'], function () {
        Route::get('/settings/{crypt}', 'Pentaforce\ShopApiController@settings');
        Route::post('/update-settings/{crypt}', 'Pentaforce\ShopApiController@updateSettings');

        // charge
        Route::get('/charge/{crypt}', 'Pentaforce\ShopApiController@charge');
        Route::post('/delete-charge/{crypt}', 'Pentaforce\ShopApiController@deleteCharge');
        Route::post('/add-charge/{crypt}', 'Pentaforce\ShopApiController@addCharge');
        Route::post('/update-charge/{crypt}', 'Pentaforce\ShopApiController@updateCharge');

        // coupon
        Route::get('/coupon/{crypt}', 'Pentaforce\ShopApiController@coupon');
        Route::post('/delete-coupon/{crypt}', 'Pentaforce\ShopApiController@deleteCoupon');
        Route::post('/add-coupon/{crypt}', 'Pentaforce\ShopApiController@addCoupon');
        Route::post('/update-coupon/{crypt}', 'Pentaforce\ShopApiController@updateCoupon');

        /*
        ===========================
        Manage Items
        ===========================
        */
        // Category
        Route::get('/item-category/{crypt}', 'Pentaforce\ShopApiController@itemCategory');
        Route::post('/item-category-add/{crypt}', 'Pentaforce\ShopApiController@itemCategoryAdd');
        Route::post('/item-category-update/{crypt}', 'Pentaforce\ShopApiController@itemCategoryUpdate');
        Route::post('/item-category-delete/{crypt}', 'Pentaforce\ShopApiController@itemCategoryDelete');
        Route::post('/item-category-feature/{crypt}', 'Pentaforce\ShopApiController@itemCategoryFeature');

        // Subcategory
        Route::get('/item-subcategory/{crypt}', 'Pentaforce\ShopApiController@itemSubcategory');
        Route::post('/item-subcategory-add/{crypt}', 'Pentaforce\ShopApiController@itemSubcategoryAdd');
        Route::post('/item-subcategory-update/{crypt}', 'Pentaforce\ShopApiController@itemSubcategoryUpdate');
        Route::post('/item-subcategory-delete/{crypt}', 'Pentaforce\ShopApiController@itemSubcategoryDelete');

        // Add Item - DIGITAL PRODUCT
        Route::get('/item-product/{crypt}', 'Pentaforce\ShopApiController@itemDigitalProduct');
        Route::post('/item-product-add/{crypt}', 'Pentaforce\ShopApiController@itemDigitalProductAdd');
        Route::get('/item-product-subCategroy/{id}', 'Pentaforce\ShopApiController@itemDigitalProductsubCategroy');
        Route::post('/item-product-update/{crypt}', 'Pentaforce\ShopApiController@itemDigitalProductUpdate');
        Route::get('/item-product-single-show', 'Pentaforce\ShopApiController@itemDigitalProductSingleShow');
        Route::post('/item-product-delete/{crypt}', 'Pentaforce\ShopApiController@itemDigitalProductDelete');


        /*
        ===========================
        ALL Orders
        ===========================
        */
        Route::post('/item-order-details/{id}', 'Pentaforce\ShopApiController@itemOrderDetails');
        Route::post('/item-order-status/{crypt}', 'Pentaforce\ShopApiController@itemOrderStatus');
        Route::post('/item-order-payment-status/{crypt}', 'Pentaforce\ShopApiController@itemOrderPaymentStatus');
        Route::post('/item-order-delete/{crypt}', 'Pentaforce\ShopApiController@itemOrderDelete');

        // all Orders
        Route::get('/item-order-all/{crypt}', 'Pentaforce\ShopApiController@itemOrderAll');

        // pending Orders
        Route::get('/item-order-pending/{crypt}', 'Pentaforce\ShopApiController@itemOrderPending');

        // processing Orders
        Route::get('/item-order-processing/{crypt}', 'Pentaforce\ShopApiController@itemOrderProcessing');

        // completed Orders
        Route::get('/item-order-completed/{crypt}', 'Pentaforce\ShopApiController@itemOrderCompleted');

        // rejected Orders
        Route::get('/item-order-rejected/{crypt}', 'Pentaforce\ShopApiController@itemOrderRejected');

        // report Orders
        Route::get('/item-order-report', 'Pentaforce\ShopApiController@itemOrderReport');
    });

    /*
    ===========================
    Basic Settings
    ===========================
    */
    Route::group(['prefix' => 'basic-settings'], function () {
        // theme
        Route::get('/theme-show/{crypt}', 'Pentaforce\BasicSettingsController@themeShow');
        Route::post('/theme-update/{crypt}', 'Pentaforce\BasicSettingsController@themeUpdate');

        // General Settings
        Route::get('/general-show/{crypt}', 'Pentaforce\BasicSettingsController@generalShow');
        Route::post('/general-update/{crypt}', 'Pentaforce\BasicSettingsController@generalUpdate');

        // Website Appearance
        Route::get('/appearance-show/{crypt}', 'Pentaforce\BasicSettingsController@appearanceShow');
        Route::post('/appearance-update/{crypt}', 'Pentaforce\BasicSettingsController@appearanceUpdate');

        // Home Sections
        Route::get('/home-sections/{crypt}', 'Pentaforce\BasicSettingsController@homeSectionsShow');
        Route::post('/home-sections-update/{crypt}', 'Pentaforce\BasicSettingsController@homeSectionsUpdate');

        // Page Headings
        Route::get('/page-sections/{crypt}', 'Pentaforce\BasicSettingsController@pageSectionsShow');
        Route::post('/page-sections-update/{crypt}', 'Pentaforce\BasicSettingsController@pageSectionsUpdate');

        // Update SEO Information
        Route::get('/seo-info/{crypt}', 'Pentaforce\BasicSettingsController@seoInfoShow');
        Route::post('/seo-info-update/{crypt}', 'Pentaforce\BasicSettingsController@seoInfoUpdate');

        // Plugins
        Route::get('/plugins/{crypt}', 'Pentaforce\BasicSettingsController@pluginsShow');
        Route::post('/plugins-update/{crypt}', 'Pentaforce\BasicSettingsController@pluginsUpdate');

        // footer
        Route::get('/footer/{crypt}', 'Pentaforce\BasicSettingsController@footerShow');

        // text
        Route::post('/footer-text-update/{crypt}', 'Pentaforce\BasicSettingsController@footerTextUpdateShow');

        // quick links
        Route::post('/footer-quick-add/{crypt}', 'Pentaforce\BasicSettingsController@footerQuickAddShow');
        Route::post('/footer-quick-update/{crypt}', 'Pentaforce\BasicSettingsController@footerQuickUpdateShow');
        Route::post('/footer-quick-delete/{crypt}', 'Pentaforce\BasicSettingsController@footerQuickDeleteShow');

    });

    // post
    Route::controller(\Pentaforce\PaymentGatewaysApiController::class)->prefix('payment')->group(function () {
        // Online Gateways
        Route::get('/online/{crypt}', 'onlineShow')->name('onlineShow');
        Route::post('/online-update/{crypt}', 'onlineUpdate')->name('onlineUpdate');
    });

});

// php artisan make:controller Pentaforce/PaymentGatewaysApiController
