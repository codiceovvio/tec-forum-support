<?php
/**
 * Plugin Name:       TEC Addon: Eventbrite Timezone
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-968003
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

class TEC_Forum_968003 {

	public static $ID = 968003;

	public static $_instance = null;

	public function __construct(){
		add_filter( 'http_request_args', array( __CLASS__, 'http_request_args' ), 15, 2 );
	}

	public static function instance(){
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function http_request_args( $request, $url ) {
		if ( ! class_exists( 'Tribe__Events__Tickets__Eventbrite__Main' ) ){
			return $request;
		}

		if ( version_compare( Tribe__Events__Tickets__Eventbrite__Main::$pluginVersion, '3.11', '>=' ) ){
			return $request;
		}

		$baseurl = apply_filters( 'tribe-eventbrite-base_api_url', 'https://www.eventbriteapi.com/v3/' );
		if ( false === strpos( $url, $baseurl ) ){
			return $request;
		}

		if (
			false === strpos( $url, $baseurl . 'events' ) ||
			false !== strpos( $url, '/unpublish/?token=' ) ||
			false !== strpos( $url, '/unpublish?token=' ) ||
			false !== strpos( $url, '/publish/?token=' ) ||
			false !== strpos( $url, '/publish?token=' )
		){
			return $request;
		}

		if ( ! is_array( $request['body'] ) || ! isset( $request['body']['event.start.utc'], $request['body']['event.start.timezone'], $request['body']['event.end.utc'], $request['body']['event.end.timezone'] ) ){
			return $request;
		}

		// Remove the Z param
		$request['body']['event.start.utc'] = str_replace( 'Z', '', $request['body']['event.start.utc'] );
		$request['body']['event.end.utc'] = str_replace( 'Z', '', $request['body']['event.end.utc'] );

		// Add the timezone and the Z Param
		$request['body']['event.start.utc'] = date( Tribe__Events__Tickets__Eventbrite__API::$date_format, self::wp_strtotime( $request['body']['event.start.utc'] ) );
		$request['body']['event.end.utc'] = date( Tribe__Events__Tickets__Eventbrite__API::$date_format, self::wp_strtotime( $request['body']['event.end.utc'] ) );

		return $request;
	}

	/**
	 * Converts a locally-formatted date to a unix timestamp. This is a drop-in
	 * replacement for `strtotime()`, except that where strtotime assumes GMT, this
	 * assumes local time (as described below). If a timezone is specified, this
	 * function defers to strtotime().
	 *
	 * If there is a timezone_string available, the date is assumed to be in that
	 * timezone, otherwise it simply subtracts the value of the 'gmt_offset'
	 * option.
	 *
	 * @see strtotime()
	 * @uses get_option() to retrieve the value of 'gmt_offset'.
	 * @param string $string A date/time string. See `strtotime` for valid formats.
	 * @return int UNIX timestamp.
	 */
	private static function wp_strtotime( $string ) {
		// If there's a timezone specified, we shouldn't convert it
		try {
			$test_date = new DateTime( $string );
			if ( 'UTC' != $test_date->getTimezone()->getName() ) {
				return strtotime( $string );
			}
		} catch ( Exception $e ) {
			return strtotime( $string );
		}

		$tz = get_option( 'timezone_string' );
		if ( ! empty( $tz ) ) {
			$date = date_create( $string, new DateTimeZone( $tz ) );
			if ( ! $date ) {
				return strtotime( $string );
			}
			$date->setTimezone( new DateTimeZone( 'UTC' ) );
			return $date->format( 'U' );
		} else {
			$offset = (float) get_option( 'gmt_offset' );
			$seconds = intval( $offset * HOUR_IN_SECONDS );
			$timestamp = strtotime( $string ) - $seconds;
			return $timestamp;
		}
	}
}
add_action( 'plugins_loaded', array( 'TEC_Forum_968003', 'instance' ), 15 );