/**
 * @author Giulio
 */
jQuery(document).ready(function() 
{
	var qs_picker_open = false;
	jQuery('.quick_search_colorpicker').each(function()
	{
		var qs_wrap = jQuery(this).parent();
		jQuery(this).farbtastic(jQuery('.quick_search_color', qs_wrap))
	});
	
	
	jQuery('.quick_search_colorpicker_button').toggle(
		function() 
		{
			qs_hide_all_colorpicker();
			var qs_context = jQuery(this).parent();
			jQuery('.quick_search_colorpicker', qs_context).fadeIn('fast');
			qs_picker_open = true;
		},
		function() 
		{
			var qs_context = jQuery(this).parent();
			jQuery('.quick_search_colorpicker', qs_context).fadeOut('fast');
			qs_picker_open = false;
		}

	);
	
	jQuery(document).mousedown(function()
	{
		if ( true == qs_picker_open ) return;
		qs_hide_all_colorpicker();
	});
	jQuery(document).mouseup(function()
	{
		qs_picker_open = false;
	});
	
});

function qs_hide_all_colorpicker()
{
	jQuery('.quick_search_colorpicker').each(function()
	{
		jQuery(this).fadeOut('fast');
	});
}
