<footer class="footer">
  <div class="container-fluid">
    <div class="d-block mx-auto">
        @if(!empty($bs->copyright_text))
           {!! replaceBaseUrl($bs->copyright_text) !!}
        @endif
    </div>
  </div>
</footer>
