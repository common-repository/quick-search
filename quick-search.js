/**
 * @author Giulio
 */
jQuery(document).ready(function() 
{
	var quick_search_form 	= jQuery("#" + quick_search_settings.form_id);
	var quick_search_input 	= jQuery("input:text", quick_search_form);
	
	jQuery("input:submit", quick_search_form).css("display", "none");
	jQuery(quick_search_input).attr('autocomplete', 'off');
	jQuery(quick_search_input).css('padding-right', '18px');
	
	var quick_search_input_offset = jQuery(quick_search_input).offset();
	jQuery(quick_search_input).wrap('<div style="position: relative; text-align: left"></div>');
	
	jQuery(quick_search_input).parent().append('<div id="quick_search_result"></div>');

	var quick_search_popup = jQuery("#quick_search_result");
	jQuery(quick_search_popup).css(
	{
		'width'		: quick_search_settings.menu_width,
		'position'	: 'absolute',
		'z-index'	: '9999',
		'display'	: 'none',
		'top'		: jQuery(quick_search_input).height() + 4,
		'background': quick_search_settings.menu_bgcolor
	});

	jQuery(document).click(function() 
	{
		jQuery(quick_search_popup).css('display', 'none');
	});
	
	jQuery(quick_search_input).keyup(function() 
	{
		var terms = jQuery(this).attr("value")
		jQuery(quick_search_input).addClass("quick_search_loading");
		if(terms != '')
		{
			jQuery.get(quick_search_settings.base_url + "/wp-content/plugins/quick-search/search.php?s=" + terms, function(result) 
			{
				jQuery(quick_search_popup).css('display', 'block');
				jQuery(quick_search_popup).html(result);
				jQuery(quick_search_input).removeClass("quick_search_loading");
			});
		}
		else
		{
			jQuery(quick_search_popup).css('display', 'none');
			jQuery(quick_search_input).removeClass("quick_search_loading");
		}
		
	});
	
});
