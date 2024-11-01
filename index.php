<?php
/* 
 * Plugin Name:   Top 7 List Generator
 * Version:       1.3.0
 * Plugin URI:    http://www.dimbal.com/
 * Description:   Integrate a premade "Top 10" style list in your blog.  Users vote on the answers they agree with.  A great way to add fresh content.
 * Author:        The Top 7
 * Author URI:    http://www.thetop7.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit();	// sanity check

//error_reporting(E_ALL);
//ini_set('display_errors','On');


//Register the activation function
register_activation_hook(__FILE__, 'top7_activate');

//init process for Button Control
add_action('init', 'top7_add_interface');


// FUNCTIONS ///////////////////////////

/*
 * Function that is run when the plugin is activated
 */
function top7_activate(){
	
}
	
/*
 * Adds all of the appropriate stuff to WordPress
 */
function top7_add_interface(){
	//Would be nice to let the user choose which tool bar level to have it display in (future release)
	add_filter("mce_external_plugins", "top7_add_tinymce_plugin");
	add_filter("mce_buttons", "top7_register_tinymce_button");
}

/*
 * Adds an HTML button to the editor so that a user can select it while editing a post
 */
function top7_register_tinymce_button($buttons){
	array_push($buttons, "top7");
	return $buttons;
}

/*
 * Adds an js callback hook for when the button is pressed by the user
 */
function top7_add_tinymce_plugin($plugin_array) {
	$plugin_array['top7'] = plugin_dir_url(__file__).'editor_plugin.js';
   	return $plugin_array;
}

/*
 * This will be the function that translates the shortcode into something useful.  
 * In this case it will be a placeholder DIV and proper element information so that the JS can render it via AJAX
 * RETURN the HTML to be put in place of the shortcode
 */
function top7_shortcode_handler($atts){
	extract( shortcode_atts( array(
		'slug' => 'default-slug',
		'title' => 'The Top 7',
		'hide_description' => 'default',
		'hide_sharing' => 'default',
		'limit_list' => 'default'
		), $atts ) );
	
	$rand = rand(0,10000);
    $title = str_replace("-", " ", $title);
    $title = ucfirst($title);  
    //At this point I now have the variables from the shortcode... now what do I want to do with them?? Build the HTML?
    $html = '<div id="top7_embed_container_'.$rand.'" class="top7_embed_container" top7-slug="'.$slug.'" top7-hidedescription="'.$hide_description.'" top7-hidesharing="'.$hide_sharing.'" top7-limitlist="'.$limit_list.'"><a href="http://www.thetop7.com/top-7/'.$slug.'">'.$title.'</a></div><link rel="stylesheet" type="text/css" href="http://www.thetop7.com/css/top7.css" />';
    return $html;
}

/* 
 * Enque scripts and make any other needed calls
 */

wp_enqueue_script('top7render','http://www.thetop7.com/wpp/v1/render.js');
wp_enqueue_script('top7slugs','http://www.thetop7.com/wpp/v1/slugs.js');
add_shortcode( 'top7', 'top7_shortcode_handler' );





//ADMIN MENU PAGE
add_action( 'admin_menu', 'top7_plugin_menu' );
function top7_plugin_menu() {
	add_options_page( 'The Top 7 Settings and Options', 'The Top 7', 'manage_options', 'top7-plugin-options', 'top7_plugin_options' );
}
function top7_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	?>
	<div style="float:right; margin: 10px 10px 0 0"><a href="http://www.dimbal.com"><img src="http://www.dimbal.com/images/logo_300.png" alt="Dimbal Software" /></a></div>
	<h1>The Top 7</h1>
	<p style="font-style:italic; font-size:larger;">Easily add pre-made Top 7 lists to your website in 3 simple steps.</p>
	<hr />
	<div style="display:table; width:100%;">
		<div style="display:table-cell; width:auto;">
			<!-- LEFT SIDE CONTENT -->
			<h4>Usage Instructions</h4>
			<p>This plugin comes ready to use right out of the box.  Below are some tips to help you include a Top7 list in your posts.</p>
			<p>1. On your Post Editor find the Top7 button and click.</p>
			<p><img src="http://www.thetop7.com/images/wpp-top7-button.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<p>2. Using the window that appears search for a Top7 list that you want to include in your post.</p>
			<p><img src="http://www.thetop7.com/images/wpp-top7-popup.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<p>3. Select the list you want to use, then check any optional settings for that list.  Click the "Insert" to include the shortcode for the Top7 list in your post.</p>
			<p><img src="http://www.thetop7.com/images/wpp-top7-example.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<p>That's it!  That sure was easy - wasn't it.</p>
		</div>
		<div style="display:table-cell; width:300px; padding-left:20px;">
			<!-- RIGHT SIDE CONTENT -->
			<h4>Did you like this Plugin?  Please help it grow.</h4>
			<div style="text-align:center;"><a href="http://wordpress.org/support/view/plugin-reviews/top-7">Rate this Plugin on Wordpress</a></div>
			<br />
			<div style="text-align:center;">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="5GMXFKZ79EJFA">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
			<hr />
			<h4>Follow us for Free Giveaways and more...</h4>
			<div id="fb-root"></div>
			<script type="text/javascript">
			  // Additional JS functions here
			  window.fbAsyncInit = function() {
			    FB.init({
			      appId      : '539348092746687', // App ID
			      //channelUrl : '//<?=(URL_ROOT)?>channel.html', // Channel File
			      status     : true, // check login status
			      cookie     : true, // enable cookies to allow the server to access the session
			      xfbml      : true,  // parse XFBML
			      frictionlessRequests: true,  //Enable Frictionless requests
			    });
			  };

			  // Load the SDK Asynchronously
			  (function(d){
			     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			     if (d.getElementById(id)) {return;}
			     js = d.createElement('script'); js.id = id; js.async = true;
			     js.src = "//connect.facebook.net/en_US/all.js";
			     ref.parentNode.insertBefore(js, ref);
			   }(document));
			</script>
			<div style="text-align:center;"><div class="fb-like" data-href="https://www.facebook.com/dimbalsoftware" data-send="false" data-layout="standard" data-show-faces="false" data-width="200"></div></div>
			<hr />
			<h4>Questions?  Support?  Record a Bug?</h4>
			<p>Need help with this plugin? Visit...</p>
			<p><a href="http://www.dimbal.com/support">http://www.dimbal.com/support</a></p>
			<hr />
			<h4>Other great Dimbal Products</h4>
			<div class="dbmWidgetWrapper" dbmZone="18"></div>
			<div class="dbmWidgetWrapper" dbmZone="19"></div>
			<div class="dbmWidgetWrapper" dbmZone="20"></div>
			<a href="http://www.dimbal.com">Powered by the Dimbal Banner Manager</a>
		</div>
	</div>
	<?
	wp_enqueue_script('dbmScript','http://www.dimbal.com/dbm/banner/dbm.js', false);
}