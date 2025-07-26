<footer class="bg-dark text-light py-4 mt-5">
  <div class="container text-center">
    <?php
    wp_nav_menu(array(
        'theme_location' => 'footer',
        'container' => false,
        'menu_class' => 'nav justify-content-center mb-3',
        'fallback_cb' => false
    ));
    ?>
    <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
