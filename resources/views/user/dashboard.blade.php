@extends('user.layout')
@php
$user = Auth::guard('web')->user();
$userLanguages = \App\Models\User\Language::where('user_id', $user->id)->get();
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



@php
$package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
if (!empty($user)) {
    $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
    $permissions = json_decode($permissions, true);
}
@endphp

@section('content')
    <div class="mt-2 mb-4">
        <h2 class="pb-2">{{ $keywords['Welcome_back'] ?? 'Welcome back' }}, {{ Auth::guard('web')->user()->first_name }}
            {{ Auth::guard('web')->user()->last_name }}!</h2>
    </div>
    @if (is_null($package))
        <div class="alert alert-warning">
            {{ __('Your membership is expired. Please') }} <a
                href="{{ route('user.plan.extend.index') }}">{{ __('click here') }}</a>
            {{ __('to purchase a new package / extend the current package.') }}
        </div>
    @else
        <div class="row justify-content-center align-items-center mb-1">
            <div class="col-12">
                <div class="alert border-left border-primary text-dark">
                    @if ($package_count >= 2)
                        @if ($next_membership->status == 0)
                            <strong
                                class="text-danger">{{ $keywords['pending_package_text'] ?? 'You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.' }}</strong><br>
                        @elseif ($next_membership->status == 1)
                            <strong class="text-danger">
                                {{ $keywords['package_purchase_warning'] ?? __('You have another package to activate after the current package expires. You cannot purchase / extend any package, until the next package is activated') }}
                            </strong><br>
                        @endif
                    @endif

                    <strong>{{ $keywords['Current_Package'] ?? 'Current Package' }}: </strong>
                    {{ $current_package->title }}
                    <span class="badge badge-secondary">{{ $current_package->term }}</span>
                    @if ($current_membership->is_trial == 1)
                        ({{ $keywords['Expire_Date'] ?? 'Expire Date ' }}:
                        {{ Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
                        <span class="badge badge-primary">Trial</span>
                    @else
                        ({{ $keywords['Expire_Date'] ?? 'Expire Date ' }}:
                        {{ $current_package->term === 'lifetime' ? 'Lifetime' : Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
                    @endif

                    @if ($package_count >= 2)
                        <div>
                            <strong>{{ $keywords['Next_Package_To_Activate'] ?? 'Next Package To Activate' }}: </strong>
                            {{ $next_package->title }} <span
                                class="badge badge-secondary">{{ $next_package->term }}</span>
                            @if ($current_package->term != 'lifetime' && $current_membership->is_trial != 1)
                                (
                                {{ $keywords['Activation_Date'] ?? 'Activation Date' }}:
                                {{ Carbon\Carbon::parse($next_membership->start_date)->format('M-d-Y') }},
                                {{ $keywords['Expire_Date'] ?? 'Expire Date ' }}:
                                {{ $next_package->term === 'lifetime' ? 'Lifetime' : Carbon\Carbon::parse($next_membership->expire_date)->format('M-d-Y') }})
                            @endif
                            @if ($next_membership->status == 0)
                                <span
                                    class="badge badge-warning">{{ $keywords['Decision_Pending'] ?? 'Decision Pending' }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-6 col-md-4">
            <a class="card card-stats card-info card-round"
                href="{{ route('user.post_management.posts', ['language' => $default->code]) }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-window-restore"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">{{ $keywords['Posts'] ?? __('Posts') }}</p>
                                <h4 class="card-title">{{ $post_count }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a class="card card-stats card-danger card-round"
                href="{{ route('user.post_management.posts', ['language' => $default->code]) }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-archive"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">{{ $keywords['Featured_Posts'] ?? __('Featured Posts') }}</p>
                                <h4 class="card-title">{{ $featured_post_count }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a class="card card-stats card-round card-warning"
                href="{{ route('user.post_management.categories', ['language' => $default->code]) }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-box-open"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">{{ $keywords['Post_Categories'] ?? __('Post Categories') }}</p>
                                <h4 class="card-title">{{ $post_category_count }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @if (!empty($permissions) && in_array('Gallery', $permissions))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-round card-secondary"
                    href="{{ route('user.gallery_management.gallery', ['language' => $default->code]) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-images"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ $keywords['Gallery_Items'] ?? __('Gallery Items') }}</p>
                                    <h4 class="card-title">{{ $gallery_item_count }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (!empty($permissions) && in_array('Gallery', $permissions))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-round card-primary"
                    href="{{ route('user.gallery_management.gallery', ['language' => $default->code]) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-store-alt"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">
                                        {{ $keywords['Featured_Gallery_Items'] ?? __('Featured Gallery Items') }}</p>
                                    <h4 class="card-title">{{ $featured_gallery_item_count }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (!empty($permissions) && in_array('Gallery', $permissions))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-success card-round"
                    href="{{ route('user.gallery_management.categories', ['language' => $default->code]) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-image"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">
                                        {{ $keywords['Gallery_Categories'] ?? __('Gallery Categories') }}</p>
                                    <h4 class="card-title">{{ $gallery_category_count }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (!empty($permissions) && in_array('FAQ', $permissions))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-danger card-round"
                    href="{{ route('user.faq_management', ['language' => $default->code]) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ $keywords['FAQs'] ?? __('FAQs') }}</p>
                                    <h4 class="card-title">{{ $faq_count }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        <div class="col-sm-6 col-md-4">
            <a class="card  card-stats card-round card-warning" href="{{ route('user.language.index', ['language' => $default->code]) }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-language"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">{{ $keywords['Languages'] ?? __('Languages') }}</p>
                                <h4 class="card-title">{{ $language_count }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @if (!empty($permissions) && in_array('Advertisement', $permissions))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-info card-round" href="{{ route('user.advertisements', ['language' => $default->code]) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-ad"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ $keywords['Advertisements'] ?? __('Advertisements') }}</p>
                                    <h4 class="card-title">{{ $advertisement_count }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (!empty($permissions) && in_array('Follow/Unfollow', $permissions))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-default card-round" href="{{ route('user.follower.list', ['language' => $default->code]) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ $keywords['Followers'] ?? __('Followers') }}</p>
                                    <h4 class="card-title">{{ $followers }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        @if (!empty($permissions) && in_array('Follow/Unfollow', $permissions))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-primary card-round" href="{{ route('user.following.list', ['language' => $default->code]) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">{{ $keywords['Followings'] ?? __('Followings') }}</p>
                                    <h4 class="card-title">{{ $followings }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>

    @if (!empty($package))
        <div class="row">
            <div class="col-lg-6">
                <div class="row row-card-no-pd">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <h4 class="card-title">
                                        {{ $keywords['Recent_Payment_Logs'] ?? __('Recent Payment Logs') }}</h4>
                                </div>
                                <p class="card-category">
                                    {{ $keywords['10_latest_payment_logs'] ?? '10 latest payment logs' }}
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @if (count($memberships) == 0)
                                            <div class="text-center">
                                                {{ $keywords['NO_PAYMENT_LOG_FOUND'] ?? 'NO PAYMENT LOG FOUND' }}</div>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-striped mt-3">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">
                                                                {{ $keywords['Transaction_Id'] ?? 'Transaction Id' }}</th>
                                                            <th scope="col">{{ $keywords['Amount'] ?? 'Amount' }}</th>
                                                            <th scope="col">
                                                                {{ $keywords['Payment_Status'] ?? 'Payment Status' }}</th>
                                                            <th scope="col">{{ $keywords['Actions'] ?? 'Actions' }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($memberships as $key => $membership)
                                                            <tr>
                                                                <td>{{ strlen($membership->transaction_id) > 30 ? mb_substr($membership->transaction_id, 0, 30, 'UTF-8') . '...' : $membership->transaction_id }}
                                                                </td>
                                                                @php
                                                                    $bex = json_decode($membership->settings);
                                                                @endphp
                                                                <td>
                                                                    @if ($membership->price == 0)
                                                                        {{ __('Free') }}
                                                                    @else
                                                                        {{ format_price($membership->price) }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($membership->status == 1)
                                                                        <h3 class="d-inline-block badge badge-success">
                                                                            {{ $keywords['Success'] ?? 'Success' }}
                                                                        </h3>
                                                                    @elseif ($membership->status == 0)
                                                                        <h3 class="d-inline-block badge badge-warning">
                                                                            {{ $keywords['Pending'] ?? 'Pending' }}
                                                                        </h3>
                                                                    @elseif ($membership->status == 2)
                                                                        <h3 class="d-inline-block badge badge-danger">
                                                                            {{ $keywords['Rejected'] ?? 'Rejected' }}
                                                                        </h3>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (!empty($membership->name !== 'anonymous'))
                                                                        <a class="btn btn-sm btn-info" href="javascript:void(0)"
                                                                            data-toggle="modal"
                                                                            data-target="#detailsModal{{ $membership->id }}">{{ $keywords['Detail'] ?? 'Detail' }}</a>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <div class="modal fade"
                                                                id="detailsModal{{ $membership->id }}" tabindex="-1"
                                                                role="dialog" aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">
                                                                                {{ $keywords['Owner_Details'] ?? 'Owner Details' }}
                                                                            </h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <h3 class="text-warning">
                                                                                {{ $keywords['Member_details'] ?? 'Member details' }}
                                                                            </h3>
                                                                            <label>{{ $keywords['Name'] ?? 'Name' }}</label>
                                                                            <p>{{ $membership->user->first_name . ' ' . $membership->user->last_name }}
                                                                            </p>
                                                                            <label>{{ $keywords['Email'] ?? 'Email' }}</label>
                                                                            <p>{{ $membership->user->email }}</p>
                                                                            <label>{{ $keywords['Phone'] ?? 'Phone' }}</label>
                                                                            <p>{{ $membership->user->phone }}</p>
                                                                            <h3 class="text-warning">
                                                                                {{ $keywords['Payment_details'] ?? 'Payment details' }}
                                                                            </h3>
                                                                            <p><strong>{{ $keywords['Cost'] ?? 'Cost' }}:
                                                                                </strong>
                                                                                {{ $membership->price == 0 ? __('Free') : $membership->price }}
                                                                            </p>
                                                                            <p><strong>{{ $keywords['Currency'] ?? 'Currency' }}:
                                                                                </strong> {{ $membership->currency }}
                                                                            </p>
                                                                            <p><strong>{{ $keywords['Method'] ?? 'Method' }}:
                                                                                </strong> {{ $membership->payment_method }}
                                                                            </p>
                                                                            <h3 class="text-warning">
                                                                                {{ $keywords['Package_Details'] ?? 'Package Details' }}
                                                                            </h3>
                                                                            <p><strong>{{ $keywords['Title'] ?? 'Title' }}:
                                                                                </strong>{{ !empty($membership->package) ? $membership->package->title : '' }}
                                                                            </p>
                                                                            <p><strong>{{ $keywords['Term'] ?? 'Term' }}:
                                                                                </strong>
                                                                                {{ !empty($membership->package) ? $membership->package->term : '' }}
                                                                            </p>
                                                                            <p><strong>{{ $keywords['Start_Date'] ?? 'Start Date' }}:
                                                                                </strong>{{ \Illuminate\Support\Carbon::parse($membership->start_date)->format('M-d-Y') }}
                                                                            </p>
                                                                            <p><strong>{{ $keywords['Expire_Date'] ?? 'Expire  Date' }}:
                                                                                </strong>{{ \Illuminate\Support\Carbon::parse($membership->expire_date)->format('M-d-Y') }}
                                                                            </p>
                                                                            <p>
                                                                                <strong>{{ $keywords['Purchase_Type'] ?? 'Purchase Type' }}:
                                                                                </strong>
                                                                                @if ($membership->is_trial == 1)
                                                                                    {{ $keywords['Trial'] ?? 'Trial' }}
                                                                                @else
                                                                                    {{ $membership->price == 0 ? __('Free') : __('Regular') }}
                                                                                @endif
                                                                            </p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">
                                                                                {{ $keywords['Close'] ?? 'Close' }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row row-card-no-pd">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <h4 class="card-title">{{ $keywords['Latest_Posts'] ?? __('Latest Posts') }}</h4>
                                </div>
                                <p class="card-category">
                                    {{ $keywords['10_latest_posts'] ?? __('10 latest posts') }}
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        @if (count($posts) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-striped mt-3">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">{{ $keywords['Title'] ?? __('Title') }}
                                                            </th>
                                                            <th scope="col">
                                                                {{ $keywords['Category'] ?? __('Category') }}</th>
                                                            <th scope="col">
                                                                {{ $keywords['Actions'] ?? __('Actions') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($posts as $key => $post)
                                                            <tr>
                                                                <td>
                                                                    {{ strlen($post->title) > 30 ? mb_substr($post->title, 0, 30) . '...' : $post->title }}
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $category = App\Models\User\PostCategory::where('id', $post->post_category_id)->first();
                                                                    @endphp
                                                                    {{ $category->name }}
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-secondary btn-xs mr-1"
                                                                        href="{{ route('user.post_management.edit_post', ['id' => $post->post_id, 'language' => $default->code]) }}">
                                                                        <span class="btn-label">
                                                                            <i class="fas fa-edit"></i>
                                                                        </span>
                                                                    </a>

                                                                    <form class="deleteform d-inline-block"
                                                                        action="{{ route('user.post_management.delete_post', ['id' => $post->post_id]) }}"
                                                                        method="post">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-xs deletebtn">
                                                                            <span class="btn-label">
                                                                                <i class="fas fa-trash"></i>
                                                                            </span>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                {{ $keywords['NO_POST_FOUND'] ?? __('NO POST FOUND') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
