jQuery(document).ready(function(){
    $('.thumbnail a').attr('rel', 'gallery').colorbox({
        maxWidth	: '80%',
        maxHeight	: '80%',
        opacity		: '0.2', 
        loop		: false,
        fixed		: true
    });	
});
