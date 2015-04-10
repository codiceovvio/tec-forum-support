<?php
/**
 * Plugin Name:       TEC Snippet: Community Form with Tags
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-954138
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

class TEC_Forum_954138 {

	public static $ID = 954138;

	public static $_instance = null;

	public function __construct(){
		add_filter( 'tribe_events_community_after_the_content', array( __CLASS__, 'community_after_the_content' ) );
		add_filter( 'tribe_events_community_allowed_event_fields', array( __CLASS__, 'allowed_event_fields' ), 15 );
	}

	public static function instance(){
		if ( ! is_a( self::$_instance, __CLASS__ ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function allowed_event_fields( $allowed_fields ){
		$allowed_fields[] = 'tags_input';
		return $allowed_fields;
	}

	public static function community_after_the_content(){
		$tagi = '';

		if ( isset( $_POST['tags_input'] ) ) {
			$tagi = esc_attr( strip_tags( $_POST['tags_input'] ) );
		} else {
			$a = array();
			$posttags = get_the_tags();
			if ( $posttags ) {
				foreach ( $posttags as $tag ) {
					$a[]  = $tag->name;
				}
			}
			$tagi = implode( ',', $a );
		}

		?>
		<div class="events-community-post-content bubble" id="event_tags">
			<table class="tribe-community-event-info" cellspacing="0" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td colspan="2" class="tribe_sectionheader">
							<h4 class="event-time">Event Tags:</h4>
						</td>
					</tr>
					<tr>
						<td>
							<textarea name="tags_input" placeholder="E.g.: Use commas to separate each one of the tags"><?php echo esc_attr( $tagi ); ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}

}
add_action( 'init', array( 'TEC_Forum_954138', 'instance' ) );

