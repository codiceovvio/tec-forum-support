<?php
/**
 * Plugin Name:       TEC Addon: Change Category labels
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-957928
 * Description:       The Events Calendar Support Addon
 * Version:           0.1.2
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

	public static $singular = 'Category';
	public static $plural = 'Categories';

	public function __construct(){
		if ( class_exists( 'Tribe__Events__Main' ) ){
			$this->instances['tec'] = Tribe__Events__Main::instance();
		} else {
			$this->instances['tec'] = TribeEvents::instance();
		}

		add_action( 'tribe_get_event_categories', array( __CLASS__, 'get_event_categories' ), 15, 4 );
		add_filter( 'tribe_display_settings_tab_fields', array( __CLASS__, 'options_field' ), 15, 1 );

		add_action( 'gettext', array( __CLASS__, 'theme_text' ), 15, 3 );

		self::$singular = $this->instances['tec']->getOption( self::$ID . '_categorySingular', self::$singular );
		self::$plural = $this->instances['tec']->getOption( self::$ID . '_categoryPlural', self::$plural );
	}

	public static function instance(){
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function options_field( $fields = array() ){
		// Creates the field configurations
		$field = array(
			self::$ID . '_categorySingular' => array(
				'type'            => 'text',
				'label'           => esc_attr__( 'Category Singular', 'tribe-events-calendar' ),
				'tooltip'         => esc_attr__( 'All instances of Category in Singular will be replaced by this field\'s content', 'tribe-events-calendar' ),
				'validation_type' => 'html',
			),
			self::$ID . '_categoryPlural' => array(
				'type'            => 'text',
				'label'           => esc_attr__( 'Category Plural', 'tribe-events-calendar' ),
				'tooltip'         => esc_attr__( 'All instances of Category in Plural will be replaced by this field\'s content', 'tribe-events-calendar' ),
				'validation_type' => 'html',
			)
		);
		// Places this field in the right spot
		$key = array_search( 'tribeDisableTribeBar', array_keys( $fields ) );
		$total = count( $fields );
		$fields = array_slice( $fields, 0, $key, true ) + $field + array_slice( $fields, 3, $total - 1, true );
		return $fields;
	}

	public static function get_event_categories( $html, $post_id, $args, $categories ){
		$terms = get_the_terms( $post_id, 'tribe_events_cat' );
		$categories = array();

		if ( ! empty( $terms ) ){
			foreach ( $terms as $term ) {
				$categories[] = $term->name;
			}
		}

		// check for the occurances of links in the returned string
		$label = is_null( $args['label'] ) ? _n( esc_attr( self::$singular ), esc_attr( self::$singular ), count( $categories ), 'tribe-events-calendar' ) : $args['label'];

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