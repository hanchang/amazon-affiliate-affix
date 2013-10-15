<?php
/*
 * Plugin Name: Amazon Affiliate Affix
 * Plugin URI: https://github.com/hanchang/amazon-affiliate-affix
 * Description: Wordpress plugin for adding a widget to the sidebar containing Amazon Associate (affiliate) links that affixes as the user scrolls.
 * Version: 0.2
 * Author: Han Chang
 * Author URI: http://www.szuhanchang.com
 * License: GPL2
 */

// TODO: REMOVE in production!!!
error_reporting(E_ALL);

add_action('widgets_init', create_function('', 'return register_widget("AmazonAffiliateAffix");'));
add_action('wp_enqueue_scripts', 'amazon_affiliate_affix_scripts');

function amazon_affiliate_affix_scripts() {
  wp_register_style('amazon-affiliate-affix-style', plugins_url('style.css', __FILE__));
  wp_enqueue_style('amazon-affiliate-affix-style');
  wp_enqueue_script('jquery-affix', plugins_url('affix.js', __FILE__), array('jquery'), false, true);
}

class AmazonAffiliateAffix extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'AmazonAffiliateAffix', // Base ID
			__('Amazon Affiliate Affix', 'text_domain'), // Name
			array( 'description' => __( 'Amazon Affiliate Affix Description', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
    $products = $this->aaa_products(get_the_ID());

    if (empty($products)) {
      return;
    }

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
    }

    foreach ($products as $product) {
?>
      <div class="aaa-product">
        <h4>
          <a class="amazon-title" href="<?php echo $product['product_url']; ?>"><?php echo $product['title']; ?></a>
          <a class="amazon-button" href="<?php echo $product['product_url']; ?>">View on Amazon</a>
        </h4>
        <div>
          <a class="aaa-image" href="<?php echo $product['product_url']; ?>"><img src="<?php echo $product['image_url']; ?>" /></a>
          <p class="aaa-description"><?php echo $product['description']; ?></p>
        </div>
      </div>
<?php
    }

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
 	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">
			<?php _e( 'Title:' ); ?>
		</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

  public function aaa_products( $post_id, $action = 'get', $product_url = '' ) {//, $product_name = '', $product_description = '' ) {
    switch ($action) {
    case 'update' :
      if ($product_url) { 
        add_post_meta( $post_id, 'product_url', $product_url );
        return true;
      }
      else {
        return false; 
      }
      
      break;
    case 'get' :
      return $this->parse_amazon_product_meta(get_post_meta($post_id, 'amazon_product'));
      break;
    case 'delete' :
      delete_post_meta( $post_id, 'product_url', $product_url );
      break;
    default :
      return false;
      break;
    } // end switch
  }

  /* Parses the array of product metadata in each post into a string. */
  private function parse_amazon_product_meta($product_meta_array) {
    $retval = array();
    $headers = array('title', 'description', 'image_url', 'product_url');
    foreach($product_meta_array as $product_meta) {
      $tmp = array_map('trim', explode('|', $product_meta));
      $retval[] = array_combine($headers, $tmp);
    }
    return $retval;
  }
} // end class
