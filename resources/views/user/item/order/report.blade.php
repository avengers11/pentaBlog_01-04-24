@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ $keywords['Report'] ?? __('Report') }}
    </h4>
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
        <a href="#">{{ $keywords['Shop_Management'] ?? __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $keywords['Manage_Orders'] ?? __('Manage Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">
          {{ $keywords['Report'] ?? __('Report') }}
        </a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header p-1">
            <div class="row">
                <div class="col-lg-10">
                    <form action="{{url()->full()}}" class="form-inline">
                        <div class="form-group">
                            <label for="">{{ $keywords['From'] ?? __('From') }}</label>
                            <input class="form-control datepicker" type="text" name="from_date" placeholder="{{ $keywords['From'] ?? __('From') }}" value="{{request()->input('from_date') ? request()->input('from_date') : '' }}" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="">{{ $keywords['To'] ?? __('To') }}</label>
                            <input class="form-control datepicker ml-1" type="text" name="to_date" placeholder="{{ $keywords['To'] ?? __('To') }}" value="{{request()->input('to_date') ? request()->input('to_date') : '' }}" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="">{{ $keywords['Payment_Method'] ?? __('Payment Method') }}</label>
                            <select name="payment_method" class="form-control ml-1">
                                <option value="" selected>{{ $keywords['All'] ?? __('All') }}</option>
                                @if (!empty($onPms))
                                    @foreach ($onPms as $onPm)
                                    <option value="{{$onPm->keyword}}" {{request()->input('payment_method') == $onPm->keyword ? 'selected' : ''}}>{{$onPm->name}}</option>
                                    @endforeach
                                @endif
                                @if (!empty($offPms))
                                    @foreach ($offPms as $offPm)
                                    <option value="{{$offPm->name}}" {{request()->input('payment_method') == $offPm->name ? 'selected' : ''}}>{{$offPm->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">{{ $keywords['Payment_Status'] ?? __('Payment Status') }}</label>
                            <select name="payment_status" class="form-control ml-1">
                                <option value="" selected>{{ $keywords['All'] ?? __('All') }}</option>
                                <option value="Pending" {{request()->input('payment_status') == 'Pending' ? 'selected' : ''}}>{{ $keywords['Pending'] ?? __('Pending') }}</option>
                                <option value="Completed" {{request()->input('payment_status') == 'Completed' ? 'selected' : ''}}>{{ $keywords['Completed'] ?? __('Completed') }}</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="">{{ $keywords['Order_Status'] ?? __('Order Status') }}</label>
                            <select name="order_status" class="form-control ml-1">
                                <option value="" selected>{{ $keywords['All'] ?? __('All') }}</option>
                                <option value="pending" {{request()->input('order_status') == 'pending' ? 'selected' : ''}}>{{ $keywords['Pending'] ?? __('Pending') }}</option>
                                <option value="processing" {{request()->input('order_status') == 'processing' ? 'selected' : ''}}>{{ $keywords['Processing'] ?? __('Processing') }}</option>
                                <option value="completed" {{request()->input('order_status') == 'completed' ? 'selected' : ''}}>{{ $keywords['Completed'] ?? __('Completed') }}</option>
                                <option value="rejected" {{request()->input('order_status') == 'rejected' ? 'selected' : ''}}>{{ $keywords['Rejected'] ?? __('Rejected') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm ml-1">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                        </div>
                    </form>
              </div>
              <div class="col-lg-2">
                <form action="{{route('user.orders.export')}}" class="form-inline justify-content-end">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-sm ml-1" title="CSV Format">{{ $keywords['Export'] ?? __('Export') }}</button>
                    </div>
                </form>
              </div>
            </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) > 0)
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{$keywords['Order_Number'] ?? 'Order Number'}}</th>
                        <th scope="col">{{$keywords['Billing_Name'] ?? 'Billing Name'}}</th>
                        <th scope="col">{{$keywords['Billing_Email'] ?? 'Billing Email'}}</th>
                        <th scope="col">{{$keywords['Billing_Phone'] ?? 'Billing Phone'}}</th>
                        <th scope="col">{{$keywords['Billing_City'] ?? 'Billing City'}}</th>
                        <th scope="col">{{$keywords['Billing_Country'] ?? 'Billing Country'}}</th>
                        <th scope="col">{{$keywords['Shipping_Name'] ?? 'Shipping Name'}}</th>
                        <th scope="col">{{$keywords['Shipping_Email'] ?? 'Shipping Email'}}</th>
                        <th scope="col">{{$keywords['Shipping_Phone'] ?? 'Shipping Phone'}}</th>
                        <th scope="col">{{$keywords['Shipping_City'] ?? 'Shipping City'}}</th>
                        <th scope="col">{{$keywords['Shipping_Country'] ?? 'Shipping Country'}}</th>
                        <th scope="col">{{$keywords['Gateway'] ?? 'Gateway'}}</th>
                        <th scope="col">{{$keywords['Shipping_Method'] ?? 'Shipping Method'}}</th>
                        <th scope="col">{{$keywords['Payment_Status'] ?? 'Payment Status'}}</th>
                        <th scope="col">{{$keywords['Order_Status'] ?? 'Order Status'}}</th>
                        <th scope="col">{{$keywords['Cart_Total'] ?? 'Cart Total'}}</th>
                        <th scope="col">{{$keywords['Discount'] ?? 'Discount'}}</th>
                        <th scope="col">{{$keywords['Tax'] ?? 'Tax'}}</th>
                        <th scope="col">{{$keywords['Shipping_Charge'] ?? 'Shipping Charge'}}</th>
                        <th scope="col">{{$keywords['Total'] ?? 'Total'}}</th>
                        <th scope="col">{{$keywords['Date'] ?? 'Date'}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $key => $order)
                        <tr>
                          <td>#{{$order->order_number}}</td>
                          <td>{{$order->billing_fname}}</td>
                          <td>{{$order->billing_email}}</td>
                          <td>{{$order->billing_number}}</td>
                          <td>{{$order->billing_city}}</td>
                          <td>{{$order->billing_country}}</td>
                          <td>{{$order->shpping_fname}}</td>
                          <td>{{$order->shpping_email}}</td>
                          <td>{{$order->shpping_number}}</td>
                          <td>{{$order->shpping_city}}</td>
                          <td>{{$order->shpping_country}}</td>
                          <td>{{ucfirst($order->method)}}</td>
                          <td>{{$order->shipping_method ? $order->shipping_method : '-'}}</td>
                          <td>
                              @if ($order->payment_status == 'Pending')
                                <span class="badge badge-warning">{{$keywords['Pending'] ?? 'Pending'}}</span>
                              @elseif ($order->payment_status == 'Completed')
                                <span class="badge badge-success">{{$keywords['Completed'] ?? 'Completed'}}</span>
                              @endif
                          </td>
                          <td>
                            @if ($order->order_status == 'pending')
                              <span class="badge badge-warning">{{$keywords['Pending'] ?? 'Pending'}}</span>
                            @elseif ($order->order_status == 'processing')
                              <span class="badge badge-primary">{{$keywords['Processing'] ?? 'Processing'}}</span>
                            @elseif ($order->order_status == 'completed')
                              <span class="badge badge-success">{{$keywords['Completed'] ?? 'Completed'}}</span>
                            @elseif ($order->order_status == 'rejected')
                              <span class="badge badge-danger">{{$keywords['Rejected'] ?? 'Rejected'}}</span>
                            @endif
                          </td>
                          <td>{{$userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : ''}}{{round($order->cart_total,2)}}{{$userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : ''}}</td>
                          <td>{{$userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : ''}}{{round($order->discount,2)}}{{$userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : ''}}</td>
                          <td>{{$userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : ''}}{{round($order->tax,2)}}{{$userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : ''}}</td>
                          <td>{{$userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : ''}} {{round($order->shipping_charge,2)}} {{$userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : ''}}</td>
                          <td>{{$userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : ''}}{{round($order->total,2)}}{{$userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : ''}}</td>
                          <td>
                              {{$order->created_at}}
                          </td>
                        </tr>


                        {{-- Receipt Modal --}}
                        <div class="modal fade" id="receiptModal{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">{{$keywords['Receipt_Image'] ?? 'Receipt Image'}}</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{asset('assets/front/receipt/' . $order->receipt)}}" alt="Receipt" width="100%">
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">{{$keywords['Close'] ?? 'Close'}}</button>
                                </div>
                              </div>
                            </div>
                          </div>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <!-- Send Mail Modal -->
                <div class="modal fade" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{$keywords['Send_Mail'] ?? 'Send Mail'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="ajaxEditForm" class="" action="{{route('user.orders.mail')}}" method="POST">
                          @csrf
                          <div class="form-group">
                            <label for="">{{$keywords['Client_Mail'] ?? 'Client Mail'}} **</label>
                            <input id="inemail" type="text" class="form-control" name="email" value="" placeholder="{{$keywords['Enter_email'] ?? 'Enter email'}}">
                            <p id="eerremail" class="mb-0 text-danger em"></p>
                          </div>
                          <div class="form-group">
                            <label for="">{{$keywords['Subject'] ?? 'Subject'}} **</label>
                            <input id="insubject" type="text" class="form-control" name="subject" value="" placeholder="{{$keywords['Enter_subject'] ?? 'Enter subject'}}">
                            <p id="eerrsubject" class="mb-0 text-danger em"></p>
                          </div>
                          <div class="form-group">
                            <label for="">{{$keywords['Message'] ?? 'Message'}} **</label>
                            <textarea id="inmessage" class="form-control summernote" name="message" placeholder="{{$keywords['Enter_message'] ?? 'Enter message'}}" data-height="150"></textarea>
                            <p id="eerrmessage" class="mb-0 text-danger em"></p>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{$keywords['Close'] ?? 'Close'}}</button>
                        <button id="updateBtn" type="button" class="btn btn-primary">{{$keywords['Send_Mail'] ?? 'Send Mail'}}</button>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>

        @if (!empty($orders))
            <div class="card-footer">
            <div class="row">
                <div class="d-inline-block mx-auto">
                {{$orders->links()}}
                </div>
            </div>
            </div>
        @endif
      </div>
    </div>
  </div>

@endsection
