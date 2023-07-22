<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class Auxesia_Product_Category extends \Elementor\Widget_Base {

	// Your widget's name, title, icon and category
    public function get_name() {
        return 'auxesia_product_category';
    }

    public function get_title() {
        return __( 'Auxesia Product Category', 'auxesia-product-category' );
    }

    public function get_icon() {
        return 'eicon-product-related';
    }

    public function get_categories() {
        return [ 'general' ];
    }




	// widget's sidebar settings
    protected function _register_controls() {

    }





	//Renders what widget will displays on the front-end
    protected function render() {
		


    }

}
