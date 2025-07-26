<?php if ( is_active_sidebar( 'main_sidebar' ) ) : ?>
  <aside id="secondary" class="widget-area sidebar bg-light p-4">
    <?php dynamic_sidebar( 'main_sidebar' ); ?>
  </aside>
<?php endif; ?>
