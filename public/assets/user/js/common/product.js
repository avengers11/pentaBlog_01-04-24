"use strict";
$(document).ready(function () {
  let prevGatewayId;

  if (sessionStorage.getItem('discount')) {
    if (typeof sessionStorage.getItem('discount') == 'number') {
      $('#discount-amount').text(sessionStorage.getItem('discount').toFixed(2));
    } else {
      $('#discount-amount').text(sessionStorage.getItem('discount'));
    }
  }

  
  if ($("input[name='shipping_cost']").length > 0) {
    if (sessionStorage.getItem('shipping_id')) {
      $("#shipping" + sessionStorage.getItem('shipping_id')).attr('checked', true);
    } else {
      $("input[name='shipping_cost']").eq(0).attr('checked', true);
    }
  }

  if (sessionStorage.getItem('newSubtotal')) {
    $('#subtotal-amount').text(parseFloat(sessionStorage.getItem('newSubtotal')).toFixed(2));
  }

  if (sessionStorage.getItem('charge')) {
    $('#shipping-charge-amount').text(sessionStorage.getItem('charge'));
  }

  if (sessionStorage.getItem('taxAmount')) {
    $('#tax-amount').text(parseFloat(sessionStorage.getItem('taxAmount')).toFixed(2));
  }

  if (sessionStorage.getItem('grandTotal')) {
    $('#grandtotal-amount').text(parseFloat(sessionStorage.getItem('grandTotal')).toFixed(2));
  }

  // add item to the cart by clicking on shop icon
  $('.add-to-cart-link').on('click', function (event) {
    event.preventDefault();

    let url = $(this).attr('href');

    // pass the url to controller
    $.get(url, function (response) {
      if ('success' in response) {
        $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
        toastr['success'](response.success);
      } else if ('error' in response) {
        toastr['error'](response.error);
      }
    });
  });


  // set the product quantity by clicking on (-) or (+) button
  $('#sub-btn').on('click', function () {
    let quantity = $(this).next().val();

    if (parseInt(quantity) > 1) {
      $(this).next().val(parseInt(quantity) - 1);
    }
  });

  $('#add-btn').on('click', function () {
    let quantity = $(this).prev().val();

    $(this).prev().val(parseInt(quantity) + 1);
  });


  // add item to the cart by clicking on 'Add To Cart' button
  $('.add-to-cart-btn').on('click', function (event) {
    event.preventDefault();

    let url = $(this).attr('href');
    let amount = $('#product-quantity').val();

    // replace with value
    url = url.replace('q', amount);

    // pass the url to controller
    $.get(url, function (response) {
      if ('success' in response) {
        $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
        toastr['success'](response.success);
      } else if ('error' in response) {
        toastr['error'](response.error);
      }
    });
  });


  // update the cart by clicking on 'Update Cart' button
  $('.update-cart-btn').on('click', function (event) {
    event.preventDefault();

    let updateCartURL = $(this).attr('href');

    // initialize empty array
    let productId = [];
    let productUnitPrice = [];
    let productQuantity = [];

    // using each() function to get all the values of same class
    $('.product-id').each(function () {
      productId.push($(this).val());
    });

    $('.product-unit-price').each(function () {
      let price = $(this).text();

      // convert string to number then push to array
      productUnitPrice.push(parseFloat(price));
    });

    $('.product-qty').each(function () {
      let quantity = $(this).val();

      // convert string to number then push to array
      productQuantity.push(parseInt(quantity));
    });

    // initialize a formData
    let formData = new FormData();

    // now, append all the array's value in formData key to send it to the controller
    for (let index = 0; index < productId.length; index++) {
      formData.append('id[]', productId[index]);
      formData.append('unitPrice[]', productUnitPrice[index]);
      formData.append('quantity[]', productQuantity[index]);
    }

    // set csrf-token for ajax request
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // send ajax request
    $.ajax({
      method: 'POST',
      url: updateCartURL,
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) {
        // if response is success then update the total price of each product
        $('.single-product-total').each(function (index) {
          let totalPrice = productUnitPrice[index] * productQuantity[index];

          $(this).text(totalPrice.toFixed(2));
        });
        $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
        toastr['success'](response.success);
      },
      error: function (errorData) {
        toastr['error'](errorData.responseJSON.error);
      }
    });
  });


  // remove product(s) by clicking on cross icon
  $('.remove-product-link').on('click', function (event) {
    event.preventDefault();

    let removeProductURL = $(this).attr('href');

    // get the product-id from the url to use it later.
    let productId = removeProductURL.split('/').pop();

    $.get(removeProductURL, function (response) {
      if ('success' in response) {
        if (response.numOfProducts > 0) {
          // remove only the selected product from DOM
          $('#cart-product-item' + productId).remove();
        } else {
          // first, remove the table and buttons(upadate cart, checkout) from DOM
          $('#product-cart-table').remove();
          $('#product-cart-buttons').remove();

          // then, show a message in div tag
          const markUp = `<div class="text-center">
              <h1>Cart is Empty!</h1>
            </div>`;

          $('#cart-msg').html(markUp);
        }
        $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
        toastr['success'](response.success);
      } else if ('error' in response) {
        toastr['error'](response.error);
      }
    });
  });


  // copy billing details values to shipping details
  $('#shipping-check').on('click', function () {
    if ($(this).prop('checked')) {
      let firstName = $('input[name="billing_first_name"]').val();
      $('input[name="shipping_first_name"]').val(firstName);

      let lastName = $('input[name="billing_last_name"]').val();
      $('input[name="shipping_last_name"]').val(lastName);

      let email = $('input[name="billing_email"]').val();
      $('input[name="shipping_email"]').val(email);

      let phone = $('input[name="billing_contact_number"]').val();
      $('input[name="shipping_contact_number"]').val(phone);

      let address = $('input[name="billing_address"]').val();
      $('input[name="shipping_address"]').val(address);

      let city = $('input[name="billing_city"]').val();
      $('input[name="shipping_city"]').val(city);

      let state = $('input[name="billing_state"]').val();
      $('input[name="shipping_state"]').val(state);

      let country = $('input[name="billing_country"]').val();
      $('input[name="shipping_country"]').val(country);
    } else {
      $('input[name="shipping_first_name"]').val('');
      $('input[name="shipping_last_name"]').val('');
      $('input[name="shipping_email"]').val('');
      $('input[name="shipping_contact_number"]').val('');
      $('input[name="shipping_address"]').val('');
      $('input[name="shipping_city"]').val('');
      $('input[name="shipping_state"]').val('');
      $('input[name="shipping_country"]').val('');
    }
  });


  // get shipping cost by clicking on radio button
  $('input[name="shipping_cost"]').on('change', function () {
    let chargeId = $('input[name="shipping_cost"]:checked').val();
    let charge = $('input[name="shipping_cost"]:checked').data('shipping_charge');
    sessionStorage.setItem('charge', charge);
    sessionStorage.setItem('shipping_id', chargeId);

    // set the amount of selected shipping charge in 'charge summary' table
    $('#shipping-charge-amount').text(charge);

    // set value to a hidden input field
    $('#shipping-charge-id').val(chargeId);

    let subTotal = $('#subtotal-amount').text();
    let tax = $('#tax-amount').text();

    // get the new grand total
    let grandTotal = parseFloat(subTotal) + parseFloat(charge) + parseFloat(tax);
    sessionStorage.setItem('grandTotal', grandTotal);

    $('#grandtotal-amount').text(grandTotal.toFixed(2));
  });


  // get discount amount & apply the coupon by clicking on 'Apply' button
  $('#coupon-btn').on('click', function (event) {
    event.preventDefault();

    let code = $('#coupon-code').val();

    if (code) {
      applyCoupon(code);
    } else {
      alert('Please enter your coupon code.');
    }
  });


  // get discount amount & apply the coupon by pressing on 'Enter' key
  $('#coupon-code').keypress(function (event) {
    if (event.which == 13) {
      let code = $(this).val();

      if (code) {
        applyCoupon(code);
      } else {
        alert('Please enter your coupon code.');
      }
    }
  });


  // perform some action according to selected radio button
  $('.single_radio').on('change', function () {
    let radioBtnVal = $('input[name="gateway"]:checked').val();
    let dataType = parseInt(radioBtnVal);

    if (isNaN(dataType)) {
      // add 'd-none' class for previously selected gateway.
      if (prevGatewayId) {
        $('#gateway-attachment-' + prevGatewayId).addClass('d-none');
        $('#gateway-description-' + prevGatewayId).addClass('d-none');
        $('#gateway-instructions-' + prevGatewayId).addClass('d-none');
      }

      // show or hide 'Stripe' form
      if (radioBtnVal == 'stripe') {
        $('#stripe_form').removeClass('d-none');
      } else {
        $('#stripe_form').addClass('d-none');
      }
    } else {
      let url = mainURL + '/shop/checkout/offline-gateway/' + radioBtnVal + '/check-attachment';

      $.get(url, function (response) {
        if ('status' in response) {
          // add 'd-none' class for stripe form
          if (!$('#stripe_form').hasClass('d-none')) {
            $('#stripe_form').addClass('d-none');
          }

          // add 'd-none' class for previously selected gateway.
          if (prevGatewayId) {
            $('#gateway-attachment-' + prevGatewayId).addClass('d-none');
            $('#gateway-description-' + prevGatewayId).addClass('d-none');
            $('#gateway-instructions-' + prevGatewayId).addClass('d-none');
          }

          // show attachment input field, description & instructions of offline gateway
          if (response.status == 1) {
            $('#gateway-attachment-' + radioBtnVal).removeClass('d-none');
          }

          $('#gateway-description-' + radioBtnVal).removeClass('d-none');
          $('#gateway-instructions-' + radioBtnVal).removeClass('d-none');

          prevGatewayId = response.id;
        } else if ('errorMsg' in response) {
          toastr['error'](response.errorMsg);
        }
      });
    }
  });


  // get the rating (star) value in integer
  $('.review-value li a').on('click', function () {
    let ratingValue = $(this).attr('data-ratingVal');

    // first, remove star color from all the 'review-value' class
    if ($('.review-value li a i').hasClass('text-warning')) {
      $('.review-value li a i').removeClass('text-warning');
    }

    // second, add star color to the selected parent class
    let parentClass = 'review-' + ratingValue;
    $('.' + parentClass + ' li a i').addClass('text-warning');

    // finally, set the rating value to a hidden input field
    $('#rating-id').val(ratingValue);
  });
});

function applyCoupon(couponCode) {
  let url = mainURL + '/shop/checkout/apply-coupon';

  let data = {
    coupon: couponCode,
    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  };

  $.post(url, data, function (response) {
    if ('success' in response) {
      let discount = response.amount;
      sessionStorage.setItem('discount', discount);

      if (typeof discount == 'number') {
        $('#discount-amount').text(discount.toFixed(2));
      } else {
        $('#discount-amount').text(discount);
      }

      let total = $('#total-amount').text();

      let newSubtotal = parseFloat(total) - parseFloat(discount);
      sessionStorage.setItem('newSubtotal', newSubtotal);

      $('#subtotal-amount').text(newSubtotal.toFixed(2));

      let shippingCharge;

      if (response.digitalStatus == 0) {
        shippingCharge = $('#shipping-charge-amount').text();
      } else {
        shippingCharge = 0;
      }

      const tax = taxRate;
      let taxAmount = newSubtotal * (tax / 100);
      sessionStorage.setItem('taxAmount', taxAmount);

      $('#tax-amount').text(taxAmount.toFixed(2));

      let newGrandTotal = newSubtotal + parseFloat(shippingCharge) + taxAmount;
      sessionStorage.setItem('grandTotal', newGrandTotal);

      $('#grandtotal-amount').text(newGrandTotal.toFixed(2));

      toastr['success'](response.success);
      $('#coupon-code').val('');
    } else if ('error' in response) {
      toastr['error'](response.error);
    }
  });
}

// validate the card number for stripe payment gateway
function checkCard(cardNumber) {
  let status = Stripe.card.validateCardNumber(cardNumber);

  if (status == false) {
    $('#card-error').html('Invalid card number!');
  } else {
    $('#card-error').html('');
  }
}

// validate the cvc number for stripe payment gateway
function checkCVC(cvcNumber) {
  let status = Stripe.card.validateCVC(cvcNumber);

  if (status == false) {
    $('#cvc-error').html('Invalid cvc number!');
  } else {
    $('#cvc-error').html('');
  }
}
