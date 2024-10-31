<?php 
/*
Plugin Name: Quick General Options
Plugin URI: http://qgo.alandawi.com.ar
Description: 
Version: 1.0
Author: Alan Gabriel Dawidowicz
Author URI: http://www.alandawi.com.ar
License: GPL2
	
	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
?>
<?php  
/***************** GENERAL OPTIONS *****************/
    
    // Easy Maintenance Mode
    if (get_option('check_maintenance') == true) {
        function cwc_maintenance_mode() {
            if ( !current_user_can( 'edit_themes' ) || !is_user_logged_in() ) {
                wp_die('Maintenance, please come back soon.');
            }
        }
        add_action('get_header', 'cwc_maintenance_mode');
    }


    // Disable automatic formatting
    if (get_option('check_automatic_formatting') == true) {
        remove_filter('the_content', 'wptexturize');
        remove_filter('the_excerpt', 'wptexturize');
    }


    // Remove RSD link
    if (get_option('check_rsd_link') == true) {
        remove_action('wp_head', 'rsd_link');
    }


    // Remove wlwmanifest
    if (get_option('check_wlwmanifest_link') == true) {
        remove_action('wp_head', 'wlwmanifest_link');
    }


    // Automatically generate the meta-description in the news
    if (get_option('check_trw_metadesc') == true) {
        function trw_metadesc() {
            global $post;
            if (!is_single()) { return; }
            $meta = strip_tags($post->post_content);
            $meta = strip_shortcodes($post->post_content);
            $meta = str_replace(array("\n", "\r", "\t"), ' ', $meta);
            $meta = substr($meta, 0, 125);
            echo "<meta name='description' content='$meta' />";
        }
        add_action('wp_head', 'trw_metadesc');
    }

/***************** END GENERAL OPTIONS *****************/
?>
<?php  
/***************** VISUAL OPTIONS *****************/

    // Shortcodes in Widgets
    if (get_option('check_shortcode_widgets') == true) {
        add_filter('widget_text', 'do_shortcode');
    }

/***************** END VISUAL OPTIONS *****************/
?>
<?php  
/***************** SECURITY OPTIONS *****************/

    // Hide login errors
    if (get_option('check_login_errors') == true) {
        add_filter('login_errors', create_function('$a', "return null;"));
    }


    // Prevent direct file access to functions.php
    if (get_option('check_secure_functions') == true) {
        if (!empty($_SERVER['SCRIPT_FILENAME']) && 'functions.php' == basename($_SERVER['SCRIPT_FILENAME']))
        {
        die ('No access!');
        }
    }


    // Restrict wp-admin access to subscribers
    if (get_option('check_restrict_access_admin_panel') == true) {
        function restrict_access_admin_panel(){
            global $current_user;
            get_currentuserinfo();
            if ($current_user->user_level <  4) {
                wp_redirect( get_bloginfo('url') );
                exit;
            }
        }
        add_action('admin_init', 'restrict_access_admin_panel', 1);
    }



    // Remove the WordPress version
    if (get_option('check_remove_version_wp') == true) {
        remove_action('wp_head', 'wp_generator');
    }



    // Remove the WordPress version of the RSS
    if (get_option('check_remove_feed_generator') == true) {
        function remove_feed_generator() {
            return '';
        }
        add_filter('the_generator', 'remove_feed_generator');
    }

/***************** END SECURITY OPTIONS *****************/
?>
<?php  
/***************** WORDPRESS DEFAULT OPTIONS *****************/

    // Disable auto-saving items
    if (get_option('check_disable_autosave') == true) {
        function disableAutoSave(){
            wp_deregister_script('autosave');
        }
        add_action( 'wp_print_scripts', 'disableAutoSave' );
    }


    // Add thumbnails
    if (get_option('check_post_thumbnails') == true) {
        add_theme_support( 'post-thumbnails' );
    }
    


    // Set as default to the HTML editor
    if (get_option('check_wp_default_editor') == true) {
        add_filter('wp_default_editor', create_function('', 'return "html";'));
    }
    


    // Disable the default WordPress widgets
    if (get_option('check_disable_default_widgets') == true) {
        function unregister_default_wp_widgets() {
            unregister_widget('WP_Widget_Pages');
            unregister_widget('WP_Widget_Calendar');
            unregister_widget('WP_Widget_Archives');
            unregister_widget('WP_Widget_Links');
            unregister_widget('WP_Widget_Meta');
            unregister_widget('WP_Widget_Search');
            unregister_widget('WP_Widget_Text');
            unregister_widget('WP_Widget_Categories');
            unregister_widget('WP_Widget_Recent_Posts');
            unregister_widget('WP_Widget_Recent_Comments');
            unregister_widget('WP_Widget_RSS');
            unregister_widget('WP_Widget_Tag_Cloud');
        }
        add_action('widgets_init', 'unregister_default_wp_widgets', 1);
    }
    


    // Remove margin-top automatically generated
    if (get_option('check_remove_margin_top') == true) {
        function my_function_admin_bar(){ return false; }
            add_filter( 'show_admin_bar' , 'my_function_admin_bar');
    }


    // Prevent WordPress to compress your jpg images
    if (get_option('check_jpeg_quality') == true) {
        add_filter( 'jpeg_quality', create_function( '', 'return 100;' ) );
    }
    


    // Remove Gallery Settings
    if (get_option('check_gallery_settings') == true) {
        add_action( 'admin_head_media_upload_gallery_form', 'mfields_remove_gallery_setting_div' );
        if( !function_exists( 'mfields_remove_gallery_setting_div' ) ) {
            function mfields_remove_gallery_setting_div() {
                print '
                    <style type="text/css">
                        #gallery-settings *{
                        display:none;
                        }
                    </style>';
            }
        }
    }
    


    // Unlock buttons useful in the visual editor
    if (get_option('check_more_buttons_editor') == true) {
        function more_buttons_editor($buttons) {
        $buttons[] = 'hr';
        $buttons[] = 'sub';
        $buttons[] = 'sup';
        $buttons[] = 'fontselect';
        $buttons[] = 'fontsizeselect';
        $buttons[] = 'cleanup';
        $buttons[] = 'styleselect';
        return $buttons;
        }
        add_filter("mce_buttons_3", "more_buttons_editor");
    }

/***************** END WORDPRESS DEFAULT OPTIONS *****************/
?>
<?php

add_action('admin_menu', 'qgo_create_menu');

function qgo_create_menu() {
	add_menu_page('Quick GO', 'Quick GO', 'administrator', __FILE__, 'qgo_settings_page',plugins_url('/img/generic.png', __FILE__));

	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	register_setting( 'qgo-settings', 'check_maintenance' );
    register_setting( 'qgo-settings', 'check_automatic_formatting' );
    register_setting( 'qgo-settings', 'check_rsd_link' );
    register_setting( 'qgo-settings', 'check_wlwmanifest_link' );
    register_setting( 'qgo-settings', 'check_trw_metadesc' );
    register_setting( 'qgo-settings', 'check_shortcode_widgets' );
    register_setting( 'qgo-settings', 'check_login_errors' );
    register_setting( 'qgo-settings', 'check_secure_functions' );
    register_setting( 'qgo-settings', 'check_restrict_access_admin_panel' );
    register_setting( 'qgo-settings', 'check_remove_version_wp' );
    register_setting( 'qgo-settings', 'check_remove_feed_generator' );
    register_setting( 'qgo-settings', 'check_disable_autosave' );
    register_setting( 'qgo-settings', 'check_post_thumbnails' );
    register_setting( 'qgo-settings', 'check_wp_default_editor' );
    register_setting( 'qgo-settings', 'check_disable_default_widgets' );
    register_setting( 'qgo-settings', 'check_remove_margin_top' );
    register_setting( 'qgo-settings', 'check_jpeg_quality' );
    register_setting( 'qgo-settings', 'check_gallery_settings' );
    register_setting( 'qgo-settings', 'check_more_buttons_editor' );
}

function qgo_settings_page() {
?>

<style type="text/css">
    .wrap-qgo h2 {
        color: #333333;
        font-size: 20px;
    }
    .wrap-option {
        width: 900px;
        margin-top: 20px;
        margin-bottom: 50px;
    }
    .wrap-option h3 {
        color: #333333;
        font-size: 15px;
    }
    .description {
    }
    .result {
        float: left;
        margin-right: 5px;
    }
    .result input {
    }
    .note {
        margin-top: 5px;
        margin-bottom: 30px;
        background: #F8F8F8;
        color: #666666;
        padding: 15px;
        border: 1px dashed #CCCCCC;
    }
</style>

<div class="wrap-qgo">
<h2>Quick General Options</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'qgo-settings' ); ?>
    <?php do_settings_sections( 'qgo-settings' ); ?>

    <div class="wrap-option">
        <h3>General:</h3>
        <div class="result">
            <input type="checkbox" name="check_maintenance" value="false"<?php if (get_option('check_maintenance')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Quick Maintenance Mode:</div>
        <div class="note">Sometimes, you need to put your blog on hold while performing some maintenance. Many plugins are allowing you to do so, but here is a simpler solution: Just paste the following snippet into your functions.php file and save it. Your blog is now unavailable to anyone except administrators. Don’t forget to remove the code when you’re done with maintenance!</div>


        
        <div class="result">
            <input type="checkbox" name="check_automatic_formatting" value="false"<?php if (get_option('check_automatic_formatting')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Automatic Formatting:</div>
        <div class="note">WordPress does alot of automatic formating to the posts if you’re using the visual editor. This is easy to disable.</div>


        
        <div class="result">
            <input type="checkbox" name="check_rsd_link" value="false"<?php if (get_option('check_rsd_link')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Remove RSD link:</div>
        <div class="note">If you want to remove the RSD link that clutters your header.</div>


        
        <div class="result">
            <input type="checkbox" name="check_wlwmanifest_link" value="false"<?php if (get_option('check_wlwmanifest_link')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Remove wlwmanifest:</div>
        <div class="note">This adds a xml-file to your blog to enable Windows Live Writer to interact with your WordPress blog. If you’re not using it, you should remove it.</div>



        <div class="result">
            <input type="checkbox" name="check_trw_metadesc" value="false"<?php if (get_option('check_trw_metadesc')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Meta-description in the news:</div>
        <div class="note">Automatically generate the meta-description in the news.</div>
    </div>




    <div class="wrap-option">
        <h3>Visual:</h3>
        <div class="result">
            <input type="checkbox" name="check_shortcode_widgets" value="false"<?php if (get_option('check_shortcode_widgets')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Shortcodes in Widgets:</div>
        <div class="note">If you have shortcodes that you use in your WordPress blog, you might want to add them in a widget. Although this is not possible, it’s very easy to add support for it.</div>
    </div>




    <div class="wrap-option">
        <h3>Security:</h3>
        <div class="result">
            <input type="checkbox" name="check_login_errors" value="false"<?php if (get_option('check_login_errors')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Hide login errors:</div>
        <div class="note">For security reasons, you might want to hide the login messages that WordPress shows when you’re trying to login with the wrong information. The message that says “Invalid username” helps the hacker in his search for the right username and password.</div>



        <div class="result">
            <input type="checkbox" name="check_secure_functions" value="false"<?php if (get_option('check_secure_functions')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Secure Functions:</div>
        <div class="note">Prevent direct file access to functions.php.</div>



        <div class="result">
            <input type="checkbox" name="check_restrict_access_admin_panel" value="false"<?php if (get_option('check_restrict_access_admin_panel')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Restrict subscribers:</div>
        <div class="note">Restrict wp-admin access to subscribers.</div>



        <div class="result">
            <input type="checkbox" name="check_remove_version_wp" value="false"<?php if (get_option('check_remove_version_wp')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Remove the WordPress version:</div>
        <div class="note">Remove the WordPress version from prying eyes.</div>



        <div class="result">
            <input type="checkbox" name="check_remove_feed_generator" value="false"<?php if (get_option('check_remove_feed_generator')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Remove the WordPress version of the RSS:</div>
        <div class="note">Remove the WordPress version of the RSS from prying eyes.</div>
    </div>




    <div class="wrap-option">
        <h3>WordPress Default:</h3>
        <div class="result">
            <input type="checkbox" name="check_disable_autosave" value="false"<?php if (get_option('check_disable_autosave')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Disable auto-saving items:</div>
        <div class="note">WordPress have a very useful function that autosaves your posts while you’re typing them in the dashboard. Though, if for some reason you’d like to disable this function, it is very easy to do.</div>



        <div class="result">
            <input type="checkbox" name="check_post_thumbnails" value="false"<?php if (get_option('check_post_thumbnails')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Add thumbnails:</div>
        <div class="note">Use: < ?php the_post_thumbnail(); ?></div>



        <div class="result">
            <input type="checkbox" name="check_wp_default_editor" value="false"<?php if (get_option('check_wp_default_editor')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Set as default to the HTML editor:</div>
        <div class="note">If you want this editor appears by default when you edit or create an article.</div>



        <div class="result">
            <input type="checkbox" name="check_disable_default_widgets" value="false"<?php if (get_option('check_disable_default_widgets')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Disable the default WordPress widgets:</div>
        <div class="note">If you want to remove the default WordPress widgets such as Links, Files, Calendar, etc.</div>



        <div class="result">
            <input type="checkbox" name="check_remove_margin_top" value="false"<?php if (get_option('check_remove_margin_top')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Remove margin-top automatically generated:</div>
        <div class="note">WordPress automatically generates a margin-top on top of our template, you must activate to disable this option..</div>



        <div class="result">
            <input type="checkbox" name="check_jpeg_quality" value="false"<?php if (get_option('check_jpeg_quality')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Prevent WordPress to compress your jpg images:</div>
        <div class="note">By default, WordPress compress uploaded jpg images. This is a good thing because it makes the files smaller, but in some case, image quality can be more important. If you need to prevent WordPress to compress your images, simply apply this simple tip.</div>



        <div class="result">
            <input type="checkbox" name="check_gallery_settings" value="false"<?php if (get_option('check_gallery_settings')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Remove Gallery Settings:</div>
        <div class="note">This is a simple solution to to remove the gallery settings div from the media upload thick box.</div>



        <div class="result">
            <input type="checkbox" name="check_more_buttons_editor" value="false"<?php if (get_option('check_more_buttons_editor')==true) echo 'checked="checked" '; ?> />
        </div>
        <div class="description">Unlock buttons useful in the visual editor:</div>
        <div class="note">The visual editor of WordPress has almost all the necessary buttons to edit the content of your article, but sometimes some options require a bit more detailed, such as styles, font sizes, etc..</div>
    </div>

    <input type="submit" class="button-primary" value="Save Changes" />

</form>
</div>
<?php } ?>