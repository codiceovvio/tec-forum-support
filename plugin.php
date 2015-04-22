<?php
/**
 * Plugin Name:       TEC Addon: Display Event details on RSS feed content
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-952243
 * Description:       The Events Calendar Support Addon
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
// add_filter( "", "plugin_function_name" )

class TEC_Forum_952243 {

	public static $ID = 952243;

	public static $_instance = null;

	public function __construct(){
		add_action( 'the_content_feed', array( __CLASS__, 'content_feed' ) );
	}

	public static function instance(){
		if ( ! is_a( self::$_instance, __CLASS__ ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function content_feed( $content ){
		if ( ! tribe_is_event( get_the_ID() ) ){
			return $content;
		}

		$content .= '<hr>';
		$content .= '<ul>';
		$content .= '<li><b>' . esc_attr_e( 'Organizer', 'tribe-events-calendar' ) . ':</b> ' . tribe_get_organizer() . '</li>';
		$content .= '<li><b>' . esc_attr_e( 'Venue', 'tribe-events-calendar' ) . ':</b> ' . tribe_get_venue() . '</li>';
		$content .= '<li><b>' . esc_attr_e( 'Address', 'tribe-events-calendar' ) . ':</b> ' . tribe_get_full_address() . '</li>';
		$content .= '<li><b>' . esc_attr_e( 'Google Map', 'tribe-events-calendar' ) . ':</b> ' . tribe_show_google_map_link() . '</li>';
		$content .= '</ul>';

		return $content;
	}

}
add_action( 'init', array( 'TEC_Forum_952243', 'instance' ) );
