<section class="olima_blog_standard pt-140 pb-100">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        @if (count($posts) == 0)
          <div class="py-5 bg-light text-center">
            <h2 class="text-center">{{$keywords['No_Post_Found'] ?? __('No Post Found') . '!' }}</h2>
          </div>
        @else
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

            <div class="grid_item mb-60">
              @php $sldImgs = json_decode($post->slider_images); @endphp

              <div class="post_img">
                <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}">
                  <img data-src="{{ asset('assets/user/img/posts/slider-images/' . $sldImgs[0]) }}" class="w-100 lazy" alt="post image">
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
                <h2>
                  <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}">
                    {{ strlen($post->title) > 30 ? mb_substr($post->title, 0, 30, 'UTF-8') . '...' : $post->title }}
                  </a>
                </h2>
                <p>{!! strlen(strip_tags($post->content)) > 100 ? mb_substr(strip_tags($post->content), 0, 100, 'UTF-8') . '...' : replaceBaseUrl(strip_tags($post->content), 'summernote') !!}</p>

                <a href="{{ route('front.user.post_details', ['slug' => $post->slug, getParam()]) }}" class="btn_link">{{$keywords['Read_More'] ?? __('Read More') }}</a>

                @php
                    $bookmarkCount = \App\Models\User\BookmarkPost::where('post_id', $post->post_id)->count();
                @endphp
                <a href="{{ route('front.user.make_bookmark', ['id' => $post->post_id, getParam()]) }}" class="btn_heart post-info-{{ $post->post_id }} {{ Auth::guard('customer')->check() == true && $postBookmarked == 1 ? 'post-bookmarked' : '' }}"><i class="fas fa-heart"></i><span id="bookmark-info-{{ $post->post_id }}">{{ $bookmarkCount }}</span></a>
              </div>
            </div>
          @endforeach

          {{ $posts->appends([
            'title' => request()->input('title'),
            'category' => request()->input('category')
          ])->links() }}
        @endif
      </div>

        @includeIf('user-front.common.post.sidebar')
    </div>
  </div>
</section>
