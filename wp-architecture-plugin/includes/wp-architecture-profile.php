<?php
/**
 * Plugin Name: WP Architecture Profile
 * Description: Adds Architect profiles CPT with advanced features.
 * Version: 1.2
 * Author: Rubaiyat Afreen
 */

if (!defined('ABSPATH')) exit;

// Register CPT with custom fields
function wp_architecture_register_profile_cpt() {
    register_post_type('architect_profile', array(
        'labels' => array(
            'name' => 'Architects',
            'singular_name' => 'Architect',
            'add_new' => 'Add New Architect',
            'add_new_item' => 'Add New Architect',
            'edit_item' => 'Edit Architect',
            'new_item' => 'New Architect',
            'view_item' => 'View Architect',
            'search_items' => 'Search Architects',
            'not_found' => 'No architects found',
            'not_found_in_trash' => 'No architects found in Trash'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-businessman',
        'show_in_rest' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'architects'),
    ));
}
add_action('init', 'wp_architecture_register_profile_cpt');

// Add custom meta boxes
function wp_architecture_add_meta_boxes() {
    add_meta_box(
        'architect_details',
        'Architect Details',
        'wp_architecture_meta_box_callback',
        'architect_profile',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wp_architecture_add_meta_boxes');

// Meta box callback function
function wp_architecture_meta_box_callback($post) {
    wp_nonce_field('wp_architecture_save_meta', 'wp_architecture_meta_nonce');
    
    $designation = get_post_meta($post->ID, '_architect_designation', true);
    $projects_total = get_post_meta($post->ID, '_architect_projects_total', true);
    $projects_completed = get_post_meta($post->ID, '_architect_projects_completed', true);
    $hourly_rate = get_post_meta($post->ID, '_architect_hourly_rate', true);
    $availability = get_post_meta($post->ID, '_architect_availability', true);
    $specializations = get_post_meta($post->ID, '_architect_specializations', true);
    $experience = get_post_meta($post->ID, '_architect_experience', true);
    $email = get_post_meta($post->ID, '_architect_email', true);
    $phone = get_post_meta($post->ID, '_architect_phone', true);
    $location = get_post_meta($post->ID, '_architect_location', true);

    ?>
    <div style="display: grid; grid-template-columns: max-content 1fr; gap: 12px; align-items: center;">
        <label for="architect_designation">Designation:</label>
        <input type="text" id="architect_designation" name="architect_designation" value="<?php echo esc_attr($designation); ?>" style="width: 100%;">
        
        <label for="architect_projects_total">Total Projects:</label>
        <input type="number" id="architect_projects_total" name="architect_projects_total" value="<?php echo esc_attr($projects_total); ?>" style="width: 100%;">
        
        <label for="architect_projects_completed">Completed Projects:</label>
        <input type="number" id="architect_projects_completed" name="architect_projects_completed" value="<?php echo esc_attr($projects_completed); ?>" style="width: 100%;">
        
        <label for="architect_hourly_rate">Hourly Rate ($):</label>
        <input type="number" id="architect_hourly_rate" name="architect_hourly_rate" value="<?php echo esc_attr($hourly_rate); ?>" style="width: 100%;">
        
        <label for="architect_availability">Availability:</label>
        <select id="architect_availability" name="architect_availability" style="width: 100%;">
            <option value="available" <?php selected($availability, 'available'); ?>>Available</option>
            <option value="limited" <?php selected($availability, 'limited'); ?>>Limited Availability</option>
            <option value="unavailable" <?php selected($availability, 'unavailable'); ?>>Unavailable</option>
        </select>
        
        <label for="architect_specializations">Specializations (comma separated):</label>
        <input type="text" id="architect_specializations" name="architect_specializations" value="<?php echo esc_attr($specializations); ?>" style="width: 100%;">
        
        <label for="architect_experience">Years of Experience:</label>
        <input type="number" id="architect_experience" name="architect_experience" value="<?php echo esc_attr($experience); ?>" style="width: 100%;">
        
        <label for="architect_email">Contact Email:</label>
        <input type="email" id="architect_email" name="architect_email" value="<?php echo esc_attr($email); ?>" style="width: 100%;">
        
        <label for="architect_phone">Contact Phone:</label>
        <input type="text" id="architect_phone" name="architect_phone" value="<?php echo esc_attr($phone); ?>" style="width: 100%;">
        
        <label for="architect_location">Location:</label>
        <input type="text" id="architect_location" name="architect_location" value="<?php echo esc_attr($location); ?>" style="width: 100%;">
    </div>
    <?php
}

// Save meta box data
function wp_architecture_save_meta_box_data($post_id) {
    if (!isset($_POST['wp_architecture_meta_nonce']) || 
        !wp_verify_nonce($_POST['wp_architecture_meta_nonce'], 'wp_architecture_save_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['architect_designation'])) {
        update_post_meta($post_id, '_architect_designation', sanitize_text_field($_POST['architect_designation']));
    }
    
    if (isset($_POST['architect_projects_total'])) {
        update_post_meta($post_id, '_architect_projects_total', intval($_POST['architect_projects_total']));
    }
    
    if (isset($_POST['architect_projects_completed'])) {
        update_post_meta($post_id, '_architect_projects_completed', intval($_POST['architect_projects_completed']));
    }

    if (isset($_POST['architect_hourly_rate'])) {
        update_post_meta($post_id, '_architect_hourly_rate', floatval($_POST['architect_hourly_rate']));
    }
    
    if (isset($_POST['architect_availability'])) {
        update_post_meta($post_id, '_architect_availability', sanitize_text_field($_POST['architect_availability']));
    }
    
    if (isset($_POST['architect_specializations'])) {
        update_post_meta($post_id, '_architect_specializations', sanitize_text_field($_POST['architect_specializations']));
    }
    
    if (isset($_POST['architect_experience'])) {
        update_post_meta($post_id, '_architect_experience', intval($_POST['architect_experience']));
    }
    
    if (isset($_POST['architect_email'])) {
        update_post_meta($post_id, '_architect_email', sanitize_email($_POST['architect_email']));
    }
    
    if (isset($_POST['architect_phone'])) {
        update_post_meta($post_id, '_architect_phone', sanitize_text_field($_POST['architect_phone']));
    }
    
    if (isset($_POST['architect_location'])) {
        update_post_meta($post_id, '_architect_location', sanitize_text_field($_POST['architect_location']));
    }
}
add_action('save_post_architect_profile', 'wp_architecture_save_meta_box_data');

// Add custom columns to admin list
function wp_architecture_custom_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => $columns['title'],
        'designation' => 'Designation',
        'projects_total' => 'Total Projects',
        'projects_completed' => 'Completed Projects',
        'hourly_rate' => 'Hourly Rate',
        'availability' => 'Availability',
        'experience' => 'Experience',
        'email' => 'Email',
        'phone' => 'Phone',
        'location' => 'Location',
        'date' => $columns['date']
    );
    return $new_columns;
}
add_filter('manage_architect_profile_posts_columns', 'wp_architecture_custom_columns');

// Populate custom columns
function wp_architecture_custom_column_data($column, $post_id) {
    switch ($column) {
        case 'designation':
            echo esc_html(get_post_meta($post_id, '_architect_designation', true));
            break;
        case 'projects_total':
            echo esc_html(get_post_meta($post_id, '_architect_projects_total', true));
            break;
        case 'projects_completed':
            echo esc_html(get_post_meta($post_id, '_architect_projects_completed', true));
            break;
        case 'hourly_rate':
            echo esc_html(get_post_meta($post_id, '_architect_hourly_rate', true));
            break;
        case 'availability':
            echo esc_html(get_post_meta($post_id, '_architect_availability', true));
            break;
        case 'experience':
            echo esc_html(get_post_meta($post_id, '_architect_experience', true));
            break;
        case 'email':
            echo esc_html(get_post_meta($post_id, '_architect_email', true));
            break;
        case 'phone':
            echo esc_html(get_post_meta($post_id, '_architect_phone', true));
            break;
        case 'location':
            echo esc_html(get_post_meta($post_id, '_architect_location', true));
            break;
    }
}
add_action('manage_architect_profile_posts_custom_column', 'wp_architecture_custom_column_data', 10, 2);

// Make columns sortable
function wp_architecture_sortable_columns($columns) {
    $columns['designation'] = 'designation';
    $columns['projects_total'] = 'projects_total';
    $columns['projects_completed'] = 'projects_completed';
    $columns['hourly_rate'] = 'hourly_rate';
    $columns['availability'] = 'availability';
    $columns['experience'] = 'experience';
    return $columns;
}
add_filter('manage_edit-architect_profile_sortable_columns', 'wp_architecture_sortable_columns');

// Shortcode to show Architect Profiles
function wp_architecture_profiles_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ), $atts);
    
    $args = array(
        'post_type' => 'architect_profile',
        'posts_per_page' => $atts['limit'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order']
    );
    
    $query = new WP_Query($args);
    
    if (!$query->have_posts()) {
        return '<p>No architect profiles found.</p>';
    }

    ob_start();

    // Show success message if present
    if (isset($_GET['success'])) {
        echo '<div class="notice notice-success"><p>Architect added successfully!</p></div>';
    }
    
    // Admin actions if user has permission
    if (current_user_can('edit_posts')) {
        echo '<div class="text-right mb-3">';
        echo '<button class="button button-primary" data-bs-toggle="modal" data-bs-target="#addArchitectModal">Add New Architect</button>';
        echo '</div>';
    }
    
    echo '<div class="architect-profiles-container">';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr>';
    echo '<th>ID</th>';
    echo '<th>Name</th>';
    echo '<th>Designation</th>';
    echo '<th>Total Projects</th>';
    echo '<th>Completed Projects</th>';
    echo '<th>Hourly Rate</th>';
    echo '<th>Availability</th>';
    echo '<th>Experience</th>';
    echo '<th>Email</th>';
    echo '<th>Phone</th>';
    echo '<th>Location</th>';
    echo '<th>Shortcode</th>'; // Add Shortcode column
    if (current_user_can('edit_posts')) {
        echo '<th>Actions</th>';
    }
    echo '</tr></thead>';
    echo '<tbody>';
    
    while ($query->have_posts()) : $query->the_post();
        $post_id = get_the_ID();
        $designation = get_post_meta($post_id, '_architect_designation', true);
        $projects_total = get_post_meta($post_id, '_architect_projects_total', true);
        $projects_completed = get_post_meta($post_id, '_architect_projects_completed', true);
        $hourly_rate = get_post_meta($post_id, '_architect_hourly_rate', true);
        $availability = get_post_meta($post_id, '_architect_availability', true);
        $experience = get_post_meta($post_id, '_architect_experience', true);
        $email = get_post_meta($post_id, '_architect_email', true);
        $phone = get_post_meta($post_id, '_architect_phone', true);
        $location = get_post_meta($post_id, '_architect_location', true);
        
        echo '<tr>';
        echo '<td>' . esc_html($post_id) . '</td>';
        echo '<td><strong>' . esc_html(get_the_title()) . '</strong>';
        if (has_post_thumbnail()) {
            echo '<div class="architect-thumbnail">' . get_the_post_thumbnail($post_id, 'thumbnail') . '</div>';
        }
        echo '</td>';
        echo '<td>' . esc_html($designation) . '</td>';
        echo '<td>' . esc_html($projects_total) . '</td>';
        echo '<td>' . esc_html($projects_completed) . '</td>';
        echo '<td>' . esc_html($hourly_rate) . '</td>';
        echo '<td>' . esc_html($availability) . '</td>';
        echo '<td>' . esc_html($experience) . '</td>';
        echo '<td>' . esc_html($email) . '</td>';
        echo '<td>' . esc_html($phone) . '</td>';
        echo '<td>' . esc_html($location) . '</td>';
        // Add shortcode column
        echo '<td><code>[architect_profile id="' . esc_attr($post_id) . '"]</code></td>';
        
        if (current_user_can('edit_posts')) {
            echo '<td>';
            echo '<a href="' . get_edit_post_link($post_id) . '" class="button">Edit</a> ';
            echo '<a href="' . get_delete_post_link($post_id) . '" class="button" onclick="return confirm(\'Are you sure you want to delete this architect?\')">Delete</a>';
            echo '</td>';
        }
        
        echo '</tr>';
    endwhile;
    
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('architect_profiles', 'wp_architecture_profiles_shortcode');

// Individual Architect Profile Shortcode
function wp_architecture_single_profile_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts);

    if (!$atts['id']) {
        return '<p>No architect ID provided.</p>';
    }

    $post = get_post($atts['id']);
    if (!$post || $post->post_type != 'architect_profile') {
        return '<p>Architect not found.</p>';
    }

    ob_start();
    
    $designation = get_post_meta($post->ID, '_architect_designation', true);
    $projects_total = get_post_meta($post->ID, '_architect_projects_total', true);
    $projects_completed = get_post_meta($post->ID, '_architect_projects_completed', true);
    $hourly_rate = get_post_meta($post->ID, '_architect_hourly_rate', true);
    $availability = get_post_meta($post->ID, '_architect_availability', true);
    $experience = get_post_meta($post->ID, '_architect_experience', true);
    $email = get_post_meta($post->ID, '_architect_email', true);
    $phone = get_post_meta($post->ID, '_architect_phone', true);
    $location = get_post_meta($post->ID, '_architect_location', true);
    $specializations = get_post_meta($post->ID, '_architect_specializations', true);
    ?>
    
    <div class="architect-single-profile">
        <div class="row">
            <div class="col-md-4">
                <?php if (has_post_thumbnail($post->ID)) : ?>
                    <div class="architect-photo mb-3">
                        <?php echo get_the_post_thumbnail($post->ID, 'medium', array('class' => 'img-fluid rounded')); ?>
                    </div>
                <?php endif; ?>
                
                <div class="architect-contact mb-3">
                    <?php if ($email) : ?>
                        <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                    <?php endif; ?>
                    
                    <?php if ($phone) : ?>
                        <p><strong>Phone:</strong> <?php echo esc_html($phone); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($location) : ?>
                        <p><strong>Location:</strong> <?php echo esc_html($location); ?></p>
                    <?php endif; ?>
                </div>
                
                <button class="btn btn-primary hire-architect-btn" 
                        data-architect-id="<?php echo esc_attr($post->ID); ?>"
                        data-architect-name="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                    Hire This Architect
                </button>
            </div>
            
            <div class="col-md-8">
                <h2><?php echo esc_html(get_the_title($post->ID)); ?></h2>
                <?php if ($designation) : ?>
                    <p class="text-muted h4"><?php echo esc_html($designation); ?></p>
                <?php endif; ?>
                
                <div class="architect-bio mt-3">
                    <?php echo apply_filters('the_content', $post->post_content); ?>
                </div>
                
                <div class="architect-stats row mt-4">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html($projects_completed); ?></span>
                            <span class="stat-label">Projects Completed</span>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo esc_html($experience); ?>+</span>
                            <span class="stat-label">Years Experience</span>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">$<?php echo esc_html($hourly_rate); ?></span>
                            <span class="stat-label">Hourly Rate</span>
                        </div>
                    </div>
                </div>
                
                <?php if ($specializations) : ?>
                    <div class="specializations mt-4">
                        <h4>Specializations</h4>
                        <ul class="list-inline">
                            <?php 
                            $specs = explode(',', $specializations);
                            foreach ($specs as $spec) :
                                $spec = trim($spec);
                                if ($spec) : ?>
                                    <li class="list-inline-item badge bg-light text-dark"><?php echo esc_html($spec); ?></li>
                                <?php endif;
                            endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php
    return ob_get_clean();
}
add_shortcode('architect_profile', 'wp_architecture_single_profile_shortcode');

// Add New Architect Modal
function wp_architecture_add_new_modal() {
    if (!current_user_can('edit_posts')) return;
    ?>
    <div class="modal fade" id="addArchitectModal" tabindex="-1" aria-labelledby="addArchitectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addArchitectModalLabel">Add New Architect</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    // Updated form opening tag to include enctype for file upload
                    echo '<form id="add-architect-form" action="' . admin_url('admin-post.php') . '" method="post" enctype="multipart/form-data">';
                    ?>
                        <input type="hidden" name="action" value="submit_new_architect">
                        <?php wp_nonce_field('add_new_architect_nonce', 'architect_nonce'); ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="architect_name" class="form-label">Name*</label>
                                <input type="text" class="form-control" id="architect_name" name="architect_name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="architect_designation" class="form-label">Designation</label>
                                <input type="text" class="form-control" id="architect_designation" name="architect_designation">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="architect_bio" class="form-label">Bio/Description</label>
                            <textarea class="form-control" id="architect_bio" name="architect_bio" rows="4"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="architect_experience" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control" id="architect_experience" name="architect_experience">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="architect_hourly_rate" class="form-label">Hourly Rate ($)</label>
                                <input type="number" class="form-control" id="architect_hourly_rate" name="architect_hourly_rate">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="architect_availability" class="form-label">Availability</label>
                                <select class="form-select" id="architect_availability" name="architect_availability">
                                    <option value="available">Available</option>
                                    <option value="limited">Limited Availability</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="architect_specializations" class="form-label">Specializations (comma separated)</label>
                            <input type="text" class="form-control" id="architect_specializations" name="architect_specializations">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="architect_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="architect_email" name="architect_email">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="architect_phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="architect_phone" name="architect_phone">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="architect_location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="architect_location" name="architect_location">
                        </div>
                        
                        <div class="mb-3">
                            <label for="architect_photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="architect_photo" name="architect_photo">
                        </div>

                        <div class="mb-3">
                            <label for="architect_featured_image" class="form-label">Featured Image</label>
                            <input type="file" class="form-control" id="architect_featured_image" name="architect_featured_image">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="add-architect-form" class="btn btn-primary">Save Architect</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('admin_footer', 'wp_architecture_add_new_modal');
add_action('wp_footer', 'wp_architecture_add_new_modal');

// Handle New Architect Submission
function wp_architecture_handle_new_architect() {
    if (!isset($_POST['architect_nonce']) || !wp_verify_nonce($_POST['architect_nonce'], 'add_new_architect_nonce')) {
        wp_die('Security check failed');
    }

    if (!current_user_can('edit_posts')) {
        wp_die('Permission denied');
    }

    $post_data = array(
        'post_title'   => sanitize_text_field($_POST['architect_name']),
        'post_content' => wp_kses_post($_POST['architect_bio']),
        'post_type'    => 'architect_profile',
        'post_status'  => 'publish'
    );

    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        // Save meta fields
        $fields = array(
            '_architect_designation',
            '_architect_experience',
            '_architect_hourly_rate',
            '_architect_availability',
            '_architect_specializations',
            '_architect_email',
            '_architect_phone',
            '_architect_location'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Handle file upload
        if (!empty($_FILES['architect_photo']['name'])) {
            $upload_overrides = array('test_form' => false);
            $uploaded_file = wp_handle_upload($_FILES['architect_photo'], $upload_overrides);
            
            if (isset($uploaded_file['file'])) {
                $filetype = wp_check_filetype(basename($uploaded_file['file']), null);
                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title' => sanitize_file_name($_FILES['architect_photo']['name']),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                
                $attach_id = wp_insert_attachment($attachment, $uploaded_file['file'], $post_id);
                if (!is_wp_error($attach_id)) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_file['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);
                    set_post_thumbnail($post_id, $attach_id);
                }
            }
        }

        // Updated redirect to include success message
        wp_redirect(add_query_arg(array(
            'architect_added' => $post_id,
            'success' => '1'
        ), wp_get_referer()));
        exit;
    } else {
        wp_die('Error creating architect profile');
    }
}
add_action('admin_post_submit_new_architect', 'wp_architecture_handle_new_architect');

// You can add this snippet in your template or above the table in the shortcode output to show the success message:
if (isset($_GET['success'])) {
    echo '<div class="notice notice-success"><p>Architect added successfully!</p></div>';
}