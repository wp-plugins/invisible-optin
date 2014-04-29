<?php
/*
Plugin Name: Invisible Optin
Plugin URI: http://TheInvisibleOptin.com
Description: Facebook's Re-Marketing/Custom Audience Plugin for WordPress. Use this Plugin to Add Facebook's Re-Targeting Pixels in your WordPress Website.
Version: 1.0
Author: InvisibleOptin.com
Author URI: http://InvisibleOptin.com/

Copyright 2014-2015 Invisible Optin

Use with confidence
*/

$invisible_optin_db_version = "0.4";
add_action('admin_init', 'invisible_optin_admin_init');
add_action('admin_head', 'invisible_optin_admin_init_admin_head');
 
function invisible_optin_admin_init(){
  wp_enqueue_script('word-count');
  wp_enqueue_script('post');
  wp_enqueue_script('editor');
  wp_enqueue_script('media-upload');
}

function invisible_optin_admin_init_admin_head(){
	// conditions here
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'jquery-color' );
	wp_print_scripts('editor');
	if (function_exists('add_thickbox')) add_thickbox();
	wp_print_scripts('media-upload');
	if (function_exists('wp_tiny_mce')) wp_tiny_mce();
	wp_admin_css();
	wp_enqueue_script('utils');
	do_action("admin_print_styles-post-php");
	do_action('admin_print_styles');
} 

function invisible_optin_install (){
   global $wpdb;
   global $invisible_optin_db_version;
   $table_settings = $wpdb->prefix . "invisible_optin_settings";   
   $installed_ver = get_option( "invisible_optin_db_version" );
	if($wpdb->get_var("show tables like '$table_settings'") != $table_settings || $installed_ver != $invisible_optin_db_version) 
	{
	  // Settings Table
		$exceuete_settings = "CREATE TABLE IF NOT EXISTS ".$table_settings." (`option_name` varchar(255) NOT NULL,`option_value` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		$wpdb->query($exceuete_settings);	  				
	   
		$wpdb->insert( 
			$table_settings, 
			array( 
				'option_name' => 'CONF_SCRIPT_CODE'
			), 
			array( 
				'%s' 
			) 
		);	
       
       update_option( "invisible_optin_db_version", $invisible_optin_db_version );
       add_option("invisible_optin_db_version", $invisible_optin_db_version);
    }	
}  
register_activation_hook(__FILE__,'invisible_optin_install');

function invisible_optin_uninstall(){
   global $wpdb;
   global $invisible_optin_db_version;
   $table_settings = $wpdb->prefix . "invisible_optin_settings";
   $wpdb->query("DROP TABLE ".$table_settings."");
}
register_uninstall_hook( __FILE__, 'invisible_optin_uninstall');

function invisible_optin_update_db_check(){
    global $invisible_optin_db_version;
    if (get_site_option('invisible_optin_db_version') != $invisible_optin_db_version) 
	{
		//redirect_install();
    }
}
add_action('plugins_loaded', 'invisible_optin_update_db_check');

function invisible_optin_menu(){
	add_menu_page("Invisible Optin", "Invisible Optin", 0, "invisible-optin", "invisible_optin_main",plugin_dir_url( __FILE__ ) . 'images/admin.ico');
	//add_submenu_page("invisible-optin", "Settings", "Settings", 0, "invisible-optin", "invisible-optin");
}
add_action('admin_menu', 'invisible_optin_menu');
	

function invisible_optin_main(){
   global $wpdb;
   require_once dirname( __FILE__ ) . '/invisible_optin_settings.php';
}


add_action('wp_head', 'invisible_optin_add_custom_code_head');
	
function invisible_optin_add_custom_code_head(){
	invisible_optin_add_custom_code('header');
}
	
function invisible_optin_add_custom_code($position){
	global $wpdb;	
	$table_name = $wpdb->prefix . "invisible_optin_settings";
	
	$conf_script_code = $wpdb->get_var( 
		$wpdb->prepare( 
			"SELECT      option_value 
			FROM        $table_name 
			WHERE       option_name = %s",
			'CONF_SCRIPT_CODE'
		) 
	);
	
	if($conf_script_code != ''){
		echo '<!-- Custom Code Start-->';
		echo PHP_EOL;
		echo stripslashes($conf_script_code);
		echo PHP_EOL;
		echo '<!-- Custom Code Start-->';
		echo PHP_EOL;
	}
}

function invisible_optin_wp_admin_style() {
        wp_register_style( 'custom_layout', plugins_url('/css/invisible_optin.css', __FILE__) );
        wp_enqueue_style( 'custom_layout' );
}
add_action( 'admin_enqueue_scripts', 'invisible_optin_wp_admin_style' );

?>
