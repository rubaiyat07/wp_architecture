<?php

// Theme Support
function wp_architecture_theme_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('custom-logo');
  add_theme_support('custom-background');
  add_theme_support('custom-header');
  add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

  register_nav_menus(array(
    'primary_menu' => 'Primary Menu',
    'footer_menu' => 'Footer Menu'
  ));
}
add_action('after_setup_theme', 'wp_architecture_theme_setup');


// Enqueue Styles and Scripts
function wp_architecture_enqueue_scripts() {
    // Styles
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css');
    wp_enqueue_style('style', get_stylesheet_uri());

    // Scripts
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    
    // Add main.js - assuming it's in your theme's js directory
    wp_enqueue_script('main-js', get_template_directory_uri() . '/js/main.js', array('jquery', 'bootstrap-js'), '1.0', true);
    
    // Localize script if you need to pass PHP variables to JS
    wp_localize_script('main-js', 'wpArchitectureVars', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wp_architecture_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'wp_architecture_enqueue_scripts');


// Register Custom Post Type for Projects
function wp_architecture_register_cpt() {
  register_post_type('project', array(
    'labels' => array(
      'name' => 'Projects',
      'singular_name' => 'Project',
      'add_new_item' => 'Add New Project',
      'edit_item' => 'Edit Project',
      'new_item' => 'New Project',
      'view_item' => 'View Project',
      'search_items' => 'Search Projects',
      'not_found' => 'No projects found',
      'not_found_in_trash' => 'No projects found in trash',
    ),
    'public' => true,
    'has_archive' => true,
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
    'rewrite' => array('slug' => 'projects'),
    'show_in_rest' => true,
    'menu_icon' => 'dashicons-portfolio',
    'taxonomies' => array('project_category'), // Show taxonomy in admin post editor
  ));
}
add_action('init', 'wp_architecture_register_cpt');


// Register Custom Taxonomy for Project Categories
function wp_architecture_register_project_taxonomy() {
  $labels = array(
    'name'              => 'Project Categories',
    'singular_name'     => 'Project Category',
    'search_items'      => 'Search Project Categories',
    'all_items'         => 'All Project Categories',
    'parent_item'       => 'Parent Project Category',
    'parent_item_colon' => 'Parent Project Category:',
    'edit_item'         => 'Edit Project Category',
    'update_item'       => 'Update Project Category',
    'add_new_item'      => 'Add New Project Category',
    'new_item_name'     => 'New Project Category Name',
    'menu_name'         => 'Project Categories',
  );

  $args = array(
    'hierarchical'      => true, // Category-like taxonomy
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array('slug' => 'project-category'),
    'show_in_rest'      => true, // Gutenberg support
  );

  register_taxonomy('project_category', array('project'), $args);
}
add_action('init', 'wp_architecture_register_project_taxonomy');


// Add Project ID column in admin project list
function wp_architecture_projects_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'cb') { // after checkbox column
            $new_columns['project_id'] = 'Project ID';
        }
    }
    return $new_columns;
}
add_filter('manage_project_posts_columns', 'wp_architecture_projects_columns');


// Populate Project ID column content
function wp_architecture_projects_custom_column($column, $post_id) {
    if ($column === 'project_id') {
        echo esc_html($post_id);
    }
}
add_action('manage_project_posts_custom_column', 'wp_architecture_projects_custom_column', 10, 2);


// Customizer Settings
function wp_architecture_customize_register($wp_customize) {
  // Hero Section
  $wp_customize->add_section('hero_section', array(
    'title' => 'Hero Section',
    'priority' => 10,
  ));
  $wp_customize->add_setting('hero_title', array('default' => 'Designing the Future of Spaces'));
  $wp_customize->add_control('hero_title', array(
    'label' => 'Hero Title',
    'section' => 'hero_section',
    'type' => 'text',
  ));
  $wp_customize->add_setting('hero_subtitle', array('default' => 'Innovative, sustainable, and elegant architecture solutions.'));
  $wp_customize->add_control('hero_subtitle', array(
    'label' => 'Hero Subtitle',
    'section' => 'hero_section',
    'type' => 'textarea',
  ));
  $wp_customize->add_setting('hero_button_text', array('default' => 'Learn More'));
  $wp_customize->add_control('hero_button_text', array(
    'label' => 'Hero Button Text',
    'section' => 'hero_section',
    'type' => 'text',
  ));
  $wp_customize->add_setting('hero_bg_image');
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_bg_image', array(
    'label' => 'Hero Background Image',
    'section' => 'hero_section',
    'settings' => 'hero_bg_image'
  )));

  // About Section
  $wp_customize->add_section('about_section', array(
    'title' => 'About Section',
    'priority' => 20,
  ));
  $wp_customize->add_setting('about_title', array('default' => 'About Us'));
  $wp_customize->add_control('about_title', array(
    'label' => 'About Title',
    'section' => 'about_section',
    'type' => 'text',
  ));
  $wp_customize->add_setting('about_description', array('default' => 'We are a leading architectural firm...'));
  $wp_customize->add_control('about_description', array(
    'label' => 'About Description',
    'section' => 'about_section',
    'type' => 'textarea',
  ));
  $wp_customize->add_setting('about_image');
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'about_image', array(
    'label' => 'About Image',
    'section' => 'about_section',
    'settings' => 'about_image'
  )));

  // Services Section
  $wp_customize->add_section('services_section', array(
    'title' => 'Services Section',
    'priority' => 30,
  ));
  $wp_customize->add_setting('services_title', array('default' => 'Our Services'));
  $wp_customize->add_control('services_title', array(
    'label' => 'Services Title',
    'section' => 'services_section',
    'type' => 'text',
  ));

  // Services Page Links
  $wp_customize->add_section('services_links', array(
    'title' => __('Service Page Links', 'wp_architecture'),
    'priority' => 130,
  ));

  $wp_customize->add_setting('service_link_residential');
  $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'service_link_residential', array(
    'label' => __('Residential Design Page URL', 'wp_architecture'),
    'section' => 'services_links',
    'type' => 'dropdown-pages'
  )));

  $wp_customize->add_setting('service_link_commercial');
  $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'service_link_commercial', array(
    'label' => __('Commercial Projects Page URL', 'wp_architecture'),
    'section' => 'services_links',
    'type' => 'dropdown-pages'
  )));

  $wp_customize->add_setting('service_link_interior');
  $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'service_link_interior', array(
    'label' => __('Interior Design Page URL', 'wp_architecture'),
    'section' => 'services_links',
    'type' => 'dropdown-pages'
  )));

  // Projects Section
  $wp_customize->add_section('projects_section', array(
    'title' => 'Projects Section',
    'priority' => 40,
  ));
  $wp_customize->add_setting('projects_title', array('default' => 'Recent Projects'));
  $wp_customize->add_control('projects_title', array(
    'label' => 'Projects Title',
    'section' => 'projects_section',
    'type' => 'text',
  ));

  // CTA Section
  $wp_customize->add_section('cta_section', array(
    'title' => 'CTA Section',
    'priority' => 50,
  ));
  $wp_customize->add_setting('cta_title', array('default' => "Let's Build Something Beautiful Together"));
  $wp_customize->add_control('cta_title', array(
    'label' => 'CTA Title',
    'section' => 'cta_section',
    'type' => 'text',
  ));
  $wp_customize->add_setting('cta_button_text', array('default' => 'Contact Us'));
  $wp_customize->add_control('cta_button_text', array(
    'label' => 'CTA Button Text',
    'section' => 'cta_section',
    'type' => 'text',
  ));
  $wp_customize->add_setting('cta_button_url', array('default' => home_url('/contact')));
  $wp_customize->add_control('cta_button_url', array(
    'label' => 'CTA Button URL',
    'section' => 'cta_section',
    'type' => 'url',
  ));

  // Add this to the wp_architecture_customize_register function, in the CTA Section area
$wp_customize->add_setting('cta_description', array(
  'default' => 'From concept to completion, we\'re with you every step of the way...'
));
$wp_customize->add_control('cta_description', array(
  'label' => 'CTA Description',
  'section' => 'cta_section',
  'type' => 'textarea',
));

// Stats
$wp_customize->add_setting('cta_stat1', array('default' => '25+'));
$wp_customize->add_control('cta_stat1', array(
  'label' => 'Stat 1 Value',
  'section' => 'cta_section',
  'type' => 'text',
));

$wp_customize->add_setting('cta_stat1_label', array('default' => 'Years of Excellence'));
$wp_customize->add_control('cta_stat1_label', array(
  'label' => 'Stat 1 Label',
  'section' => 'cta_section',
  'type' => 'text',
));

$wp_customize->add_setting('cta_stat2', array('default' => '1,564+'));
$wp_customize->add_control('cta_stat2', array(
  'label' => 'Stat 2 Value',
  'section' => 'cta_section',
  'type' => 'text',
));

$wp_customize->add_setting('cta_stat2_label', array('default' => 'Satisfied Clients'));
$wp_customize->add_control('cta_stat2_label', array(
  'label' => 'Stat 2 Label',
  'section' => 'cta_section',
  'type' => 'text',
));

$wp_customize->add_setting('cta_stat3', array('default' => '50'));
$wp_customize->add_control('cta_stat3', array(
  'label' => 'Stat 3 Value',
  'section' => 'cta_section',
  'type' => 'text',
));

$wp_customize->add_setting('cta_stat3_label', array('default' => 'Industry Awards'));
$wp_customize->add_control('cta_stat3_label', array(
  'label' => 'Stat 3 Label',
  'section' => 'cta_section',
  'type' => 'text',
));

// CTA Images
$wp_customize->add_setting('cta_image_top');
$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'cta_image_top', array(
  'label' => 'Top CTA Image',
  'section' => 'cta_section',
)));

$wp_customize->add_setting('cta_image_left');
$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'cta_image_left', array(
  'label' => 'Left Bottom CTA Image',
  'section' => 'cta_section',
)));

$wp_customize->add_setting('cta_image_right');
$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'cta_image_right', array(
  'label' => 'Right Bottom CTA Image',
  'section' => 'cta_section',
)));

  // Header Color Scheme
  $wp_customize->add_section('header_options', array(
    'title' => 'Header Options',
    'priority' => 5,
  ));
  $wp_customize->add_setting('header_color_scheme', array(
    'default' => 'light',
    'sanitize_callback' => 'sanitize_text_field',
  ));
  $wp_customize->add_control('header_color_scheme', array(
    'label' => 'Header Color Scheme',
    'section' => 'header_options',
    'type' => 'radio',
    'choices' => array(
      'light' => 'Light',
      'dark' => 'Dark',
    ),
  ));

  // Leadership Section
  $wp_customize->add_section('leadership_section', array(
    'title' => 'Leadership Section',
    'priority' => 60,
  ));

  // Section Title & Subtitle
  $wp_customize->add_setting('leadership_title', array('default' => 'Words from Our Leaders'));
  $wp_customize->add_control('leadership_title', array(
    'label' => 'Section Title',
    'section' => 'leadership_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('leadership_subtitle', array('default' => 'Insights and vision from our leadership team'));
  $wp_customize->add_control('leadership_subtitle', array(
    'label' => 'Section Subtitle',
    'section' => 'leadership_section',
    'type' => 'text',
  ));

  // Leader 1
  $wp_customize->add_setting('leader1_name', array('default' => 'John Smith'));
  $wp_customize->add_control('leader1_name', array(
    'label' => 'Leader 1 Name',
    'section' => 'leadership_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('leader1_title', array('default' => 'Founder & CEO'));
  $wp_customize->add_control('leader1_title', array(
    'label' => 'Leader 1 Title',
    'section' => 'leadership_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('leader1_quote', array('default' => 'Great architecture is about creating spaces that inspire people to live better lives.'));
  $wp_customize->add_control('leader1_quote', array(
    'label' => 'Leader 1 Quote',
    'section' => 'leadership_section',
    'type' => 'textarea',
  ));

  $wp_customize->add_setting('leader1_image');
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'leader1_image', array(
    'label' => 'Leader 1 Image',
    'section' => 'leadership_section',
  )));

  // Leader 2
  $wp_customize->add_setting('leader2_name', array('default' => 'Sarah Johnson'));
  $wp_customize->add_control('leader2_name', array(
    'label' => 'Leader 2 Name',
    'section' => 'leadership_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('leader2_title', array('default' => 'Lead Architect'));
  $wp_customize->add_control('leader2_title', array(
    'label' => 'Leader 2 Title',
    'section' => 'leadership_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('leader2_quote', array('default' => 'Sustainability isn\'t just a trend - it\'s our responsibility to future generations.'));
  $wp_customize->add_control('leader2_quote', array(
    'label' => 'Leader 2 Quote',
    'section' => 'leadership_section',
    'type' => 'textarea',
  ));

  $wp_customize->add_setting('leader2_image');
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'leader2_image', array(
    'label' => 'Leader 2 Image',
    'section' => 'leadership_section',
  )));

  // Leader 3
  $wp_customize->add_setting('leader3_name', array('default' => 'Michael Chen'));
  $wp_customize->add_control('leader3_name', array(
    'label' => 'Leader 3 Name',
    'section' => 'leadership_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('leader3_title', array('default' => 'Design Director'));
  $wp_customize->add_control('leader3_title', array(
    'label' => 'Leader 3 Title',
    'section' => 'leadership_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('leader3_quote', array('default' => 'Every project is an opportunity to redefine what\'s possible in architectural design.'));
  $wp_customize->add_control('leader3_quote', array(
    'label' => 'Leader 3 Quote',
    'section' => 'leadership_section',
    'type' => 'textarea',
  ));

  $wp_customize->add_setting('leader3_image');
  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'leader3_image', array(
    'label' => 'Leader 3 Image',
    'section' => 'leadership_section',
  )));
}
add_action('customize_register', 'wp_architecture_customize_register');



// Register Widgets (Sidebar)
function wp_architecture_widgets_init() {
  register_sidebar(array(
    'name' => 'Main Sidebar',
    'id' => 'main_sidebar',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ));
}
add_action('widgets_init', 'wp_architecture_widgets_init');


// Additional script enqueue (your arch-main-js)
function arch_enqueue_scripts() {
    wp_enqueue_script('jquery'); // jQuery load koro
    wp_enqueue_script(
        'arch-main-js',
        get_template_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'arch_enqueue_scripts');

