<?php
/**
 * Plugin Name:       The Events Calendar: Snippet 945349
 * Plugin URI:        https://gist.github.com/bordoni/420661affe489e08fd5e
 * Description:       Wendy requested a snippet to use new data on Month and Week tooltips
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

class TEC_Forum_945349 {

	public static $ID = 945349;

	public static $_instance = null;

	public function __construct(){
		add_filter( 'tribe_events_template_data_array', array( __CLASS__, 'template_data_array' ), 10, 3 );
		add_filter( 'tribe_events_template_paths', array( __CLASS__, 'template_paths' ) );
	}

	public static function instance(){
		if ( ! is_a( self::$_instance, __CLASS__ ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function template_data_array( $json, $event, $additional ){
		$json['venue'] = '';
		$json['organizer'] = '';
		$json['price'] = '';

		$venue = tribe_get_venue_id( $event );
		if ( $venue ){
			$json['venue'] = $venue;
			$json['venue_link'] = tribe_get_venue_link( $venue, false );
			$json['venue_title'] = tribe_get_venue( $venue );
		}

		$organizer = tribe_get_organizer( $event );
		if ( $organizer ){
			$json['organizer'] = $organizer;
		}

		if ( function_exists( 'wootickets_init' ) ){
			if ( class_exists( 'Tribe__Events__Tickets__Woo__Woo_Tickets', true ) ){
				$instance = Tribe__Events__Tickets__Woo__Woo_Tickets::get_instance();
			} else {
				$instance = TribeWooTickets::get_instance();
			}
			$tickets = $instance->get_tickets_ids( $event );
			if ( ! empty( $tickets ) ){
				$min = PHP_INT_MAX;
				$max = -1;

				foreach ( $tickets as $ticket ) {
					$ticket = $instance->get_ticket( $event, $ticket );
					$min = min( array( $min, $ticket->price ) );
					$max = max( array( $max, $ticket->price ) );
				}

				if ( $min == $max ){
					$json['price'] = wc_price( $min );
				} else {
					$json['price'] = 'from ' . wc_price( $min ) . ' to ' . wc_price( $max );
				}
			}
		}

		return $json;
	}

	public static function template_paths( $bases ){
		return array( 'forum-' . self::$ID => plugin_dir_path( __FILE__ ) ) + $bases;
	}

}
add_action( 'init', array( 'TEC_Forum_945349', 'instance' ) );

