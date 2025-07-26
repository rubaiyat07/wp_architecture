<?php get_header(); ?>

<main class="container mt-5 pt-5 custom-page">
  <div class="row">
    <div class="col-md-12">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('mb-5'); ?>>
          <h1 class="my-5"><?php the_title(); ?></h1>
          <div class="content">
            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile; else : ?>
        <p><?php esc_html_e('Sorry, this page does not exist.', 'wp_architecture'); ?></p>
      <?php endif; ?>
    </div>

  </div>
</main>

<?php get_footer(); ?>
