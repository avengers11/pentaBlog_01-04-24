@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Section_Customization'] ?? __('Section Customization') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user-dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Home_Page'] ?? __('Home Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Section_Customization'] ?? __('Section Customization') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title">{{ $keywords['Customize_Sections'] ?? __('Customize Sections') }}</div>
                        </div>
                    </div>
                </div>
                <form id="ajaxForm" action="{{ route('user.basic_settings.update_home_sections') }}" method="post">
                    @csrf
                    <div class="card-body pt-5 pb-5">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                @csrf

                                @if ($websiteInfo->theme_version != 7)
                                    <div class="form-group">
                                        <label>{{ $keywords['Slider_Posts'] ?? __('Slider Posts') }} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="slider_posts" value="1"
                                                    class="selectgroup-input"
                                                    {{ isset($hs) && $hs->slider_posts == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="slider_posts" value="0"
                                                    class="selectgroup-input"
                                                    {{ !isset($hs) || $hs->slider_posts == 0 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                @if ($websiteInfo->theme_version == 7)
                                    <div class="form-group">
                                        <label>{{ $keywords['hero_section_posts'] ?? __('Hero Section Posts') }} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="hero_section_posts" value="1"
                                                    class="selectgroup-input"
                                                    {{ isset($hs) && $hs->hero_section_posts == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="hero_section_posts" value="0"
                                                    class="selectgroup-input"
                                                    {{ !isset($hs) || $hs->hero_section_posts == 0 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                @if ($websiteInfo->theme_version != 3 && $websiteInfo->theme_version != 4)
                                    <div class="form-group">
                                        <label>{{ $keywords['Post_Categories'] ?? __('Post Categories') }} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="post_categories" value="1"
                                                    class="selectgroup-input"
                                                    {{ isset($hs) && $hs->post_categories == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="post_categories" value="0"
                                                    class="selectgroup-input"
                                                    {{ !isset($hs) || $hs->post_categories == 0 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                @if ($websiteInfo->theme_version != 3)
                                    <div class="form-group">
                                        <label>{{ $keywords['Featured_Posts'] ?? __('Featured Posts') }} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="featured_posts" value="1"
                                                    class="selectgroup-input"
                                                    {{ isset($hs) && $hs->featured_posts == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="featured_posts" value="0"
                                                    class="selectgroup-input"
                                                    {{ !isset($hs) || $hs->featured_posts == 0 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>{{ $keywords['Latest_Posts'] ?? __('Latest Posts') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="latest_posts" value="1"
                                                class="selectgroup-input"
                                                {{ isset($hs) && $hs->latest_posts == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="latest_posts" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($hs) || $hs->latest_posts == 0 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                @if ($websiteInfo->theme_version == 1|| $websiteInfo->theme_version == 2 || $websiteInfo->theme_version == 3 ||  $websiteInfo->theme_version == 5 || $websiteInfo->theme_version == 6)
                                    <div class="form-group">
                                        <label>{{ $keywords['Author_Info'] ?? __('Author Info') }} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="author_info" value="1"
                                                    class="selectgroup-input"
                                                    {{ isset($hs) && $hs->author_info == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="author_info" value="0"
                                                    class="selectgroup-input"
                                                    {{ !isset($hs) || $hs->author_info == 0 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ $keywords['Popular_Posts'] ?? __('Popular Posts') }} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="popular_posts" value="1"
                                                    class="selectgroup-input"
                                                    {{ isset($hs) && $hs->popular_posts == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="popular_posts" value="0"
                                                    class="selectgroup-input"
                                                    {{ !isset($hs) || $hs->popular_posts == 0 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label>{{ $keywords['Newsletter'] ?? __('Newsletter') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="newsletter" value="1"
                                                class="selectgroup-input"
                                                {{ isset($hs) && $hs->newsletter == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="newsletter" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($hs) || $hs->newsletter == 0 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                @if ($websiteInfo->theme_version != 4)
                                    <div class="form-group">
                                        <label>{{ $keywords['Sidebar_Ads'] ?? __('Sidebar Ads') }} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="sidebar_ads" value="1"
                                                    class="selectgroup-input"
                                                    {{ isset($hs) && $hs->sidebar_ads == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="sidebar_ads" value="0"
                                                    class="selectgroup-input"
                                                    {{ !isset($hs) || $hs->sidebar_ads == 0 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                @php
                                    $user = Auth::guard('web')->user();
                                    if (!empty($user)) {
                                        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
                                        $permissions = json_decode($permissions, true);
                                    }
                                @endphp
                                @if (!empty($permissions) && in_array('Gallery', $permissions))
                                    @if ($websiteInfo->theme_version != 3)
                                        <div class="form-group">
                                            <label>{{ $keywords['Gallery'] ?? __('Gallery') }} **</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="gallery" value="1"
                                                        class="selectgroup-input"
                                                        {{ isset($hs) && $hs->gallery == 1 ? 'checked' : '' }}>
                                                    <span
                                                        class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="gallery" value="0"
                                                        class="selectgroup-input"
                                                        {{ !isset($hs) || $hs->gallery == 0 ? 'checked' : '' }}>
                                                    <span
                                                        class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <div class="form-group">
                                    <label>{{ $keywords['Featured_Category_Posts'] ?? __('Featured Category Posts') }}
                                        **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="featured_category_posts" value="1"
                                                class="selectgroup-input"
                                                {{ isset($hs) && $hs->featured_category_posts == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="featured_category_posts" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($hs) || $hs->featured_category_posts == 0 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ $keywords['Footer'] ?? __('Footer') }} **</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="footer" value="1"
                                                class="selectgroup-input"
                                                {{ isset($hs) && $hs->footer == 1 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="footer" value="0"
                                                class="selectgroup-input"
                                                {{ !isset($hs) || $hs->footer == 0 ? 'checked' : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                                @if (
                                    $websiteInfo->theme_version == 4 ||
                                        $websiteInfo->theme_version == 5 ||
                                        $websiteInfo->theme_version == 6 ||
                                        $websiteInfo->theme_version == 7)
                                    <div class="form-group">
                                        <label>{{ $keywords['Copyright_Text'] ?? __('Copyright Text') }} **</label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="copyright_text" value="1"
                                                    class="selectgroup-input"
                                                    {{ isset($hs) && $hs->copyright_text == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="copyright_text" value="0"
                                                    class="selectgroup-input"
                                                    {{ !isset($hs) || $hs->copyright_text == 0 ? 'checked' : '' }}>
                                                <span
                                                    class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form">
                            <div class="form-group from-show-notify row">
                                <div class="col-12 text-center">
                                    <button type="submit" id="submitBtn"
                                        class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
