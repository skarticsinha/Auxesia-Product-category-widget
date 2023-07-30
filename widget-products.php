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
    
        <section class="products">
            <p class="count"><?php echo $products_query->found_posts; ?> Products Listed</p>
            <div class="product-container">
                <?php
                if ($products_query->have_posts()) {
                    while ($products_query->have_posts()) {
                        $products_query->the_post();
                        global $product;
                        ?>
                        <div class="product" data-name="<?php echo esc_attr(get_post_field('post_name')); ?>">
                            <?php
                            // Get product categories
                            $product_categories = wp_get_post_terms(get_the_ID(), 'product_cat');
                            if (!empty($product_categories)) {
                                $tag_class = '';
                                foreach ($product_categories as $category) {
                                    $tag_class .= ' tag-' . $category->slug;
                                }
                                ?>
                                <div class="tag<?php echo $tag_class; ?>">
                                    <p><?php echo $product_categories[0]->name; ?></p>
                                </div>
                            <?php } ?>
                            <?php the_post_thumbnail(); ?>
                            <h3><?php the_title(); ?></h3>
                            <p class="description"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                            <button>Request detail</button>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p>No products found.</p>';
                }
                ?>
            </div>
        </section>

        <?php
    }
}
