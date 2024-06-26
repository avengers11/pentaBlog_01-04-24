

<!-- CSS Files -->
<link href="{{asset('assets/front/css/all.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/admin/css/fontawesome-iconpicker.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/dropzone.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-datepicker.css')}}">
<link rel="stylesheet" href="{{asset('assets/front/css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/jquery.timepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/atlantis.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/admin-main.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/custom.css')}}">
@if(request()->cookie('user-theme') == 'dark')
<link rel="stylesheet" href="{{asset('assets/admin/css/dark.css')}}">
@endif
@if (!empty($selLang) && $selLang->rtl == 1)
<link rel="stylesheet" href="{{asset('assets/admin/css/admin-rtl.css')}}">
@endif

