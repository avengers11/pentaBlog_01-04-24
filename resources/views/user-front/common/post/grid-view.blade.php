<section class="olima_blog_grid pt-140 pb-80">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        @if (count($posts) == 0)
        <div class="py-5 bg-light text-center">
          <h2 class="text-center">{{$keywords['No_Post_Found'] ?? __('No Post Found') . '!' }}</h2>
        </div>
        @else
          <div class="row">
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

              <div class="col-lg-6">
                <div class="grid_item mb-50">

                  <div class="post_img">
                    <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}">
                      <img data-src="{{ asset('assets/user/img/posts/' . $post->thumbnail_image) }}" class="w-100 lazy" alt="post image">
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
                        {{ strlen($post->title) > 40 ? mb_substr($post->title, 0, 40, 'UTF-8') . '...' : $post->title }}
                      </a>
                    </h3>
                    <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}" class="btn_link">{{$keywords['Read_More'] ?? __('Read More') }}</a>
                    <a href="{{ route('front.user.make_bookmark', ['id' => $post->post_id, getParam()]) }}" class="btn_heart post-info-{{ $post->post_id }} {{ Auth::guard('customer')->check() == true && $postBookmarked == 1 ? 'post-bookmarked' : '' }}">
                        <i class="fas fa-heart"></i>
                        @php
                            $bookmarkCount = \App\Models\User\BookmarkPost::where('post_id', $post->post_id)->count();
                        @endphp
                        <span id="bookmark-info-{{ $post->post_id }}">{{ $bookmarkCount }}</span>
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="col-lg-12">
            {{ $posts->appends(['title' => request()->input('title'),'category' => request()->input('category')])->links() }}
          </div>
        @endif
      </div>

      @includeIf('user-front.common.post.sidebar')
    </div>
  </div>
</section>
