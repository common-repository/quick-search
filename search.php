<?php


require('../../../wp-blog-header.php'); 

if (isset($_GET['s']) && trim($_GET['s']) != '') {
  
	$term = mysql_real_escape_string(trim($_GET['s']));
	$maxchars = (get_option('quick_search_max_chars') != '') ? get_option('quick_search_max_chars') : 50;
	
	$li_background = (get_option('quick_search_menu_label_bgcolor') != '') ? 'background: ' . get_option('quick_search_menu_label_bgcolor') . ';' : 'background: #A0A0A0';
	$li_background .= (get_option('quick_search_menu_label_color') != '') ? 'color: ' . get_option('quick_search_menu_label_color') . ';' : 'color: #efefef';

	$posts_limit = (get_option('quick_search_posts_limit') != '') ? get_option('quick_search_posts_limit') : 4;
	$sql_posts = "SELECT 
	        * 
	      FROM 
	        " . $wpdb->prefix . "posts wp 
	      WHERE 
	        wp.post_type = 'post' AND 
	        wp.post_status = 'publish' AND 
	        -- wp.post_date <= '" . date('Y-m-d H:i:s', time()) . "' AND
	        ((wp.post_title LIKE '%${term}%') OR (wp.post_content LIKE '%${term}%')) 
	      ORDER BY 
	        wp.post_date DESC 
	      LIMIT $posts_limit";
	
	$pages_limit = (get_option('quick_search_pages_limit') != '') ? get_option('quick_search_pages_limit') : 4;	  
	$sql_pages = "SELECT 
	        * 
	      FROM 
	        " . $wpdb->prefix . "posts wp 
	      WHERE 
	        wp.post_type = 'page' AND 
	        wp.post_status = 'publish' AND 
	        ((wp.post_title LIKE '%${term}%') OR (wp.post_content LIKE '%${term}%')) 
	      ORDER BY 
	        wp.post_date DESC 
	      LIMIT $pages_limit";
		  
	$comments_limit = (get_option('quick_search_comments_limit') != '') ? get_option('quick_search_comments_limit') : 4;	
	$sql_comments = "SELECT 
	        * 
	      FROM 
	        " . $wpdb->prefix . "comments wp 
	      WHERE 
	        wp.comment_approved  = 1 AND 
	        (wp.comment_content  LIKE '%${term}%')
	      ORDER BY 
	        wp.comment_date DESC 
	      LIMIT $comments_limit";
		  
	$result = "<ul>";
	
	/* ------------------- */
	if(get_option('quick_search_show_posts'))
	{
		$result .= "<li class=\"quick_search_type\" style=\"$li_background\">" . __("Posts") . "</li>";
		$result .= "<ul>";
		$posts = $wpdb->get_results($sql_posts);
		if (count($posts)) 
		{
			foreach($posts as $post) 
			{
				$result .= '<li>';
				$result .= '<a href="' . get_permalink($post->ID) . '">';
				$result .= '<strong>' . $post->post_title . '</strong><br />';
				$result .= '<span>' . substr(strip_tags($post->post_content), 0, $maxchars) . '...</span>';
				$result .= '</a>';
				$result .= '</li>';
			}
			
		} 
		else 
		{
			$result .= '<li>' . __("no result found") . '</li>';
		}
		$result .= "</ul>";
	}
	/* ------------------- */
	if(get_option('quick_search_show_pages'))
	{
		$result .= "<li class=\"quick_search_type\" style=\"$li_background\">" . __("Pages") . "</li>";
		$result .= "<ul>";
		$pages = $wpdb->get_results($sql_pages);
		if (count($pages)) 
		{
			foreach($pages as $page) 
			{
				$result .= '<li>';
				$result .= '<a href="' . get_permalink($page->ID) . '">';
				$result .= '<strong>' . $page->post_title . '</strong><br />';
				$result .= '<span>' . substr(strip_tags($page->post_content), 0, $maxchars) . '...</span>';
				$result .= '</a>';
				$result .= '</li>';
			}
			
		} 
		else 
		{
			$result .= '<li>' . __("no result found") . '</li>';
		}
		$result .= "</ul>";
	}
	/* ------------------- */
	if(get_option('quick_search_show_comments'))
	{
		$result .= "<li class=\"quick_search_type\" style=\"$li_background\">" . __("Comments") . "</li>";
		$result .= "<ul>";
		$comments = $wpdb->get_results($sql_comments);
		if (count($comments)) 
		{
			foreach($comments as $comment) 
			{
				$result .= '<li>';
				$result .= '<a href="' . get_comment_link($comment->ID) . '">';
				$result .= '<strong>' . $comment->comment_author . '</strong><br />';
				$result .= '<span>' . substr(strip_tags($comment->comment_content), 0, $maxchars) . '...</span>';
				$result .= '</a>';
				$result .= '</li>';
			}
			
		} 
		else 
		{
			$result .= '<li>' . __("no result found") . '</li>';
		}
		$result .= "</ul>";
	}
	/* ------------------- */
	
	$result .= '</ul>';
	
	echo $result;
  
}

?>