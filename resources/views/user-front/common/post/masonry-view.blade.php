<section class="olima_blog_masonry pt-140 pb-140" id="blog_masonry">
  <div class="container">
    @if (count($posts) == 0)
      <div class="py-5 bg-light text-center">
        <h2 class="text-center">{{$keywords['No_Post_Found'] ?? __('No Post Found') . '!' }}</h2>
      </div>
    @else
      <div class="masonry_grid row">
        @foreach ($posts as $post)
          @auth('customer')
            @php
              $postBookmarked = 0;

              foreach ($bookmarkPosts as $bookmarkPost) {
                if ($bookmarkPost->post_id == $post->post_id) {
                  $postBookmarked = 1;
                  break;
                }
              }
            @endphp
          @endauth

          <div class="col-lg-4 col-md-6 col-sm-12 grid_column">
            <div class="grid_item mb-60">
              <div class="post_img">
                <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}">
                  <img src="{{ $post->thumbnail_image != null ? Storage::url($post->thumbnail_image) : asset('assets/admin/img/noimage.jpg') }}" class="w-100" alt="post image">
                </a>
              </div>

              <div class="post_content">
                @php
                  // first, convert the string into date object
                  $date = Carbon\Carbon::parse($post->created_at);
                @endphp

                <div class="post_meta">
                  <span class="calender">{{ date_format($date, 'M d, Y') }}</span>
                  <span class="writer">{{ $post->author }}</span>
                </div>
                <h3>
                  <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}">
                    {{ strlen($post->title) > 30 ? mb_substr($post->title, 0, 30, 'UTF-8') . '...' : $post->title }}
                  </a>
                </h3>
                <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}" class="btn_link">{{$keywords['Read_More'] ?? __('Read More') }}</a>

                @php
                    $bookmarkCount = \App\Models\User\BookmarkPost::where('post_id', $post->post_id)->count();
                @endphp
                <a href="{{ route('front.user.make_bookmark', ['id' => $post->post_id, getParam()]) }}" class="btn_heart post-info-{{ $post->post_id }} {{ Auth::guard('customer')->check() == true && $postBookmarked == 1 ? 'post-bookmarked' : '' }}"><i class="fas fa-heart"></i><span id="bookmark-info-{{ $post->post_id }}">{{ $bookmarkCount }}</span></a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="row">
        <div class="col-lg-12">
          {{ $posts->appends(['title' => request()->input('title')])->links() }}
        </div>
      </div>
    @endif
  </div>
</section>
