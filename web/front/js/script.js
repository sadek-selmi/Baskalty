; (function ($) {
    "use strict";

    $(document).ready(function () {

         /**-----------------------------
         *  Popup
         * ---------------------------*/
        $(".floating-icon-info").on("click", function() {
            $(".info-popup").toggleClass("active");
            $(".location-popup").removeClass("active");
            $(".message-popup").removeClass("active");
        });
        $(".info-popup-content_close").on("click", function() {
            $(this)
                .parent()
                .parent()
                .removeClass("active");
        });

        $(".floating-icon-location").on("click", function() {
            $(".location-popup").toggleClass("active");
            $(".message-popup").removeClass("active");
            $(".info-popup").removeClass("active");
        });
        $(".location-popup-content_close").on("click", function() {
            $(this)
                .parent()
                .parent()
                .removeClass("active");
        });

        $(".floating-icon-message").on("click", function() {
            $(".message-popup").toggleClass("active");
            $(".info-popup").removeClass("active");
            $(".location-popup").removeClass("active");
        });
        $(".message-popup-content_close").on("click", function() {
            $(this)
                .parent()
                .parent()
                .removeClass("active");
        });


        /*--------------------
           wow js init
       ---------------------*/
        new WOW().init();

        /*--------------------
           Nice select
        ---------------------*/
        $('.country_select').niceSelect();


        /*-------------------------------------
            magnific popup video activation
        -------------------------------------*/
        $('.video-play-btn,.video-popup,.small-vide-play-btn').magnificPopup({
            type: 'video'
        });

        $(".about-video-btn").magnificPopup({
            items: {
                src: "https://www.youtube.com/watch?v=j0hcRDypgmY"
            },
            type: "iframe",
            iframe: {
                markup:
                    '<div class="mfp-iframe-scaler">' +
                    '<div class="mfp-close"></div>' +
                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                    "</div>",
                patterns: {
                    youtube: {
                        index: "youtube.com/",
                        id: "v=",
                        src: "//www.youtube.com/embed/j0hcRDypgmY"
                    }
                },
                srcAction: "iframe_src"
            }
        });

        $(".video-btn").magnificPopup({
            items: {
                src: "https://www.youtube.com/watch?v=j0hcRDypgmY"
            },
            type: "iframe",
            iframe: {
                markup:
                    '<div class="mfp-iframe-scaler">' +
                    '<div class="mfp-close"></div>' +
                    '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                    "</div>",
                patterns: {
                    youtube: {
                        index: "youtube.com/",
                        id: "v=",
                        src: "//www.youtube.com/embed/j0hcRDypgmY"
                    }
                },
                srcAction: "iframe_src"
            }
        });


        // Modal close
        $(".alert-area .close-one").click(function(){
            $(".close-one-content").hide();
        });

        $(".alert-area .close-two").click(function(){
            $(".close-two-content").hide();
        });

        $(".alert-area .close-three").click(function(){
            $(".close-three-content").hide();
        });

        $(".alert-area .close-four").click(function(){
            $(".close-four-content").hide();
        });

        $(".alert-area .close-five").click(function(){
            $(".close-five-content").hide();
        });

        $(".info-popup2-content_close").click(function(){
            $(".info-popup2").hide();
        });

        $(".location-popup2-content_close").click(function(){
            $(".location-popup2").hide();
        });

        $(".message-popup2-content_close").click(function(){
            $(".message-popup2").hide();
        });


        /*-------------------------------------
            slick sliders
        -------------------------------------*/

        // breadcrumb slider
        $('.breadcrumb-slider').slick({
            dots: true,
            arrows: false,
        });

        $(".breadcrumb-slider").on("beforeChange", function (event, slick, currentSlide, nextSlide) {
            var firstNumber = check_number(++nextSlide);
            $(".breadcrumb-slider .single-item .slider-top .total").text(firstNumber);
        });

        // banner slider
        $('.banner-slider').slick({
            dots: false,
            arrows: true,
            asNavFor: '.banner-sm-slider',
            fade: true,
            autoplay: true
        });

        $('.banner-slider').slickAnimation();

        $('.banner-slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            var totalSlide = slick.slideCount;
            var currentSlideindex = ++slick.currentSlide;
            var progress = 100 / totalSlide;
            var progressWidth = progress * currentSlideindex;
            ProgressbarStart(progressWidth)
            $('.controler-wrapper .total-controler').text(check_number(totalSlide));
            $('.controler-wrapper .active-controler').text(check_number(currentSlideindex));

        });

        function ProgressbarStart(bannerprogress) {
            $('.progressbar .home-slider-progress-active').css({ 'width': bannerprogress + '%' });
        }

        $('.banner-sm-slider').slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            asNavFor: '.banner-slider',
            dots: false,
            arrows: true,
            focusOnSelect: true,
            centerMode: true,
            centerPadding: 0,
            // fade: true
            // autoplay: true
        });

        // banner slider
        $('.banner-slider2').slick({
            dots: true,
            arrows: true,
            fade: true,
            // autoplay: true
        });

        $('.banner-slider2').slickAnimation();

        // about slider
        $('.home-about-slider').slick({
            dots: true,
            arrows: true,
            slidesToShow: 2
        });

        // trail slider
        $('.trail-slider').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 850,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        autoplay: true
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // blog slider1
        $('.blog-slider1').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 3,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // blog slider2
        $('.blog-slider2').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // blog slider3
        $('.blog-slider3').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // blog right slider
        $('.blog-right-slider').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // quote slider1
        var quoteSlider = $('.quote-slider1');
        quoteSlider.slick({
            dots: false,
            arrows: true,
            fade: true,
            speed: 1500
        });

        //have to write code for bind it with static images
        quoteSlider.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            var liIndex = nextSlide + 1;
            var slideImageliIndex = (slick.slideCount == liIndex) ? liIndex - 1 : liIndex;
            var cart = $('.quote-slider .slick-slide[data-slick-index="' + slideImageliIndex + '"]').find('.image');
            var imgtodrag = $('.quote-image-menu li:nth-child(' + liIndex + ')').find("img").eq(0);
            if (imgtodrag) {
                AnimateTestimonialImage(imgtodrag, cart)
            }
        });

        //have to write code for bind static image to slider accordion to slide index of images

        $(document).on('click', '.quote-image-menu li', function (e) {
            var el = $(this);
            var elIndex = el.prevAll().length;
            quoteSlider.slick('slickGoTo', elIndex);
            var cart = $('.quote-slider .slick-slide[data-slick-index="' + elIndex + '"]').find('.image');
            var imgtodrag = el.find("img").eq(0);
            if (imgtodrag) {
                AnimateTestimonialImage(imgtodrag, cart)
            }

        });

        function AnimateTestimonialImage(imgtodrag, cart) {
            var imgclone = imgtodrag.clone().offset({
                top: imgtodrag.offset().top,
                left: imgtodrag.offset().left
            }).css({
                'opacity': '0.5',
                'position': 'absolute',
                'height': '130px',
                'width': '130px',
                'z-index': '100'
            }).appendTo($('body')).animate({
                'top': cart.offset().top + 10,
                'left': cart.offset().left + 10,
                'width': 130,
                'height': 130
            }, 300);


            imgclone.animate({
                'visibility': 'hidden',
                'opacity': '0'
            }, function () {
                $(this).remove()
            });
        }

        // Quote slider 2
        $('.quote-slider2').slick({
            dots: false,
            arrows: true,
            slidesToShow: 1
        });

        $('.quote-slider2').on("beforeChange", function (event, slick, currentSlide, nextSlide) {
            $(".quote .count").text(check_number(++nextSlide));
        });

        // brand slider
        $('.brand-slider').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 4,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                }
            ]
        });

        // event slider1
        $('.event-slider').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false,
                        arrows: false,
                    }
                }
            ]
        });

        // event slider2
        $('.event-slider2').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        /*-------------------------------------
            Swiper sliders
        -------------------------------------*/
        var swiper = new Swiper('.swiper-container.two', {
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable: true
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 3000,
            },
            speed: 1000,
            effect: 'coverflow',
            loop: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            slidesPerView: 3,
            initialSlide: 3,
            paginationClickable: true,
            observer: true,
            observeParents: true,
            coverflowEffect: {
                rotate: 0,
                stretch: 50,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            }
        });

        /*-------------------------------------
            owl-carousel
        -------------------------------------*/

        $('.owl-carousel').owlCarousel({
            stagePadding: 100,
            loop: true,
            margin: 0,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        })

        //Scroll Down
        $('.scroll a').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top}, 500, 'linear');
        });

        /*-------------------------------------
           Massonry isotop
       -------------------------------------*/
        $(document).ready(function () {

            $('.grid').isotope({
                itemSelector: '.grid-item',
            });

            // filter items on button click
            $('.portfolio-menu').on('click', 'li', function () {
                var filterValue = $(this).attr('data-filter');
                $('.grid').isotope({ filter: filterValue });
                $('.portfolio-menu li').removeClass('active');
                $(this).addClass('active');
            });
        })

        /*-------------------------------
            Portfolio filter 
        ---------------------------------*/
        var $Container = $('.portfolio-masonry');
        if ($Container.length > 0) {
            $('.portfolio-masonry').imagesLoaded(function () {
                var festivarMasonry = $Container.isotope({
                    itemSelector: '.masonry-item', // use a separate class for itemSelector, other than .col-
                    masonry: {
                        gutter: 0
                    }
                });
                $(document).on('click', '.portfolio-menu li', function () {
                    var filterValue = $(this).attr('data-filter');
                    festivarMasonry.isotope({
                        filter: filterValue
                    });
                });
            });
            $(document).on('click', '.portfolio-menu li', function () {
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
            });
        }

        // check_number function
        function check_number(num) {
            var IsInteger = /^[0-9]+$/.test(num);
            return IsInteger ? "0" + num : null;
        }

        /*------------------------------
           counter section activation
       -------------------------------*/
        var counternumber = $('.count-num');
        counternumber.counterUp({
            delay: 20,
            time: 3000
        });

        /*------------------------------
           Tab code
       -------------------------------*/

        // event tab
        $(".tab-accordion ul li").on('click', function () {
            var tabClass = $(this).attr("class");
            $(this).addClass("active").siblings().removeClass("active");
            $("." + tabClass + "-content").addClass("active").siblings().removeClass("active");
        });

        // shop tab
        $(".shop-tab-accordion ul li").on('click', function () {
            var tabClass = $(this).attr("class");
            $(this).addClass("active").siblings().removeClass("active");
            $("." + tabClass + "-content").addClass("active").siblings().removeClass("active");
        });

        $('.slider-tabfor').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.slider-tabnav'
        });
        $('.slider-tabnav').slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            asNavFor: '.slider-tabfor',
            dots: false,
            arrows: true,
            focusOnSelect: true,
            centerMode: true,
            centerPadding: 0
        });

        /* pricing Active */
        var singlepricing = $('.pricing-area .content-bottom')
        
        singlepricing.mouseover(function() {
            singlepricing.removeClass('active');
            $(this).addClass('active');
        });


        /*----------------------
            Search Popup
        -----------------------*/
        var bodyOvrelay = $('#body-overlay');
        var searchPopup = $('#search-popup');

        $(document).on('click', '#body-overlay', function (e) {
            e.preventDefault();
            bodyOvrelay.removeClass('active');
            searchPopup.removeClass('active');
        });
        $(document).on('click', '#search', function (e) {
            e.preventDefault();
            searchPopup.addClass('active');
            bodyOvrelay.addClass('active');
        });


    });

    // hotspot
    $(".lg-hotspot-label").show("fast");
    $(".lg-hotspot-label").hide();

    $(".lg-hotspot-button").click(function () {
        var thisLabel = $(this).siblings(".lg-hotspot-label");
        var thisLabelState = thisLabel.css("display");
        $(".lg-hotspot-label").fadeOut(0).parent().css("z-index", "0");
        if (thisLabelState == "none") {
            thisLabel.fadeIn(0);
            $(this).parent().css("z-index", "999");
        }
    });

    // humberger toggle menu
    $(document).ready(function(){
        $("#menuToggle #menu .submenu").click(function(){
            $(".dropdown").slideToggle();
        });
    });

    // progress bar

    $('.html').rProgressbar({
        percentage: 80,
        height: 3,
        fillBackgroundColor: '#E3051A',
        ShowProgressCount: false
    });
    $('.css').rProgressbar({
        percentage: 90,
        height: 3,
        fillBackgroundColor: '#E3051A',
        ShowProgressCount: false
    });



    $('.php').rProgressbar({
        percentage: 75,
        height: 3,
        fillBackgroundColor: '#E3051A',
        ShowProgressCount: false
    });



    $('.javascript').rProgressbar({
        percentage: 65,
        height: 3,
        fillBackgroundColor: '#E3051A',
        ShowProgressCount: false
    });



    $('.jquery').rProgressbar({
        percentage: 95,
        height: 3,
        fillBackgroundColor: '#E3051A',
        ShowProgressCount: false
    });



    var lastScrollTop = "";
    var floatingIcon = $("#service_info_item");
    $(window).on("scroll", function() {
        /*---------------------------------------
        sticky menu activation && Sticky Icon Bar
        -----------------------------------------*/
        var st = $(this).scrollTop();

        if ($(window).width() < 992) {
            if (st > lastScrollTop) {
                // hide sticky menu on scrolldown
                showFloatingIcon();
            } else {
                // active sticky menu on scrollup
                hideFloatingIcon();
            }
        }
        lastScrollTop = st;
    });

    $(window).on("resize", function(e) {
        e.preventDefault();
        //floatingIcon
        if ($(window).width() < 768) {
            hideFloatingIcon();
        } else {
            showFloatingIcon();
        }
    });

    function hideFloatingIcon() {
        //floatingIcon
        floatingIcon.hide();
    }

    function showFloatingIcon() {
        //floatingIcon
        floatingIcon.show();
    }



})(jQuery);