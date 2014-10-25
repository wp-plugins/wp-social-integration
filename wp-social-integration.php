<?php
/*
Plugin Name: wp social integration
Plugin URI: http://extensions.techhelpsource.com/wordpress/wp-social-integration/
Description: facebook login, open graph and basic meta tags for social media, facebook home news feed, social(facebook..) plugins (free version)
Author: mitsol	
Version: 1.0
Author URI: http://extensions.techhelpsource.com/ 
License: GPLv2 or later 
*/ 
/* 
Copyright 2014 mitsol (email : mridulcs2012@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//for paid version - 

//common functions 
//include_once dirname(__FILE__) . '/wp-social-integration_functions.php';  
if (is_admin()){ 
	include_once dirname(__FILE__) . '/admin/wp-social-integration_admin.php';	
	//admin style
	add_action('admin_print_styles', 'wp_social_integration_css_all_page');
	add_action( 'admin_menu', 'wp_social_integration_plugin_settings' ); //add  setings menu item in dashboard menu	 
	
	register_activation_hook(__FILE__, 'wp_social_integration_activation'); // code to be run after activate of plugin
	register_deactivation_hook(__FILE__, 'wp_social_integration_deactivation');
} 
else {
	include_once dirname(__FILE__) . '/wp-social-integration_meta.php';	
	include_once dirname(__FILE__) . '/wp-social-integration_feed_social.php';	
	
	add_shortcode("wp_social_integration_feed_short_code", 'wp_social_integration_feed_replace_scode');	
	add_shortcode("wp_social_integration_like_button", 'wp_social_integration_like_button_function');
	add_shortcode("wp_social_integration_follow_button", 'wp_social_integration_follow_button_function');
	add_shortcode("wp_social_integration_send_button", 'wp_social_integration_send_button_function');
	
	//add_action( 'wp_head', 'msfb_wall_settings_styles' ); //settings styles
	//add_action('wp_enqueue_scripts', 'wp_social_integration_styles');// sdd styles				
	//add_action( 'widgets_init', create_function('', 'return register_widget("facebook_wall_and_social_integration");') ); //add a widget at right of wp site
	add_filter('widget_text', 'shortcode_unautop'); // enabling short code in default text widget also see echo do_shortcode($var); in function
	add_filter('widget_text', 'do_shortcode',11);
	//add_action('wp_enqueue_scripts', 'wp_social_integration_scripts'); //add scripts at last to load page fast
	//add_action( 'wp_footer', 'wp_social_integration_uniquejs' );
	error_reporting(0);
} 





