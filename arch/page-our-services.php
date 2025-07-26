<?php
/* Template Name: Our Services */

get_header();
?>

<div class="container py-5 custom-page">

  <h1 class="mb-4"><?php the_title(); ?></h1>

  <section id="residential" class="slider-section mb-5">
    <h2>Residential Design</h2>
    <?php echo do_shortcode('[simple_slider category="residential" autoplay="true" interval="3000"]'); ?>
  </section>

  <section id="commercial" class="slider-section mb-5">
    <h2>Commercial Projects</h2>
    <?php echo do_shortcode('[simple_slider category="commercial"]'); ?>
  </section>

  <section id="interior" class="slider-section mb-5">
    <h2>Interior Architecture</h2>
    <?php echo do_shortcode('[simple_slider category="interior"]'); ?>
  </section>

</div>

<?php
get_footer();
?>
