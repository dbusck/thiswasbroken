$(document).ready(function () {
    $(function() {
        //caches a jQuery object containing the header element
        var header = $("header");
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();
            var heroHeight = $('.cover-image').outerHeight();
            var headerHeight = $('header').outerHeight();

            if (scroll >= heroHeight - (headerHeight + 150) ) {
                header.addClass('fixed');
            } else {
                header.removeClass("fixed");
            }
        });
    });


    // Portrait info popup function
    $('.image-holder').on('click',function(){
        var classElement = $(this).parent();
        
        if ( classElement.hasClass('show') ) {
            classElement.removeClass('show');
        } else {
            $('.person').removeClass('show');
            classElement.addClass('show');
        }
        return false;
    });

    // Remove popup if clicking elsewhere
    $(document).mouseup(function (e) {
        var container = $('.person');

        if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
        container.removeClass('show');
        }
    });


    // Detect anchor links & scroll to position
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 300);
                    return false;
                }
            }
        });
    });

});

/* HeadImage */
var className;
var imageTop;
var imageLeft;
var imageBottom;
var imageRight;

function HeadImage(className){
    
    /* Setting the global instance of classname to the given parameter*/
    this.className = className;
    
    /* Calculating the borders of the image */
    this.imageLeft = $("."+this.className+">.head-image").offset().left;
    this.imageRight = this.imageLeft + $("."+this.className+">.head-image").width();
    this.imageTop = $("."+this.className+">.head-image").offset().top;
    this.imageBottom = this.imageTop + $("."+this.className+">.head-image").height();
    
    /* This function determines where the mouse pointer is relative to the image
     * and displays the correct image accordingly. */
    this.setImageDirection = function(){
        $("."+this.className+">.head-image").removeClass('show');
        if(mouseX >= this.imageLeft && mouseX <= this.imageRight && mouseY <= this.imageTop){
            $("."+this.className+">.up").addClass("show");
        } else if(mouseX < this.imageLeft && mouseY < this.imageTop){
            $("."+this.className+">.up-left").addClass("show");
        } else if(mouseX <= this.imageLeft && mouseY >= this.imageTop && mouseY <= this.imageBottom){
            $("."+this.className+">.left").addClass("show");
        } else if(mouseX < this.imageLeft && mouseY > this.imageBottom){
            $("."+this.className+">.down-left").addClass("show");
        } else if(mouseX >= this.imageLeft && mouseX <= this.imageRight && mouseY >= this.imageBottom){
            $("."+this.className+">.down").addClass("show");
        } else if(mouseX > this.imageRight && mouseY > this.imageBottom){
            $("."+this.className+">.down-right").addClass("show");
        } else if(mouseX >= this.imageRight && mouseY >= this.imageTop && mouseY <= this.imageBottom){
            $("."+this.className+">.right").addClass("show");
        } else if(mouseX > this.imageRight && mouseY < this.imageTop){
            $("."+this.className+">.up-right").addClass("show");
        }
    };
}