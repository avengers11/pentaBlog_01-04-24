<div class="modal fade" id="variationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title @if (request()->is('admin/*')) text-white @endif" id="exampleModalLongTitle">
                    <span></span>
                    <small class="ml-2">
                        ({{ $be->base_currency_text_position == 'left' ? $be->base_currency_text : '' }}
                        <span id="productPrice"></span>
                        {{ $be->base_currency_text_position == 'right' ? $be->base_currency_text : '' }})
                    </small>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="variants">
                    {{-- All variants will be appended here by jquery --}}
                </div>
            </div>
            <div class="modal-footer justify-content-center align-items-stretch">
                <div class="col-lg-4">
                    <div class="modal-quantity">
                        {{-- <span class="minus"><i class="fas fa-minus"></i></span> --}}
                        <input type="number" class="nice-input" name="cart-amount" value="1" min="1">
                        {{-- <span class="plus"><i class="fas fa-plus"></i></span> --}}
                    </div>
                </div>
                <div class="col-lg-8">
                    <button type="button" class="btn btn-primary h-100 btn-block text-uppercase modal-cart-link">
                        <span class="d-block">{{ $keywords['Add_to_cart'] ?? 'Add to cart' }}</span>
                        <i class="fas fa-spinner d-none"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
