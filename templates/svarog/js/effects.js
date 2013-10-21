jQuery(document).ready(function() {

Cufon.replace('.blogname h1', { fontFamily: 'Myriad Pro' });

});

function changeEmbed(embedid){
	$('.embedcontainer').hide();
	$('#embed'+embedid).show();
	$('li.selected').removeClass('selected');
	$('#selector'+embedid).addClass('selected');
}