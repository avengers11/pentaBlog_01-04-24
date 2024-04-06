@extends('user.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-iconpicker.min.css') }}">
@endsection
@php
$userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
$userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();

$user = Auth::guard('web')->user();
$package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
if (!empty($user)) {
    $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
    $permissions = json_decode($permissions, true);
}
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Drag_and_Drop_Menu_Builder'] ?? __('Drag & Drop Menu Builder') }}</h4>
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
                <a href="#">{{ $keywords['Menu_Builder'] ?? __('Menu Builder') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ $keywords['Menu_Builder'] ?? __('Menu Builder') }}</div>
                        </div>
                        <div class="col-lg-2">

                        </div>
                    </div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row no-gutters">
                        <div class="col-lg-4">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">
                                    {{ $keywords['Pre_built_Menus'] ?? __('Pre-built Menus') }}</div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">{{ $keywords['Home'] ?? 'Home' }} <a
                                                data-text="{{ $keywords['Home'] ?? 'Home' }}" data-type="home"
                                                class="addToMenus btn btn-primary btn-sm float-right"
                                                href="">{{ $keywords['Add_to_Menus'] ?? __('Add to Menus') }}</a>
                                        </li>
                                        @if (!empty($permissions) && in_array('Gallery', $permissions))
                                            <li class="list-group-item">{{ $keywords['Gallery'] ?? 'Gallery' }} <a
                                                    data-text="{{ $keywords['Gallery'] ?? 'Gallery' }}" data-type="gallery"
                                                    class="addToMenus btn btn-primary btn-sm float-right"
                                                    href="">{{ $keywords['Add_to_Menus'] ?? __('Add to Menus') }}</a>
                                            </li>
                                        @endif
                                        <li class="list-group-item">{{ $keywords['Posts'] ?? 'Posts' }} <a
                                                data-text="{{ $keywords['Posts'] ?? 'Posts' }}" data-type="posts"
                                                class="addToMenus btn btn-primary btn-sm float-right"
                                                href="">{{ $keywords['Add_to_Menus'] ?? __('Add to Menus') }}</a>
                                        </li>
                                        <li class="list-group-item">{{ $keywords['About'] ?? 'About' }} <a
                                                data-text="{{ $keywords['About'] ?? 'About' }}" data-type="about"
                                                class="addToMenus btn btn-primary btn-sm float-right"
                                                href="">{{ $keywords['Add_to_Menus'] ?? __('Add to Menus') }}</a>
                                        </li>
                                        <li class="list-group-item">{{ $keywords['Contact'] ?? 'Contact' }} <a
                                                data-text="{{ $keywords['Contact'] ?? 'Contact' }}" data-type="contact"
                                                class="addToMenus btn btn-primary btn-sm float-right"
                                                href="">{{ $keywords['Add_to_Menus'] ?? __('Add to Menus') }}</a>
                                        </li>
                                        @if (!empty($permissions) && in_array('FAQ', $permissions))
                                            <li class="list-group-item">{{ $keywords['FAQ'] ?? 'FAQ' }} <a
                                                    data-text="{{ $keywords['FAQ'] ?? 'FAQ' }}" data-type="faq"
                                                    class="addToMenus btn btn-primary btn-sm float-right"
                                                    href="">{{ $keywords['Add_to_Menus'] ?? __('Add to Menus') }}</a>
                                            </li>
                                        @endif
                                        @if (!empty($permissions) && in_array('Ecommerce', $permissions))
                                            <li class="list-group-item">{{ $keywords['Shop'] ?? 'Shop' }} <a
                                                    data-text="{{ $keywords['Shop'] ?? 'Shop' }}" data-type="shop"
                                                    class="addToMenus btn btn-primary btn-sm float-right"
                                                    href="">{{ $keywords['Add_to_Menus'] ?? __('Add to Menus') }}</a>
                                            </li>
                                        @endif
                                        @if (!empty($permissions) && in_array('Custom Pages', $permissions))
                                            @foreach ($apages as $apage)
                                                <li class="list-group-item">
                                                    {{ $apage->title }} <span
                                                        class="badge badge-primary">{{ __('Custom Page') }}</span>
                                                    <a data-text="{{ $apage->title }}" data-type="{{ $apage->page_id }}"
                                                        data-custom="yes"
                                                        class="addToMenus btn btn-primary btn-sm float-right"
                                                        href="">{{ $keywords['Add_to_Menus'] ?? __('Add to Menus') }}</a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white"> {{ $keywords['Add_Edit_Menu'] ?? __('Add / Edit Menu') }}</div>
                                <div class="card-body">
                                    <form id="frmEdit" class="form-horizontal">
                                        <input class="item-menu" type="hidden" name="type" value="">

                                        <div id="withUrl">
                                            <div class="form-group">
                                                <label for="text">{{ $keywords['Text'] ?? __('Text') }}</label>
                                                <input type="text" class="form-control item-menu" name="text" placeholder="{{ $keywords['Text'] ?? __('Text') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="href">{{ $keywords['URL'] ?? __('URL') }}</label>
                                                <input type="text" class="form-control item-menu" name="href" placeholder="{{ $keywords['URL'] ?? __('URL') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="target">{{ $keywords['Target'] ?? __('Target') }}</label>
                                                <select name="target" id="target" class="form-control item-menu">
                                                    <option value="_self">{{ $keywords['Self'] ?? __('Self') }}</option>
                                                    <option value="_blank">{{ $keywords['Blank'] ?? __('Blank') }}</option>
                                                    <option value="_top">{{ $keywords['Top'] ?? __('Top') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="withoutUrl" style="display: none;">
                                            <div class="form-group">
                                                <label for="text">{{ $keywords['Text'] ?? __('Text') }}</label>
                                                <input type="text" class="form-control item-menu" name="text" placeholder="{{ $keywords['Text'] ?? __('Text') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="href"> {{ $keywords['URL'] ?? __('URL') }}</label>
                                                <input type="text" class="form-control item-menu" name="href" placeholder="{{ $keywords['URL'] ?? __('URL') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="target">{{__('Target')}}</label>
                                                <select name="target" class="form-control item-menu">
                                                    <option value="_self">{{ $keywords['Self'] ?? __('Self') }}</option>
                                                    <option value="_blank">{{ $keywords['Blank'] ?? __('Blank') }}</option>
                                                    <option value="_top">{{ $keywords['Top'] ?? __('Top') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i class="fas fa-sync-alt mx-1"></i>{{ $keywords['Update'] ?? __('Update') }}</button>
                                    <button type="button" id="btnAdd" class="btn btn-success"><i class="fas fa-plus mx-1"></i>{{ $keywords['Add'] ?? __('Add') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    {{ $keywords['Website_Menus'] ?? __('Website Menus') }}</div>
                                <div class="card-body">
                                    <ul id="myEditor" class="sortableLists list-group">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-3">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button id="btnOutput"
                                    class="btn btn-success">{{ $keywords['Update_Menu'] ?? __('Update Menu') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugin/jquery-menu-editor/jquery-menu-editor.js') }}">
    </script>
    <script>
        "use strict";
        var prevMenus = @php echo json_encode($prevMenu) @endphp;
        var langid = {{ $lang_id }};
        var menuUpdate = "{{ route('user.menu_builder.update') }}";
    </script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/menu-builder.js') }}"></script>
@endsection
