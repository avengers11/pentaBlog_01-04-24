<div class="widget widget-categories mb-40">
    <h4 class="title">
        <button class="accordion-button" type="button" data-bs-toggle="collapse"
            data-bs-target="#blogCategory">
            {{ $keywords['Categories'] ?? 'Categories' }}
            <span class="icons">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
    </h4>
    @if($postCategories->count() > 0)
    <div id="blogCategory" class="collapse show">
        <div class="accordion-body mt-20 scroll-y">
            <ul class="list-unstyled m-0">
                @foreach ($postCategories as $postCategory)
                <li>
                    <a href="{{ route('front.user.posts', ['category' => $postCategory->id, getParam()]) }}" target="_self" title="{{ $postCategory->name }}"
                        class="border p-20 radius-sm">
                        <span>{{ $postCategory->name }}</span>
                        <span class="qty">{{ $postCategory->postContentList ? $postCategory->postContentList->count() : '' }}</span>
                    </a>
                </li>
                @endforeach

            </ul>
        </div>
    </div>
    @else
      <div class="bg-light py-2 mt-20 text-center" role="alert">
        {{ $keywords['No_Categories_Found'] ?? 'No Categories Found !' }}
      </div>
    @endif

</div>
