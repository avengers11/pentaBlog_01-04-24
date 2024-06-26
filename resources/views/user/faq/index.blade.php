@extends('user.layout')

@php
    $userDefaultLang = \App\Models\User\Language::where([
        ['user_id',\Illuminate\Support\Facades\Auth::id()],
        ['is_default',1]
    ])->first();
    $userLanguages = \App\Models\User\Language::where('user_id',\Illuminate\Support\Facades\Auth::id())->get();
@endphp

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ $keywords['FAQ_Management'] ?? __('FAQ Management') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('user-dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $keywords['FAQ_Management'] ?? __('FAQ Management') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ $keywords['FAQs'] ?? __('FAQs') }}</div>
            </div>

            <div class="col-lg-3">
                
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a
                href="#"
                data-toggle="modal"
                data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"
              ><i class="fas fa-plus"></i> {{ $keywords['Add_FAQ'] ?? __('Add FAQ') }}</a>

              <button
                class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('user.faq_management.bulk_delete_faq') }}"
              ><i class="flaticon-interface-5"></i> {{ $keywords['Delete'] ?? __('Delete') }}</button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($faqs) == 0)
                <h3 class="text-center">{{ $keywords['NO_FAQ_FOUND'] ?? __('NO FAQ FOUND!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ $keywords['Question'] ?? __('Question') }}</th>
                        <th scope="col">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}</th>
                        <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($faqs as $faq)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $faq->id }}">
                          </td>
                          <td>
                            {{strlen($faq->question) > 70 ? mb_substr($faq->question,0,70,'utf-8') . '...' : $faq->question}}
                          </td>
                          <td>{{ $faq->serial_number }}</td>
                          <td>
                            <a
                              class="btn btn-secondary btn-sm mr-1 edit-btn"
                              href="#"
                              data-toggle="modal"
                              data-target="#editModal"
                              data-id="{{ $faq->id }}"
                              data-question="{{ $faq->question }}"
                              data-answer="{{ $faq->answer }}"
                              data-serial_number="{{ $faq->serial_number }}"
                            >
                                <i class="fas fa-edit"></i>
                            </a>

                            <form
                              class="deleteform d-inline-block"
                              action="{{ route('user.faq_management.delete_faq') }}"
                              method="post"
                            >
                              @csrf
                              <input type="hidden" name="faq_id" value="{{ $faq->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                  <i class="fas fa-trash"></i>
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

  {{-- create modal --}}
  @include('user.faq.create')

  {{-- edit modal --}}
  @include('user.faq.edit')
@endsection

@section('scripts')
    <script src="{{asset('assets/admin/js/edit.js')}}"></script>
@endsection
