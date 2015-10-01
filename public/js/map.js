var i = -100;
jQuery(document).ready(function(){
    var pointer = jQuery(".pointers .pointer");
    var information = jQuery(".information");
    
    pointer.mouseenter(function(e){
        var data = JSON.parse(jQuery(this).attr('data-content'));
        
        information.css({
            "top": (e.clientY - 50)+"px",
            "left": (e.clientX - 50)+"px"
        }).find(".content_information").fadeIn(1000);
        
        information.find("h3").text(data.title);
        information.find("p").text(data.text);
    });
    
    pointer.mouseleave(function(e){
        information.find(".content_information").fadeOut(1000);
    });
    
   jQuery(".menu-list__title").after('<hr class="separator">');
   
});

function isScrolledIntoView( element ) {
    var elementTop    = element.getBoundingClientRect().top,
        elementBottom = element.getBoundingClientRect().bottom;

    return elementTop >= 0 && elementBottom <= window.innerHeight;
}