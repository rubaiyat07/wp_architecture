<?php
/**
 * Template Name: Hire an Architect
 * Description: Template for hiring architects with filtering options
 */

get_header(); ?>

<div class="container py-5 custom-page hire-architect-page">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-title"><?php the_title(); ?></h1>
            
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="page-content">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; endif; ?>
            
            <!-- Architects Listing -->
            <div id="architects-results" class="row">
                <?php
                // Query architects
                $args = array(
                    'post_type' => 'architect_profile',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC'
                );
                
                $architects = new WP_Query($args);
                
                if ($architects->have_posts()) :
                    while ($architects->have_posts()) : $architects->the_post();
                        $architect_id = get_the_ID();
                        $designation = get_post_meta($architect_id, '_architect_designation', true);
                        $projects_total = get_post_meta($architect_id, '_architect_projects_total', true);
                        $projects_completed = get_post_meta($architect_id, '_architect_projects_completed', true);
                        $hourly_rate = get_post_meta($architect_id, '_architect_hourly_rate', true);
                        $availability = get_post_meta($architect_id, '_architect_availability', true);
                ?>
                    <div class="col-md-4 mb-4 architect-card">
                        <div class="card h-100">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="card-img-top architect-photo">
                                    <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <h3 class="card-title"><?php the_title(); ?></h3>
                                <p class="text-muted"><?php echo esc_html($designation); ?></p>
                                
                                <div class="architect-stats mb-3">
                                    <div class="stat-item">
                                        <span class="stat-number"><?php echo esc_html($projects_completed); ?></span>
                                        <span class="stat-label">Projects Completed</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-number"><?php echo esc_html($projects_total); ?></span>
                                        <span class="stat-label">Total Projects</span>
                                    </div>
                                    <?php if ($hourly_rate) : ?>
                                        <div class="stat-item">
                                            <span class="stat-number">$<?php echo esc_html($hourly_rate); ?></span>
                                            <span class="stat-label">Hourly Rate</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="availability-badge mb-3">
                                    <?php 
                                    $availability_classes = array(
                                        'available' => 'bg-success',
                                        'limited' => 'bg-warning',
                                        'unavailable' => 'bg-danger'
                                    );
                                    $availability_text = array(
                                        'available' => 'Available',
                                        'limited' => 'Limited Availability',
                                        'unavailable' => 'Currently Unavailable'
                                    );
                                    ?>
                                    <span class="badge <?php echo isset($availability_classes[$availability]) ? $availability_classes[$availability] : 'bg-secondary'; ?>">
                                        <?php echo isset($availability_text[$availability]) ? $availability_text[$availability] : 'Availability Unknown'; ?>
                                    </span>
                                </div>
                                
                                <div class="architect-actions">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">View Profile</a>
                                    <button class="btn btn-primary btn-sm hire-architect-btn" 
                                            data-architect-id="<?php echo esc_attr($architect_id); ?>"
                                            data-architect-name="<?php echo esc_attr(get_the_title()); ?>"
                                            <?php echo ($availability === 'unavailable') ? 'disabled' : ''; ?>>
                                        Hire This Architect
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<div class="col-12"><div class="alert alert-info">No architects found.</div></div>';
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Hire Architect Modal -->
<div class="modal fade" id="hireArchitectModal" tabindex="-1" aria-labelledby="hireArchitectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hireArchitectModalLabel">Hire <span id="modal-architect-name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="hire-architect-form">
                    <input type="hidden" id="architect-id" name="architect_id">
                    
                    <div class="mb-3">
                        <label for="hire-name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="hire-name" name="hire_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hire-email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="hire-email" name="hire_email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hire-phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="hire-phone" name="hire_phone">
                    </div>
                    
                    <div class="mb-3">
                        <label for="project-type" class="form-label">Project Type</label>
                        <select class="form-select" id="project-type" name="project_type" required>
                            <option value="">Select Project Type</option>
                            <option value="Residential">Residential</option>
                            <option value="Commercial">Commercial</option>
                            <option value="Industrial">Industrial</option>
                            <option value="Landscape">Landscape</option>
                            <option value="Interior Design">Interior Design</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="project-details" class="form-label">Project Details</label>
                        <textarea class="form-control" id="project-details" name="project_details" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="project-budget" class="form-label">Estimated Budget ($)</label>
                        <input type="number" class="form-control" id="project-budget" name="project_budget">
                    </div>
                    
                    <div class="mb-3">
                        <label for="project-timeline" class="form-label">Project Timeline</label>
                        <select class="form-select" id="project-timeline" name="project_timeline">
                            <option value="">Flexible</option>
                            <option value="1-3 months">1-3 months</option>
                            <option value="3-6 months">3-6 months</option>
                            <option value="6-12 months">6-12 months</option>
                            <option value="1+ years">1+ years</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit-hire-request">Submit Request</button>
            </div>
        </div>
    </div>
</div>

<?php
// Enqueue necessary scripts and styles
wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '', true);
wp_enqueue_script('architect-hire-script', get_template_directory_uri() . '/js/architect-hire.js', array('jquery'), '1.0', true);

// Localize script for AJAX
wp_localize_script('architect-hire-script', 'architectHireVars', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('hire_architect_nonce')
));
?>

<style>
    .hire-architect-page {
        padding: 30px 0;
    }
    
    .architect-card .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .architect-card:hover .card {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .architect-photo {
        height: 200px;
        overflow: hidden;
    }
    
    .architect-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .architect-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        display: block;
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    .stat-label {
        display: block;
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .architect-actions {
        display: flex;
        justify-content: space-between;
    }
    
    .availability-badge .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
    }
    
    @media (max-width: 767px) {
        .architect-card {
            margin-bottom: 20px;
        }
    }
</style>

<script>
jQuery(document).ready(function($) {
    // Handle hire button click
    $('.hire-architect-btn').click(function() {
        var architectId = $(this).data('architect-id');
        var architectName = $(this).data('architect-name');
        
        $('#modal-architect-name').text(architectName);
        $('#architect-id').val(architectId);
        
        var modal = new bootstrap.Modal(document.getElementById('hireArchitectModal'));
        modal.show();
    });
    
    // Handle form submission
    $('#submit-hire-request').click(function() {
        var formData = $('#hire-architect-form').serialize();
        
        $.ajax({
            url: architectHireVars.ajaxurl,
            type: 'POST',
            data: {
                action: 'submit_architect_hire_request',
                nonce: architectHireVars.nonce,
                form_data: formData
            },
            beforeSend: function() {
                $('#submit-hire-request').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
            },
            success: function(response) {
                if (response.success) {
                    $('#hireArchitectModal .modal-body').html('<div class="alert alert-success">' + response.data.message + '</div>');
                    $('#submit-hire-request').hide();
                } else {
                    alert('Error: ' + response.data.message);
                    $('#submit-hire-request').prop('disabled', false).text('Submit Request');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $('#submit-hire-request').prop('disabled', false).text('Submit Request');
            }
        });
    });
});
</script>

<?php get_footer();