<?php get_header(); ?>

<main class="container py-5 custom-page">
  <div class="row">
    <div class="col-md-8">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <article <?php post_class('mb-5'); ?>>

          <!-- Post Title -->
          <header class="mb-4 border-bottom pb-3">
            <h1 class="display-4 fw-semibold"><?php the_title(); ?></h1>

            <!-- Meta -->
            <div class="text-muted d-flex flex-wrap gap-3 small mt-2">
              <span><i class="bi bi-calendar-event"></i> <?php echo get_the_date(); ?></span>
              <span><i class="bi bi-person"></i> <?php the_author(); ?></span>
              <span><i class="bi bi-folder"></i> <?php the_category(', '); ?></span>
            </div>
          </header>

          <!-- Featured Image -->
          <?php if (has_post_thumbnail()) : ?>
            <div class="mb-4">
              <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded-3 shadow-sm']); ?>
            </div>
          <?php endif; ?>

          <!-- Content -->
          <div class="post-content mb-5 fs-5 lh-lg">
            <?php the_content(); ?>
          </div>

          <!-- Tags -->
          <?php the_tags('<div class="mb-4 text-muted small">Tags: <span class="badge bg-secondary me-1">', '</span><span class="badge bg-secondary me-1">', '</span></div>'); ?>

        </article>

        <!-- Post Navigation -->
        <div class="post-navigation d-flex justify-content-between align-items-center border-top pt-4 mt-5">
          <div class="prev-post">
            <?php previous_post_link('%link', '<i class="bi bi-arrow-left"></i> %title'); ?>
          </div>
          <div class="next-post text-end">
            <?php next_post_link('%link', '%title <i class="bi bi-arrow-right"></i>'); ?>
          </div>
        </div>

        <!-- Comments Section -->
        <?php if (comments_open() || get_comments_number()) : ?>
          <div class="mt-5 pt-5 border-top">
            <?php comments_template(); ?>
          </div>
        <?php endif; ?>

      <?php endwhile; else : ?>
        <p class="text-center">Sorry, no post found.</p>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4 mt-4">
      <?php get_sidebar(); ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>
