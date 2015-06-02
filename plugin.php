<?php
/**
 * Plugin Name:       The Events Calendar: Snippet 947803
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-947803
 * Description:       The Events Calendar Support Addon
 * Version:           0.1.1
 * Author:            Gustavo Bordoni
 * Author URI:        http://bordoni.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
	die;
}

class TEC_Forum_947803 {

	public static $ID = 947803;

	public static $_instance = null;

	public function __construct(){
		add_filter( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
	}

	public static function instance(){
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function admin_menu(){
		global $menu;
		if ( ! current_user_can( 'manage_options' ) ) {
			foreach ( $menu as $key => $_menu ) {
				if ( false !== strpos( $_menu[2], 'separator' ) ){
					unset( $menu[ $key ] );
				}
			}
			remove_menu_page( 'edit.php?post_type=tribe_events' );
		}
	}
}
add_action( 'init', array( 'TEC_Forum_947803', 'instance' ) );
