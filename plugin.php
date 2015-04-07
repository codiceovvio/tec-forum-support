<?php
/**
 * Plugin Name:       The Events Calendar: Snippet 951413
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-951413
 * Description:       The Events Calendar Support Addon
 * Version:           0.1.0
 * Author:            Matthew Batchelder
 * Author URI:        http://tri.be
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
	die;
}

class TEC_Forum_951413 {
	public static $ID = 951413;
	public static $_instance = null;
	public $instances = array();
	public function __construct(){
		if ( ! class_exists( 'Tribe__Events__Events' ) ) {
			return;
		}

		remove_filter( 'tribe_organizer_table_top', array( Tribe__Events__Events::instance(), 'displayEventOrganizerDropdown' ) );
	}

	public static function instance(){
		if ( ! is_a( self::$_instance, __CLASS__ ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}
add_action( 'init', array( 'TEC_Forum_951413', 'instance' ) );