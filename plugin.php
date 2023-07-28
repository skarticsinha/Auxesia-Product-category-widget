<?php
/**
 * Plugin Name: Auxesia Custom
 * Description: A custom Elementor widget for Auxesia to display products
 * Version: 1.0
 * Author: S Kartic
 * Author URI: www.github.com/skarticsinha
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register the "Auxesia Product Category" widget
function register_product_category_widget() {
    require_once plugin_dir_path(__FILE__) . 'widget-product-category.php';
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Auxesia_Product_Category());
}
add_action('elementor/widgets/widgets_registered', 'register_product_category_widget');

// Register the "Auxesia Products" widget
function register_products_widget() {
    require_once plugin_dir_path(__FILE__) . 'widget-products.php';
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Auxesia_Products_Widget());
}
add_action('elementor/widgets/widgets_registered', 'register_products_widget');
