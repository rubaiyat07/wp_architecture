<?php
/**
 * Plugin Name: WP Architecture Contact & Map
 * Description: A simple contact form with OpenStreetMap integration using Leaflet.js.
 * Version: 1.2
 * Author: Rubaiyat Afreen
 */

if (!defined('ABSPATH')) {
    exit;
}

// Start session for flash messages
function wp_architecture_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'wp_architecture_start_session');

// Handle form submission
function wp_architecture_handle_contact_form() {
    if (isset($_POST['submit_message'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wp_architecture_feedback';

        // Sanitize inputs
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $company = isset($_POST['company']) ? sanitize_text_field($_POST['company']) : '';
        $message_type = sanitize_text_field($_POST['message_type']);
        $message = sanitize_textarea_field($_POST['message']);

        // Insert into DB
        $result = $wpdb->insert(
            $table_name,
            [
                'name'         => $name,
                'email'        => $email,
                'company'      => $company,
                'message_type' => $message_type,
                'message'      => $message,
                'submitted_at' => current_time('mysql'),
            ]
        );

        // Enhanced error logging
        if ($result === false) {
            error_log('Feedback insert failed: ' . $wpdb->last_error);
            error_log('Query: ' . $wpdb->last_query);
            $_SESSION['wp_architecture_contact_success'] = false;
        } else {
            $_SESSION['wp_architecture_contact_success'] = true;
            
            // Send email notification
            $to = get_option('admin_email');
            $subject = 'New ' . ucfirst($message_type) . ' Message from ' . $name;
            $body = "Name: $name\nEmail: $email\n";
            if (!empty($company)) $body .= "Company: $company\n";
            $body .= "Type: $message_type\n\nMessage:\n$message";
            $headers = ['Content-Type: text/plain; charset=UTF-8', "Reply-To: $name <$email>"];
            wp_mail($to, $subject, $body, $headers);
        }

        // Redirect to avoid resubmission
        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }
}
add_action('init', 'wp_architecture_handle_contact_form');

// Shortcode Output
function wp_architecture_contact_map_shortcode() {
    ob_start();
    ?>

    <?php if (isset($_SESSION['wp_architecture_contact_success'])): ?>
        <?php if ($_SESSION['wp_architecture_contact_success']): ?>
            <div class="wp-architecture-alert wp-architecture-alert-success">
                Thank you for your message. We will get back to you soon.
            </div>
        <?php else: ?>
            <div class="wp-architecture-alert wp-architecture-alert-error">
                Sorry, there was a problem submitting your message. Please try again later.
            </div>
        <?php endif; ?>
        <?php unset($_SESSION['wp_architecture_contact_success']); ?>
    <?php endif; ?>

    <form method="post" class="wp-architecture-contact-form">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" required class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" required class="form-control">
        </div>

        <div class="form-group">
            <label for="company">Company (optional):</label>
            <input type="text" name="company" class="form-control">
        </div>

        <div class="form-group">
            <label>Message Type:</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="message_type" value="contact" checked> Contact
                </label>
                <label class="radio-label">
                    <input type="radio" name="message_type" value="feedback"> Feedback
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="message">Message:</label>
            <textarea name="message" required class="form-control"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" name="submit_message" value="Send" class="submit-button">
        </div>
    </form>

    <div id="wp-architecture-map" class="wp-architecture-map-container"></div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var map = L.map('wp-architecture-map').setView([23.8103, 90.4125], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);

            L.marker([23.8103, 90.4125]).addTo(map)
                .bindPopup('Dhaka')
                .openPopup();
        });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('wp_architecture_contact_map', 'wp_architecture_contact_map_shortcode');

// Add some basic styling
function wp_architecture_contact_map_styles() {
    echo '
    <style>
        .wp-architecture-contact-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        textarea.form-control {
            min-height: 120px;
        }
        .radio-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }
        .radio-label {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .submit-button {
            background: #3a7bd5;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-button:hover {
            background: #2a6bc5;
        }
        .wp-architecture-map-container {
            width: 100%;
            height: 400px;
            margin-top: 30px;
            border-radius: 8px;
            overflow: hidden;
        }
        .wp-architecture-alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .wp-architecture-alert-success {
            background: #d4edda;
            color: #155724;
        }
        .wp-architecture-alert-error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
    ';
}
add_action('wp_head', 'wp_architecture_contact_map_styles');