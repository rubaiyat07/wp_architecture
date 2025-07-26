<?php get_header(); ?>

<main>

  <!-- Hero Section -->
  <section class="hero bg-dark text-white d-flex align-items-center" style="min-height: 80vh; background: url('<?php echo esc_url(get_theme_mod('hero_bg_image', get_template_directory_uri() . '/assets/img/hero.jpg')); ?>') no-repeat center center / cover;">
    <div class="container text-center">
      <h1 class="display-3 fw-bold mt-2"><?php echo esc_html(get_theme_mod('hero_title', 'Designing the Future of Spaces')); ?></h1>
      <p class="lead"><?php echo esc_html(get_theme_mod('hero_subtitle', 'Innovative, sustainable, and elegant architecture solutions.')); ?></p>
      <a href="#about" class="btn btn-outline-light btn-lg mt-4"><?php echo esc_html(get_theme_mod('hero_button_text', 'Learn More')); ?></a>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-5 bg-light">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h2 class="fw-bold"><?php echo esc_html(get_theme_mod('about_title', 'About Us')); ?></h2>
          <p><?php echo esc_html(get_theme_mod('about_description', 'We are a leading architectural firm specializing in modern and sustainable designs. Our mission is to create spaces that inspire and endure.')); ?></p>
        </div>
        <div class="col-md-6">
          <img src="<?php echo esc_url(get_theme_mod('about_image', get_template_directory_uri() . '/assets/img/about.jpg')); ?>" class="img-fluid rounded shadow" alt="About us">
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
<section id="services" class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5 fw-bold mb-3"><?php echo esc_html(get_theme_mod('services_title', 'Our Services')); ?></h2>
      <p class="lead text-muted"><?php echo esc_html(get_theme_mod('services_subtitle', 'Comprehensive architectural solutions')); ?></p>
    </div>
    <div class="row g-4">

      <!-- Residential Design -->
      <div class="col-md-4">
        <div class="service-card">
          <div class="service-icon">
            <i class="bi bi-building"></i>
          </div>
          <h3 class="service-title">Residential Design</h3>
          <p class="service-description">Innovative and functional homes tailored to your lifestyle.</p>
          <a href="<?php echo site_url('/our-services/#residential'); ?>" class="service-link">
            Learn more <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Commercial Projects -->
      <div class="col-md-4">
        <div class="service-card">
          <div class="service-icon">
            <i class="bi bi-bank"></i>
          </div>
          <h3 class="service-title">Commercial Projects</h3>
          <p class="service-description">Efficient, sustainable business spaces that drive success.</p>
          <a href="<?php echo site_url('/our-services/#commercial'); ?>" class="service-link">
            Learn more <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Interior Architecture -->
      <div class="col-md-4">
        <div class="service-card">
          <div class="service-icon">
            <i class="bi bi-bricks"></i>
          </div>
          <h3 class="service-title">Interior Architecture</h3>
          <p class="service-description">Designing beautiful interiors with a focus on experience and flow.</p>
          <a href="<?php echo site_url('/our-services/#interior'); ?>" class="service-link">
            Learn more <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Client Feedbacks Section -->
<section id="client-feedbacks" class="py-5">
  <div class="container">
    <div class="row align-items-center">
      <!-- Feedback Cards (Left Side) -->
      <div class="col-lg-8">
        <div class="feedback-carousel">
          <?php
          global $wpdb;
          $table_name = $wpdb->prefix . 'wp_architecture_feedback';
          
          $feedbacks = $wpdb->get_results(
              $wpdb->prepare(
                  "SELECT * FROM $table_name WHERE message_type = %s ORDER BY submitted_at DESC LIMIT %d",
                  'feedback',
                  3
              )
          );
          
          if ($feedbacks) {
              foreach ($feedbacks as $feedback) {
                  ?>
                  <div class="feedback-card">
                    <div class="feedback-icon">
                      <i class="bi bi-chat-square-quote"></i>
                    </div>
                    <div class="feedback-content">
                      <blockquote class="feedback-text">
                        "<?php echo esc_html(wp_trim_words($feedback->message, 30)); ?>"
                      </blockquote>
                      <div class="feedback-author">
                        <div class="author-avatar">
                          <?php echo get_avatar($feedback->email, 60, '', $feedback->name); ?>
                        </div>
                        <div class="author-info">
                          <h5><?php echo esc_html($feedback->name); ?></h5>
                          <?php if (!empty($feedback->company)): ?>
                            <p class="author-company"><?php echo esc_html($feedback->company); ?></p>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
              }
          } else {
              echo '<div class="feedback-card no-feedback">No feedbacks found.</div>';
          }
          ?>
        </div>
      </div>
      
      <!-- Section Title (Right Side) -->
      <div class="col-lg-4">
        <div class="section-title-wrapper">
          <span class="section-subtitle">Testimonials</span>
          <h2 class="section-title">Client Feedbacks</h2>
          <p class="section-description">Hear what our valued clients say about our architectural services and their experiences working with us.</p>
          <div class="section-decoration">
            <div class="decoration-line"></div>
            <div class="decoration-dot"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



  <!-- Portfolio (Recent Projects using CPT) -->
  <section id="projects" class="py-5 bg-light">
    <div class="container">
      <h2 class="fw-bold text-center mb-5"><?php echo esc_html(get_theme_mod('projects_title', 'Recent Projects')); ?></h2>
      <div class="row">
        <?php
        $args = array(
          'post_type' => 'project',
          'posts_per_page' => 3
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
          while ($query->have_posts()) : $query->the_post(); ?>
            <div class="col-md-4 mb-4">
              <div class="card h-100 shadow-sm">
                <?php if (has_post_thumbnail()) : ?>
                  <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?></a>
                <?php endif; ?>
                <div class="card-body">
                  <h5 class="card-title"><?php the_title(); ?></h5>
                  <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                  <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary">View Project</a>
                </div>
              </div>
            </div>
        <?php endwhile; wp_reset_postdata(); else : ?>
          <p class="text-center">No projects found.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>


<!-- Leadership Section -->
<section id="leadership" class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5 fw-bold mb-3"><?php echo esc_html(get_theme_mod('leadership_title', 'Words from Our Leaders')); ?></h2>
      <p class="lead text-muted"><?php echo esc_html(get_theme_mod('leadership_subtitle', 'Insights and vision from our leadership team')); ?></p>
    </div>
    
    <div class="row g-4">
      <?php
      $leaders = array(
        array(
          'name' => get_theme_mod('leader1_name', 'John Smith'),
          'title' => get_theme_mod('leader1_title', 'Founder & CEO'),
          'quote' => get_theme_mod('leader1_quote', 'Great architecture is about creating spaces that inspire people to live better lives.'),
          'image' => get_theme_mod('leader1_image', get_template_directory_uri() . '/assets/img/leader1.jpg')
        ),
        array(
          'name' => get_theme_mod('leader2_name', 'Sarah Johnson'),
          'title' => get_theme_mod('leader2_title', 'Lead Architect'),
          'quote' => get_theme_mod('leader2_quote', 'Sustainability isn\'t just a trend - it\'s our responsibility to future generations.'),
          'image' => get_theme_mod('leader2_image', get_template_directory_uri() . '/assets/img/leader2.jpg')
        ),
        array(
          'name' => get_theme_mod('leader3_name', 'Michael Chen'),
          'title' => get_theme_mod('leader3_title', 'Design Director'),
          'quote' => get_theme_mod('leader3_quote', 'Every project is an opportunity to redefine what\'s possible in architectural design.'),
          'image' => get_theme_mod('leader3_image', get_template_directory_uri() . '/assets/img/leader3.jpg')
        )
      );
      
      foreach ($leaders as $leader) : ?>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body text-center">
              <img src="<?php echo esc_url($leader['image']); ?>" class="rounded-circle" width="120" height="120" alt="<?php echo esc_attr($leader['name']); ?>">
              <blockquote class="blockquote">
                <p class="font-italic">"<?php echo esc_html($leader['quote']); ?>"</p>
              </blockquote>
              <h5><?php echo esc_html($leader['name']); ?></h5>
              <p class="text-muted"><?php echo esc_html($leader['title']); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


  <!-- CTA Section -->
<section class="py-5 text-center cta-section text-secondary">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h2 class="fw-bold mb-4">Discover how we can bring your architectural dreams to life.</h2>
        <p class="lead mb-4">From concept to completion, we're with you every step of the way. Ready to start your journey? Contact us today for a free consultation and let's create something extraordinary together.</p>
        
        <div class="stats mb-5">
          <div class="row g-4">
            <div class="col-4">
              <div class="stat-number">25+</div>
              <div class="stat-label">Years of Excellence</div>
            </div>
            <div class="col-4">
              <div class="stat-number">1,564+</div>
              <div class="stat-label">Satisfied Clients</div>
            </div>
            <div class="col-4">
              <div class="stat-number">50</div>
              <div class="stat-label">Industry Awards</div>
            </div>
          </div>
        </div>
        
        <a href="<?php echo esc_url(get_theme_mod('cta_button_url', home_url('/contact'))); ?>" class="btn btn-light btn-lg mt-3 cta-button">
          <?php echo esc_html(get_theme_mod('cta_button_text', "Let's Work Together")); ?>
          <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
      
      <div class="col-lg-6 mt-5 mt-lg-0">
        <div class="cta-images">
          <?php if(get_theme_mod('cta_image_top')): ?>
            <div class="cta-image-top mb-3">
              <img src="<?php echo esc_url(get_theme_mod('cta_image_top')); ?>" class="img-fluid rounded shadow" alt="CTA Image Top">
            </div>
          <?php endif; ?>
          
          <div class="row g-3">
            <?php if(get_theme_mod('cta_image_left')): ?>
              <div class="col-md-6">
                <img src="<?php echo esc_url(get_theme_mod('cta_image_left')); ?>" class="img-fluid rounded shadow" alt="CTA Image Left">
              </div>
            <?php endif; ?>
            
            <?php if(get_theme_mod('cta_image_right')): ?>
              <div class="col-md-6">
                <img src="<?php echo esc_url(get_theme_mod('cta_image_right')); ?>" class="img-fluid rounded shadow" alt="CTA Image Right">
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</main>

<?php get_footer(); ?>
