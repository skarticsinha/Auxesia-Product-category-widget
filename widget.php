<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Auxesia_Product_Category extends \Elementor\Widget_Base {

    // Widget name, title, icon, and category
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

    // Widget controls/settings
    protected function _register_controls() {
        // Add controls for widget settings if needed
    }

    // Get all product categories from WooCommerce excluding "Uncategorized"
    protected function get_product_categories() {
        $categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );

        // Remove "Uncategorized" category from the list
        foreach ( $categories as $key => $category ) {
            if ( $category->slug === 'uncategorized' ) {
                unset( $categories[ $key ] );
                break;
            }
        }

        // Add "All" category to the beginning of the list
        $all_category = (object) array(
            'term_id' => 0,
            'name' => 'All',
            'slug' => 'all',
        );
        array_unshift( $categories, $all_category );

        return $categories;
    }

    // Widget frontend render
    protected function render() {
        $settings = $this->get_settings_for_display();
        $categories = $this->get_product_categories();
        ?>
        <style>
            .auxesia-slider-outer {
                max-width: 60vw;
                margin: 0 auto;
                overflow: hidden;
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
            }
            .controls {
                display: flex;
                justify-content: space-between;
                margin-top: 10px;
            }
        </style>
        <div class="auxesia-slider-outer">
            <div class="auxesia-slider-container">
                <?php foreach ( $categories as $category ) : ?>
                    <div class="swiper-slide">
                        <?php echo $category->name; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="controls">
                <button class="prev-button">Previous</button>
                <button class="next-button">Next</button>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const outerContainer = document.querySelector('.auxesia-slider-outer');
                const container = outerContainer.querySelector('.auxesia-slider-container');
                const slides = container.querySelectorAll('.swiper-slide');
                const prevButton = outerContainer.querySelector('.prev-button');
                const nextButton = outerContainer.querySelector('.next-button');
                const slideWidth = slides[0].offsetWidth;
                const slidesPerView = 3; // Set the number of slides to show at once
                let currentIndex = 0;

                // Calculate the width of each slide based on the number of slides to show
                const slideWidthWithSpacing = slideWidth * slidesPerView;
                container.style.width = `${slideWidthWithSpacing}px`;

                function updateSliderPosition() {
                    container.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
                }

                function handleNextSlide() {
                    currentIndex = Math.min(currentIndex + 1, slides.length - slidesPerView);
                    updateSliderPosition();
                }

                function handlePrevSlide() {
                    currentIndex = Math.max(currentIndex - 1, 0);
                    updateSliderPosition();
                }

                prevButton.addEventListener('click', handlePrevSlide);
                nextButton.addEventListener('click', handleNextSlide);
            });
        </script>
        <?php
    }
}
