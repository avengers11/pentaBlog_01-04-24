"use strict";
new LazyLoad();

$(window).on('load', function (event) {
    // popup js
    if ($('.popup-wrapper').length > 0) {
      let $firstPopup = $('.popup-wrapper').eq(0);

      appearPopup($firstPopup);
    }

    // Preloader JS
    $('.preloader').delay(500).fadeOut('500');

  });

  function appearPopup($this) {
    let closedPopups = [];
    if (sessionStorage.getItem('closedPopups')) {
      closedPopups = JSON.parse(sessionStorage.getItem('closedPopups'));
    }

    // if the popup is not in closedPopups Array
    if (closedPopups.indexOf($this.data('popup_id')) == -1) {
      $('#' + $this.attr('id')).show();
      let popupDelay = $this.data('popup_delay');

      setTimeout(function () {
        jQuery.magnificPopup.open({
          items: { src: '#' + $this.attr('id') },
          type: 'inline',
          callbacks: {
            afterClose: function () {
              // after the popup is closed, store it in the sessionStorage & show next popup
              closedPopups.push($this.data('popup_id'));
              sessionStorage.setItem('closedPopups', JSON.stringify(closedPopups));

              if ($this.next('.popup-wrapper').length > 0) {
                appearPopup($this.next('.popup-wrapper'));
              }
            }
          }
        }, 0);
      }, popupDelay);
    } else {
      if ($this.next('.popup-wrapper').length > 0) {
        appearPopup($this.next('.popup-wrapper'));
      }
    }
  }
