<?php get_header(); ?>

<main class="container mt-5 custom-page">
    <?php if ( have_posts() ) : ?>
        <div class="row">
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-body">
                            <h2 class="card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="card-text"><?php the_excerpt(); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p><?php esc_html_e('No posts found.', 'wp_architecture'); ?></p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
