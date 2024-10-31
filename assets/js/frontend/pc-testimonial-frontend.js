(function ($, window, document, undefined) {
    'use strict';
    
    var pc_ajax_url = pc_frontend.pc_ajax;
    
    $('.cd-testimonials-wrapper').flexslider({
        selector: ".cd-testimonials > li",
        animation: "slide",
        controlNav: false,
        slideshow: false,
        smoothHeight: true,
        start: function () {
            $('.cd-testimonials').children('li').css({
                'opacity': 1,
                'position': 'relative'
            });
        }
    });

    //open the testimonials modal page
    $(document).on('click','.cd-see-all', function (e) {
        e.preventDefault();
        $('.cd-testimonials-all').addClass('is-visible');
    });

    //close the testimonials modal page
        $(document).on('click','.cd-testimonials-all .close-btn', function (e) {
        $('.cd-testimonials-all').removeClass('is-visible');
        e.preventDefault();
    });
    $(document).keyup(function (event) {
        //check if user has pressed 'Esc'
        if (event.which == '27') {
            $('.cd-testimonials-all').removeClass('is-visible');
        }
    });

    //build the grid for the testimonials modal page
    $('.cd-testimonials-all-wrapper').children('ul').masonry({
        itemSelector: '.cd-testimonials-item'
    });



})(jQuery, window, document);
