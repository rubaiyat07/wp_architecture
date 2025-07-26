<?php
/*
 * Template Name: Projects Page
 */

get_header();

$selected_categories = get_theme_mod('projects_categories', []);

$args = array(
    'post_type' => 'project',
);

if (!empty($selected_categories)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'project_category',  // your custom taxonomy slug
            'field'    => 'slug',
            'terms'    => $selected_categories,
            'operator' => 'IN',
        ),
    );
}

$projects = new WP_Query($args);
?>

<section id="projects" class="projects-section">
    <div class="container">
        <h1><?php echo esc_html(get_theme_mod('projects_title', 'Recent Projects')); ?></h1>

        <?php if ($projects->have_posts()) : ?>
            <div class="projects-list">
                <?php while ($projects->have_posts()) : $projects->the_post(); ?>
                    <div class="project-item">
                        <h2><?php the_title(); ?></h2>
                        <div class="project-excerpt"><?php the_excerpt(); ?></div>
                        <!-- Add more project details here if needed -->
                    </div>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p>No projects found.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
