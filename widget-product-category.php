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

    $this->add_responsive_control(
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

    $this->add_responsive_control(
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
            'separator' => 'before', // To separate the responsive control from the main control
        ]
    );

    $this->add_responsive_control(
        'show_arrows',
        [
            'label' => __('Show Arrows', 'auxesia-product-category'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'auxesia-product-category'),
            'label_off' => __('No', 'auxesia-product-category'),
            'default' => 'yes',
            'selectors' => [
                '{{WRAPPER}} .controls' => 'display: {{VALUE}};',
            ],
        ]
    );

    $this->add_responsive_control(
        'icon_color',
        [
            'label' => __('Icon Color', 'auxesia-product-category'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .controls i' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'show_arrows' => 'yes',
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
            'condition' => [
                'show_arrows' => 'yes',
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
            'condition' => [
                'show_arrows' => 'yes',
            ],
        ]
    );

    $this->add_responsive_control(
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

    $this->end_controls_section();

    $this->start_controls_section(
        'section_active_category',
        [
            'label' => __('Active Category', 'auxesia-product-category'),
        ]
    );

    $this->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'active_category_typography',
            'label' => __('Active Category Typography', 'auxesia-product-category'),
            'selector' => '{{WRAPPER}} .auxesia-slider-container .swiper-slide.active',
        ]
    );

    $this->add_control(
        'active_category_color',
        [
            'label' => __('Active Category Color', 'auxesia-product-category'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .auxesia-slider-container .swiper-slide.active' => 'color: {{VALUE}};',
            ],
        ]
    );

    $this->add_control(
        'active_category_background_color',
        [
            'label' => __('Active Category Background Color', 'auxesia-product-category'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .auxesia-slider-container .swiper-slide.active' => 'background-color: {{VALUE}};',
            ],
        ]
    );

    $this->end_controls_section();

    // Add a new responsive section for Arrow Size
    $this->start_controls_section(
        'section_responsive_arrow_size',
        [
            'label' => __('Responsive Arrow Size', 'auxesia-product-category'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_responsive_control(
        'arrow_size_responsive',
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
    $active_category = isset($_GET['product_cat']) ? $_GET['product_cat'] : 'all'; // Get the active category from URL parameter

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

    // Get active category index
    $active_category_index = 0;
    foreach ($categories as $index => $category) {
        if ($category->slug === $active_category) {
            $active_category_index = $index;
            break;
        }
    }

    $this->add_inline_editing_attributes('text_color', 'basic');
    ?>
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
            gap: 5rem;
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
    <div class="controls-wrapper">
        <div class="controls">
            <i class="<?php echo esc_attr($settings['prev_icon']['value']); ?>"></i>
            <i class="<?php echo esc_attr($settings['next_icon']['value']); ?>"></i>
        </div>
    </div>
    <div class="auxesia-slider-outer">
        <div class="auxesia-slider-container">
            <?php foreach ($categories as $index => $category) : ?>
                <div class="swiper-slide <?php echo $active_category_index === $index ? 'active' : ''; ?>"
                     onclick="handleSlideClick(this)"
                     data-category="<?php echo esc_attr($category->slug); ?>">
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

        let currentIndex = <?php echo $active_category_index; ?>;

        function updateSliderPosition() {
            const maxVisibleSlides = Math.floor(containerWidth / slideWidth);
            const maxIndex = Math.max(0, totalSlides - maxVisibleSlides + 1); // Increase maxTranslate by 1 slide (slideWidth)

            currentIndex = Math.max(0, Math.min(maxIndex, currentIndex));
            const translateXValue = -currentIndex * slideWidth;
            container.style.transform = `translateX(${translateXValue}px)`;
        }
		
		function handleSlideClick(clickedSlide) {
    const categorySlug = clickedSlide.dataset.category;
    // Now you can use the categorySlug in your code as needed.
    // ...
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

        slides.forEach((slide) => {
            slide.addEventListener('mouseover', () => {
                slide.style.cursor = 'pointer';
            });

            slide.addEventListener('mouseout', () => {
                slide.style.cursor = 'default';
            });
        });

        prevButton.addEventListener('mouseover', () => {
            outerContainer.style.cursor = 'pointer';
        });

        nextButton.addEventListener('mouseover', () => {
            outerContainer.style.cursor = 'pointer';
        });
    });

    function handleSlideClick(clickedSlide) {
        const slides = document.querySelectorAll('.swiper-slide');
        slides.forEach((slide, index) => {
            slide.classList.remove('active');
            if (slide === clickedSlide) {
                currentIndex = index;
            }
        });

        clickedSlide.classList.add('active');
        updateSliderPosition();
		
		/* Responsive script */
        function updateContainerLayout() {
            const container = document.querySelector('.auxesia-slider-container');
            const slideWidth = container.querySelector('.swiper-slide').offsetWidth;
            const containerWidth = container.offsetWidth;
            const maxVisibleSlides = Math.floor(containerWidth / slideWidth);

            container.style.flexWrap = maxVisibleSlides > 1 ? 'nowrap' : 'wrap';
            container.style.justifyContent = maxVisibleSlides > 1 ? 'flex-start' : 'center';
        }

        // Call the function initially and on window resize
        updateContainerLayout();
        window.addEventListener('resize', updateContainerLayout);
    }	
</script>
    <?php
}
}