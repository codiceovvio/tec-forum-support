<?php
/**
 * Plugin Name:       TEC Addon: Testing Filters CSV
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-ticket-40224
 * Description:       The Events Calendar QA Addon
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

class TEC_Ticket_40224 {

	public static $ID = 40224;

	public static $_instance = null;

	public function __construct() {
		add_filter( 'tribe_events_tickets_attendees_csv_items', array( __CLASS__, 'filter_items' ), 15, 2 );
	}

	public static function instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public static function filter_items( $items ) {
		foreach ( $items as $key => $item ) {
			if ( 0 === $key ) {
				continue;
			}

			if ( 'Completed' === $item[1] ){
				$item[7] = 'Checking Done!';
			}
		}

	}
}
add_action( 'plugins_loaded', array( 'TEC_Ticket_40224', 'instance' ), 15 );