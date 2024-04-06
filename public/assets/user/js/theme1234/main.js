"use strict";
var olimaDoc;

(function ($) {

  olimaDoc = {
    init: function () {
      this.mainMenu();
      this.video_slide();
    },
    //===== 01. Main Menu
    mainMenu() {
      // Variables
      var var_window = $(window),
        navContainer = $('.nav-container'),
        pushedWrap = $('.nav-pushed-item'),
        pushItem = $('.nav-push-item'),
        pushedHtml = pushItem.html(),
        pushBlank = '',
        navbarToggler = $('.navbar-toggler'),
        navMenu = $('.nav-menu'),
        navMenuLi = $('.nav-menu ul li ul li'),
        closeIcon = $('.navbar-close');

      // navbar toggler
      navbarToggler.on('click', function () {
        navbarToggler.toggleClass('active');
        navMenu.toggleClass('menu-on');
      });

      // close icon
      closeIcon.on('click', function () {
        navMenu.removeClass('menu-on');
        navbarToggler.removeClass('active');
      });

      // adds toggle button to li items that have children
      navMenu.find('li a').each(function () {
        if ($(this).next().length > 0) {
          $(this)
            .parent('li')
            .append(
              '<span class="dd-trigger"><i class="fas fa-angle-down"></i></span>'
            );
        }
      });

      // expands the dropdown menu on each click
      navMenu.find('li .dd-trigger').on('click', function (e) {
        e.preventDefault();
        $(this)
          .parent('li')
          .children('ul')
          .stop(true, true)
          .slideToggle(350);
        $(this).parent('li').toggleClass('active');
      });

      // check browser width in real-time
      function breakpointCheck() {
        var windoWidth = window.innerWidth;
        if (windoWidth <= 991) {
          navContainer.addClass('breakpoint-on');
          pushedWrap.html(pushedHtml);
          pushItem.hide();
        } else {
          navContainer.removeClass('breakpoint-on');
          pushedWrap.html(pushBlank);
          pushItem.show();
        }
      }

      breakpointCheck();
      var_window.on('resize', function () {
        breakpointCheck();
      });
    },

    video_slide() {
      var mySwiperOne = new Swiper('.video_slide_v2', {
        slidesPerView: 3.2,
        centeredSlides: true,
        loop: true,
        effect: 'coverflow',
        spaceBetween: 0,
        speed: 700,
        grabCursor: true,
        autoplay: true,
        coverflowEffect: {
          rotate: 0,
          stretch: 100,
          depth: 160,
          modifier: 1,
          slideShadows: false
        },
        navigation: {
          nextEl: '.video_slide_v2_nav .swiper-button-next',
          prevEl: '.video_slide_v2_nav .swiper-button-prev'
        },
        breakpoints: {
          1199: {
            coverflowEffect: {
              stretch: 57
            }
          },
          991: {
            slidesPerView: 2.8,
            coverflowEffect: {
              stretch: 50
            }
          },
          767: {
            slidesPerView: 2,
            coverflowEffect: {
              stretch: 50
            }
          },
          450: {
            slidesPerView: 1.4,
            coverflowEffect: {
              stretch: 50
            },
          },
          320: {
            slidesPerView: 1,
            coverflowEffect: {
              stretch: 25
            },
          }
        }
      });
    },
  };

  // Document Ready
  $(document).ready(function () {
    olimaDoc.init();
  });

  // format date & time for announcement popup
  $('.offer-timer').each(function () {
    let $this = $(this);

    let date = new Date($this.data('end_date'));
    let year = parseInt(new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date));
    let month = parseInt(new Intl.DateTimeFormat('en', { month: 'numeric' }).format(date));
    let day = parseInt(new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date));

    let time = $this.data('end_time');
    time = time.split(':');
    let hour = parseInt(time[0]);
    let minute = parseInt(time[1]);

    $this.syotimer({
      year: year,
      month: month,
      day: day,
      hour: hour,
      minute: minute
    });
  });

  //magnific-popup js
  $('.play_btn').magnificPopup({
    type: 'image',
    removalDelay: 300,
    mainClass: 'mfp-fade',
    gallery: {
      enabled: true
    }
  });

  $('.img-popup').magnificPopup({
    type: 'image',
    gallery: {
      enabled: true
    }
  });

  // Show or Hide The 'Back To Top' Button
  $(window).on('scroll', function () {
    if ($(this).scrollTop() > 600) {
      $('.back-to-top').stop().fadeIn();
    } else {
      $('.back-to-top').stop().fadeOut();
    }
  });

  // Animate The 'Back To Top'
  $('.back-to-top').on('click', function (event) {
    event.preventDefault();

    $('html, body').animate({
      scrollTop: 0
    }, 1500);
  });

  // slick slider
  $('.hero_post_slide_v1').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    slidesToShow: 1,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="arrow prev"><span><i class="flaticon-back"></i>' + previous + '</span></div>',
    nextArrow: '<div class="arrow next"><span>' + next + '<i class="flaticon-right"></i></span></div>',
    centerMode: $('.hero_post_slide_v1 .grid_item').length > 1 ? true : false,
    variableWidth: $('.hero_post_slide_v1 .grid_item').length > 1 ? true : false,
    responsive: [{
      breakpoint: 1024,
      settings: {
        arrows: false,
      }
    },
    {
      breakpoint: 600,
      settings: {
        arrows: false,
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
      }
    }]
  });

  $('.hero_post_slide_v2').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    slidesToShow: 3,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="arrow prev"><span><i class="flaticon-back"></i>' + previous + '</span></div>',
    nextArrow: '<div class="arrow next"><span>' + next + '<i class="flaticon-right"></i></span></div>',
    centerMode: $('.hero_post_slide_v2 .grid_item').length > 3 ? true : false,
    focusOnSelect: true,
    responsive: [{
      breakpoint: 1200,
      settings: {
        slidesToShow: 1,
        arrows: false,
      }
    },
    {
      breakpoint: 480,
      settings: {
        centerMode: false,
        slidesToShow: 1,
        arrows: false,
      }
    }
    ]
  });

  $('.hero_post_slide_v3').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    slidesToShow: 3,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="arrow prev"><span><i class="fas fa-angle-left"></i></span></div>',
    nextArrow: '<div class="arrow next"><span><i class="fas fa-angle-right"></i></span></div>',
    centerMode: $('.hero_post_slide_v3 .grid_item').length > 3 ? true : false,
    variableWidth: $('.hero_post_slide_v3 .grid_item').length > 3 ? true : false,
    responsive: [{
      breakpoint: 1300,
      settings: {
        arrows: false,
        slidesToShow: 2,
      }
    },
    {
      breakpoint: 992,
      settings: {
        arrows: false,
        slidesToShow: 1,
      }
    }
    ]
  });

  $('.hero_post_slide_v4').slick({
    dots: false,
    arrows: true,
    autoplay: false,
    autoplaySpeed: 2500,
    slidesToShow: 3,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><span><i class="fas fa-angle-left"></i></span></div>',
    nextArrow: '<div class="next"><span><i class="fas fa-angle-right"></i></span></div>',
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 2,
          arrows: false
        }
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
          arrows: false
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          arrows: false
        }
      }
    ]
  });

  $('.latest-slider-one').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><span><i class="fas fa-angle-left"></i></span></div>',
    nextArrow: '<div class="next"><span><i class="fas fa-angle-right"></i></span></div>',
    slidesToShow: 1,
    slidesToScroll: 1
  });

  $('.latest-slider-two').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><span><i class="fas fa-angle-left"></i></span></div>',
    nextArrow: '<div class="next"><span><i class="fas fa-angle-right"></i></span></div>',
    slidesToShow: 1,
    slidesToScroll: 1
  });

  $('.categories_slide').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="arrow prev"><span><i class="fas fa-angle-left"></i></span></div>',
    nextArrow: '<div class="arrow next"><span><i class="fas fa-angle-right"></i></span></div>',
    slidesToShow: 5,
    slidesToScroll: 1,
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        arrows: false,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 3,
        arrows: false,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 2,
        arrows: false,
      }
    }
    ]
  });

  $('.about-slider-one').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2500,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><span><i class="fas fa-angle-left"></i></span></div>',
    nextArrow: '<div class="next"><span><i class="fas fa-angle-right"></i></span></div>',
    slidesToShow: 3,
    slidesToScroll: 1,
    variableWidth: true
  });


  $('.video_slide_v1').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    centerMode: $('.video_slide_v1 .grid_item').length > 5 ? true : false,
    slidesToShow: 5,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="arrow prev"><span><i class="flaticon-back"></i>' + previous + '</span></div>',
    nextArrow: '<div class="arrow next"><span>' + next + '<i class="flaticon-right"></i></span></div>',
    focusOnSelect: true,
    responsive: [{
      breakpoint: 1400,
      settings: {
        slidesToShow: 3
      }
    },
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 3,
        arrows: false,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        arrows: false,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        arrows: false,
      }
    }
    ]
  });

  $('.video_big_slide').slick({
    dots: false,
    arrows: false,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2500,
    rtl: langDir == 1 ? true : false,
    asNavFor: '.video_thumb_slide',
    slidesToShow: 1,
    slidesToScroll: 1
  });

  var videoThumbSlider = $(".video_thumb_slide");
  videoThumbSlider.slick({
    dots: false,
    arrows: false,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2500,
    focusOnSelect: true,
    vertical: true,
    asNavFor: '.video_big_slide',
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [{
      breakpoint: 1400,
      settings: {
        slidesToShow: 3
      }
    },
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3
      }
    },
    ]
  });

  videoThumbSlider.on('wheel', (function (e) {
    e.preventDefault();
    if (e.originalEvent.deltaY < 0) {
      $(this).slick('slickPrev');
    } else {
      $(this).slick('slickNext');
    }
  }));

  $('.testimonial_slide_one').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2500,
    slidesToShow: 2,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><span><i class="fas fa-angle-left"></i></span></div>',
    nextArrow: '<div class="next"><span><i class="fas fa-angle-right"></i></span></div>',
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1
      }
    }
    ]
  });

  $('.sponsor_slide_one').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2500,
    slidesToShow: 4,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><span><i class="fas fa-angle-left"></i></span></div>',
    nextArrow: '<div class="next"><span><i class="fas fa-angle-right"></i></span></div>',
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 3
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1
      }
    }
    ]
  });

  $('.related_product_slide').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    slidesToShow: 3,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="next"><i class="fas fa-angle-right"></i></div>',
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1
      }
    }
    ]
  });

  $('.related_post_slide,.post-gallery-slider').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    slidesToShow: 2,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="next"><i class="fas fa-angle-right"></i></div>',
    responsive: [
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1
        }
      }
    ]
  });

  $('.blog_details_slide').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="arrow prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="arrow next"><i class="fas fa-angle-right"></i></div>',
    slidesToShow: 1,
    slidesToScroll: 1
  });

  // Related item slider
  $('.releted_post_slide').slick({
    dots: false,
    arrows: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2500,
    slidesToShow: 3,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1
        }
      }
    ]
  });

  $('.shop_big_slide').slick({
    dots: false,
    arrows: true,
    autoplay: true,
    autoplaySpeed: 2500,
    rtl: langDir == 1 ? true : false,
    asNavFor: '.shop_thumb_slide',
    prevArrow: '<div class="prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="next"><i class="fas fa-angle-right"></i></div>',
    slidesToShow: 1,
    slidesToScroll: 1
  });

  $('.shop_thumb_slide').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2500,
    focusOnSelect: true,
    rtl: langDir == 1 ? true : false,
    asNavFor: '.shop_big_slide',
    prevArrow: '<div class="arrow prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="arrow next"><i class="fas fa-angle-right"></i></div>',
    slidesToShow: 3,
    slidesToScroll: 1
  });

  $('.instagram_slide_v1').slick({
    dots: false,
    arrows: true,
    autoplay: false,
    autoplaySpeed: 1500,
    slidesToShow: 6,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="next"><i class="fas fa-angle-right"></i></div>',
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 3
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1
      }
    }
    ]
  });

  $('.instagram_slide_v2').slick({
    dots: false,
    arrows: true,
    autoplay: false,
    autoplaySpeed: 2500,
    slidesToShow: 5,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false,
    prevArrow: '<div class="prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="next"><i class="fas fa-angle-right"></i></div>',
    responsive: [{
      breakpoint: 1024,
      settings: {
        slidesToShow: 3
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1
      }
    }
    ]
  });

  // gallery js
  $(".gallery-single").magnificPopup({
    type: "image",
    gallery: {
      enabled: true
    }
  });

  //isotope js
  $('#blog_masonry').imagesLoaded(function () {
    var $grid = $('.masonry_grid').isotope({
      itemSelector: '.grid_column',
      percentPosition: true,
      masonry: {
        columnWidth: 1
      }
    });
  });

  $('#highlights_post').imagesLoaded(function () {
    var $grid = $('.masonry_grid').isotope({
      itemSelector: '.grid_column',
      percentPosition: true,
      masonry: {
        columnWidth: 1
      }
    });

    $('.post_filter').on('click', 'button', function () {
      var filterValue = $(this).attr('data-filter');
      $grid.isotope({
        filter: filterValue
      });
    });

    $('.post_filter').each(function (i, buttonGroup) {
      var $buttonGroup = $(buttonGroup);
      $buttonGroup.on('click', 'button', function () {
        $buttonGroup.find('.active_btn').removeClass('active_btn');
        $(this).addClass('active_btn');
      });
    });
  });

  $('#latest_post_v2').imagesLoaded(function () {
    var $grid = $('.masonry_grid').isotope({
      itemSelector: '.grid_column',
      percentPosition: true,
      masonry: {
        columnWidth: 1
      }
    });
  });

  // nice slect init
  $('.olima_select').niceSelect();

  // nice number
  $($('.nice-input')).niceNumber({
    buttonDecrement: '<i class="fas fa-angle-left"></i>',
    buttonIncrement: '<i class="fas fa-angle-right"></i>',
    onDecrement: function ($currentInput, amount, settings) {
      if (amount == 0) {
        $currentInput.val(1)
      }
    }
  });



  //  Quantity Increment

  $(document).on('click', '.qtyMinus', function () {
    var numProduct = Number($(this).next().val());
    if (numProduct > 0) $(this).next().val(numProduct - 1);
  });

  $(document).on('click', '.qtyPlus', function () {
    var numProduct = Number($(this).prev().val());
    $(this).prev().val(numProduct + 1);
  });


  // search post by category
  $('ul.categories li a').on('click', function (e) {
    e.preventDefault();

    let value = $(this).data('category_id');
    $('#categoryKey').val(value);
    $('#submitBtn').trigger('click');
  });

  // search product by sorting
  $('#sort-type').on('change', function () {
    let value = $(this).val();

    $('#sortKey').val(value);
    $('#submitBtn').trigger('click');
  });


  // search product by typing product title in the search-box
  $('#search-input').keypress(function (e) {
    if (e.which == 13) {
      let value = $(this).val();

      if (value == '') {
        alert('Please enter something.');
      } else {
        $('#searchKey').val(value);
        $('#submitBtn').trigger('click');
      }
    }
  });

  $('.upload').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      $('.user-photo').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });

  $('#bookmark-table').DataTable({
    ordering: false,
    responsive: true
  });

  $('#order-table').DataTable({
    ordering: false,
    responsive: true
  });

  // lazyload init
  new LazyLoad();


})(window.jQuery);


$(window).on('load', function (event) {
  // popup js
  if ($('.popup-wrapper').length > 0) {
    let $firstPopup = $('.popup-wrapper').eq(0);

    appearPopup($firstPopup);
  }

  // Preloader JS
  $('.preloader').delay(500).fadeOut('500');

  $('#masonry-gallery').imagesLoaded(function () {
    // items on button click
    $('.filter-btn').on('click', 'li', function () {
      var filterValue = $(this).attr('data-filter');
      $grid.isotope({
        filter: filterValue
      });
    });

    // menu active class
    $('.filter-btn li').on('click', function (e) {
      $(this).siblings('.active').removeClass('active');
      $(this).addClass('active');
      e.preventDefault();
    });

    var $grid = $('.masonry-row').isotope({
      itemSelector: '.gallery-column',
      percentPosition: true,
      originLeft: langDir == 1 ? false : true,
      masonry: {
        columnWidth: 1
      }
    });
  });
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
