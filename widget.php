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
        return __('Auxesia Product Category', 'auxesia-product-category');
    }

    public function get_icon() {
        return 'eicon-product-related';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Slider Settings', 'auxesia-product-category'),
            ]
        );

        $this->add_control(
            'slider_width',
            [
                'label' => __('Slider Width', 'auxesia-product-category'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 60,
                    'unit' => 'vw',
                ],
                'range' => [
                    'vw' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .auxesia-slider-outer' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'prev_icon',
            [
                'label' => __('Previous Icon', 'auxesia-product-category'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'next_icon',
            [
                'label' => __('Next Icon', 'auxesia-product-category'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'auxesia-product-category'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .controls i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'auxesia-product-category'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .auxesia-slider-container .swiper-slide' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'categories_typography',
                'label' => __('Categories Typography', 'auxesia-product-category'),
                'selector' => '{{WRAPPER}} .auxesia-slider-container .swiper-slide',
            ]
        );

        $this->add_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'auxesia-product-category'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.1,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0.1,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .controls i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    // Get all product categories from WooCommerce excluding "Uncategorized"
    protected function get_product_categories() {
        $categories = get_terms('product_cat', array('hide_empty' => false));

        // Remove "Uncategorized" category from the list
        foreach ($categories as $key => $category) {
            if ($category->slug === 'uncategorized') {
                unset($categories[$key]);
                break;
            }
        }

        // Add "All" category to the beginning of the list
        $all_category = (object) array(
            'term_id' => 0,
            'name' => 'All',
            'slug' => 'all',
        );
        array_unshift($categories, $all_category);

        return $categories;
    }

    // Helper function to get the plugin directory URL
    protected function get_plugin_dir_url() {
        return plugin_dir_url(__FILE__);
    }

    // Widget frontend render
    protected function render() {
        $settings = $this->get_settings_for_display();
        $categories = $this->get_product_categories();

        // Add plugin directory URL
        $plugin_dir_url = $this->get_plugin_dir_url();

        // Add Font Awesome 6 stylesheet link
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';

        // Output the Font Awesome 6 webfont stylesheet
        echo '<style>
                @font-face {
                    font-family: "Font Awesome 6 Free";
                    font-style: normal;
                    font-weight: 400;
                    src: url(' . $plugin_dir_url . 'webfonts/fa-regular-400.woff2) format("woff2"),
                         url(' . $plugin_dir_url . 'webfonts/fa-regular-400.woff) format("woff");
                }
                
                .controls i {
                    font-family: "Font Awesome 6 Free";
                }
            </style>';

        // Add inline styles for arrow size
        $arrow_size = isset($settings['arrow_size']['size']) ? $settings['arrow_size']['size'] : 20;
        $arrow_unit = isset($settings['arrow_size']['unit']) ? $settings['arrow_size']['unit'] : 'px';

        $this->add_inline_editing_attributes('text_color', 'basic');
        ?>
        <style>
    .auxesia-slider-outer {
        max-width: <?php echo $settings['slider_width']['size'] . $settings['slider_width']['unit']; ?>;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
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
</style>
<div class="controls-wrapper">
    <div class="controls">
        <i class="<?php echo esc_attr($settings['prev_icon']['value']); ?>"></i>
        <i class="<?php echo esc_attr($settings['next_icon']['value']); ?>"></i>
    </div>
</div>
<div class="auxesia-slider-outer">
    <div class="auxesia-slider-container">
        <?php foreach ($categories as $category) : ?>
            <div class="swiper-slide">
                <?php echo $category->name; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const outerContainer = document.querySelector('.auxesia-slider-outer');
        const container = outerContainer.querySelector('.auxesia-slider-container');
        const slides = container.querySelectorAll('.swiper-slide');
        const prevButton = document.querySelector('.controls i:first-child');
        const nextButton = document.querySelector('.controls i:last-child');
        const slideWidth = slides[0].offsetWidth;
        const slidesPerView = 3; // Set the number of slides to show at once
        const totalSlides = slides.length;

        const containerWidth = slidesPerView * slideWidth;

        let currentIndex = 0;

        function updateSliderPosition() {
            // Calculate the maximum number of slides that can be shown without overflowing
            const maxVisibleSlides = Math.floor(containerWidth / slideWidth);

            // Calculate the maximum value for currentIndex to avoid overflowing
            const maxIndex = Math.max(0, totalSlides - maxVisibleSlides + 2); // Increase maxTranslate by 1 slide (slideWidth) and 10 pixels

            // Limit currentIndex within bounds
            currentIndex = Math.max(0, Math.min(maxIndex, currentIndex));

            const translateXValue = -currentIndex * slideWidth;
            container.style.transform = `translateX(${translateXValue}px)`;
        }

        function handleNextSlide() {
            currentIndex++;
            updateSliderPosition();
        }

        function handlePrevSlide() {
            currentIndex--;
            updateSliderPosition();
        }

        prevButton.addEventListener('click', handlePrevSlide);
        nextButton.addEventListener('click', handleNextSlide);

        // Add hover event listeners for slider elements and icons
        slides.forEach((slide) => {
            slide.addEventListener('mouseover', () => {
                outerContainer.style.cursor = 'grab';
            });

            slide.addEventListener('mouseout', () => {
                outerContainer.style.cursor = 'default';
            });
        });

        prevButton.addEventListener('mouseover', () => {
            outerContainer.style.cursor = 'pointer';
        });

        nextButton.addEventListener('mouseover', () => {
            outerContainer.style.cursor = 'pointer';
        });
    });
</script>
    <?php
}
}
