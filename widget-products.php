<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Auxesia_Products_Widget extends \Elementor\Widget_Base {

    // Widget name, title, icon, and category
    public function get_name() {
        return 'auxesia_products_widget';
    }

    public function get_title() {
        return __('Auxesia Products', 'auxesia-products-widget');
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        // Define your widget controls here
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $active_category = isset($_GET['product_cat']) ? $_GET['product_cat'] : 'all'; // Get the active category from URL parameter

        // Fetch all products based on the selected category
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1, // Show all products
        );

        if ($active_category !== 'all') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $active_category,
                ),
            );
        }

        $products_query = new WP_Query($args);
        ?>

        <!-- Display the product list -->
        <div id="product-list">
            <?php
            if ($products_query->have_posts()) {
                while ($products_query->have_posts()) {
                    $products_query->the_post();
                    global $product;
                    ?>
                    <div class="product-item" data-category="<?php echo $active_category; ?>">
                        <h3><?php echo get_the_title(); ?></h3>
                        <p><?php echo $product->get_price_html(); ?></p>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            } else {
                echo '<p>No products found.</p>';
            }
            ?>
        </div>

        <?php
    }
}
