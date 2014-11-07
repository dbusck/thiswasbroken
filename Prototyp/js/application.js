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




/*
Filename: HeadImage.js
Project: rotating heads
Type: javascript
Author: Jan Dellsperger
Initial Version: 14. October 2013

This is the class for the head-image object. Per rotating head one headImage
object has to be instanciated.

Changelog:
23.10.2013 - 1px where everyones front picture was shown removed
*/
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
        $("."+this.className+">.head-image").css("z-index","0");
        if(mouseX >= this.imageLeft && mouseX <= this.imageRight && mouseY <= this.imageTop){
            $("."+this.className+">.up").css("z-index","1");
        } else if(mouseX < this.imageLeft && mouseY < this.imageTop){
            $("."+this.className+">.up-left").css("z-index","1");
        } else if(mouseX <= this.imageLeft && mouseY >= this.imageTop && mouseY <= this.imageBottom){
            $("."+this.className+">.left").css("z-index","1");
        } else if(mouseX < this.imageLeft && mouseY > this.imageBottom){
            $("."+this.className+">.down-left").css("z-index","1");
        } else if(mouseX >= this.imageLeft && mouseX <= this.imageRight && mouseY >= this.imageBottom){
            $("."+this.className+">.down").css("z-index","1");
        } else if(mouseX > this.imageRight && mouseY > this.imageBottom){
            $("."+this.className+">.down-right").css("z-index","1");
        } else if(mouseX >= this.imageRight && mouseY >= this.imageTop && mouseY <= this.imageBottom){
            $("."+this.className+">.right").css("z-index","1");
        } else if(mouseX > this.imageRight && mouseY < this.imageTop){
            $("."+this.className+">.up-right").css("z-index","1");
        } else{
            $("."+this.className+">.front").css("z-index","1");
            $(".text-holder").css("display","none");
            $("."+this.className+".text-holder").css("display","block");
        }
    };
}

/* Declaring the global variables */
var mouseX;
var mouseY;

/* Calling the initialization function */
$(init);

/* The images need to re-initialize on load and on resize, or else the areas
 * where each image is displayed will be wrong. */
$(window).load(init);
$(window).resize(init);

/* Setting the mousemove event caller */
$(window).mousemove(getMousePosition);

/* This function is called on document ready, on load and on resize
 * and initiallizes all the images */
function init(){
    
    /* Instanciate the mouse position variables */
    mouseX = 0;
    mouseY = 0;
    
    /* Instanciate a HeadImage class for every image */
    douglas = new HeadImage("douglas");
    mathias = new HeadImage("mathias");
    niklas = new HeadImage("niklas");
    katie = new HeadImage("katie");
    max = new HeadImage("max");
}

/* This function is called on mouse move and gets the mouse position. 
 * It also calls the HeadImage function to display the correct image*/
function getMousePosition(event){
    
    /* Setting the mouse position variables */
    mouseX = event.pageX;
    mouseY = event.pageY;
    
    /*Calling the setImageDirection function of the HeadImage class
     * to display the correct image*/

    douglas.setImageDirection();
    mathias.setImageDirection();
    niklas.setImageDirection();
    katie.setImageDirection();
    max.setImageDirection();
}

