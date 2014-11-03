$(function() {
    //caches a jQuery object containing the header element
    var header = $("header");
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        var heroHeight = $('.cover-image').outerHeight();
        var headerHeight = $('header').outerHeight();

        if (scroll >= heroHeight - headerHeight) {
            header.addClass('fixed');
        } else {
            header.removeClass("fixed");
        }
    });
});