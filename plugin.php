<?php
/**
 * Plugin Name:       TEC Addon: Divi Archive Posts Per Page Fix
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-ticket-39520
 * Description:       The Events Calendar Snippet Plugin
 * Version:           0.1.0
 * Author:            Gustavo Bordoni
 * Author URI:        http://bordoni.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
	die;
}

class TEC_Ticket_39520 {

	public static $ID = 39520;

	public static $_instance = null;

	public function __construct() {
		// It needs to run after 50 priority
		add_filter( 'parse_query', array( $this, 'remove_divi_pre_get_posts' ), 100 );
	}

	public static function instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function remove_divi_pre_get_posts( $query ) {
		if ( $query->tribe_is_event_query ) {
			remove_action( 'pre_get_posts', 'et_custom_posts_per_page' );
		}
	}
}
add_action( 'plugins_loaded', array( 'TEC_Ticket_39520', 'instance' ), 15 );