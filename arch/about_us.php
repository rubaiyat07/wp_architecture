<?php
/* Template Name: About Us */

get_header();
?>
<div class="about-page mt-5 pt-5 custom-page">
  <!-- Hero Section -->
  <section class="about-hero text-center py-5">
    <div class="container">
      <h1>Our Architectural Vision</h1>
      <p class="lead">Creating spaces that inspire since 2005</p>
    </div>
  </section>

  <!-- Core Content - Side by Side -->
  <section class="about-content py-5">
    <div class="container">
      <div class="row align-items-center">
        <!-- Text Column -->
        <div class="col-lg-12 mb-4 mb-lg-0 pe-lg-5">
          <h2 class="mb-3">Who We Are</h2>
          <p class="mb-4">We are an award-winning architecture practice specializing in sustainable design solutions. Our team delivers innovative projects across Europe and Asia.</p>
          <img src="<?php echo get_template_directory_uri(); ?>/images/about-team.jpg" alt="Our design team" class="img-fluid rounded d-lg-none">
        </div>
        
        <!-- Cards Column -->
        <div class="col-lg-12">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="core-card h-100">
                <div class="card-icon"><i class="bi bi-tree"></i></div>
                <h3>Sustainability</h3>
                <p>Carbon-neutral designs using renewable materials and energy-efficient systems.</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="core-card h-100">
                <div class="card-icon"><i class="bi bi-lightbulb"></i></div>
                <h3>Innovation</h3>
                <p>Cutting-edge solutions blending technology with timeless design principles.</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="core-card h-100">
                <div class="card-icon"><i class="bi bi-people"></i></div>
                <h3>Collaboration</h3>
                <p>Working closely with clients to transform visions into reality.</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="core-card h-100">
                <div class="card-icon"><i class="bi bi-building"></i></div>
                <h3>Context</h3>
                <p>Designs that respect and enhance their surroundings.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Team Section -->
  <section class="team-section py-5 bg-light">
    <div class="container text-center">
      <h2 class="mb-5">Our Leadership</h2>
      <div class="row justify-content-center">
        <div class="col-md-4 mb-4">
          <div class="team-member">
            <img src="<?php echo get_template_directory_uri(); ?>/images/team-1.jpg" alt="Elena Rodriguez" class="img-fluid rounded-circle mb-3">
            <h4>Elena Rodriguez</h4>
            <p class="text-muted">Founding Partner</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="team-member">
            <img src="<?php echo get_template_directory_uri(); ?>/images/team-2.jpg" alt="Marcus Chen" class="img-fluid rounded-circle mb-3">
            <h4>Marcus Chen</h4>
            <p class="text-muted">Design Director</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
<section class="about-cta py-5 text-center">
  <div class="container">
    <h2 class="mb-4">Ready to discuss your project?</h2>
    <?php 
    // Get the Hire an Architect page URL
   $hire_page = get_page_by_path('hire-architect', OBJECT, 'page');
if ($hire_page) {
  $hire_url = get_permalink($hire_page->ID);
  echo '<a href="' . esc_url($hire_url) . '" class="btn btn-primary btn-lg px-4">Get Started</a>';
} else {
  echo '<a href="/contact" class="btn btn-primary btn-lg px-4">Get Started</a>';
}


    ?>
  </div>
</section>
</div>

<?php
get_footer();
?>
