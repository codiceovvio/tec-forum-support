<?php
/**
 * Plugin Name:       TEC Addon: Change Category label to Internships
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-957928
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

class TEC_Forum_957928 {

	public static $ID = 957928;

	public static $_instance = null;

	public static $singular = 'Internship';
	public static $plural = 'Internships';

	public function __construct(){
		add_action( 'tribe_get_event_categories', array( __CLASS__, 'get_event_categories' ), 15, 4 );
		add_action( 'gettext', array( __CLASS__, 'theme_text' ), 15, 3 );
	}

	public static function instance(){
		if ( ! is_a( self::$_instance, __CLASS__ ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function get_event_categories( $html, $post_id, $args, $categories ){
		$terms = get_the_terms( $post_id, 'tribe_events_cat' );
		$categories = array();

		foreach ( $terms as $term ) {
			$categories[] = $term->name;
		}

		// check for the occurances of links in the returned string
		$label = is_null( $args['label'] ) ? esc_attr_n( self::$singular, self::$plural, count( $categories ), 'tribe-events-calendar' ) : $args['label'];

		$html = ! empty( $categories ) ? sprintf(
			'%s%s:%s %s%s%s',
			$args['label_before'],
			$label,
			$args['label_after'],
			$args['wrap_before'],
			implode( ', ', $categories ),
			$args['wrap_after']
		) : '';

		return $html;
	}

	public static function theme_text( $text, $otext, $domain ) {
		if ( 0 !== strpos( $domain, 'tribe' ) ) {
			return $text;
		}

		$custom = array(
			'%s Categories' => '%s ' . self::$plural,
			'%s Category' => '%s ' . self::$singular,
			'Search %s Categories' => 'Search %s ' . self::$plural,
			'All %s Categories' => 'All %s ' . self::$plural,
			'Parent %s Category' => 'Parent %s ' . self::$singular,
			'Parent %s Category:' => 'Parent %s ' . self::$singular . ':',
			'Edit %s Category' => 'Edit %s ' . self::$singular,
			'Update %s Category' => 'Edit %s ' . self::$singular,
			'Add New %s Category' => 'Add New %s ' . self::$singular,
		);

		if ( ! isset( $custom[ $otext ] ) ) {
			return $text;
		}

		return $custom[ $otext ];
	}

}
add_action( 'plugins_loaded', array( 'TEC_Forum_957928', 'instance' ), 15 );