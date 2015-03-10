<?php
/**
 * Plugin Name:       The Events Calendar: Snippet 945676
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-945676
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

class TEC_Forum_945676 {

	public static $ID = 'ReverseOrder';

	public static $_instance = null;

	public function __construct(){
		add_filter( 'the_posts', array( __CLASS__, 'the_posts' ), 55, 2 );
	}

	public static function instance(){
		if ( ! is_a( self::$_instance, __CLASS__ ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function the_posts( $posts, $query ){
		if ( ! empty( $query->query_vars['eventDisplay'] ) && 'list' === $query->query_vars['eventDisplay'] && $query->tribe_is_past ) {
			$posts = array_reverse( $posts );
		}
		return $posts;
	}
}
add_action( 'init', array( 'TEC_Forum_945676', 'instance' ) );
