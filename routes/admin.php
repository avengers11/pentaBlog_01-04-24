<?php
    $domain = env('WEBSITE_HOST');

    if (!app()->runningInConsole()) {
        if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
            $domain = 'www.' . env('WEBSITE_HOST');
        }
    }

    /*=======================================================
    ******************** Admin Routes **********************
    =======================================================*/
    Route::get('/set-locale-admin', 'Admin\BasicController@setLocaleAdmin')->name('set-Locale-admin');
    Route::domain($domain)->group(function() {
        Route::group(['prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
            Route::get('/', 'Admin\LoginController@login')->name('admin.login');
            Route::post('/login', 'Admin\LoginController@authenticate')->name('admin.auth');
    
            Route::get('/mail-form', 'Admin\ForgetController@mailForm')->name('admin.forget.form');
            Route::post('/sendmail', 'Admin\ForgetController@sendmail')->name('admin.forget.mail');
        });
    
    
        Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'checkstatus','adminLang']], function () {
            // RTL check
            Route::get('/rtlcheck/{langid}', 'Admin\LanguageController@rtlcheck')->name('admin.rtlcheck');
            // admin redirect to dashboard route
            Route::get('/change-theme', 'Admin\DashboardController@changeTheme')->name('admin.theme.change');
            // Summernote image upload
            Route::post('/summernote/upload', 'Admin\SummernoteController@upload')->name('admin.summernote.upload');
            // Admin logout Route
            Route::get('/logout', 'Admin\LoginController@logout')->name('admin.logout');
            // Admin Dashboard Routes
            Route::group(['middleware' => 'checkpermission:Dashboard'], function () {
                Route::get('/dashboard', 'Admin\DashboardController@dashboard')->name('admin.dashboard');
            });
            // Admin Profile Routes
            Route::get('/changePassword', 'Admin\ProfileController@changePass')->name('admin.changePass');
            Route::post('/profile/updatePassword', 'Admin\ProfileController@updatePassword')->name('admin.updatePassword');
            Route::get('/profile/edit', 'Admin\ProfileController@editProfile')->name('admin.editProfile');
            Route::post('/profile/update', 'Admin\ProfileController@updateProfile')->name('admin.updateProfile');
    
            Route::group(['middleware' => 'checkpermission:Settings'], function () {
                // Admin Favicon Routes
                Route::get('/favicon', 'Admin\BasicController@favicon')->name('admin.favicon');
                Route::post('/favicon/post', 'Admin\BasicController@updatefav')->name('admin.favicon.update');
                // Admin Logo Routes
                Route::get('/logo', 'Admin\BasicController@logo')->name('admin.logo');
                Route::post('/logo/post', 'Admin\BasicController@updatelogo')->name('admin.logo.update');
                // Admin Preloader Routes
                Route::get('/preloader', 'Admin\BasicController@preloader')->name('admin.preloader');
                Route::post('/preloader/post', 'Admin\BasicController@updatepreloader')->name('admin.preloader.update');
                // Admin Basic Information Routes
                Route::get('/basicinfo', 'Admin\BasicController@basicinfo')->name('admin.basicinfo');
                Route::post('/basicinfo/post', 'Admin\BasicController@updatebasicinfo')->name('admin.basicinfo.update');
                // Admin Email Settings Routes
                Route::get('/mail-from-admin', 'Admin\EmailController@mailFromAdmin')->name('admin.mailFromAdmin');
                Route::post('/mail-from-admin/update', 'Admin\EmailController@updateMailFromAdmin')->name('admin.mailfromadmin.update');
                Route::get('/mail-to-admin', 'Admin\EmailController@mailToAdmin')->name('admin.mailToAdmin');
                Route::post('/mail-to-admin/update', 'Admin\EmailController@updateMailToAdmin')->name('admin.mailtoadmin.update');
                Route::get('/mail_templates', 'Admin\MailTemplateController@mailTemplates')->name('admin.mail_templates');
                Route::get('/edit_mail_template/{id}', 'Admin\MailTemplateController@editMailTemplate')->name('admin.edit_mail_template');
                Route::post('/update_mail_template/{id}', 'Admin\MailTemplateController@updateMailTemplate')->name('admin.update_mail_template');
                // Admin Breadcrumb Routes
                Route::get('/breadcrumb', 'Admin\BasicController@breadcrumb')->name('admin.breadcrumb');
                Route::post('/breadcrumb/update', 'Admin\BasicController@updatebreadcrumb')->name('admin.breadcrumb.update');
                // Admin Scripts Routes
                Route::get('/script', 'Admin\BasicController@script')->name('admin.script');
                Route::post('/script/update', 'Admin\BasicController@updatescript')->name('admin.script.update');
                // Admin Social Routes
                Route::get('/social', 'Admin\SocialController@index')->name('admin.social.index');
                Route::post('/social/store', 'Admin\SocialController@store')->name('admin.social.store');
                Route::get('/social/{id}/edit', 'Admin\SocialController@edit')->name('admin.social.edit');
                Route::post('/social/update', 'Admin\SocialController@update')->name('admin.social.update');
                Route::post('/social/delete', 'Admin\SocialController@delete')->name('admin.social.delete');
                // Admin Maintanance Mode Routes
                Route::get('/maintainance', 'Admin\BasicController@maintainance')->name('admin.maintainance');
                Route::post('/maintainance/update', 'Admin\BasicController@updatemaintainance')->name('admin.maintainance.update');
                // Admin Section Customization Routes
                Route::get('/sections', 'Admin\BasicController@sections')->name('admin.sections.index');
                Route::post('/sections/update', 'Admin\BasicController@updatesections')->name('admin.sections.update');
                // Admin Cookie Alert Routes
                Route::get('/cookie-alert', 'Admin\BasicController@cookiealert')->name('admin.cookie.alert');
                Route::post('/cookie-alert/{langid}/update', 'Admin\BasicController@updatecookie')->name('admin.cookie.update');
                // basic settings seo route
                Route::get('/seo', 'Admin\BasicController@seo')->name('admin.seo');
                Route::post('/seo/update', 'Admin\BasicController@updateSEO')->name('admin.seo.update');
            });
            // Admin Subscriber Routes
            Route::group(['middleware' => 'checkpermission:Subscribers'], function () {
                Route::get('/subscribers', 'Admin\SubscriberController@index')->name('admin.subscriber.index');
                Route::get('/mailsubscriber', 'Admin\SubscriberController@mailsubscriber')->name('admin.mailsubscriber');
                Route::post('/subscribers/sendmail', 'Admin\SubscriberController@subscsendmail')->name('admin.subscribers.sendmail');
                Route::post('/subscriber/delete', 'Admin\SubscriberController@delete')->name('admin.subscriber.delete');
                Route::post('/subscriber/bulk-delete', 'Admin\SubscriberController@bulkDelete')->name('admin.subscriber.bulk.delete');
            });
            // Admin Menu Builder Routes
            Route::group(['middleware' => 'checkpermission:Menu Builder'], function () {
                Route::get('/menu-builder', 'Admin\MenuBuilderController@index')->name('admin.menu_builder.index');
                Route::post('/menu-builder/update', 'Admin\MenuBuilderController@update')->name('admin.menu_builder.update');
            });
            // Admin Home Page Routes
            Route::group(['middleware' => 'checkpermission:Home Page'], function () {
                // Admin Hero Section Image & Text Routes
                Route::get('/herosection/imgtext', 'Admin\HerosectionController@imgtext')->name('admin.herosection.imgtext');
                Route::post('/herosection/{langid}/update', 'Admin\HerosectionController@update')->name('admin.herosection.update');
    
                // Admin Feature Routes
                Route::get('/features', 'Admin\FeatureController@index')->name('admin.feature.index');
                Route::post('/feature/store', 'Admin\FeatureController@store')->name('admin.feature.store');
                Route::get('/feature/{id}/edit', 'Admin\FeatureController@edit')->name('admin.feature.edit');
                Route::post('/feature/update', 'Admin\FeatureController@update')->name('admin.feature.update');
                Route::post('/feature/delete', 'Admin\FeatureController@delete')->name('admin.feature.delete');
    
                // Admin Work Process Routes
                Route::get('/process', 'Admin\ProcessController@index')->name('admin.process.index');
                Route::post('/process/store', 'Admin\ProcessController@store')->name('admin.process.store');
                Route::get('/process/{id}/edit', 'Admin\ProcessController@edit')->name('admin.process.edit');
                Route::post('/process/update', 'Admin\ProcessController@update')->name('admin.process.update');
                Route::post('/process/delete', 'Admin\ProcessController@delete')->name('admin.process.delete');
    
                // Admin Intro Section Routes
                Route::get('/introsection', 'Admin\IntrosectionController@index')->name('admin.introsection.index');
                Route::post('/introsection/{langid}/update', 'Admin\IntrosectionController@update')->name('admin.introsection.update');
                Route::post('/introsection/remove/image', 'Admin\IntrosectionController@removeImage')->name('admin.introsection.img.rmv');
    
                // Admin Testimonial Routes
                Route::get('/testimonials', 'Admin\TestimonialController@index')->name('admin.testimonial.index');
                Route::get('/testimonial/create', 'Admin\TestimonialController@create')->name('admin.testimonial.create');
                Route::post('/testimonial/store', 'Admin\TestimonialController@store')->name('admin.testimonial.store');
                Route::get('/testimonial/{id}/edit', 'Admin\TestimonialController@edit')->name('admin.testimonial.edit');
                Route::post('/testimonial/update', 'Admin\TestimonialController@update')->name('admin.testimonial.update');
                Route::post('/testimonial/delete', 'Admin\TestimonialController@delete')->name('admin.testimonial.delete');
                Route::post('/testimonialtext/{langid}/update', 'Admin\TestimonialController@textupdate')->name('admin.testimonialtext.update');
    
                // Admin home page text routes
                Route::get('/home-page-text-section', 'Admin\HomePageTextController@index')->name('admin.home.page.text.index');
                Route::post('/home-page-text-section/{langid}/update', 'Admin\HomePageTextController@update')->name('admin.home.page.text.update');
    
                // Admin Partner Routes
                Route::get('/partners', 'Admin\PartnerController@index')->name('admin.partner.index');
                Route::post('/partner/store', 'Admin\PartnerController@store')->name('admin.partner.store');
                Route::post('/partner/upload', 'Admin\PartnerController@upload')->name('admin.partner.upload');
                Route::get('/partner/{id}/edit', 'Admin\PartnerController@edit')->name('admin.partner.edit');
                Route::post('/partner/update', 'Admin\PartnerController@update')->name('admin.partner.update');
                Route::post('/partner/{id}/uploadUpdate', 'Admin\PartnerController@uploadUpdate')->name('admin.partner.uploadUpdate');
                Route::post('/partner/delete', 'Admin\PartnerController@delete')->name('admin.partner.delete');
    
            });
            // Admin Pages Routes
            Route::group(['middleware' => 'checkpermission:Pages'], function () {
                Route::get('/pages', 'Admin\PageController@index')->name('admin.page.index');
                Route::get('/page/create', 'Admin\PageController@create')->name('admin.page.create');
                Route::post('/page/store', 'Admin\PageController@store')->name('admin.page.store');
                Route::get('/page/{menuID}/edit', 'Admin\PageController@edit')->name('admin.page.edit');
                Route::post('/page/update', 'Admin\PageController@update')->name('admin.page.update');
                Route::post('/page/delete', 'Admin\PageController@delete')->name('admin.page.delete');
                Route::post('/page/bulk-delete', 'Admin\PageController@bulkDelete')->name('admin.page.bulk.delete');
            });
            // Admin Footer Routes
            Route::group(['middleware' => 'checkpermission:Footer'], function () {
                // Admin Footer Logo Text Routes
                Route::get('/footers', 'Admin\FooterController@index')->name('admin.footer.index');
                Route::post('/footer/{langid}/update', 'Admin\FooterController@update')->name('admin.footer.update');
                Route::post('/footer/remove/image', 'Admin\FooterController@removeImage')->name('admin.footer.rmvimg');
    
                // Admin Ulink Routes
                Route::get('/ulinks', 'Admin\UlinkController@index')->name('admin.ulink.index');
                Route::get('/ulink/create', 'Admin\UlinkController@create')->name('admin.ulink.create');
                Route::post('/ulink/store', 'Admin\UlinkController@store')->name('admin.ulink.store');
                Route::get('/ulink/{id}/edit', 'Admin\UlinkController@edit')->name('admin.ulink.edit');
                Route::post('/ulink/update', 'Admin\UlinkController@update')->name('admin.ulink.update');
                Route::post('/ulink/delete', 'Admin\UlinkController@delete')->name('admin.ulink.delete');
            });
    
            // Announcement Popup Routes
            Route::group(['middleware' => 'checkpermission:Announcement Popup'], function () {
                Route::get('popups', 'Admin\PopupController@index')->name('admin.popup.index');
                Route::get('popup/types', 'Admin\PopupController@types')->name('admin.popup.types');
                Route::get('popup/{id}/edit', 'Admin\PopupController@edit')->name('admin.popup.edit');
                Route::get('popup/create', 'Admin\PopupController@create')->name('admin.popup.create');
                Route::post('popup/store', 'Admin\PopupController@store')->name('admin.popup.store');
                Route::post('popup/delete', 'Admin\PopupController@delete')->name('admin.popup.delete');
                Route::post('popup/bulk-delete', 'Admin\PopupController@bulkDelete')->name('admin.popup.bulk.delete');
                Route::post('popup/status', 'Admin\PopupController@status')->name('admin.popup.status');
                Route::post('popup/update', 'Admin\PopupController@update')->name('admin.popup.update');
            });
            //Admin Register User Routes
            Route::group(['middleware' => 'checkpermission:Registered Users'], function () {
                Route::get('register/users', 'Admin\RegisterUserController@index')->name('admin.register.user');
                Route::post('register/user/store', 'Admin\RegisterUserController@store')->name('register.user.store');
                Route::post('register/users/ban', 'Admin\RegisterUserController@userban')->name('register.user.ban');
                Route::post('register/users/featured', 'Admin\RegisterUserController@userFeatured')->name('register.user.featured');
                Route::post('register/users/template', 'Admin\RegisterUserController@userTemplate')->name('register.user.template');
                Route::post('register/users/template/update', 'Admin\RegisterUserController@userUpdateTemplate')->name('register.user.updateTemplate');
                Route::post('register/users/email', 'Admin\RegisterUserController@emailStatus')->name('register.user.email');
                Route::get('register/user/details/{id}', 'Admin\RegisterUserController@view')->name('register.user.view');
                Route::post('register/admin/secret-login', 'Admin\RegisterUserController@secretUserLogin')->name('user.secretUserLogin');
                Route::post('register/user/delete', 'Admin\RegisterUserController@delete')->name('register.user.delete');
                Route::post('register/user/bulk-delete', 'Admin\RegisterUserController@bulkDelete')->name('register.user.bulk.delete');
                Route::get('register/user/{id}/changePassword', 'Admin\RegisterUserController@changePass')->name('register.user.changePass');
                Route::post('register/user/updatePassword', 'Admin\RegisterUserController@updatePassword')->name('register.user.updatePassword');
            });
            // Admin FAQ Routes
            Route::group(['middleware' => 'checkpermission:FAQ Management'], function () {
                Route::get('/faqs', 'Admin\FaqController@index')->name('admin.faq.index');
                Route::get('/faq/create', 'Admin\FaqController@create')->name('admin.faq.create');
                Route::post('/faq/store', 'Admin\FaqController@store')->name('admin.faq.store');
                Route::post('/faq/update', 'Admin\FaqController@update')->name('admin.faq.update');
                Route::post('/faq/delete', 'Admin\FaqController@delete')->name('admin.faq.delete');
                Route::post('/faq/bulk-delete', 'Admin\FaqController@bulkDelete')->name('admin.faq.bulk.delete');
            });
            // Admin Blog Routes
            Route::group(['middleware' => 'checkpermission:Blogs'], function () {
                // Admin Blog Category Routes
                Route::get('/bcategorys', 'Admin\BcategoryController@index')->name('admin.bcategory.index');
                Route::post('/bcategory/store', 'Admin\BcategoryController@store')->name('admin.bcategory.store');
                Route::post('/bcategory/update', 'Admin\BcategoryController@update')->name('admin.bcategory.update');
                Route::post('/bcategory/delete', 'Admin\BcategoryController@delete')->name('admin.bcategory.delete');
                Route::post('/bcategory/bulk-delete', 'Admin\BcategoryController@bulkDelete')->name('admin.bcategory.bulk.delete');
    
                // Admin Blog Routes
                Route::get('/blogs', 'Admin\BlogController@index')->name('admin.blog.index');
                Route::post('/blog/upload', 'Admin\BlogController@upload')->name('admin.blog.upload');
                Route::post('/blog/store', 'Admin\BlogController@store')->name('admin.blog.store');
                Route::get('/blog/{id}/edit', 'Admin\BlogController@edit')->name('admin.blog.edit');
                Route::post('/blog/update', 'Admin\BlogController@update')->name('admin.blog.update');
                Route::post('/blog/{id}/uploadUpdate', 'Admin\BlogController@uploadUpdate')->name('admin.blog.uploadUpdate');
                Route::post('/blog/delete', 'Admin\BlogController@delete')->name('admin.blog.delete');
                Route::post('/blog/bulk-delete', 'Admin\BlogController@bulkDelete')->name('admin.blog.bulk.delete');
                Route::get('/blog/{langid}/getcats', 'Admin\BlogController@getcats')->name('admin.blog.getcats');
            });
            // Admin Sitemap Routes
            Route::group(['middleware' => 'checkpermission:Sitemap'], function () {
                Route::get('/sitemap', 'Admin\SitemapController@index')->name('admin.sitemap.index');
                Route::post('/sitemap/store', 'Admin\SitemapController@store')->name('admin.sitemap.store');
                Route::get('/sitemap/{id}/update', 'Admin\SitemapController@update')->name('admin.sitemap.update');
                Route::post('/sitemap/{id}/delete', 'Admin\SitemapController@delete')->name('admin.sitemap.delete');
                Route::post('/sitemap/download', 'Admin\SitemapController@download')->name('admin.sitemap.download');
            });
            // Admin Contact Routes
            Route::group(['middleware' => 'checkpermission:Contact Page'], function () {
                Route::get('/contact', 'Admin\ContactController@index')->name('admin.contact.index');
                Route::post('/contact/{langid}/post', 'Admin\ContactController@update')->name('admin.contact.update');
            });
            // Admin Gateways Routes
            Route::group(['middleware' => 'checkpermission:Payment Gateways'], function () {
                // Admin Online Gateways Routes
                Route::get('/gateways', 'Admin\GatewayController@index')->name('admin.gateway.index');
                Route::post('/stripe/update', 'Admin\GatewayController@stripeUpdate')->name('admin.stripe.update');
                Route::post('/anet/update', 'Admin\GatewayController@anetUpdate')->name('admin.anet.update');
                Route::post('/paypal/update', 'Admin\GatewayController@paypalUpdate')->name('admin.paypal.update');
                Route::post('/paystack/update', 'Admin\GatewayController@paystackUpdate')->name('admin.paystack.update');
                Route::post('/paytm/update', 'Admin\GatewayController@paytmUpdate')->name('admin.paytm.update');
                Route::post('/flutterwave/update', 'Admin\GatewayController@flutterwaveUpdate')->name('admin.flutterwave.update');
                Route::post('/instamojo/update', 'Admin\GatewayController@instamojoUpdate')->name('admin.instamojo.update');
                Route::post('/mollie/update', 'Admin\GatewayController@mollieUpdate')->name('admin.mollie.update');
                Route::post('/razorpay/update', 'Admin\GatewayController@razorpayUpdate')->name('admin.razorpay.update');
                Route::post('/mercadopago/update', 'Admin\GatewayController@mercadopagoUpdate')->name('admin.mercadopago.update');
    
                // Admin Offline Gateway Routes
                Route::get('/offline/gateways', 'Admin\GatewayController@offline')->name('admin.gateway.offline');
                Route::post('/offline/gateway/store', 'Admin\GatewayController@store')->name('admin.gateway.offline.store');
                Route::post('/offline/gateway/update', 'Admin\GatewayController@update')->name('admin.gateway.offline.update');
                Route::post('/offline/status', 'Admin\GatewayController@status')->name('admin.offline.status');
                Route::post('/offline/gateway/delete', 'Admin\GatewayController@delete')->name('admin.offline.gateway.delete');
            });
            // Admin Roles Routes
            Route::group(['middleware' => 'checkpermission:Role Management'], function () {
                Route::get('/roles', 'Admin\RoleController@index')->name('admin.role.index');
                Route::post('/role/store', 'Admin\RoleController@store')->name('admin.role.store');
                Route::post('/role/update', 'Admin\RoleController@update')->name('admin.role.update');
                Route::post('/role/delete', 'Admin\RoleController@delete')->name('admin.role.delete');
                Route::get('role/{id}/permissions/manage', 'Admin\RoleController@managePermissions')->name('admin.role.permissions.manage');
                Route::post('role/permissions/update', 'Admin\RoleController@updatePermissions')->name('admin.role.permissions.update');
            });
            // Admin Users Routes
            Route::group(['middleware' => 'checkpermission:Admins Management'], function () {
                Route::get('/users', 'Admin\UserController@index')->name('admin.user.index');
                Route::post('/user/upload', 'Admin\UserController@upload')->name('admin.user.upload');
                Route::post('/user/store', 'Admin\UserController@store')->name('admin.user.store');
                Route::get('/user/{id}/edit', 'Admin\UserController@edit')->name('admin.user.edit');
                Route::post('/user/update', 'Admin\UserController@update')->name('admin.user.update');
                Route::post('/user/{id}/uploadUpdate', 'Admin\UserController@uploadUpdate')->name('admin.user.uploadUpdate');
                Route::post('/user/delete', 'Admin\UserController@delete')->name('admin.user.delete');
            });
            // Admin Language Routes
            Route::group(['middleware' => 'checkpermission:Language Management'], function () {
                Route::get('/languages', 'Admin\LanguageController@index')->name('admin.language.index');
                Route::get('/language/{id}/edit', 'Admin\LanguageController@edit')->name('admin.language.edit');
                Route::get('/language/{id}/edit/keyword', 'Admin\LanguageController@editKeyword')->name('admin.language.editKeyword');
                Route::get('/language/{id}/edit/admin-keyword', 'Admin\LanguageController@editAdminKeyword')->name('admin.language.editAdminKeyword');
                Route::post('/language/store', 'Admin\LanguageController@store')->name('admin.language.store');
                Route::post('/language/upload', 'Admin\LanguageController@upload')->name('admin.language.upload');
                Route::post('/language/{id}/uploadUpdate', 'Admin\LanguageController@uploadUpdate')->name('admin.language.uploadUpdate');
                Route::post('/language/{id}/default', 'Admin\LanguageController@default')->name('admin.language.default');
                Route::post('/language/{id}/delete', 'Admin\LanguageController@delete')->name('admin.language.delete');
                Route::post('/language/update', 'Admin\LanguageController@update')->name('admin.language.update');
                Route::post('/language/{id}/update/keyword', 'Admin\LanguageController@updateKeyword')->name('admin.language.updateKeyword');
            });
    
            // Admin Cache Clear Routes
            Route::get('/cache-clear', 'Admin\CacheController@clear')->name('admin.cache.clear');
            // Admin Packages Routes
            Route::group(['middleware' => 'checkpermission:Packages'], function () {
                // Package Settings routes
                Route::get('/package/settings', 'Admin\PackageController@settings')->name('admin.package.settings');
                Route::post('/package/settings', 'Admin\PackageController@updateSettings')->name('admin.package.settings');
                // Package Settings routes
                Route::get('/package/features', 'Admin\PackageController@features')->name('admin.package.features');
                Route::post('/package/features', 'Admin\PackageController@updateFeatures')->name('admin.package.features');
                // Package routes
                Route::get('packages', 'Admin\PackageController@index')->name('admin.package.index');
                Route::post('package/upload', 'Admin\PackageController@upload')->name('admin.package.upload');
                Route::post('package/store', 'Admin\PackageController@store')->name('admin.package.store');
                Route::get('package/{id}/edit', 'Admin\PackageController@edit')->name('admin.package.edit');
                Route::post('package/update', 'Admin\PackageController@update')->name('admin.package.update');
                Route::post('package/{id}/uploadUpdate', 'Admin\PackageController@uploadUpdate')->name('admin.package.uploadUpdate');
                Route::post('package/delete', 'Admin\PackageController@delete')->name('admin.package.delete');
                Route::post('package/bulk-delete', 'Admin\PackageController@bulkDelete')->name('admin.package.bulk.delete');
            });
            // Admin Payment Log Routes
            Route::group(['middleware' => 'checkpermission:Payment Log'], function () {
                Route::get('/payment-log', 'Admin\PaymentLogController@index')->name('admin.payment-log.index');
                Route::post('/payment-log/update', 'Admin\PaymentLogController@update')->name('admin.payment-log.update');
            });
            // Admin Custom Domains Routes
            Route::group(['middleware' => 'checkpermission:Custom Domain'], function () {
                Route::get('/domains', 'Admin\CustomDomainController@index')->name('admin.custom-domain.index');
                Route::get('/domain/texts', 'Admin\CustomDomainController@texts')->name('admin.custom-domain.texts');
                Route::post('/domain/texts', 'Admin\CustomDomainController@updateTexts')->name('admin.custom-domain.texts');
                Route::post('/domain/status', 'Admin\CustomDomainController@status')->name('admin.custom-domain.status');
                Route::post('/domain/mail', 'Admin\CustomDomainController@mail')->name('admin.custom-domain.mail');
                Route::post('/domain/delete', 'Admin\CustomDomainController@delete')->name('admin.custom-domain.delete');
                Route::post('/domain/bulk-delete', 'Admin\CustomDomainController@bulkDelete')->name('admin.custom-domain.bulk.delete');
            });
            // Admin Subdomains Routes
            Route::group(['middleware' => 'checkpermission:Subdomain'], function () {
                Route::get('/subdomains', 'Admin\SubdomainController@index')->name('admin.subdomain.index');
                Route::post('/subdomain/status', 'Admin\SubdomainController@status')->name('admin.subdomain.status');
                Route::post('/subdomain/mail', 'Admin\SubdomainController@mail')->name('admin.subdomain.mail');
            });
        });
    });
?>