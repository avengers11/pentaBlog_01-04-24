@php
    $user = Auth::guard('web')->user();
    $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
    if (!empty($user)) {
        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
        $permissions = json_decode($permissions, true);
    }
@endphp
@if (Session::has('currentLangCode'))
    @php
        $default = \App\Models\User\Language::where('code', Session::get('currentLangCode'))
            ->where('user_id', $user->id)
            ->first();
    @endphp
@else
    @php
        $default = \App\Models\User\Language::where('is_default', 1)
            ->where('user_id', $user->id)
            ->first();
    @endphp
@endif
<div class="sidebar sidebar-style-2" @if (request()->cookie('user-theme') == 'dark') data-background-color="dark2" @endif>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    @if (!empty(Auth::user()->photo))
                        <img src="{{ Storage::url(Auth::user()->photo) }}" alt="..."
                            class="avatar-img rounded">
                    @else
                        <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..."
                            class="avatar-img rounded">
                    @endif
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
                            <span class="user-level">{{ auth()->user()->username }}</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>
                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            @if (!is_null($package))
                                <li>
                                    <a href="{{ route('user-profile-update') . '?language=' . $default->code }}">
                                        <span
                                            class="link-collapse">{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('user.changePass') . '?language=' . $default->code }}">
                                    <span
                                        class="link-collapse">{{ $keywords['Change_Password'] ?? __('Change Password') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user-logout') . '?language=' . $default->code }}">
                                    <span class="link-collapse">{{ $keywords['Logout'] ?? __('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-primary">
                <div class="row mb-2">
                    <div class="col-12">
                        <form action="">
                            <div class="form-group py-0">
                                <input name="term" type="text" class="form-control sidebar-search ltr"
                                    value=""
                                    placeholder="{{ $keywords['Search_Menu_Here'] ?? __('Search Menu Here...') }}">
                            </div>
                        </form>
                    </div>
                </div>
                <li class="nav-item
                @if (request()->path() == 'user/dashboard') active @endif">
                    <a href="{{ route('user-dashboard') . '?language=' . $default->code }}">
                        <i class="la flaticon-paint-palette"></i>
                        <p>{{ $keywords['Dashboard'] ?? __('Dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item
                @if (request()->path() == 'user/profile') active @endif">
                    <a href="{{ route('user-profile') . '?language=' . $default->code }}">
                        <i class="far fa-user-circle"></i>
                        <p>{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</p>
                    </a>
                </li>

                @if (!is_null($package))
                    {{-- Menu Builder --}}
                    <li class="nav-item
                @if (request()->path() == 'user/menu-builder') active @endif">
                        <a href="{{ route('user.menu_builder.index') . '?language=' . $default->code }}">
                            <i class="fas fa-bars"></i>
                            <p>{{ $keywords['Menu_Builder'] ?? __('Menu Builder') }}</p>
                        </a>
                    </li>
                    @if (!empty($permissions) && in_array('Ecommerce', $permissions))
                        {{-- START SHOP MANAGEMENT --}}
                        <li
                            class="nav-item
                            @if (request()->path() == 'user/category') active
                            @elseif(request()->path() == 'user/subcategory') active
                            @elseif(request()->is('user/subcategory/*/edit')) active
                            @elseif(request()->is('user/category/*/edit')) active
                            @elseif(request()->path() == 'user/item') active
                            @elseif(request()->routeIs('user.item.type')) active
                            @elseif(request()->is('user/item/*/edit')) active
                            @elseif(request()->path() == 'user/item/all/orders') active
                            @elseif(request()->path() == 'user/item/pending/orders') active
                            @elseif(request()->path() == 'user/item/processing/orders') active
                            @elseif(request()->path() == 'user/item/completed/orders') active
                            @elseif(request()->path() == 'user/item/rejected/orders') active
                            @elseif(request()->routeIs('user.item.variations')) active
                            @elseif(request()->routeIs('user.item.create')) active
                            @elseif(request()->routeIs('user.item.details')) active
                            @elseif(request()->path() == 'user/coupon') active
                            @elseif(request()->routeIs('user.coupon.edit')) active
                            @elseif(request()->path() == 'user/shipping') active
                            @elseif(request()->routeIs('user.shipping.edit')) active
                            @elseif(request()->routeIs('user.item.settings')) active
                            @elseif(request()->path() == 'user/item/orders/report') active @endif">
                            <a data-toggle="collapse" href="#category">
                                <i class="fas fa-store-alt"></i>
                                <p>{{ $keywords['Shop_Management'] ?? __('Shop Management') }}</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse
                            @if (request()->path() == 'user/category') show
                            @elseif(request()->path() == 'user/subcategory') show
                            @elseif(request()->is('user/subcategory/*/edit')) show
                            @elseif(request()->is('user/category/*/edit')) show
                            @elseif(request()->routeIs('user.item.type')) show
                            @elseif(request()->path() == 'user/item') show
                            @elseif(request()->is('user/item/*/edit')) show
                            @elseif(request()->path() == 'user/item/all/orders') show
                            @elseif(request()->path() == 'user/item/pending/orders') show
                            @elseif(request()->path() == 'user/item/processing/orders') show
                            @elseif(request()->path() == 'user/item/completed/orders') show
                            @elseif(request()->path() == 'user/item/rejected/orders') show
                            @elseif(request()->routeIs('user.item.create')) show
                            @elseif(request()->routeIs('user.item.details')) show
                            @elseif(request()->path() == 'user/coupon') show
                            @elseif(request()->routeIs('user.coupon.edit')) show
                            @elseif(request()->routeIs('user.item.variations')) show
                            @elseif(request()->path() == 'user/shipping') show
                            @elseif(request()->routeIs('user.shipping.edit')) show
                            @elseif(request()->routeIs('user.item.settings')) show
                            @elseif(request()->path() == 'user/item/orders/report') show @endif"
                                id="category">
                                <ul class="nav nav-collapse">
                                    <li class="@if (request()->routeIs('user.item.settings')) active @endif">
                                        <a href="{{ route('user.item.settings') . '?language=' . $default->code }}">
                                            <span class="sub-item">{{ $keywords['Settings'] ?? __('Settings') }}</span>
                                        </a>
                                    </li>
                                    <li
                                        class="
                                @if (request()->path() == 'user/shipping') active
                                @elseif(request()->routeIs('user.shipping.edit')) active @endif">
                                        <a href="{{ route('user.shipping.index') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Shipping_Charges'] ?? __('Shipping Charges') }}</span>
                                        </a>
                                    </li>
                                    <li
                                        class="
                            @if (request()->path() == 'user/coupon') active
                            @elseif(request()->routeIs('user.coupon.edit')) active @endif">
                                        <a href="{{ route('user.coupon.index') . '?language=' . $default->code }}">
                                            <span class="sub-item">{{ $keywords['Coupons'] ?? __('Coupons') }}</span>
                                        </a>
                                    </li>
                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#productManagement"
                                            aria-expanded="{{ request()->path() == 'user/category' || request()->path() == 'user/subcategory' || request()->is('user/category/*/edit') || request()->is('user/subcategory/*/edit') || request()->routeIs('user.item.type') || request()->routeIs('user.item.variations') || request()->routeIs('user.item.create') || request()->routeIs('user.item.index') || request()->routeIs('user.item.edit') ? 'true' : 'false' }}">
                                            <span
                                                class="sub-item">{{ $keywords['Manage_Items'] ?? __('Manage Items') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse
                                        @if (request()->path() == 'user/category') show
                                        @elseif(request()->is('user/category/*/edit')) show
                                        @elseif(request()->path() == 'user/subcategory') show
                                        @elseif(request()->is('user/subcategory/*/edit')) show
                                        @elseif(request()->routeIs('user.item.type')) show
                                        @elseif(request()->routeIs('user.item.variations')) show
                                        @elseif(request()->routeIs('user.item.create')) show
                                        @elseif(request()->routeIs('user.item.index')) show
                                        @elseif(request()->routeIs('user.item.edit')) show @endif"
                                            id="productManagement" style="">
                                            <ul class="nav nav-collapse subnav">
                                                <li
                                                    class="
                                                @if (request()->path() == 'user/category') active
                                                @elseif(request()->is('user/category/*/edit')) active @endif">
                                                    <a
                                                        href="{{ route('user.itemcategory.index') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['Category'] ?? __('Category') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="
                                                @if (request()->path() == 'user/subcategory') active
                                                @elseif(request()->is('user/subcategory/*/edit')) active @endif">
                                                    <a
                                                        href="{{ route('user.itemsubcategory.index') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['Subcategory'] ?? __('Subcategory') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="
                                                @if (request()->routeIs('user.item.type')) active
                                                @elseif(request()->routeIs('user.item.create')) active @endif">
                                                    <a
                                                        href="{{ route('user.item.type') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['Add_Item'] ?? __('Add Item') }}</span>
                                                    </a>
                                                </li>
                                                <li
                                                    class="
                                                @if (request()->path() == 'user/item') active
                                                @elseif(request()->is('user/item/*/edit')) active
                                                @elseif(request()->routeIs('user.item.variations')) active @endif">
                                                    <a
                                                        href="{{ route('user.item.index') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['Items'] ?? __('Items') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>


                                    <li class="submenu">
                                        <a data-toggle="collapse" href="#manageOrders"
                                            aria-expanded="{{ request()->routeIs('user.all.item.orders') || request()->routeIs('user.pending.item.orders') || request()->routeIs('user.processing.item.orders') || request()->routeIs('user.completed.item.orders') || request()->routeIs('user.rejected.item.orders') || request()->routeIs('user.item.details') || request()->path() == 'admin/product/orders/report' ? 'true' : 'false' }}">
                                            <span
                                                class="sub-item">{{ $keywords['Manage_Orders'] ?? __('Manage Orders') }}</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse
                            @if (request()->routeIs('user.all.item.orders')) show
                            @elseif(request()->routeIs('user.pending.item.orders')) show
                            @elseif(request()->routeIs('user.processing.item.orders')) show
                            @elseif(request()->routeIs('user.completed.item.orders')) show
                            @elseif(request()->routeIs('user.rejected.item.orders')) show
                            @elseif(request()->routeIs('user.item.details')) show
                            @elseif(request()->path() == 'user/item/orders/report') show @endif"
                                            id="manageOrders" style="">
                                            <ul class="nav nav-collapse subnav">
                                                <li class="@if (request()->path() == 'user/item/all/orders') active @endif">
                                                    <a
                                                        href="{{ route('user.all.item.orders') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['All_Orders'] ?? __('All Orders') }}</span>
                                                    </a>
                                                </li>
                                                <li class="@if (request()->path() == 'user/item/pending/orders') active @endif">
                                                    <a
                                                        href="{{ route('user.pending.item.orders') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['Pending_Orders'] ?? __('Pending Orders') }}</span>
                                                    </a>
                                                </li>
                                                <li class="@if (request()->path() == 'user/item/processing/orders') active @endif">
                                                    <a
                                                        href="{{ route('user.processing.item.orders') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['Processing_Orders'] ?? __('Processing Orders') }}</span>
                                                    </a>
                                                </li>
                                                <li class="@if (request()->path() == 'user/item/completed/orders') active @endif">
                                                    <a
                                                        href="{{ route('user.completed.item.orders') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['Completed_Orders'] ?? __('Completed Orders') }}</span>
                                                    </a>
                                                </li>
                                                <li class="@if (request()->path() == 'user/item/rejected/orders') active @endif">
                                                    <a
                                                        href="{{ route('user.rejected.item.orders') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['Rejected_Orders'] ?? __('Rejected Orders') }}</span>
                                                    </a>
                                                </li>
                                                <li class="@if (request()->path() == 'user/item/orders/report') active @endif">
                                                    <a
                                                        href="{{ route('user.orders.report') . '?language=' . $default->code }}">
                                                        <span
                                                            class="sub-item">{{ $keywords['Report'] ?? __('Report') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        {{-- END SHOP MANAGEMENT --}}
                    @endif
                    <li
                        class="nav-item @if (request()->routeIs('user.basic_settings.favicon')) active
            @elseif (request()->routeIs('user.basic_settings.logo')) active
            @elseif (request()->routeIs('user.basic_settings.preloader')) active
            @elseif (request()->routeIs('user.basic_settings.preferences')) active
            @elseif (request()->routeIs('user.basic_settings.information')) active
            @elseif (request()->routeIs('user.basic_settings.theme_and_home')) active
            @elseif (request()->routeIs('user.basic_settings.appearance')) active
            @elseif (request()->routeIs('user.basic_settings.breadcrumb')) active
            @elseif (request()->routeIs('user.basic_settings.page_headings')) active
            @elseif (request()->routeIs('user.basic_settings.plugins')) active
            @elseif (request()->routeIs('user.basic_settings.seo')) active
            @elseif (request()->routeIs('user.basic_settings.mail_templates')) active
            @elseif (request()->routeIs('user.basic_settings.edit_mail_template')) active
            @elseif (request()->routeIs('user.basic_settings.maintenance_mode')) active
            @elseif (request()->routeIs('user.basic_settings.home_sections')) active
            @elseif (request()->routeIs('user.basic_settings.cookie_alert')) active
            @elseif (request()->path() == 'user/mail/information/subscriber')) active
            @elseif (request()->routeIs('user.basic_settings.background_sections')) active @endif">
                        <a data-toggle="collapse" href="#basic_settings">
                            <i class="la flaticon-settings"></i>
                            <p>{{ $keywords['Basic_Settings'] ?? __('Basic Settings') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="basic_settings"
                            class="collapse
              @if (request()->routeIs('user.basic_settings.favicon')) show
              @elseif (request()->routeIs('user.basic_settings.logo')) show
              @elseif (request()->routeIs('user.basic_settings.preloader')) show
              @elseif (request()->routeIs('user.basic_settings.preferences')) show
              @elseif (request()->routeIs('user.basic_settings.information')) show
              @elseif (request()->routeIs('user.basic_settings.theme_and_home')) show
              @elseif (request()->routeIs('user.basic_settings.currency')) show
              @elseif (request()->routeIs('user.basic_settings.appearance')) show
              @elseif (request()->routeIs('user.basic_settings.breadcrumb')) show
              @elseif (request()->routeIs('user.basic_settings.page_headings')) show
              @elseif (request()->routeIs('user.basic_settings.mail_templates')) show
              @elseif (request()->routeIs('user.basic_settings.edit_mail_template')) show
              @elseif (request()->routeIs('user.basic_settings.plugins')) show
              @elseif (request()->routeIs('user.basic_settings.seo')) show
              @elseif (request()->routeIs('user.basic_settings.home_sections')) show
              @elseif (request()->routeIs('user.basic_settings.cookie_alert')) show
              @elseif (request()->path() == 'user/mail/information/subscriber') show
              @elseif (request()->routeIs('user.basic_settings.background_sections')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('user.basic_settings.preferences') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.preferences') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Preferences'] ?? __('Preferences') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('user.basic_settings.theme_and_home') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.theme_and_home') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Theme_and_Home'] ?? __('Theme & Home') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('user.basic_settings.information') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.information') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['General_Settings'] ?? __('General Settings') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('user.basic_settings.logo') ? 'active' : '' }}">
                                    <a href="{{ route('user.basic_settings.logo') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Logo'] ?? __('Logo') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('user.basic_settings.favicon') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.favicon') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Favicon'] ?? __('Favicon') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('user.basic_settings.preloader') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.preloader') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Preloader'] ?? __('Preloader') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('user.basic_settings.breadcrumb') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.breadcrumb') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Breadcrumb'] ?? __('Breadcrumb') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('user.basic_settings.appearance') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.appearance') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Website_Appearance'] ?? __('Website Appearance') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('user.basic_settings.home_sections') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.home_sections') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Home_Sections'] ?? __('Home Sections') }}</span>
                                    </a>
                                </li>

                                @if ($userBs->theme_version == 5)
                                    <li
                                        class="{{ request()->routeIs('user.basic_settings.background_sections') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('user.basic_settings.background_sections') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Background_Image_Sections'] ?? __('Background Image Sections') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li
                                    class="{{ request()->routeIs('user.basic_settings.page_headings') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.page_headings', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Page_Headings'] ?? __('Page Headings') }}</span>
                                    </a>
                                </li>
                                @php
                                    $plugins = ['Google Analytics', 'Google Recaptcha', 'Disqus', 'WhatsApp', 'Facebook Pixel', 'Tawk.to'];
                                @endphp
                                @if (!empty($permissions) && array_intersect($plugins, $permissions))
                                    <li
                                        class="{{ request()->routeIs('user.basic_settings.plugins') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('user.basic_settings.plugins') . '?language=' . $default->code }}">
                                            <span class="sub-item">{{ $keywords['Plugins'] ?? __('Plugins') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="{{ request()->routeIs('user.basic_settings.seo') ? 'active' : '' }}">
                                    <a href="{{ route('user.basic_settings.seo', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['SEO_Informations'] ?? __('SEO Informations') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ request()->routeIs('user.basic_settings.cookie_alert') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.basic_settings.cookie_alert', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Cookie_Alert'] ?? __('Cookie Alert') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'user/mail/information/subscriber') active @endif">
                                    <a href="{{ route('user.mail.information') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Mail_Information'] ?? __('Mail Information') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="
                                            @if (request()->routeIs('user.basic_settings.mail_templates')) active
                                            @elseif (request()->routeIs('user.basic_settings.edit_mail_template')) active @endif">
                                    <a
                                        href="{{ route('user.basic_settings.mail_templates', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Mail_Templates'] ?? __('Mail Templates') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    @if (!empty($permissions) && in_array('Ecommerce', $permissions))
                        {{-- Start Payment getway --}}
                        <li
                            class="nav-item  @if (request()->path() == 'user/gateways') active   @elseif(request()->path() == 'user/offline/gateways') active @endif">
                            <a data-toggle="collapse" href="#gateways">
                                <i class="la flaticon-paypal"></i>
                                <p>{{ $keywords['Payment_Gateways'] ?? __('Payment Gateways') }}</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse  @if (request()->path() == 'user/gateways') show   @elseif(request()->path() == 'user/offline/gateways') show @endif"
                                id="gateways">
                                <ul class="nav nav-collapse">
                                    <li class="@if (request()->path() == 'user/gateways') active @endif">
                                        <a href="{{ route('user.gateway.index') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Online_Gateways'] ?? __('Online Gateways') }}</span>
                                        </a>
                                    </li>
                                    <li class="@if (request()->path() == 'user/offline/gateways') active @endif">
                                        <a href="{{ route('user.gateway.offline') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Offline_Gateways'] ?? __('Offline Gateways') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        {{-- End Payment getway --}}
                    @endif
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/domains') active
                    @elseif(request()->path() == 'user/subdomain') active @endif">
                        <a data-toggle="collapse" href="#domains">
                            <i class="fas fa-link"></i>
                            <p>{{ $keywords['Domains_and_URLs'] ?? __('Domains & URLs') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'user/domains') show
                        @elseif(request()->path() == 'user/subdomain') show @endif"
                            id="domains">
                            <ul class="nav nav-collapse">
                                @if (!empty($permissions) && in_array('Custom Domain', $permissions))
                                    <li
                                        class="
                                    @if (request()->path() == 'user/domains') active @endif">
                                        <a href="{{ route('user-domains') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Custom_Domain'] ?? __('Custom Domain') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (!empty($permissions) && in_array('Subdomain', $permissions))
                                    <li
                                        class="
                                    @if (request()->path() == 'user/subdomain') active @endif">
                                        <a href="{{ route('user-subdomain') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Subdomain_and_Path_URL'] ?? __('Subdomain & Path URL') }}</span>
                                        </a>
                                    </li>
                                @else
                                    <li
                                        class="
                                    @if (request()->path() == 'user/subdomain') active @endif">
                                        <a href="{{ route('user-subdomain') . '?language=' . $default->code }}">
                                            <span
                                                class="sub-item">{{ $keywords['Path_Based_URL'] ?? __('Path Based URL') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if (!is_null($package))
                    <li
                        class="nav-item @if (request()->routeIs('user.post_management.settings')) active
                @elseif (request()->routeIs('user.post_management.categories')) active
                @elseif (request()->routeIs('user.post_management.posts')) active
                @elseif (request()->routeIs('user.post_management.create_post')) active
                @elseif (request()->routeIs('user.post_management.edit_post')) active @endif">
                        <a data-toggle="collapse" href="#post">
                            <i class="la flaticon-chat-4"></i>
                            <p>{{ $keywords['Post_Management'] ?? __('Post Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="post"
                            class="collapse
                @if (request()->routeIs('user.post_management.settings')) show
                @elseif (request()->routeIs('user.post_management.categories')) show
                @elseif (request()->routeIs('user.post_management.posts')) show
                @elseif (request()->routeIs('user.post_management.create_post')) show
                @elseif (request()->routeIs('user.post_management.edit_post')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('user.post_management.settings') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.post_management.settings') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Settings'] ?? __('Settings') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="{{ request()->routeIs('user.post_management.categories') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.post_management.categories', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Categories'] ?? __('Categories') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="@if (request()->routeIs('user.post_management.posts')) active
                    @elseif (request()->routeIs('user.post_management.create_post')) active
                    @elseif (request()->routeIs('user.post_management.edit_post')) active @endif">
                                    <a
                                        href="{{ route('user.post_management.posts') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Posts'] ?? __('Posts') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if (!is_null($package))
                    <li
                        class="nav-item @if (request()->routeIs('user.about_me.slider_images')) active
                    @elseif (request()->routeIs('user.about_me.information')) active
                    @elseif (request()->routeIs('user.about_me.social.index')) active
                    @elseif (request()->routeIs('user.about_me.social.edit')) active
                    @elseif (request()->routeIs('user.about_me.testimonials')) active
                    @elseif (request()->routeIs('user.about_me.create_testimonial')) active
                    @elseif (request()->routeIs('user.about_me.edit_testimonial')) active
                    @elseif (request()->routeIs('user.about_me.partners')) active @endif">
                        <a data-toggle="collapse" href="#about_me">
                            <i class="far fa-address-card"></i>
                            <p>{{ $keywords['About_Me'] ?? __('About Me') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="about_me"
                            class="collapse
                    @if (request()->routeIs('user.about_me.slider_images')) show
                    @elseif (request()->routeIs('user.about_me.information')) show
                    @elseif (request()->routeIs('user.about_me.social.index')) show
                    @elseif (request()->routeIs('user.about_me.social.edit')) show
                    @elseif (request()->routeIs('user.about_me.testimonials')) show
                    @elseif (request()->routeIs('user.about_me.create_testimonial')) show
                    @elseif (request()->routeIs('user.about_me.edit_testimonial')) show
                    @elseif (request()->routeIs('user.about_me.partners')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('user.about_me.slider_images') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.about_me.slider_images') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Slider_Images'] ?? __('Slider Images') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('user.about_me.information') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.about_me.information', ['language' => $default->code]) }}">
                                        <span
                                            class="sub-item">{{ $keywords['Information'] ?? __('Information') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="@if (request()->path() == 'user/about_me/social') active
                                    @elseif(request()->is('user/about_me/social/*')) active @endif">
                                    <a
                                        href="{{ route('user.about_me.social.index') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Social_Links'] ?? __('Social Links') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="@if (request()->routeIs('user.about_me.testimonials')) active
                                @elseif (request()->routeIs('user.about_me.create_testimonial')) active
                                @elseif (request()->routeIs('user.about_me.edit_testimonial')) active @endif">
                                    <a
                                        href="{{ route('user.about_me.testimonials') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Testimonials'] ?? __('Testimonials') }}</span>
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('user.about_me.partners') ? 'active' : '' }}">
                                    <a href="{{ route('user.about_me.partners', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Partners'] ?? __('Partners') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- gallery --}}
                @if (!empty($permissions) && in_array('Gallery', $permissions))
                    <li
                        class="nav-item @if (request()->routeIs('user.gallery_management.settings')) active
            @elseif (request()->routeIs('user.gallery_management.categories')) active
            @elseif (request()->routeIs('user.gallery_management.gallery')) active @endif">
                        <a data-toggle="collapse" href="#gallery">
                            <i class="la flaticon-picture"></i>
                            <p>{{ $keywords['Gallery_Management'] ?? __('Gallery Management') }}</p>
                            <span class="caret"></span>
                        </a>

                        <div id="gallery"
                            class="collapse
              @if (request()->routeIs('user.gallery_management.settings')) show
              @elseif (request()->routeIs('user.gallery_management.categories')) show
              @elseif (request()->routeIs('user.gallery_management.gallery')) show @endif">
                            <ul class="nav nav-collapse">
                                <li
                                    class="{{ request()->routeIs('user.gallery_management.settings') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.gallery_management.settings') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Settings'] ?? __('Settings') }}</span>
                                    </a>
                                </li>

                                @if ($userBs->gallery_category_status == 1)
                                    <li
                                        class="{{ request()->routeIs('user.gallery_management.categories') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('user.gallery_management.categories', ['language' => $default->code]) }}">
                                            <span
                                                class="sub-item">{{ $keywords['Categories'] ?? __('Categories') }}</span>
                                        </a>
                                    </li>
                                @endif

                                <li
                                    class="{{ request()->routeIs('user.gallery_management.gallery') ? 'active' : '' }}">
                                    <a
                                        href="{{ route('user.gallery_management.gallery', ['language' => $default->code]) }}">
                                        <span class="sub-item">{{ $keywords['Gallery'] ?? __('Gallery') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif




                {{-- Start of FAQ Management --}}
                @if (!empty($permissions) && in_array('FAQ', $permissions))
                    <li class="nav-item
                    @if (request()->path() == 'user/faq_management') active @endif">
                        <a href="{{ route('user.faq_management', ['language' => $default->code]) }}">
                            <i class="far fa-question-circle"></i>
                            <p>{{ $keywords['FAQ_Management'] ?? __('FAQ Management') }}</p>
                        </a>
                    </li>
                @endif
                {{-- End of FAQ Management --}}


                @if (!empty($permissions) && in_array('Custom Pages', $permissions))
                    <li
                        class="nav-item @if (request()->routeIs('user.custom_pages')) active
                    @elseif (request()->routeIs('user.custom_pages.create_page')) active
                    @elseif (request()->routeIs('user.custom_pages.edit_page')) active @endif">
                        <a data-toggle="collapse" href="#pages">
                            <i class="la flaticon-file"></i>
                            <p>{{ $keywords['Custom_Pages'] ?? __('Custom Pages') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                    @if (request()->routeIs('user.custom_pages')) show
                    @elseif (request()->routeIs('user.custom_pages.create_page')) show
                    @elseif (request()->routeIs('user.custom_pages.edit_page')) show @endif"
                            id="pages">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('user.custom_pages.create_page')) active @endif">
                                    <a
                                        href="{{ route('user.custom_pages.create_page') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Create_Pages'] ?? __('Create Page') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                            @if (request()->routeIs('user.custom_pages')) active
                            @elseif (request()->routeIs('user.custom_pages.edit_page')) active @endif">
                                    <a href="{{ route('user.custom_pages') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Pages'] ?? __('Pages') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Advertisements --}}
                @if (!empty($permissions) && in_array('Advertisement', $permissions))
                    <li
                        class="nav-item
                    @if (request()->routeIs('user.advertisements')) active
                    @elseif (request()->routeIs('user.advertisement.settings')) active @endif">
                        <a data-toggle="collapse" href="#ads">
                            <i class="la flaticon-file"></i>
                            <p>{{ $keywords['Advertisements'] ?? __('Advertisements') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                    @if (request()->routeIs('user.advertisements')) show
                    @elseif (request()->routeIs('user.advertisement.settings')) show @endif"
                            id="ads">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('user.advertisement.settings')) active @endif">
                                    <a
                                        href="{{ route('user.advertisement.settings') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Settings'] ?? __('Settings') }}</span>
                                    </a>
                                </li>
                                <li class="
                    @if (request()->routeIs('user.advertisements')) active @endif">
                                    <a href="{{ route('user.advertisements') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Advertisements'] ?? __('Advertisements') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (!is_null($package))
                    {{-- footer --}}
                    <li
                        class="nav-item
                        @if (request()->routeIs('user.footer.text')) active
                        @elseif (request()->routeIs('user.footer.quick_links')) active @endif">
                        <a data-toggle="collapse" href="#footer">
                            <i class="far fa-shoe-prints"></i>
                            <p>{{ $keywords['Footer'] ?? __('Footer') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div id="footer"
                            class="collapse
                            @if (request()->routeIs('user.footer.text')) show
                            @elseif (request()->routeIs('user.footer.quick_links')) show @endif">
                            <ul class="nav nav-collapse">
                                <li class="{{ request()->routeIs('user.footer.text') ? 'active' : '' }}">
                                    <a href="{{ route('user.footer.text') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Footer_Text'] ?? __('Footer Text') }}</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('user.footer.quick_links') ? 'active' : '' }}">
                                    <a href="{{ route('user.footer.quick_links') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Quick_Links'] ?? __('Quick Links') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (!is_null($package))
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/registered-users') active
                    @elseif(request()->routeIs('user.user_details')) active
                    @elseif(request()->routeIs('user.user.change_password')) active @endif">
                        <a href="{{ route('user.registered_users') . '?language=' . $default->code }}">
                            <i class="la flaticon-paint-palette"></i>
                            <p>{{ $keywords['Registered_Users'] ?? __('Registered Users') }}</p>
                        </a>
                    </li>

                    {{-- Subscribers --}}
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/subscribers') active
                    @elseif(request()->path() == 'user/mailsubscriber') active @endif">
                        <a data-toggle="collapse" href="#subscribers">
                            <i class="la flaticon-envelope"></i>
                            <p>{{ $keywords['Subscribers'] ?? __('Subscribers') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'user/subscribers') show
                        @elseif(request()->path() == 'user/mailsubscriber') show @endif"
                            id="subscribers">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'user/subscribers') active @endif">
                                    <a href="{{ route('user.subscriber.index') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Subscribers'] ?? __('Subscribers') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'user/mailsubscriber') active @endif">
                                    <a href="{{ route('user.mailsubscriber') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Mail_to_Subscribers'] ?? __('Mail to Subscribers') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (!is_null($package))
                    {{-- Start of Language Management --}}
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/languages') active
                    @elseif(request()->is('user/language/*/edit')) active
                    @elseif(request()->is('user/language/*/edit/keyword')) active @endif">
                        <a href="{{ route('user.language.index') . '?language=' . $default->code }}">
                            <i class="fas fa-language"></i>
                            <p>{{ $keywords['Language_Management'] ?? __('Language Management') }}</p>
                        </a>
                    </li>
                    {{-- End of Language Management --}}
                @endif


                @if (!is_null($package))
                    <li
                        class="nav-item
                    @if (request()->routeIs('user.announcement_popups')) active
                    @elseif (request()->routeIs('user.announcement_popups.select_popup_type')) active
                    @elseif (request()->routeIs('user.announcement_popups.create_popup')) active
                    @elseif (request()->routeIs('user.announcement_popups.edit_popup')) active @endif">
                        <a data-toggle="collapse" href="#announcementPopup">
                            <i class="fas fa-bullhorn"></i>
                            <p>{{ $keywords['Announcement_Popup'] ?? __('Announcement Popup') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                    @if (request()->routeIs('user.announcement_popups')) show
                    @elseif (request()->routeIs('user.announcement_popups.select_popup_type')) show
                    @elseif (request()->routeIs('user.announcement_popups.create_popup')) show
                    @elseif (request()->routeIs('user.announcement_popups.edit_popup')) show @endif"
                            id="announcementPopup">
                            <ul class="nav nav-collapse">
                                <li
                                    class="@if (request()->routeIs('user.announcement_popups.select_popup_type')) active
                    @elseif (request()->routeIs('user.announcement_popups.create_popup')) active @endif">
                                    <a
                                        href="{{ route('user.announcement_popups.select_popup_type') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Add_Popup'] ?? __('Add Popup') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="@if (request()->routeIs('user.announcement_popups')) active
                    @elseif (request()->routeIs('user.announcement_popups.edit_popup')) active @endif">
                                    <a
                                        href="{{ route('user.announcement_popups') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Popups'] ?? __('Popups') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (!empty($permissions) && in_array('vCard', $permissions))
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/vcard') active
                    @elseif(request()->path() == 'user/vcard/create') active
                    @elseif(request()->is('user/vcard/*/edit')) active
                    @elseif(request()->routeIs('user.vcard.services')) active
                    @elseif(request()->routeIs('user.vcard.projects')) active
                    @elseif(request()->routeIs('user.vcard.testimonials')) active
                    @elseif(request()->routeIs('user.vcard.about')) active
                    @elseif(request()->routeIs('user.vcard.preferences')) active
                    @elseif(request()->routeIs('user.vcard.color')) active
                    @elseif(request()->routeIs('user.vcard.keywords')) active @endif">
                        <a data-toggle="collapse" href="#vcard">
                            <i class="far fa-address-card"></i>
                            <p>{{ $keywords['vCards_Management'] ?? __('vCards Management') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->path() == 'user/vcard') show
                        @elseif(request()->path() == 'user/vcard/create') show
                        @elseif(request()->is('user/vcard/*/edit')) show
                        @elseif(request()->routeIs('user.vcard.services')) show
                        @elseif(request()->routeIs('user.vcard.projects')) show
                        @elseif(request()->routeIs('user.vcard.testimonials')) show
                        @elseif(request()->routeIs('user.vcard.about')) show
                        @elseif(request()->routeIs('user.vcard.preferences')) show
                        @elseif(request()->routeIs('user.vcard.color')) show
                        @elseif(request()->routeIs('user.vcard.keywords')) show @endif"
                            id="vcard">
                            <ul class="nav nav-collapse">
                                <li
                                    class="@if (request()->path() == 'user/vcard') active
                            @elseif(request()->is('user/vcard/*/edit')) active
                            @elseif(request()->routeIs('user.vcard.services')) active
                            @elseif(request()->routeIs('user.vcard.projects')) active
                            @elseif(request()->routeIs('user.vcard.testimonials')) active
                            @elseif(request()->routeIs('user.vcard.about')) active
                            @elseif(request()->routeIs('user.vcard.preferences')) active
                            @elseif(request()->routeIs('user.vcard.color')) active
                            @elseif(request()->routeIs('user.vcard.keywords')) active @endif">
                                    <a href="{{ route('user.vcard') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['vCards'] ?? __('vCards') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->path() == 'user/vcard/create') active @endif">
                                    <a href="{{ route('user.vcard.create') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Add_vCard'] ?? __('Add vCard') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (!empty($permissions) && in_array('QR Builder', $permissions))
                    <li
                        class="nav-item
                    @if (request()->routeIs('user.qrcode')) active
                    @elseif(request()->routeIs('user.qrcode.index')) active @endif">
                        <a data-toggle="collapse" href="#qrcode">
                            <i class="fas fa-qrcode"></i>
                            <p>{{ $keywords['QR_Codes'] ?? __('QR Codes') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                        @if (request()->routeIs('user.qrcode')) show
                        @elseif(request()->routeIs('user.qrcode.index')) show @endif"
                            id="qrcode">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->routeIs('user.qrcode')) active @endif">
                                    <a href="{{ route('user.qrcode') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Generate_QR_Code'] ?? __('Generate QR Code') }}</span>
                                    </a>
                                </li>
                                <li class="@if (request()->routeIs('user.qrcode.index')) active @endif">
                                    <a href="{{ route('user.qrcode.index') . '?language=' . $default->code }}">
                                        <span
                                            class="sub-item">{{ $keywords['Saved_QR_Codes'] ?? __('Saved QR Codes') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if (!is_null($package))
                    {{-- Start of Following List --}}
                    <li
                        class="nav-item
                    @if (request()->path() == 'user/follower-list') active
                    @elseif(request()->path() == 'user/following-list') active @endif">
                        <a data-toggle="collapse" href="#follow">
                            <i class="fas fa-user-friends"></i>
                            <p>{{ $keywords['Follower_Following'] ?? __('Follower/Following') }}</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse
                            @if (request()->path() == 'user/follower-list') show
                            @elseif(request()->path() == 'user/following-list') show @endif"
                            id="follow">
                            <ul class="nav nav-collapse">
                                <li class="@if (request()->path() == 'user/follower-list') active @endif">
                                    <a href="{{ route('user.follower.list') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Follower'] ?? __('Follower') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="
                                    @if (request()->path() == 'user/following-list') active
                                    @elseif(request()->is('user/following-list')) active @endif">
                                    <a href="{{ route('user.following.list') . '?language=' . $default->code }}">
                                        <span class="sub-item">{{ $keywords['Following'] ?? __('Following') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{-- End of Following List --}}
                @endif
                <li
                    class="nav-item
                    @if (request()->path() == 'user/package-list') active
                    @elseif(request()->is('user/package/checkout/*')) active @endif">
                    <a href="{{ route('user.plan.extend.index') . '?language=' . $default->code }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>{{ $keywords['Buy_Plan'] ?? __('Buy Plan') }}</p>
                    </a>
                </li>
                <li class="nav-item
                    @if (request()->path() == 'user/payment-log') active @endif">
                    <a href="{{ route('user.payment-log.index') . '?language=' . $default->code }}">
                        <i class="fas fa-list-ol"></i>
                        <p>{{ $keywords['Payment_Logs'] ?? __('Payment Logs') }}</p>
                    </a>
                </li>
                <li class="nav-item
                    @if (request()->path() == 'user/change-password') active @endif">
                    <a href="{{ route('user.changePass') . '?language=' . $default->code }}">
                        <i class="fas fa-key"></i>
                        <p>{{ $keywords['Change_Password'] ?? __('Change Password') }}</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
