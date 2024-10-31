<?php

/*
Plugin Name: Quick Search
Plugin URI: http://giulio.ganci.eu/2009/05/30/quick-search/
Description: Quick Search add AJAX Search to your site sorting results by Posts, Page and Comments
Version: 1.2
Author: Giulio Ganci
Author URI: http://giulio.ganci.eu/
*/

register_activation_hook(__FILE__,'quick_search_init');
if(isset($_REQUEST["qs_uninstall"])) register_deactivation_hook(__FILE__, 'quick_search_uninstall');
load_plugin_textdomain( 'quick-search', FALSE, '/quick-search/languages' );
add_action('admin_menu', 'quick_search_menu');
add_filter('plugin_action_links', 'quick_search_plugin_action', 10, 2);
add_action('template_redirect', 'quick_search_required_files');


function quick_search_init()
{
	add_option('quick_search_form_id', 'searchform', '', 'no');
	add_option('quick_search_max_chars', '50', '', 'no');

	add_option('quick_search_show_posts', '1', '', 'no');
	add_option('quick_search_show_pages', '1', '', 'no');
	add_option('quick_search_show_comments', '1', '', 'no');
	add_option('quick_search_posts_limit', '4', '', 'no');
	add_option('quick_search_pages_limit', '4', '', 'no');
	add_option('quick_search_comments_limit', '4', '', 'no');
	
	add_option('quick_search_menu_width', '200', '', 'no');
	add_option('quick_search_menu_bgcolor', '#efefef', '', 'no');
	add_option('quick_search_menu_label_bgcolor', '#A0A0A0', '', 'no');
	add_option('quick_search_menu_label_color', '#efefef', '', 'no');

}

function quick_search_uninstall()
{
	delete_option('quick_search_form_id');
	delete_option('quick_search_max_chars');
	
	delete_option('quick_search_show_posts');
	delete_option('quick_search_show_pages');
	delete_option('quick_search_show_comments');
	delete_option('quick_search_posts_limit');
	delete_option('quick_search_pages_limit');
	delete_option('quick_search_comments_limit');
	
	delete_option('quick_search_menu_width');
	delete_option('quick_search_menu_bgcolor');
	delete_option('quick_search_menu_label_bgcolor');
	delete_option('quick_search_menu_label_color');
}

function quick_search_menu() 
{
	add_options_page(__('Quick Search Settings', 'quick-search'), 'Quick Search', 'manage_options','quick-search', 'quick_search_settings');
	wp_enqueue_script( 'farbtastic' );
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script('quick-search-admin', '/' . PLUGINDIR . '/quick-search/quick-search-admin.js', array('jquery'), '1.0');
	wp_register_style('quick-search-admin', WP_PLUGIN_URL . '/quick-search/quick-search-admin.css', array(), '1.0', 'screen');
	wp_enqueue_style( 'quick-search-admin' );
}

function quick_search_required_files()
{
	wp_register_style('quick-search', WP_PLUGIN_URL . '/quick-search/quick-search.css', array(), '1.0', 'screen');
	wp_enqueue_style( 'quick-search' );
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('quick-search', '/' . PLUGINDIR . '/quick-search/quick-search.js', array('jquery'), '1.0');
	wp_localize_script('quick-search', 'quick_search_settings', array
		(
			'base_url' 		=> get_option('home'),
			'form_id'		=> ((get_option('quick_search_form_id') != '') ? get_option('quick_search_form_id') : "searchform"),
			'menu_width'	=> ((get_option('quick_search_menu_width') != '') ? get_option('quick_search_menu_width') . "px" : "200px"),
			'menu_bgcolor'	=> ((get_option('quick_search_menu_bgcolor') != '') ? get_option('quick_search_menu_bgcolor') : "#efefef")
		)
	);
}

function quick_search_plugin_action($links, $file) 
{
	static $this_plugin;
	
	if( empty($this_plugin) ) $this_plugin = plugin_basename(__FILE__);

	if ( $file == $this_plugin ) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=quick-search' ) . '">' . __('Settings', 'quick-search') . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

function quick_search_settings() 
{
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="5781051">
		<h2>
			<?php _e('Quick Search Settings', 'quick-search'); ?>
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="vertical-align:middle;">
		</h2>
		<img alt="" border="0" src="https://www.paypal.com/it_IT/i/scr/pixel.gif" width="1" height="1">
		</form>
		
		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options'); ?>
			
			<h3><?php _e("Popup menu params", "quick-search"); ?></h3>
			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php _e("Form ID", "quick-search"); ?></th>
					<td>
						<input type="text" name="quick_search_form_id" value="<?php echo get_option('quick_search_form_id'); ?>" />
						<span class="setting-description"><?php _e("don't change it if you don't know.", "quick-search"); ?></span>
					</td>
					
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e("Content MAX chars", "quick-search"); ?></th>
					<td>
						<input type="text" name="quick_search_max_chars" value="<?php echo get_option('quick_search_max_chars'); ?>" />
						<span class="setting-description"><?php _e("MAX number of char to show on each single result", "quick-search"); ?></span>
					</td>
				</tr>
				
			</table>
			
			<h3><?php _e("Popup menu style", "quick-search"); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e("Menu width", "quick-search"); ?></th>
					<td>
						<input type="text" name="quick_search_menu_width" value="<?php echo get_option('quick_search_menu_width'); ?>" />
						<span class="setting-description">px</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e("Menu background color", "quick-search"); ?></th>
					<td>
						<input type="text" size="7" name="quick_search_menu_bgcolor" class="quick_search_color" value="<?php echo get_option('quick_search_menu_bgcolor'); ?>" />
						<span class="quick_search_colorpicker_button"></span>
						<div class="quick_search_colorpicker"></div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e("Menu Label background color", "quick-search"); ?></th>
					<td>
						<input type="text" size="7" name="quick_search_menu_label_bgcolor" class="quick_search_color" value="<?php echo get_option('quick_search_menu_label_bgcolor'); ?>" />
						<span class="quick_search_colorpicker_button"></span>
						<div class="quick_search_colorpicker"></div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e("Menu Label color", "quick-search"); ?></th>
					<td>
						<input type="text" size="7" name="quick_search_menu_label_color" class="quick_search_color" value="<?php echo get_option('quick_search_menu_label_color'); ?>" />
						<span class="quick_search_colorpicker_button"></span>
						<div class="quick_search_colorpicker"></div>
					</td>
				</tr>
			</table>
				
			<h3><?php _e("Search Params", "quick-search"); ?></h3>
				
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e("Search in", "quick-search"); ?></th>
					<td>
						<fieldset>
							<label for="quick_search_show_posts">
								<input type="checkbox" id="quick_search_show_posts" name="quick_search_show_posts" value="1"<?php checked(get_option('quick_search_show_posts'), 1); ?> />
								<?php _e("Posts", "quick-search"); ?>
							</label>
							<input type="text" size="3" name="quick_search_posts_limit" value="<?php echo get_option('quick_search_posts_limit') ?>" />
							<?php _e("MAX results", "quick-search"); ?>	
							<br />
							<label for="quick_search_show_pages">
								<input type="checkbox" id="quick_search_show_pages" name="quick_search_show_pages" value="1"<?php checked(get_option('quick_search_show_pages'), 1); ?> />
								<?php _e("Pages", "quick-search"); ?>
							</label>
							<input type="text" size="3" name="quick_search_pages_limit" value="<?php echo get_option('quick_search_pages_limit') ?>" />
							<?php _e("MAX results", "quick-search"); ?>
							<br />
							<label for="quick_search_show_comments">
								<input type="checkbox" id="quick_search_show_comments" name="quick_search_show_comments" value="1"<?php checked(get_option('quick_search_show_comments'), 1); ?> />
								<?php _e("Comments", "quick-search"); ?>
							</label>
							<input type="text" size="3" name="quick_search_comments_limit" value="<?php echo get_option('quick_search_comments_limit') ?>" />
							<?php _e("MAX results", "quick-search"); ?>				
						</fieldset>
					</td>
				</tr>
				
			</table>
			
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="quick_search_form_id,quick_search_max_chars,quick_search_menu_width,quick_search_show_posts,quick_search_show_pages,quick_search_show_comments,quick_search_posts_limit,quick_search_pages_limit,quick_search_comments_limit,quick_search_menu_bgcolor,quick_search_menu_label_bgcolor,quick_search_menu_label_color" />
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'quick-search') ?>" />
				<?php $quick_search_uninstall = wp_nonce_url('plugins.php?action=deactivate&amp;plugin=quick-search%2Fquick-search.php', 'deactivate-plugin_quick-search/quick-search.php'); ?>
				<input type="button" name="quick_search_uninstall" value="<?php _e('Uninstall', 'quick-search') ?>" onClick="if(confirm('<?php _e('All settings will be lost. Are you sure?', 'quick-search') ?>')) location.href = '<?php echo $quick_search_uninstall; ?>&qs_uninstall=1'; " />
			</p>

		</form>
		
	</div>
	
	<?php
}

?>