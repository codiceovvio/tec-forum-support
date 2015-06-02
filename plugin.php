<?php
/**
 * Plugin Name:       The Events Calendar: Snippet 939516
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-939516
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

class TEC_Forum_939516 {

	public static $ID = 939516;

	public static $_instance = null;

	public function __construct(){
		add_filter( 'tribe_events_template_data_array', array( __CLASS__, 'template_data_array' ), 10, 3 );
		add_filter( 'tribe_events_template_paths', array( __CLASS__, 'template_paths' ) );
	}

	public static function instance(){
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function template_data_array( $json, $event, $additional ){
		$json['venue'] = '';

		$venue = tribe_get_venue_id( $event );
		if ( $venue ){
			$json['venue'] = $venue;
			$json['venue_link'] = tribe_get_venue_link( $venue, false );
			$json['venue_title'] = tribe_get_venue( $venue );
		}

		return $json;
	}

	public static function template_paths( $bases ){
		return array( 'forum-' . self::$ID => plugin_dir_path( __FILE__ ) ) + $bases;
	}

}
add_action( 'init', array( 'TEC_Forum_939516', 'instance' ) );

