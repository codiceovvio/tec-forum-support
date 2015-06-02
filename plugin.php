<?php
/**
 * Plugin Name:       TEC Addon: Organizer Email with 'mailto:'
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-957175
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

class TEC_Forum_957175 {

	public static $ID = 957175;

	public static $_instance = null;

	public function __construct(){
		add_action( 'tribe_get_organizer_email', array( __CLASS__, 'get_organizer_email' ) );
	}

	public static function instance(){
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function get_organizer_email( $email ){
		if ( ! is_email( $email ) || ! tribe_is_event( $GLOBALS['wp_query']->post->ID ) ) {
			return $email;
		}

		return '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
	}

}
add_action( 'init', array( 'TEC_Forum_957175', 'instance' ) );
