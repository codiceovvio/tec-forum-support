<?php
/**
 * Plugin Name:       The Events Calendar: Snippet 949602
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-949602
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

class TEC_Forum_949602 {

	public static $ID = 949602;

	public static $_instance = null;

	public $instances = array();

	public function __construct(){
		if ( class_exists( 'Tribe__Events__Events' ) ){
			$this->instances['tec'] = Tribe__Events__Events::instance();
		} else {
			$this->instances['tec'] = TribeEvents::instance();
		}

		remove_filter( 'tribe-events-bar-filters', array( $this->instances['tec'], 'setup_keyword_search_in_bar' ), 1, 1 );
		add_filter( 'tribe-events-bar-filters', array( $this, 'events_bar_filter' ), 10, 1 );

		add_filter( 'tribe_display_settings_tab_fields', array( $this, 'options_field' ), 15, 1 );
	}

	public static function instance(){
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function options_field( $fields = array() ){
		// Creates the field configurations
		$field = array(
			self::$ID . '_searchOptions' => array(
				'type'            => 'textarea',
				'label'           => esc_attr__( 'Options to Search', 'tribe-events-calendar' ),
				'tooltip'         => esc_attr__( 'Each value should be on a single line.', 'tribe-events-calendar' ),
				'validation_type' => 'html',
			)
		);

		// Places this field in the right spot
		$key = array_search( 'tribeDisableTribeBar', array_keys( $fields ) );
		$total = count( $fields );

		$fields = array_slice( $fields, 0, $key, true ) + $field + array_slice( $fields, 3, $total - 1, true );
		return $fields;
	}

	public function events_bar_filter( $filters = array() ){
		$value = '';
		if ( ! empty( $_REQUEST['tribe-bar-search'] ) ) {
			$value = esc_attr( $_REQUEST['tribe-bar-search'] );
		}
		$html = array();
		$options = array_map( 'esc_attr', array_map( 'trim', (array) explode( "\n", (string) $this->instances['tec']->getOption( self::$ID . '_searchOptions', __( 'No Options Set', 'tribe-events-calendar' ) ) ) ) );

		$html[] = '<select name="tribe-bar-search" id="tribe-bar-search">';
		$html[] = '<option' . ( ! in_array( $value, $options ) ? ' selected' : '' ) . '>' . esc_attr__( 'Select an option', 'tribe-events-calendar' ) . '</option>';

		foreach ( $options as $opt ) {
			$selected = $opt == $value;
			$html[] = '<option value="' . $opt . '"' . ( $selected ? ' selected' : '' ) . '>' . $opt . '</option>';
		}
		$html[] = '</select>';

		// Here you apply your new field
		$filters['tribe-bar-search'] = array(
			'name'    => 'tribe-bar-search',
			'caption' => __( 'Search', 'tribe-events-calendar' ),
			'html'    => implode( "\n", $html ),
		);

		return $filters;
	}
}
add_action( 'init', array( 'TEC_Forum_949602', 'instance' ) );