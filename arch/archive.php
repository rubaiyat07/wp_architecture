<?php get_header(); ?>

<main class="container py-5 custom-page">

  <header class="mb-5 text-center">
    <h1 class="display-5">
      <?php the_archive_title(); ?>
    </h1>
    <p class="text-muted"><?php the_archive_description(); ?></p>
  </header>

  <div class="row">
    <!-- Main Content -->
    <div class="col-md-8">
      <?php if (have_posts()) : ?>
        <div class="row g-4">

          <?php while (have_posts()) : the_post(); ?>
            <div class="col-md-6">
              <div class="card h-100 shadow-sm">
                <?php if (has_post_thumbnail()) : ?>
                  <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium_large', ['class' => 'card-img-top']); ?>
                  </a>
                <?php endif; ?>

                <div class="card-body">
                  <h5 class="card-title">
                    <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                      <?php the_title(); ?>
                    </a>
                  </h5>
                  <p class="card-text"><?php the_excerpt(); ?></p>
                </div>

                <div class="card-footer text-muted small">
                  Published on <?php echo get_the_date(); ?>
                </div>
              </div>
            </div>
          <?php endwhile; ?>

        </div>

        <!-- Pagination -->
        <div class="mt-5">
          <?php the_posts_pagination([
            'mid_size' => 2,
            'prev_text' => __('« Previous', 'wp_architecture'),
            'next_text' => __('Next »', 'wp_architecture'),
          ]); ?>
        </div>

      <?php else : ?>
        <p class="text-center">No posts found in this archive.</p>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
      <?php get_sidebar(); ?>
    </div>
  </div>

</main>

<?php get_footer(); ?>
