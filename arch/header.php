<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
// Get the header color scheme from customizer
$header_scheme = get_theme_mod('header_color_scheme', 'light');
$header_class = ($header_scheme === 'dark') ? 'header-dark' : 'header-light';
?>

<header class="site-header <?php echo esc_attr($header_class); ?>">
  <div class="header-container">
    <div class="header-wrapper">
      <!-- Logo -->
      <div class="header-logo">
        <?php
        if (has_custom_logo()) {
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            echo '<a href="' . esc_url(home_url('/')) . '" class="logo-link">';
            echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="logo-img" />';
            echo '</a>';
        } else {
            echo '<a class="logo-text" href="' . esc_url(home_url('/')) . '">' . get_bloginfo('name') . '</a>';
        }
        ?>
      </div>

      <!-- Mobile Toggle -->
      <button class="mobile-toggle" aria-label="Mobile Menu" aria-expanded="false">
        <span class="toggle-bar"></span>
        <span class="toggle-bar"></span>
        <span class="toggle-bar"></span>
      </button>

      <!-- Main Navigation -->
      <nav class="main-navigation">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary_menu',
            'container' => false,
            'menu_class' => 'nav-menu',
            'fallback_cb' => false,
            'add_li_class' => 'menu-item',
            'link_class' => 'menu-link'
        ));
        ?>
      </nav>
    </div>
  </div>
</header>

<script>
// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.querySelector('.mobile-toggle');
  const menu = document.querySelector('.nav-menu');
  
  toggle.addEventListener('click', function() {
    this.classList.toggle('active');
    menu.classList.toggle('active');
  });
  
  // Header Scroll Effect
  window.addEventListener('scroll', function() {
    const header = document.querySelector('.site-header');
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });
});
</script>