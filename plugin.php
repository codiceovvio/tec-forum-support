<?php
/**
 * Plugin Name:       The Events Calendar: Snippet 948580
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-948580
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

class TEC_Forum_948580 {

	public static $ID = 948580;

	public static $_instance = null;

	public function __construct(){
		add_filter( 'tribe_events_recurrence_tooltip', array( __CLASS__, 'recurrence_tooltip' ), 15 );
	}

	public static function instance(){
		if ( ! is_a( self::$_instance, __CLASS__ ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function recurrence_tooltip( $tooltip ){
		global $wp_query;

		$post_id = get_the_ID();
		if ( tribe_is_recurring_event( $post_id ) && ( $wp_query->tribe_is_photo || tribe_is_photo() ) ) {
			$tooltip = '';
			$tooltip .= '<div class="recurringinfo">';
			$tooltip .= '<div class="event-is-recurring">';
			$tooltip .= '<div id="tribe-events-tooltip-'. $post_id .'" class="tribe-events-tooltip recurring-info-tooltip">';
			$tooltip .= '<div class="tribe-events-event-body">';
			$tooltip .= tribe_get_recurrence_text( $post_id );
			$tooltip .= '</div>';
			$tooltip .= '<span class="tribe-events-arrow"></span>';
			$tooltip .= '</div>';
			$tooltip .= '</div>';
			$tooltip .= '</div>';
		}
		return $tooltip;
	}

}
add_action( 'init', array( 'TEC_Forum_948580', 'instance' ) );
