@extends('admin.layout')

@php
$selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@if(!empty($selLang) && $selLang->rtl == 1)
@section('styles')
<style>
    form:not(.modal-form) input,
    form:not(.modal-form) textarea,
    form:not(.modal-form) select,
    select[name='language'] {
        direction: rtl;
    }
    form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{__('Page Lists')}}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{__('Pages')}}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{__('Page Lists')}}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-title d-inline-block">{{__('Page Lists')}}</div>
                </div>
                <div class="col-lg-3">
                    
                </div>
                <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                    <a href="{{route('admin.page.create')}}" class="btn btn-primary float-lg-right float-left btn-sm"><i class="fas fa-plus"></i> {{__('Add Page')}}</a>
                    <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete" data-href="{{route('admin.page.bulk.delete')}}"><i class="flaticon-interface-5"></i> {{__('Delete')}}</button>
                </div>
            </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($apages) == 0)
                <h2 class="text-center">{{__('NO PAGE ADDED')}}</h2>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                            <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{__('Name')}}</th>
                        <th scope="col">{{__('Title')}}</th>
                        <th scope="col">{{__('Status')}}</th>
                        <th scope="col">{{__('Actions')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($apages as $key => $apage)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{$apage->id}}">
                          </td>
                          <td>{{ $apage->name }}</td>
                          <td>{{$apage->title}}</td>
                          <td>
                            @if ($apage->status == 1)
                              <span class="badge badge-success">{{__('Active')}}</span>
                            @elseif ($apage->status == 0)
                              <span class="badge badge-danger">{{__('Deactive')}}</span>
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm" href="{{route('admin.page.edit', $apage->id) . '?language=' . request()->input('language')}}">
                            <span class="btn-label">
                              <i class="fas fa-edit"></i>
                            </span>
                            {{__('Edit')}}
                            </a>
                            <form class="d-inline-block deleteform" action="{{route('admin.page.delete')}}" method="post">
                              @csrf
                              <input type="hidden" name="pageid" value="{{$apage->id}}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                {{__('Delete')}}
                              </button>
                            </form>
                          </td>
                        </tr>
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

@endsection
