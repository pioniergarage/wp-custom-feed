<?php
/*
Plugin Name: WP Custom Post RSS Feed
Plugin URI: http://www.netattingo.com/
Description: Plugin helps to generate xml feeds of post/page/custom post.  
Author: NetAttingo Technologies
Version: 1.0.0
Author URI: http://www.netattingo.com/
*/


define('WPCPRF_PAGE_DIR', plugin_dir_path(__FILE__).'pages/');

//Include menu and assign page
function wpcprf_plugin_menu() {
 
	add_menu_page("Custom Post RSS Feeds", "Custom Post RSS Feeds", "administrator", "wpcpf-settings-page", "wpcprf_plugin_pages", 'dashicons-rss' ,38);
	add_submenu_page("wpcpf-settings-page", "About Us", "About Us", "administrator", "wpcpf-about-us", "wpcprf_plugin_pages");
}

add_action("admin_menu", "wpcprf_plugin_menu");

function wpcprf_plugin_pages() {

   $itm = WPCPRF_PAGE_DIR.$_GET["page"].'.php';
   include($itm);
}


//set feed template to page
add_filter( 'page_template', 'feed_page_template' );
function feed_page_template( $page_template )
{  
    if ( is_page( 'media-rss' ) ) { 
        $page_template = plugin_dir_path(__FILE__) . 'pages/custom-feed.php';
    }
    return $page_template;
}



//create feed page start
function check_page_feed_page_exist(){
	if( get_page_by_title( 'media-rss' ) == NULL ){
	 create_pages_feed( 'media-rss' );
	}
}

 
add_action('init','check_page_feed_page_exist');
function create_pages_feed($pageName) {
	$createPage = array(
	  'post_title'    => $pageName,
	  'post_content'  => 'Starter content',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'     => 'page',
	  'post_name'     => $pageName
	);

	// Insert the post into the database
	wp_insert_post( $createPage );
}

//create feed page end

//add admin css
function wpcprf_admin_css() {
  wp_register_style('wpcprf_admin_css', plugins_url('includes/feed-admin-style.css',__FILE__ ));
  wp_enqueue_style('wpcprf_admin_css');
}
add_action( 'admin_init','wpcprf_admin_css' );

?>