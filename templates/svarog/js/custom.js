/***************************************************
            PRETTY PHOTO
***************************************************/
jQuery.noConflict()(function($){
$(document).ready(function() {  

$("a[rel^='prettyPhoto']").prettyPhoto({opacity:0.80,default_width:500,default_height:344,theme:'light_rounded',hideflash:false,modal:false});

});
});
/***************************************************
            LIST SLIDER
***************************************************/
jQuery.noConflict()(function($){
        $(document).ready(function() {

            $.featureList(
                $("#tabs li a"),
                $("#output li"), {
                    start_item    :    1
                }
            );
        });
});

    
/***************************************************
            IMAGE HOVER
***************************************************/
jQuery.noConflict()(function($){
    $(document).ready(function() {  
            $('.img-preview').each(function() {
                $(this).hover(
                    function() {
                        $(this).stop().animate({ opacity: 0.5 }, 400);
                    },
                   function() {
                       $(this).stop().animate({ opacity: 1.0 }, 400);
                   })
                });
    });
});

/***************************************************
            SlideOut
***************************************************/
         


jQuery.noConflict()(function($){
    
    $('.social').tipsy({fade: true});
    $('.service-tipsy').tipsy({fade: true, gravity: 's'});
    $('.tooltip').tipsy({title: function() { return this.getAttribute('original-title').toUpperCase(); } });
    
  });         
    