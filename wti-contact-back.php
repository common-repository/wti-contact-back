<?php
/*
Plugin Name: WTI Contact Back
Plugin URI: http://www.webtechideas.com/wti-contact-back-plugin/
Description: WTI Contact Back is a plugin for sending website admin an email letting him know that he needs to contact you back. Its simple by having only name and email fields to fill up with.
Version: 1.0
Author: webtechideas
Author URI: http://www.webtechideas.com/
License: GPLv2 or later

Copyright 2011  Webtechideas  (email : webtechideas@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
*/

#### INSTALLATION PROCESS ####
/*
1. Download the plugin and extract it
2. Upload the directory '/wti-contact-back/' to the '/wp-content/plugins/' directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Click on 'WTI Contact Back' link under Settings menu to access the admin section
5. On widgets section, there is a widget called 'WTI Contact Back' available which can be used to as a widget
*/

add_filter('plugin_action_links', 'wti_contact_back_plugin_links', 10, 2);

function wti_contact_back_plugin_links($links, $file) {
     static $this_plugin;

     if (!$this_plugin) {
	  $this_plugin = plugin_basename(__FILE__);
     }

     if ($file == $this_plugin) {
	  // The "page" query string value must be equal to the slug
	  // of the Settings admin page we defined earlier, which in
	  // this case equals "myplugin-settings".
	  $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=WtiContactBackAdminMenu">Settings</a>';
	  array_unshift($links, $settings_link);
     }

     return $links;
}

add_action( 'init', 'WtiContactBackLoadTextDomain' );

function WtiContactBackLoadTextDomain() {
     load_plugin_textdomain( 'wti-contact-back', false, 'wti-contact-back/lang' );
}

function WtiContactBackSetOptions() {
     //adding options for wti contact back plugin
     add_option('wti_contact_back_use_jquery', '1', '', 'yes');
     add_option('wti_contact_back_to_email', get_option('admin_email'), '', 'yes');	
}

register_activation_hook(__FILE__, 'WtiContactBackSetOptions');

function WtiContactBackUnsetOptions() {
     //deleting the added options on plugin uninstall
     delete_option('wti_contact_back_use_jquery');
     delete_option('wti_contact_back_to_email');
}

register_uninstall_hook(__FILE__, 'WtiContactBackUnsetOptions');

function WtiContactBackRegisterSettings() {
     //registering the settings
     register_setting( 'wti_contact_back_options', 'wti_contact_back_use_jquery' );
     register_setting( 'wti_contact_back_options', 'wti_contact_back_to_email' );
}
add_action('admin_init', 'WtiContactBackRegisterSettings');

#### ADMIN OPTIONS ####
function WtiContactBackAdminMenu() {
     add_options_page('WTI Contact Back', 'WTI Contact Back', 'activate_plugins', 'WtiContactBackAdminMenu', 'WtiContactBackAdminSettings');
}
add_action('admin_menu', 'WtiContactBackAdminMenu');

function WtiContactBackAdminSettings() {
     //creating the admin configuration interface
?>
<div class="wrap">
     <h2><?php _e('WTI Contact Back Options', 'wti-contact-back');?></h2>
     <br class="clear" />
	
     <div id="poststuff" class="ui-sortable meta-box-sortables">
	  <div id="WtiLikePostOptions" class="postbox">
	       <h3><?php _e('Configuration', 'wti-contact-back'); ?></h3>
	       <div class="inside">
		    <form method="post" action="options.php">
			 <?php settings_fields('wti_contact_back_options'); ?>
			 <table class="form-table">
			      <tr valign="top">
				   <th scope="row"><label for="wti_contact_back_use_jquery"><?php _e('jQuery Framework', 'wti-contact-back'); ?></label></th>
     				   <td>
					<select name="wti_contact_back_use_jquery" id="wti_contact_back_use_jquery">
					     <option value="1" <?php if(get_option('wti_contact_back_use_jquery') == '1') { echo 'selected'; }?>><?php _e('Enabled', 'wti-contact-back') ?></option>
				   	     <option value="0" <?php if(get_option('wti_contact_back_use_jquery') == '0') { echo 'selected'; }?>><?php _e('Disabled', 'wti-contact-back') ?></option>
					</select>
					<span class="description"><?php _e('Disable it if you already have the jQuery framework enabled in your theme.', 'wti-contact-back'); ?></span>
				   </td>
			      </tr>
			      <tr valign="top">
				   <th scope="row"><label for="wti_contact_back_to_email"><?php _e('Recepient Email Address', 'wti-contact-back'); ?></label></th>
				   <td>	
					<input type="text" size="40" name="wti_contact_back_to_email" id="wti_contact_back_to_email" value="<?php echo get_option('wti_contact_back_to_email'); ?>" />
					<span class="description"><?php _e('Email id to receive the emails.', 'wti-contact-back');?></span>
				   </td>
			      </tr>
			      <tr valign="top">
				   <th scope="row" />
				   <td>
					<input class="button-primary" type="submit" name="Save" value="<?php _e('Save Options', 'wti-contact-back'); ?>" />
					<input class="button-secondary" type="submit" name="Reset" value="<?php _e('Reset Options', 'wti-contact-back'); ?>" onclick="return confirmReset()" />
				   </td>
			      </tr>
			 </table>
		    </form>
	       </div>
	  </div>
     </div>	
     <script>
     function confirmReset()
     {
	  //check whether user agrees to reset the settings to default or not
	  var check = confirm("<?php _e('Are you sure to reset the options to default settings?', 'wti-contact-back')?>");
		
	  if(check)
	  {
	       //reset the settings
	       document.getElementById('wti_contact_back_use_jquery').value = 1;
	       document.getElementById('wti_contact_back_to_email').value = "<?php echo get_option('admin_email')?>";
	  
	       return true;
	  }

	  return false;
     }
     </script>
</div>
<?php
}

function WtiContactBackAddWidget() {
     function WtiContactBackWidget($args) {
	  extract($args);
	  $options = get_option("wti_contact_back");
          
	  if (!is_array( $options )) {
	       $options = array(
		    'wti_contact_back_title' => __('WTI Contact Back', 'wti-contact-back'),
	       );
	  }
          
	  $title = $options['wti_contact_back_title'];

	  echo $before_widget;
	  echo $before_title . $title . $after_title;
	  echo '<div id="wti-contact-back">';

	  WtiContactBackForm();

	  echo '</div>';
	  echo $after_widget;
     }
     
     wp_register_sidebar_widget('WtiContactBack', 'WTI Contact Back', 'WtiContactBackWidget');
	
     function WtiContactBackWidgetControl() {
	  $options = get_option("wti_contact_back");

	  if (!is_array( $options )) {
	       $options = array(
		    'wti_contact_back_title' => __('WTI Contact Back', 'wti-contact-back')
	       );
	  }

          //processing the option settings for the widget
	  if (isset($_POST['wti_contact_back_submit'])) {
	       $options['wti_contact_back_title'] = htmlspecialchars($_POST['wti_contact_back_title']);
	       update_option("wti_contact_back", $options);
	  }
          
          //widget option setting fields
	  ?>
	  <p>
               <label for="wti_contact_back_title"><?php _e('Title', 'wti-contact-back'); ?>:<br />
               <input class="widefat" type="text" id="wti_contact_back_title" name="wti_contact_back_title" value="<?php echo $options['wti_contact_back_title'];?>" /></label>
          </p>
	  <input type="hidden" id="wti_contact_back_submit" name="wti_contact_back_submit" value="1" />
	  <?php
     }
     
     wp_register_widget_control('WtiContactBack', 'WTI Contact Back', 'WtiContactBackWidgetControl');
} 

add_action('init', 'WtiContactBackAddWidget');

function WtiContactBackForm() {
     ?>
     <form name="wti_contact_back_form" id="wti_contact_back_form" class="form-validate" method="post" action="">
	  <div id="wti_contact_back_form_result"></div>
	  <p class="wti_contact_back_from_name">
	       <label for="contact_name"><?php echo __('Your Name', 'wti-contact-back')?></label><br />
	       <input type="text" value="" name="contact_name" id="contact_name" />
	  </p>
	  <p class="wti_contact_back_contact_value">
	       <label for="contact_value"><?php echo __('Your Email Address', 'wti-contact-back')?></label><br />
	       <input type="text" value="" name="contact_value" id="contact_value" />
	  </p>
	  <?php wp_nonce_field('wtideas', 'wti_contact_back_nonce'); ?>
	  <p class="wti_contact_back_form_submit"><input type="submit" value="<?php echo __('Contact me back', 'wti-contact-back')?>" name="wti_contact_back_form_submit" id="wti_contact_back_form_submit"></p>
     </form>
     <?php
}

function WtiContactBackEnqueueScripts() {
     if (get_option('wti_contact_back_use_jquery') == '1') {
	 wp_enqueue_script('WtiContactBack', WP_PLUGIN_URL.'/wti-contact-back/js/wti-contact-back.js', array('jquery'), '1.0', true);	
     } else {
	 wp_enqueue_script('WtiContactBack', WP_PLUGIN_URL.'/wti-contact-back/js/wti-contact-back.js', array(), '1.0', true);	
     }
}

function WtiContactBackEnqueueStylesheet() {
     echo '<link rel="stylesheet" type="text/css" href="'.WP_PLUGIN_URL.'/wti-contact-back/css/wti-contact-back.css" media="screen" />'."\n";
}

if(!is_admin()) {
     add_action('init', 'WtiContactBackEnqueueScripts');
     add_action('wp_head', 'WtiContactBackEnqueueStylesheet');
}
?>