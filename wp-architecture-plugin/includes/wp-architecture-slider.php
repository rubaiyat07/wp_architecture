<?php
/**
 * WP Simple Slider with Carousel Functionality
 * A slider plugin with category support and Bootstrap Carousel
 */

if (!defined('ABSPATH')) exit;

class WP_Architecture_Slider {
    
    public function __construct() {
        // Register hooks
        add_action('init', array($this, 'register_cpt'));
        add_filter('manage_simple_slider_posts_columns', array($this, 'add_shortcode_column'));
        add_action('manage_simple_slider_posts_custom_column', array($this, 'show_shortcode_column'), 10, 2);
        add_shortcode('simple_slider', array($this, 'slider_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    // Enqueue required assets
    public function enqueue_assets() {
        wp_enqueue_style('bootstrap-carousel', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
        wp_enqueue_script('bootstrap-carousel', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '', true);
        
        // Custom CSS for the slider
        wp_add_inline_style('bootstrap-carousel', '
            .wpss-carousel {
                margin: 2rem 0;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                border-radius: 8px;
                overflow: hidden;
            }
            .wpss-carousel .carousel-inner {
                max-height: 600px;
            }
            .wpss-carousel .carousel-item img {
                width: 100%;
                height: auto;
                object-fit: cover;
            }
            .wpss-carousel .carousel-caption {
                background: rgba(0,0,0,0.6);
                padding: 1.5rem;
                border-radius: 4px;
                bottom: 20%;
                left: 10%;
                right: 10%;
            }
            .wpss-carousel .carousel-caption h3 {
                font-size: 2rem;
                margin-bottom: 1rem;
            }
            .wpss-carousel .carousel-caption p {
                font-size: 1.1rem;
                line-height: 1.6;
            }
            .wpss-carousel .carousel-indicators {
                bottom: 20px;
            }
            .wpss-carousel .carousel-indicators button {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin: 0 5px;
                border: 2px solid #fff;
                background: transparent;
            }
            .wpss-carousel .carousel-indicators button.active {
                background: #fff;
            }
            @media (max-width: 768px) {
                .wpss-carousel .carousel-caption {
                    bottom: 10%;
                    left: 5%;
                    right: 5%;
                    padding: 1rem;
                }
                .wpss-carousel .carousel-caption h3 {
                    font-size: 1.5rem;
                }
                .wpss-carousel .carousel-caption p {
                    font-size: 1rem;
                }
            }
        ');
    }

    // Register Slider Post Type
    public function register_cpt() {
        register_post_type('simple_slider', array(
            'labels' => array(
                'name' => 'Simple Sliders',
                'singular_name' => 'Simple Slider',
                'add_new_item' => 'Add New Slide',
            ),
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => array('title', 'thumbnail', 'excerpt'),
            'show_in_rest' => true,
        ));

        // Register taxonomy for slider categories
        register_taxonomy('slider_category', 'simple_slider', array(
            'label' => 'Slider Categories',
            'public' => true,
            'hierarchical' => true,
            'rewrite' => array('slug' => 'slider-category'),
            'show_in_rest' => true,
        ));
    }

    // Add Shortcode Column to Admin List
    public function add_shortcode_column($columns) {
        $columns['shortcode'] = 'Shortcode';
        return $columns;
    }

    public function show_shortcode_column($column, $post_id) {
        if ($column == 'shortcode') {
            echo '<code>[simple_slider id="' . $post_id . '"]</code>';
            echo '<br><code>[simple_slider category="category-slug"]</code>';
        }
    }

    // Register Shortcode to Show a Carousel Slider
    public function slider_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => '',
            'category' => '',
            'autoplay' => 'true',
            'interval' => '5000'
        ), $atts, 'simple_slider');

        // If ID is provided, show only that slider
        if (!empty($atts['id'])) {
            $post_id = intval($atts['id']);
            $post = get_post($post_id);
            if (!$post || $post->post_type !== 'simple_slider') return '';

            ob_start();
            ?>
            <div id="wpss-carousel-<?php echo $post_id; ?>" class="wpss-carousel carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <?php if (has_post_thumbnail($post_id)) : ?>
                            <?php echo get_the_post_thumbnail($post_id, 'large', ['class' => 'd-block w-100']); ?>
                        <?php endif; ?>
                        <div class="carousel-caption">
                            <h3><?php echo esc_html(get_the_title($post_id)); ?></h3>
                            <p><?php echo esc_html(get_the_excerpt($post_id)); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }

        // Otherwise, show all sliders in the category (or all if no category)
        $args = array(
            'post_type' => 'simple_slider',
            'posts_per_page' => -1,
        );

        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'slider_category',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($atts['category']),
                )
            );
        }

        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            return '<p>No sliders found in this category.</p>';
        }

        $carousel_id = 'wpss-carousel-' . uniqid();
        
        ob_start();
        ?>
        <div id="<?php echo esc_attr($carousel_id); ?>" 
             class="wpss-carousel carousel slide" 
             data-bs-ride="<?php echo $atts['autoplay'] === 'true' ? 'carousel' : 'false'; ?>"
             data-bs-interval="<?php echo esc_attr($atts['interval']); ?>">
            
            <div class="carousel-inner">
                <?php 
                $first = true;
                while ($query->have_posts()) : $query->the_post(); 
                ?>
                    <div class="carousel-item <?php echo $first ? 'active' : ''; ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', ['class' => 'd-block w-100']); ?>
                        <?php endif; ?>
                        <div class="carousel-caption">
                            <h3><?php the_title(); ?></h3>
                            <p><?php the_excerpt(); ?></p>
                        </div>
                    </div>
                <?php 
                $first = false;
                endwhile; 
                wp_reset_postdata();
                ?>
            </div>

            <?php if ($query->post_count > 1) : ?>
                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr($carousel_id); ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr($carousel_id); ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <!-- Indicators -->
                <div class="carousel-indicators">
                    <?php for ($i = 0; $i < $query->post_count; $i++) : ?>
                        <button type="button" data-bs-target="#<?php echo esc_attr($carousel_id); ?>" 
                                data-bs-slide-to="<?php echo $i; ?>" 
                                <?php echo $i === 0 ? 'class="active" aria-current="true"' : ''; ?>>
                        </button>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}