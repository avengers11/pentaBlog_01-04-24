<div class="col-lg-3">
    @php
        $shopSettings = App\Models\User\UserShopSetting::where('user_id', $user->id)->first();
    @endphp
    <div class="user-sidebar">
        <ul class="links">
            <li @if (request()->routeIs('customer.dashboard')) class="active" @endif>
                <a
                    href="{{ route('customer.dashboard', getParam()) }}">{{ $keywords['Dashboard'] ?? __('Dashboard') }}</a>
            </li>

            <li @if (request()->routeIs('customer.edit_profile')) class="active" @endif>
                <a
                    href="{{ route('customer.edit_profile', getParam()) }}">{{ $keywords['Edit_Profile'] ?? __('Edit Profile') }}</a>
            </li>
            @if( isset($shopSettings) && $shopSettings->is_shop == 1)

            <li @if (request()->routeIs('customer.orders') || request()->routeIs('customer.orders-details')) class="active" @endif>
                <a href="{{ route('customer.orders', getParam()) }}">{{ $keywords['myOrders'] ?? __('My Orders') }}</a>
            </li>

            <li @if (request()->routeIs('customer.wishlist')) class="active" @endif>
                <a
                    href="{{ route('customer.wishlist', getParam()) }}">{{ $keywords['mywishlist'] ?? __('My Wishlist') }}</a>
            </li>
            @endif
            @if (isset($shopSettings) && $shopSettings->is_shop == 1)
                <li class="@if (request()->routeIs('customer.shpping-details')) active @endif">
                    <a href="{{ route('customer.shpping-details', getParam()) }}">
                        {{ $keywords['shipping_details'] ?? __('Shipping Details') }}</a>
                </li>
                <li class="@if (request()->routeIs('customer.billing-details')) active @endif">
                    <a href="{{ route('customer.billing-details', getParam()) }}">
                        {{ $keywords['billing_details'] ?? __('Billing Details') }}</a>
                </li>
            @endif

            <li @if (request()->routeIs('customer.my_bookmarks')) class="active" @endif>
                <a
                    href="{{ route('customer.my_bookmarks', getParam()) }}">{{ $keywords['My_Bookmarks'] ?? __('My Bookmarks') }}</a>
            </li>

            <li @if (request()->routeIs('customer.change_password')) class="active" @endif>
                <a
                    href="{{ route('customer.change_password', getParam()) }}">{{ $keywords['Change_Password'] ?? __('Change Password') }}</a>
            </li>

            <li>
                <a href="{{ route('customer.logout', getParam()) }}">{{ $keywords['Logout'] ?? __('Logout') }}</a>
            </li>
        </ul>
    </div>
</div>
