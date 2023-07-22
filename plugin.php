<?php
/**
 * Plugin Name: Auxesia Custom
 * Description: A custom Elementor widget for Auxesia to display products
 * Version: 1.0
 * Author: S Kartic
 * Author URI: www.github.com/skarticsinha
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function register_custom_widget() {
    require_once plugin_dir_path( __FILE__ ) . 'widget.php';
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Auxesia_Product_Category() );
}
add_action( 'elementor/widgets/widgets_registered', 'register_custom_widget' );
