jQuery(document).ready(function(){
    var pointer = jQuery(".pointers .pointer");
    var information = jQuery(".information");
    
    pointer.mouseenter(function(e){
        var data = JSON.parse(jQuery(this).attr('data-content'));
        
        information.css({
            "top": (e.clientY - 50)+"px",
            "left": (e.clientX - 50)+"px",
            "display": "block"
        }).find(".content_information").css("opacity","1");
        
        information.find("h3").text(data.title);
        information.find("p").text(data.text);
    });
    
    pointer.mouseleave(function(e){
        information.css({
            "display": "none"
        }).find(".content_information").css("opacity","0");
    });
});