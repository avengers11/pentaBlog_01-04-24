/*-----------------------------------------------------------
 * Template Name    : Dreamzy - Responsive Blog HTML Template
 * Author           : KreativDev
 * File Description : This file contains the JavaScript for the actual template, this
					  is the file you need to edit to change the functionality of the
					  template.
 *------------------------------------------------------------

 **	This files table contents are outlined below >>>>>

 *******************************************
 *******************************************
 ** - Sticky header
 ** - Mobile menu
 ** - Navlink active class
 ** - Image to background image
 ** - Sliders
 ** - Password toggle
 ** - Countdown timer
 ** - Sidebar scroll
 ** - Youtube popup
 ** - Go to top
 ** - Lazyload image
 ** - Nice select
 ** - Footer date
 ** - Document on ready
 ** - Preloader
 ** - Aos animation
*/

!(function($) {
    "use strict";

    /*============================================
        Sticky header
    ============================================*/
    $(window).on("scroll", function() {
        var header = $(".header-area");
        // If window scroll down .is-sticky class will added to header
        if ($(window).scrollTop() >= 400) {
            header.addClass("is-sticky");
        } else {
            header.removeClass("is-sticky");
        }
    });


    /*============================================
        Mobile menu
    ============================================*/
    var mobileMenu = function() {
        // Variables
        var body = $("body"),
            mainNavbar = $(".main-navbar"),
            mobileNavbar = $(".mobile-menu"),
            cloneInto = $(".mobile-menu-wrapper"),
            cloneItem = $(".mobile-item"),
            menuToggler = $(".menu-toggler"),
            offCanvasMenu = $("#offcanvasMenu"),
            backdrop,
            _initializeBackDrop = function() {
                backdrop = document.createElement('div');
                backdrop.className = 'menu-backdrop';
                backdrop.onclick = function hideOffCanvas() {
                    menuToggler.removeClass("active"),
                        body.removeClass("mobile-menu-active"),
                        backdrop.remove();
                };
                document.body.appendChild(backdrop);
            };

        menuToggler.on("click", function() {
            $(this).toggleClass("active");
            body.toggleClass("mobile-menu-active");
            _initializeBackDrop();
            if (!body.hasClass("mobile-menu-active")) {
                $('.menu-backdrop').remove();
            }
        })

        mainNavbar.find(cloneItem).clone(!0).appendTo(cloneInto);

        if (offCanvasMenu) {
            body.find(offCanvasMenu).clone(!0).appendTo(cloneInto);
        }

        mobileNavbar.find("li").each(function(index) {
            var toggleBtn = $(this).children(".toggle")
            toggleBtn.on("click", function(e) {
                $(this)
                    .parent("li")
                    .children("ul")
                    .stop(true, true)
                    .slideToggle(350);
                $(this).parent("li").toggleClass("show");
            })
        })

        // check browser width in real-time
        var checkBreakpoint = function() {
            var winWidth = window.innerWidth;
            if (winWidth <= 1199) {
                mainNavbar.hide();
                mobileNavbar.show()
            } else {
                mainNavbar.show();
                mobileNavbar.hide();
                $('.menu-backdrop').remove();
            }
        }
        checkBreakpoint();

        $(window).on('resize', function() {
            checkBreakpoint();
        });
    }
    mobileMenu();

    var getHeaderHeight = function() {
        var headerNext = $(".header-next");
        var header = headerNext.prev(".header-area");
        var headerHeight = header.height();

        headerNext.css({
            "margin-top": headerHeight
        })
    }
    getHeaderHeight();

    $(window).on('resize', function() {
        getHeaderHeight();
    });


    /*============================================
            Navlink active class
        ============================================*/
    var a = $("#mainMenu .nav-link"),
        c = window.location;

    for (var i = 0; i < a.length; i++) {
        const el = a[i];

        if (el.href == c) {
            el.classList.add("active");
        }
    }


    /*============================================
        Image to background image
    ============================================*/
    var bgImage = $(".bg-img")
    bgImage.each(function() {
        var el = $(this),
            src = el.attr("data-bg-image");

        el.css({
            "background-image": "url(" + src + ")",
            "background-repeat": "no-repeat"
        });
    });


    /*============================================
        Password icon toggle
    ============================================*/
    $(".show-password-field").on("click", function() {
        var showIcon = $(this).children(".show-icon");
        var passwordField = $(this).prev("input");
        showIcon.toggleClass("show");
        if (passwordField.attr("type") == "password") {
            passwordField.attr("type", "text")
        } else {
            passwordField.attr("type", "password");
        }
    })


    /*============================================
        Countdown Timer
    ============================================*/
    function makeTimer() {
        var endTime = new Date("May 20, 2024 17:00:00 PDT");
        var endTime = (Date.parse(endTime)) / 1000;
        var now = new Date();
        var now = (Date.parse(now) / 1000);
        var timeLeft = endTime - now;
        var days = Math.floor(timeLeft / 86400);
        var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
        var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600)) / 60);
        var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));
        if (hours < "10") {
            hours = "0" + hours;
        }
        if (minutes < "10") {
            minutes = "0" + minutes;
        }
        if (seconds < "10") {
            seconds = "0" + seconds;
        }
        $("#days .time").html(days);
        $("#hours .time").html(hours);
        $("#minutes .time").html(minutes);
        $("#seconds .time").html(seconds);
    }
    setInterval(function() {
        makeTimer()
    }, 0);


    /*============================================
        Sliders
    ============================================*/
    // Category Slider all
    $(".category-slider").each(function() {
        var id = $(this).attr("id");
        var slidePerView = $(this).data("slides-per-view");
        var loops = $(this).data("swiper-loop");
        var sliderId = "#" + id;

        // console.log(slidePerView);

        var swiper = new Swiper(sliderId, {
            loop: loops,
            spaceBetween: 24,
            speed: 1000,
            autoplay: {
                delay: 3000,
            },
            slidesPerView: slidePerView,
            pagination: true,

            pagination: {
                el: sliderId + "-pagination",
                clickable: true,
            },

            // Navigation arrows
            navigation: {
                nextEl: sliderId + "-next",
                prevEl: sliderId + "-prev",
            },

            breakpoints: {
                320: {
                    slidesPerView: 1
                },
                576: {
                    slidesPerView: 2
                },
                992: {
                    slidesPerView: 3
                },
                1440: {
                    slidesPerView: slidePerView
                },
            }
        })
    })


    // Hero blog Slider
    // var heroBlogSlider = new Swiper(".hero-blog-slider", {
    //     loop: true,
    //     spaceBetween: 24,
    //     speed: 1000,
    //     autoplay: {
    //         delay: 3000,
    //     },
    //     slidesPerView: 4,
    //     pagination: false,

    //     // Navigation arrows
    //     navigation: {
    //         nextEl: "#hero-blog-slider-next",
    //         prevEl: "#hero-blog-slider-prev",
    //     },

    //     breakpoints: {
    //         320: {
    //             slidesPerView: 1
    //         },
    //         576: {
    //             slidesPerView: 2
    //         },
    //         992: {
    //             slidesPerView: 3
    //         },
    //         1440: {
    //             slidesPerView: 4
    //         },
    //     }
    // })
    var heroBlogSlider2 = new Swiper(".hero-blog-slider_v2", {
        loop: true,
        spaceBetween: 24,
        speed: 1000,
        autoplay: {
            delay: 3000,
        },
        slidesPerView: 1,
        pagination: true,

        pagination: {
            el: ".hero-blog-slider_v2_pagination",
            clickable: true,
            renderBullet: function(index, className) {
                return '<span class="' + className + '">' + (index + 1).toString().padStart(2, '0') + "</span>";
            },
        }
    })
    var heroBlogSlider3 = new Swiper(".hero-blog-slider_v3", {
        loop: true,
        spaceBetween: 24,
        speed: 1000,
        autoplay: {
            delay: 3000,
        },
        slidesPerView: 1,
        pagination: false,

        // Navigation arrows
        navigation: {
            nextEl: "#hero-blog-slider-next",
            prevEl: "#hero-blog-slider-prev",
        },
    })
    var heroBlogSlider4 = new Swiper(".hero-blog-slider_v4", {
        loop: true,
        spaceBetween: 24,
        speed: 1000,
        autoplay: {
            delay: 3000,
        },
        slidesPerView: 1,
        pagination: true,

        pagination: {
            el: ".hero-blog-slider_v4-pagination",
            clickable: true,
        },

        // Navigation arrows
        navigation: {
            nextEl: "#hero-blog-slider-next",
            prevEl: "#hero-blog-slider-prev",
        },
    })
    var heroBlogSlider5 = new Swiper(".hero-blog-slider_v5", {
        loop: true,
        spaceBetween: 24,
        speed: 1000,
        autoplay: {
            delay: 3000,
        },
        slidesPerView: 1,
        pagination: true,

        pagination: {
            el: ".hero-blog-slider_v5-pagination",
            clickable: true,
        }
    })

    // Blog slider
    var blogSlider1 = new Swiper(".blog-slider-1", {
        loop: true,
        spaceBetween: 24,
        speed: 1000,
        autoplay: {
            delay: 3000,
        },
        slidesPerView: 6,
        pagination: true,

        pagination: {
            el: ".blog-slider-1-pagination",
            clickable: true,
        },

        breakpoints: {
            320: {
                slidesPerView: 1
            },
            576: {
                slidesPerView: 2
            },
            992: {
                slidesPerView: 4
            },
            1440: {
                slidesPerView: 6
            },
        }
    })
    var blogSlider2 = new Swiper(".blog-slider-2", {
        loop: true,
        spaceBetween: 24,
        speed: 1000,
        autoplay: {
            delay: 3000,
        },
        slidesPerView: 1,
        pagination: true,

        pagination: {
            el: ".blog-slider-2-pagination",
            clickable: true,
        }
    })
    var blogSlider3 = new Swiper(".blog-slider-3", {
        loop: true,
        spaceBetween: 24,
        speed: 1000,
        autoplay: {
            delay: 3000,
        },
        slidesPerView: 1,
        pagination: false,

        // Navigation arrows
        navigation: {
            nextEl: "#blog-slider-3-next",
            prevEl: "#blog-slider-3-prev",
        },
    })
    var blogSlider4 = new Swiper(".blog-slider-4", {
        loop: true,
        spaceBetween: 24,
        speed: 1000,
        autoplay: {
            delay: 3000,
        },
        slidesPerView: 3,
        pagination: true,

        pagination: {
            el: ".blog-slider-4-pagination",
            clickable: true,
        },

        breakpoints: {
            320: {
                slidesPerView: 1
            },
            576: {
                slidesPerView: 2
            },
            992: {
                slidesPerView: 3
            }
        }
    })
    var blogSlider5 = new Swiper(".blog-slider-5", {
        loop: true,
        spaceBetween: 24,
        speed: 1000,
        autoplay: {
            delay: 3000,
        },
        slidesPerView: 3,
        pagination: true,

        // Navigation arrows
        navigation: {
            nextEl: "#blog-slider-5-next",
            prevEl: "#blog-slider-5-prev",
        },

        pagination: {
            el: ".blog-slider-5-pagination",
            clickable: true,
        },

        breakpoints: {
            320: {
                slidesPerView: 1
            },
            576: {
                slidesPerView: 2
            },
            992: {
                slidesPerView: 3
            },
            1200: {
                slidesPerView: 4
            }
        }
    })

    // Stop slider autoplay
    $(document).ready(function() {

        if ($(".swiper").length) {
            var mySwiper = document.querySelector(".swiper").swiper;

            $(".swiper").mouseenter(function() {
                mySwiper.autoplay.stop();
            });

            $(".swiper").mouseleave(function() {
                mySwiper.autoplay.start();
            });
        }
    });


    /*============================================
        Sidebar scroll
    ============================================*/
    $(document).ready(function() {
        $(".widget").each(function() {
            var child = $(this).find(".accordion-body.scroll-y");
            if (child.height() >= 245) {
                child.css({
                    "padding-inline-end": "10px",
                })
            }
        })
    })


    /*============================================
        Youtube popup
    ============================================*/
    $(".youtube-popup").magnificPopup({
        disableOn: 300,
        type: "iframe",
        mainClass: "mfp-3d-unfold",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false,
    })


    /*============================================
        Gallery popup
    ============================================*/
    $('.btn-search').magnificPopup({
        removalDelay: 500,
        callbacks: {
            beforeOpen: function() {
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
        midClick: true
    });


    /*============================================
        Gallery popup
    ============================================*/
    $(".gallery-popup").magnificPopup({
        delegate: 'a',
        type: 'image',
        mainClass: 'mfp-fade',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1]
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
        },
        callbacks: {
            elementParse: function(item) {
                // the class name
                if (item.el.hasClass("video-link")) {
                    item.type = 'iframe';
                } else {
                    item.type = 'image';
                }
            }
        },
        removalDelay: 500, //delay removal by X to allow out-animation
        closeOnContentClick: true,
        midClick: true
    });


    /*============================================
        Go to top
    ============================================*/
    $(window).on("scroll", function() {
        // If window scroll down .active class will added to go-top
        var goTop = $(".go-top");

        if ($(window).scrollTop() >= 200) {
            goTop.addClass("active");
        } else {
            goTop.removeClass("active")
        }
    })
    $(".go-top").on("click", function(e) {
        $("html, body").animate({
            scrollTop: 0,
        }, 0);
    });


    /*============================================
        Lazyload image
    ============================================*/
    var lazyLoad = function() {
        window.lazySizesConfig = window.lazySizesConfig || {};
        window.lazySizesConfig.loadMode = 2;
        lazySizesConfig.preloadAfterLoad = true;

        var lazyContainer = $(".lazy-container");

        if (lazyContainer.children(".lazyloaded")) {
            lazyContainer.addClass("lazy-active")
        } else {
            lazyContainer.removeClass("lazy-active")
        }
    }


    /*============================================
        Nice select
    ============================================*/
    $(".niceselect").niceSelect();

    var selectList = $(".nice-select .list")
    $(".nice-select .list").each(function() {
        var list = $(this).children();
        if (list.length > 5) {
            $(this).css({
                "height": "160px",
                "overflow-y": "scroll"
            })
        }
    })


    /*============================================
        Footer date
    ============================================*/
    var date = new Date().getFullYear();
    $("#footerDate").text(date);


    /*============================================
        Document on ready
    ============================================*/
    $(document).ready(function() {
        lazyLoad();
    })

})(jQuery);

$(window).on("load", function() {
    /*============================================
    Preloader
    ============================================*/
    const delay = 1000;
    $("#preLoader").delay(delay).fadeOut();
})

