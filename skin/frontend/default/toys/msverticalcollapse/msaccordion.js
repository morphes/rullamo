jQuery(document).ready(function(){			
	jQuery('#verticalcollapse ul.level0').before('<span class="head"><a href="javascript:void(0)"></a></span>');			
	jQuery('#verticalcollapse li.level0.active').addClass('selected');

	

	// applying the settings			
	jQuery("#verticalcollapse  li  span").click(function(){
		if(false == $mav(this).next('ul').is(':visible')) {
			jQuery('#verticalcollapse ul').slideUp(300);
		}
		jQuery(this).next('.level0').slideToggle(300);
		
		if(jQuery(this).parent().hasClass('selected')) {
			jQuery(this).parent().addClass('unselected');
		}
		
		jQuery('#verticalcollapse li.selected').each(function() {
				jQuery(this).removeClass('selected');
		});
		if(!jQuery(this).parent().hasClass('unselected')) {
			jQuery(this).parent().addClass('selected');
		}
		jQuery('#verticalcollapse li.unselected').each(function() {
				jQuery(this).removeClass('unselected');
		});
	});
});


