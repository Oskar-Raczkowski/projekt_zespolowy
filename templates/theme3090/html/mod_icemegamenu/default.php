<?php
/**
 * IceMegaMenu Extension for Joomla 3.0 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2012 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/icemegamenu.html
 * @Support 	http://www.icetheme.com/Forums/IceMegaMenu/
 *
 */



$icemegamenu->render($params, 'modIceMegaMenuXMLCallback');
?>
<script>
	jQuery(function($){
 		if($('body').hasClass('desktop_mode') || ($('body').hasClass('mobile') && screen.width>767)){
		  $('.icemegamenu li>a').click(function(){
		   var link = $(this);
		   if(link.closest('li').hasClass("parent")){
		    if(link.closest('li').hasClass("hover")){
		     if(link.attr('href').length){
		      window.location = link.attr('href')
		     }
		    }
		    else{
		     $('.icemegamenu li.parent').not(link.closest('li').parents('li')).not(link.closest('li')).removeClass('hover');
		     link.closest('li').addClass('hover').attr('data-hover','true')
		     link.closest('li').find('>ul.icesubMenu').addClass('visible')
		     return false;
		    }
		   	}
			})
		}
		else{
			$('#icemegamenu li.parent[class^="iceMenuLiLevel"]').hover(function(){
				$('#icemegamenu li.parent[class^="iceMenuLiLevel"]').not($(this).parents('li')).not($(this)).removeClass('hover');
				$(this).addClass('hover').attr('data-hover','true')
				$(this).find('>ul.icesubMenu').addClass('visible')
			},
			function(){
				$(this).attr('data-hover','false')
				$(this).delay(800).queue(function(n){
					if($(this).attr('data-hover') == 'false'){
						$(this).removeClass('hover').delay(250).queue(function(n){
							if($(this).attr('data-hover') == 'false'){
								$(this).find('>ul.icesubMenu').removeClass('visible')
							}
							n();
						});
					}
					n();
				})
			})
		}
		if(screen.width>767){
			$(window).load(function(){
				$('#icemegamenu').parents('[id*="-row"]').scrollToFixed({minWidth: 768});
			})
		}
	});
</script>