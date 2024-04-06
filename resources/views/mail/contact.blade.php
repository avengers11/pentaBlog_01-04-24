@if ($subsc)
<h4>{{__('Hello Subscriber')}}, </h4>
@endif


<p>@php echo replaceBaseUrl($text); @endphp</p>

@if ($subsc)
<p class="mb-0">{{__('Best Regards')}},</p>
<p>{{$bs->website_title}}</p>
@endif

