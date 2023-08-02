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
    // Start control section for general settings
    $this->start_controls_section(
        'general_settings_section',
        [
            'label' => __('General Settings', 'auxesia-products-widget'),
        ]
    );

    // Control for product name color
    $this->add_control(
        'product_name_color',
        [
            'label' => __('Product Name Color', 'auxesia-products-widget'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#000000',
            'selectors' => [
                '{{WRAPPER}} .product h3' => 'color: {{VALUE}};',
            ],
        ]
    );

    // Control for product name typography
    $this->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'product_name_typography',
            'label' => __('Product Name Typography', 'auxesia-products-widget'),
            'selector' => '{{WRAPPER}} .product h3',
        ]
    );

    // Control for product description color
    $this->add_control(
        'product_description_color',
        [
            'label' => __('Product Description Color', 'auxesia-products-widget'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#333333',
            'selectors' => [
                '{{WRAPPER}} .product .discription' => 'color: {{VALUE}};',
            ],
        ]
    );

    // Control for product description typography
    $this->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name' => 'product_description_typography',
            'label' => __('Product Description Typography', 'auxesia-products-widget'),
            'selector' => '{{WRAPPER}} .product .discription',
        ]
    );

    // End control section for general settings
    $this->end_controls_section();

    // Start control section for category tag styles
    $this->start_controls_section(
        'category_tag_style_section',
        [
            'label' => __('Category Tag Style', 'auxesia-products-widget'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );

    // Get all product categories
    $categories = get_terms(['taxonomy' => 'product_cat']);
    foreach ($categories as $category) {
        $category_slug = $category->slug;
        $category_name = ucfirst($category_slug);
        // Control for category tag color
        $this->add_control(
            $category_slug . '_color',
            [
                'label' => __('Color for ', 'auxesia-products-widget') . $category_name,
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.tag.' . $category_slug . ' p' => 'background-color: {{VALUE}};',
                ],
            ]
        );
    }

    // End control section for category tag styles
    $this->end_controls_section();
}

    // Get all products or products by category
    protected function get_products_by_category($category_slug) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $category_slug,
                ),
            ),
        );

        if ($category_slug === 'all') {
            unset($args['tax_query']);
        }

        $products_query = new WP_Query($args);

        return $products_query->posts;
    }

    // Widget frontend render
protected function render() {
    $settings = $this->get_settings_for_display();
    $active_category = isset($_GET['product_cat']) ? $_GET['product_cat'] : 'all'; // Get the active category from URL parameter

    // Fetch products based on the active category
    $products = $this->get_products_by_category($active_category);

    // Output the product list
    echo '<section class="products">';

    // JavaScript to handle displaying products by category and update count
    echo '<script>';
    echo 'function updateProductsByCategory(category) {';
    echo '    const productsContainer = document.querySelector(".product-container");';
    echo '    const allProducts = productsContainer.querySelectorAll(".product[data-name]");';
    echo '    let visibleProductsCount = 0;';
    echo '    function hideAllProducts() {';
    echo '        allProducts.forEach(product => product.style.display = "none");';
    echo '    }';

    echo '    function showProductsByCategory(category) {';
    echo '        allProducts.forEach(product => {';
    echo '            const productCategory = product.dataset.name;';
    echo '            if (category === "all" || productCategory === "p-" + category) {';
    echo '                product.style.display = "block";';
    echo '                visibleProductsCount++;';
    echo '            }';
    echo '        });';
    echo '    }';

    echo '    hideAllProducts();';
    echo '    showProductsByCategory(category);';

    echo '    // Update the count elements';
    echo '    const countElements = document.querySelectorAll(".count");';
    echo '    countElements.forEach(countElement => countElement.style.display = "none");';
    echo '    const categoryDivs = document.querySelectorAll(".category-container");';
    echo '    categoryDivs.forEach(categoryDiv => categoryDiv.style.display = "none");';
    echo '    const activeCategoryDiv = document.querySelector(`.category-container[data-category="${category}"]`);';
    echo '    if (activeCategoryDiv) {';
    echo '        activeCategoryDiv.style.display = "block";';
    echo '    }';
    echo '}';
    echo '</script>';

    // Output the category containers
    echo '<div class="category-container" style="display: ' . ($active_category === 'all' ? 'block' : 'none') . ';" data-category="all">';
    echo '<p class="category-name">All</p>';
    $all_products_count = count($this->get_products_by_category('all'));
    echo '<p class="count" data-category="all">' . $all_products_count . ' Products Listed</p>';
    echo '</div>';

    $categories = get_terms(['taxonomy' => 'product_cat']);
    foreach ($categories as $category) {
        $category_slug = $category->slug;
        $category_name = ucfirst($category_slug);
        $category_count = count($this->get_products_by_category($category_slug));
        echo '<div class="category-container" style="display: ' . ($active_category === $category_slug ? 'block' : 'none') . ';" data-category="' . $category_slug . '">';
        echo '<p class="category-name">' . $category_name . '</p>';
        echo '<p class="count" data-category="' . $category_slug . '">' . $category_count . ' Products Listed</p>';
        echo '</div>';
    }

    // Output the product container
    echo '<div class="product-container">';

    if ($products) {
        foreach ($products as $index => $product) {
            $product_name = $product->post_title;
            $product_image_url = get_the_post_thumbnail_url($product->ID, 'full');
            $product_description = get_post_meta($product->ID, '_yoast_wpseo_metadesc', true);
            $product_url = get_permalink($product->ID); // Get the product page URL

            // Get the product category
            $product_categories = get_the_terms($product->ID, 'product_cat');
            $product_category_slug = 'all';
            if ($product_categories && !is_wp_error($product_categories)) {
                $product_category_slug = $product_categories[0]->slug;
            }

            // Generate product HTML markup
            echo '<div class="product" data-name="p-' . ($index + 1) . '">';
            echo '<div class="tag ' . $product_category_slug . '">';
            echo '<p style="background-color: ' . $settings[$product_category_slug . '_color'] . ';">' . ucfirst($product_category_slug) . '</p>';
            echo '</div>';
            echo '<img src="' . $product_image_url . '" alt="' . $product_name . '">';

            // Apply product name style
            $product_name_style = '';
            if (!empty($settings['product_name_color'])) {
                $product_name_style .= 'color: ' . $settings['product_name_color'] . ';';
            }
            if (!empty($settings['product_name_typography'])) {
                $product_name_style .= 'font-family: ' . $settings['product_name_typography']['family'] . ';';
                $product_name_style .= 'font-weight: ' . $settings['product_name_typography']['weight'] . ';';
                $product_name_style .= 'font-size: ' . $settings['product_name_typography']['size'] . 'px;';
                $product_name_style .= 'line-height: ' . $settings['product_name_typography']['line_height'] . ';';
            }
            echo '<h3 style="' . $product_name_style . '">' . $product_name . '</h3>';

            // Apply product description style
            $product_description_style = '';
            if (!empty($settings['product_description_color'])) {
                $product_description_style .= 'color: ' . $settings['product_description_color'] . ';';
            }
            if (!empty($settings['product_description_typography'])) {
                $product_description_style .= 'font-family: ' . $settings['product_description_typography']['family'] . ';';
                $product_description_style .= 'font-weight: ' . $settings['product_description_typography']['weight'] . ';';
                $product_description_style .= 'font-size: ' . $settings['product_description_typography']['size'] . 'px;';
                $product_description_style .= 'line-height: ' . $settings['product_description_typography']['line_height'] . ';';
            }
            echo '<p class="discription" style="' . $product_description_style . '">' . $product_description . '</p>';

            // Add "Request Details" button for each product with the product URL
            echo '<a href="' . $product_url . '"><button class="req-btn"> Request Details</button></a>';

            echo '</div>';
        }
    } else {
        echo '<p>No products found.</p>';
    }

    echo '</div>';
    echo '</section>';
	
	// JavaScript to handle click event on the slider container
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", () => {';
    echo '    const sliderContainer = document.querySelector(".auxesia-slider-container");';
    echo '    sliderContainer.addEventListener("click", (event) => {';
    echo '        const clickedElement = event.target;';
    echo '        if (clickedElement.classList.contains("swiper-slide")) {';
    echo '            const activeCategory = clickedElement.dataset.category;';
    echo '            updateProductsByCategory(activeCategory);';
    echo '        }';
    echo '    });';
    echo '});';
    echo '</script>';
		
    ?>

    <style>
        .products{
    max-width: 1200px;
    margin: 1rem auto;
    padding: 2rem 0 4rem;
    position: relative;
    z-index: 0;
}

.count{
    font-size: 1rem;
    color: #5E5E5E;
    margin-bottom: 1rem;
}

.product-container{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(20rem, 1fr));
    gap: 2rem;

}

.product{
    background-color: white;
    width: fit-content;
	min-width: 22.425rem;
    padding: 1.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
}

/* === tag === */

.product .tag{
    position: relative;
    z-index: 1;
    width: fit-content;
    margin-left: -1.5rem;
}

.product .tag p{
    padding: 0.75rem 1.5rem;
}

/* === tag === */

.product img{
    position: relative;
    z-index: 0;
    transition: all 0.5s;
}

.product:hover img{
    transform: scale(1.1);

}



.product h3{
    position: relative;
    z-index: 1;
    font-weight: 400;
    font-size: 1.5rem;
}

.product p{
    position: relative;
    z-index: 1;
    padding: 0.5rem 0 1rem;
}

.product button {
    position: relative;
    z-index: 1;
    background-color: #C15690;
    color: white;
    font-size: 100%;
    font-weight: 500;
    border-style: none;
    border-radius: 4px;
    padding: 1.5vh 2.5vw;
    cursor: pointer;
    transition: all 0.3s;
}

.product button:hover{
    transform: scale(0.95);
}


/* ===== Products Section ===== */



/* ===== Products Section Responsive ===== */

/* 512px */
@media(max-width: 32em) {


    .products{
        max-width: 1200px;
        margin: 1rem auto;
        padding: 2rem 0 0 1rem;
    }

    .product-container{
        grid-template-columns: repeat(auto-fit, minmax(20rem, 1fr));
        gap: 1rem;
        justify-content: center;
        align-items: center;
        margin: -2rem;
    }

    .product{
        margin-top: -5rem;
        transform: scale(0.8);
    }
    
    .product:first-child{
        margin-top: 0;
    }


}
    </style>

    <script>
    // JavaScript to handle displaying products by category
    function updateProductsByCategory(category) {
        const productsContainer = document.querySelector('.product-container');
        const allProducts = productsContainer.querySelectorAll('.product');

        function hideAllProducts() {
            allProducts.forEach(product => product.style.display = 'none');
        }

        function showProductsByCategory(category) {
            allProducts.forEach(product => {
                const productCategory = product.querySelector('.tag p').textContent.toLowerCase();
                if (category === 'all' || productCategory === category) {
                    product.style.display = 'block';
                }
            });
        }

        hideAllProducts();
        showProductsByCategory(category);
    }

    // JavaScript to handle click event on the slider container
    const sliderContainer = document.querySelector('.auxesia-slider-container');
    sliderContainer.addEventListener('click', (event) => {
        const clickedElement = event.target;
        if (clickedElement.classList.contains('swiper-slide')) {
            const activeCategory = clickedElement.dataset.category;
            updateProductsByCategory(activeCategory);
        }
    });
</script>

    <?php
    }
}
