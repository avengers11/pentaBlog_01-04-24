@extends('user-front.common.layout')

@section('pageHeading')
  {{$keywords['My_Bookmarks'] ?? __('My Bookmarks') }}
@endsection

@section('content')
  <!-- Start Olima Breadcrumb Section -->
  <section class="olima_breadcrumb bg_image lazy" @if (!empty($bgImg)) data-bg="{{ asset('assets/user/img/' . $bgImg->breadcrumb) }}" @endif>
    <div class="bg_overlay" style="background: #{{$websiteInfo->breadcrumb_overlay_color}}; opacity: {{$websiteInfo->breadcrumb_overlay_opacity}}"></div>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="breadcrumb-title">
            <h1>{{$keywords['My_Bookmarks'] ?? __('My Bookmarks') }}</h1>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="breadcrumb-link">
            <ul>
              <li class="text-uppercase"><a href="{{route('front.user.detail.view', getParam())}}">{{$keywords['Home'] ?? __('Home') }}</a></li>
              <li class="active text-uppercase">{{$keywords['My_Bookmarks'] ?? __('My Bookmarks') }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Olima Breadcrumb Section -->

  <!-- Start All Bookmarks (Post) Section -->
  <section class="user-dashboard">
    <div class="container">
      <div class="row">
          @includeIf('user-front.user.side-navbar')

        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="account-info">
                  <div class="title">
                    <h4>{{$keywords['Bookmark_List'] ?? __('Bookmark List')}}</h4>
                  </div>

                  <div class="main-info">
                    @if (count($bookmarks) == 0)
                      <div class="row text-center">
                        <div class="col">
                          <h4>{{$keywords['No_Bookmark_Found'] ? $keywords['No_Bookmark_Found'] . '!' : __('No Bookmark Found') . '!' }}</h4>
                        </div>
                      </div>
                    @else
                      <div class="main-table">
                        <div class="table-responsive">
                          <table id="bookmark-table" class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>{{$keywords['Title'] ?? __('Title')}}</th>
                                <th>{{$keywords['Category'] ?? __('Category')}}</th>
                                <th>{{$keywords['Views'] ?? __('Views')}}</th>
                                <th>{{$keywords['Date'] ?? __('Date')}}</th>
                                <th>{{$keywords['Action'] ?? __('Action')}}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($bookmarks as $bookmark)
                                <tr>
                                  <td>{{ $loop->iteration }}</td>

                                  @php
                                    $post = $bookmark->post()->where('user_id',  $user->id)->first();
                                    $postContent = $post->content()->where('language_id', $language->id)->where('user_id',$user->id)->first();
                                    $category = $postContent->postCategory()->first();
                                  @endphp

                                  <td>
                                    {{ strlen($postContent->title) > 30 ? mb_substr($postContent->title, 0, 30, 'UTF-8') . '...' : $postContent->title }}
                                  </td>
                                  <td>{{ $category->name }}</td>
                                  <td>{{ $post->views }}</td>
                                  <td>{{ date_format($bookmark->created_at, 'M d, Y') }}</td>
                                  <td>
                                    <a href="{{ route('front.user.post_details', ['slug' => $postContent->slug, getParam()]) }}" class="btn" target="_blank">{{$keywords['Details'] ?? __('Details')}}</a>
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
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
  </section>
  <!-- End All Bookmarks (Post) Section -->
@endsection

