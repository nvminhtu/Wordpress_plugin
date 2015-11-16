<?php
/*
 Plugin Name: ZendVN MyPlugin
Plugin URI: http://www.zend.vn
Description: Tim hieu ve qua trinh chuan xay dung Plugin.
Author: ZendVN group
Version: 1.0
Author URI: http://www.zend.vn
*/
register_activation_hook(__FILE__, 'zendvn_mp_active');

/*==========================================================
 * Vi du 3
*==========================================================*/
function zendvn_mp_active(){
	global $wpdb;
	$table_name = $wpdb->prefix . "zendvn_mp_test";
	
	if($wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") != $table_name){
		$sql = "CREATE TABLE `" . $table_name . "` (
		`myid` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
		`my_name` varchar(50) DEFAULT NULL,
		PRIMARY KEY (`myid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);
	}
	
}

/*==========================================================
 * Vi du 2
*==========================================================*/
/* $str = 'a:3:{s:6:"course";s:13:"Wordpress Pro";s:6:"author";s:12:"ZendVN group";s:7:"website";s:11:"www.zend.vn";}';

echo '<pre>';
print_r(unserialize($str));
echo '</pre>';
function zendvn_mp_active(){
	$zendvn_mp_options = array(
				'course' 	=> 'Wordpress Pro',
				'author' 	=> 'ZendVN group',
				'website' 	=> 'www.zend.vn'
			);
	//Options API
	add_option("zendvn_mp_options",$zendvn_mp_options,'','yes');
	
	$zendvn_mp_version = "1.0";
	//Options API
	add_option("zendvn_mp_version",$zendvn_mp_version,'','yes');

} */

/*==========================================================
 * Vi du 1
 *==========================================================*/
/* function zendvn_mp_active(){
	$zendvn_mp_version = "1.0";	
	//Options API
	add_option("zendvn_mp_version",$zendvn_mp_version,'','yes');
	
} */
