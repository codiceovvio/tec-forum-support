<?php
/**
 * Plugin Name:       TEC Addon: Display order details on WooCommerce
 * Plugin URI:        https://github.com/bordoni/tec-forum-support/tree/plugin-955520
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

class TEC_Forum_955520 {

	public static $ID = 955520;

	public static $_instance = null;

	public function __construct(){
		add_filter( 'woocommerce_cart_item_name', array( __CLASS__, 'cart_item_name' ), 10, 3 );
		add_action( 'woocommerce_admin_order_item_values', array( __CLASS__, 'admin_order_item_values' ), 10, 3 );
		add_action( 'woocommerce_admin_order_item_headers', array( __CLASS__, 'admin_order_item_headers' ) );
	}

	public static function instance(){
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function admin_order_item_headers( ){
		echo '<th class="tec-event sortable">' . esc_attr__( 'Event', 'woocommerce' ) . '</th>';
	}

	public static function admin_order_item_values( $_product, $item, $item_id ){
		$ticket = $_product->id;
		$event = get_post_meta( $ticket, '_tribe_wooticket_for_event', true );
		if ( $event ) {
			$value = sprintf( '<a href="%s" title="ID:%d" target="_blank"><strong>%s</strong></a>', get_edit_post_link( $event ), $event, get_the_title( $event ) );
		}

		?>
		<td class="tec-event" width="35%" data-sort-value="<?php echo absint( $event ); ?>">
			<div class="view">
				<?php echo wp_kses_post( $value ); ?>
			</div>
		</td>
		<?php
	}

	public static function cart_item_name( $title, $values, $cart_item_key ){
		$ticket_meta = get_post_meta( $values['product_id'] );
		$event_id = absint( $ticket_meta['_tribe_wooticket_for_event'][0] );

		if ( $event_id ) {
			$title = sprintf( '%s for <a href="%s" target="_blank"><strong>%s</strong></a>', $title, get_permalink( $event_id ), get_the_title( $event_id ) );
		}

		return $title;
	}
}
add_action( 'init', array( 'TEC_Forum_955520', 'instance' ) );
