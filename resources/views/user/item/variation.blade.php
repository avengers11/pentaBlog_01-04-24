@extends('user.layout')


@if (!empty($input->language) && $input->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }

            .nicEdit-main {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Item Variation ') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user-dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Variation Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Variations') }}</a>
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="row">
                    <div class="col-lg-6">
                        {{ __('Edit Input') }}
                    </div>
                    <div class="col-lg-6 text-right">
                        <a class="btn btn-primary"
                            href="{{ route('user.item.index') . '?language=' . request()->input('language') }}">{{ __('Back') }}</a>
                    </div>

                    <div class="alert alert-danger pb-1" id="postErrors" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <ul></ul>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="ajaxForm" action="{{ route('user.item.variation.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $item_id }}" name="item_id">

                <div class="js-repeater">
                    <div class="mb-3">
                        <label class="form-label">Variations</label>
                        <br>
                        <button class="btn btn-primary js-repeater-add" type="button">+ Add Varient</button>
                    </div>
                    <div id="js-repeater-container">
                        @foreach ($variations as $key => $lwVariaion)
                            <div class="js-repeater-item border px-2 mt-1" data-item="{{ $key }}">
                                <div class="mb-3 row d-flex justify-content-start align-items-center">
                                    @for ($i = 0; $i < count($languages); $i++)
                                        <div class="col-12 col-md-2">
                                            <label for="form" class="form-label">Variation Name
                                                ({{ $languages[$i]->code }})
                                            </label>
                                            <div class="input-group mb-2">
                                                <input required value="{{ $lwVariaion[$i]['variant_name'] ?? '' }}"
                                                    type="text" class="form-control" placeholder=""
                                                    name="{{ $languages[$i]->code }}_variation_{{ $key }}">
                                                <input type="hidden" value="" name="variation_helper[]">
                                            </div>
                                        </div>
                                    @endfor
                                    <div class="col-12 col-md-2">
                                        <button class="btn btn-danger js-repeater-remove btn-sm align-self-center mb-2 mb-md-0 float-right float-left-md" type="button"
                                            onclick="$(this).parents('.js-repeater-item').remove()">X</button>
                                    </div>
                                    <button class="btn btn-success js-repeater-child-add btn-sm  btn-sm mx-auto mx-sm-0" type="button"
                                        data-it="{{ $key }}">
                                        Add Option
                                    </button>
                                    <div class="repeater-child-list px-3" id="options{{ $key }}">
                                        @php
                                            $op = json_decode($lwVariaion[0]['option_name']);
                                            $op_price = json_decode($lwVariaion[0]['option_price']);
                                            $op_stock = json_decode($lwVariaion[0]['option_stock']);
                                        @endphp
                                        @if ($op)
                                            @foreach ($op as $opIn => $w)
                                                <div class="repeater-child-item mb-3" id="options{{ $key }}">
                                                    <div class="row align-items-end">
                                                        @php
                                                            $opArr = [];
                                                            for ($i = 0; $i < count($languages); $i++) {
                                                                $opArr[$i] = json_decode($lwVariaion[$i]['option_name'] ?? '');
                                                            }
                                                        @endphp
                                                        @for ($i = 0; $i < count($languages); $i++)
                                                            <div class="col-12 col-md">
                                                                <label for="form" class="form-label">Option Name
                                                                    ({{ $languages[$i]->code }})
                                                                </label>
                                                                <input
                                                                    name="{{ $languages[$i]->code }}_options1_{{ $key }}[]"
                                                                    type="text" class="form-control"
                                                                    value="{{ $opArr[$i][$opIn] ?? '' }}" placeholder="">
                                                            </div>
                                                        @endfor
                                                        <div class="col-12 col-md">
                                                            <label for="form" class="form-label">Price ({{ $userBs->base_currency_symbol }})</label>
                                                            <input name="options2_{{ $key }}[]" type="number"
                                                                class="form-control" value="{{ $op_price[$opIn] }}">
                                                        </div>
                                                        <div class="col-12 col-md">
                                                            <label for="form" class="form-label">Stock</label>
                                                            <input name="options3_{{ $key }}[]" type="number"
                                                                class="form-control" value="{{ $op_stock[$opIn] }}">
                                                        </div>
                                                        <div class="col-12 col-md">
                                                            <button class="btn btn-danger js-repeater-child-remove btn-sm float-right float-left-sm mt-1 mt-sm-0 "
                                                                type="button"
                                                                onclick="$(this).parents('.repeater-child-item').remove()">X</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        {{-- @endforeach --}}
                                        {{-- @endfor --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group text-center">
                    <button id="submitBtn" type="submit" class="btn btn-success btn">{{ __('SUBMIT') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            const languages = <?= $languages ?>;
            var addItem = function(key) {
                rpItemNode = '';
                let it = $(".js-repeater-item:last-child").index() + 1;

                rpItemNode += `<div class="js-repeater-item border px-2 mt-1" data-item="${it}">
                        <div class="mb-3 row d-flex justify-content-start align-items-center">`
                for (var Itemkey in languages) {
                    rpItemNode += `<div class="col-12 col-md-2" >
                        <label for="form" class="form-label">Variation Name (${languages[Itemkey].code})</label>
                            <div class="input-group mb-2">
                                <input type="text" required class="form-control" placeholder="" name="${languages[Itemkey].code}_variation_${it}">
                                <input type="hidden" name="variation_helper[]" value="${it}" >
                            </div>
                            </div>`
                }
                rpItemNode += `<div class="col-12 col-md-2">
                                    <button class="btn btn-danger js-repeater-remove btn-sm align-self-center mb-2 mb-md-0 float-right float-left-md" type="button"
                                        onclick="$(this).parents('.js-repeater-item').remove()">X</button>
                                </div>
                            <button class="btn btn-success js-repeater-child-add btn-sm mx-auto mx-sm-0" type="button" data-it="${it}">Add Option</button>
                            <div class="repeater-child-list" id="options${it}"></div>
                        </div>
                    </div>

                    `;
                $("#js-repeater-container").append(rpItemNode);
            };
            /* find elements */
            var repeater = $(".js-repeater");
            var key = 0;
            var addBtn = repeater.find('.js-repeater-add');
            var items = $(".js-repeater-item");
            var it = $(".js-repeater-item").index();

            // items.each(function(i) {
            //     // $(this).data("item", it));
            //     var a = $(this).data('item');
            //     $(this).data('item', "asdfg");
            //     console.log($(this).data("item"));
            // })

            if (key <= 0) {
                // items.remove();
                /* handle click and add items */
                addBtn.on("click", function() {
                    key++;
                    addItem(key, it);
                });
            }

            $(document).on('click', '.js-repeater-child-add', function() {
                option = ''
                let it = $(this).data('it');
                let cit = $(this).parent().find(".repeater-child-item:last-child").index();
                console.log('cit', cit);
                let parent = $(this).parent().find("#options" + it);

                option += `<div class="repeater-child-item mb-3" id="options${it +''+ cit}">
        <div class="row align-items-end">`
                for (var optionkey in languages) {
                    option += `<div class="col-12 col-md">
                <label for="form" class="form-label">Option Name (${languages[optionkey].code})</label>
                <input required name="${languages[optionkey].code}_options1_${it}[]" type="text" class="form-control"
                    placeholder="">
            </div>`
                }
                option += `<div class="col-12 col-md">
                <label for="form" class="form-label">Price (${symbol})</label>
                <input required name="options2_${it}[]" type="number" class="form-control" value="0">
            </div>
            <div class="col-12 col-md">
                <label for="form" class="form-label">Stock</label>
                <input required name="options3_${it}[]" type="number" class="form-control" value="0">
            </div>
            <div class="col-12 col-md">
                <button class="btn btn-danger js-repeater-child-remove btn-sm float-right float-left-sm mt-1 mt-sm-0" type="button"
                    onclick="$(this).parents('.repeater-child-item').remove()">X</button>
            </div>
        </div>
    </div>`;
                $(parent).append(option);
            })
        });
    </script>
@endsection
