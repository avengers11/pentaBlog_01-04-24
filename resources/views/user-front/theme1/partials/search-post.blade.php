<!--====== Start Search Form ======-->
<div class="modal fade" id="search-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('front.user.posts', getParam()) }}" method="GET">
        <div class="form_group">
          <input type="search" class="form_control" placeholder="{{$keywords['Search_Post'] ?? __('Search Post') }}" name="title" value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">
          <button type="submit" class="search_btn"><i class="fa fa-search"></i></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--====== End Search Form ======-->
