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

        <style>
        .auxesia-slider-outer {
            max-width: <?php echo $settings['slider_width']['size'] . $settings['slider_width']['unit']; ?>;
            margin: 0 auto;
            overflow: hidden;
            position: relative;
			z-index: 2;
        }

        .auxesia-slider-container {
            display: flex;
            align-items: center;
            gap: 20px;
            white-space: nowrap;
            transition: transform 0.3s ease;
        }

        .swiper-slide {
            display: inline-block;
            width: max-content;
            color: <?php echo $settings['text_color']; ?>;
            <?php if ($settings['categories_typography']['font_size']) : ?>
                font-size: <?php echo $settings['categories_typography']['font_size']; ?>;
            <?php endif; ?>
        }

        .controls-wrapper {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: calc(<?php echo $settings['slider_width']['size'] . $settings['slider_width']['unit']; ?> + 5vw);
            z-index: 1;
            /* Allow controls to overflow */
            overflow: visible;
        }

        .controls {
            display: flex;
            justify-content: space-between;
        }

        .controls i {
            color: <?php echo $settings['icon_color']; ?>;
            font-size: <?php echo $arrow_size . $arrow_unit; ?>;
        }

        /* Position the left arrow on the left side */
        .controls i:first-child {
            margin-right: 30px; /* Add spacing between controls */
        }

        /* Position the right arrow on the right side */
        .controls i:last-child {
            margin-left: 30px; /* Add spacing between controls */
        }

        /* Set active category style */
        .auxesia-slider-container .swiper-slide.active {
            font-weight: bold;
            /* Add additional styles for active category here */
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
			
			.elementor-137 .elementor-element.elementor-element-a258427 .auxesia-slider-outer {
    			max-width: <?php echo $settings['slider_width']['size'] . $settings['slider_width']['unit']; ?>; !important
			}
			
            .auxesia-slider-container {
                gap: 10px; /* Reduce the gap between slides */
            }
            
            .controls-wrapper {
                width: calc(<?php echo $settings['slider_width']['size'] . $settings['slider_width']['unit']; ?> + 10vw);
				transform: translateX(-50%) translateY(25%);
            }

            .controls i {
                font-size: <?php echo $arrow_size / 1.5 . $arrow_unit; ?>; /* Reduce arrow size for smaller devices */
            }
        }

        @media (max-width: 576px) {
			
			.elementor-137 .elementor-element.elementor-element-a258427 .auxesia-slider-outer {
    			max-width: <?php echo $settings['slider_width']['size'] . $settings['slider_width']['unit']; ?>; !important
			}
			
            .auxesia-slider-container {
                flex-wrap: nowrap; /* Blocks the slides to wrap to a new line on smaller devices */
            }

            .swiper-slide {
                white-space: normal; /* Allow the category names to wrap */
                text-align: center; /* Center the text on smaller devices */
            }

            .controls-wrapper {
                position: absolute; /* Absolute position for smaller devices */
                transform: none;
                width: calc(<?php echo $settings['slider_width']['size'] . $settings['slider_width']['unit']; ?> + 15vw);
				transform: translateX(-25%);
                margin-top: 10px; /* Add margin between the slides and controls */
            }

            .controls {
                justify-content: space-between; /* Center the controls on smaller devices */
				transform: translateX(-25%) translateY(-25%);
            }

            .controls i {
                margin: 0 5px; /* Reduce the spacing between arrows on smaller devices */
            }
        }
    </style>

        <?php
    }
}
