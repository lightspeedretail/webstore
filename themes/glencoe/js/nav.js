$(document).ready(function(){
    $("#nav > li > a").on("click", function(e){
        if($(this).parent().has("ul")) {
            e.preventDefault();
        }

        if(!$(this).hasClass("open")) {
            // hide any open menus and remove all other classes
            $("#nav li ul").slideUp(350);
            $("#nav li a").removeClass("open");

            // open our new menu and add the open class
            $(this).next("ul").slideDown(350);
            $(this).addClass("open");
        }

        else if($(this).hasClass("open")) {
            $(this).removeClass("open");
            $(this).next("ul").slideUp(350);
        }
    });
});