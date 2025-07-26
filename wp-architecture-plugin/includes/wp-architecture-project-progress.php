<?php
/**
 * Plugin Name: WP Architecture Project Progress Tracker
 * Description: Track and display project development phases with progress percentage.
 * Version: 1.0
 * Author: Rubaiyat Afreen
 */

if (!defined('ABSPATH')) exit;

class WP_Architecture_Project_Progress {

    private $table_name;
    private $phases = [
        'discussion' => 'Discussion with Architect',
        'designing' => 'Designing Virtual Model',
        'planning' => 'Planning the Execution Structure',
        'final_checks' => 'Final Checks and Confirmation',
        'execution' => 'Executing the Plan',
        'completion' => 'Completion of Project'
    ];

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'architecture_project_progress';

        // Remove activation hook from here!

        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_post_save_project_progress', [$this, 'save_project_progress']);

        add_shortcode('project_progress', [$this, 'render_progress_shortcode']);
    }

    public function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            project_id bigint(20) NOT NULL,
            phase_key varchar(50) NOT NULL,
            completed tinyint(1) NOT NULL DEFAULT 0,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY project_phase (project_id, phase_key)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function add_admin_menu() {
        add_menu_page(
            'Project Progress',
            'Project Progress',
            'manage_options',
            'wp-architecture-project-progress',
            [$this, 'admin_page_html'],
            'dashicons-chart-line',
            26
        );
    }

    public function admin_page_html() {
        if (!current_user_can('manage_options')) {
            return;
        }

        global $wpdb;

        // Get list of projects (assuming CPT 'project' exists)
        $projects = get_posts([
            'post_type' => 'project',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        $selected_project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

        $progress_data = [];
        if ($selected_project_id) {
            $rows = $wpdb->get_results($wpdb->prepare(
                "SELECT phase_key, completed FROM {$this->table_name} WHERE project_id = %d",
                $selected_project_id
            ), OBJECT_K);
            foreach ($rows as $row) {
                $progress_data[$row->phase_key] = $row->completed;
            }
        }

        ?>
        <div class="wrap">
            <h1>Project Development Progress Tracker</h1>

            <form method="get">
                <input type="hidden" name="page" value="wp-architecture-project-progress" />
                <label for="project_id"><strong>Select Project:</strong> </label>
                <select name="project_id" id="project_id" onchange="this.form.submit()">
                    <option value="">-- Select Project --</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?php echo esc_attr($project->ID); ?>" <?php selected($selected_project_id, $project->ID); ?>>
                            <?php echo esc_html($project->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if ($selected_project_id): ?>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <?php wp_nonce_field('save_project_progress', 'progress_nonce'); ?>
                    <input type="hidden" name="action" value="save_project_progress" />
                    <input type="hidden" name="project_id" value="<?php echo esc_attr($selected_project_id); ?>" />

                    <table class="widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Phase</th>
                                <th>Completed?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->phases as $key => $label): ?>
                                <tr>
                                    <td><?php echo esc_html($label); ?></td>
                                    <td>
                                        <input type="checkbox" name="phases[<?php echo esc_attr($key); ?>]" value="1" <?php checked(!empty($progress_data[$key])); ?> />
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php submit_button('Save Progress'); ?>
                </form>

                <h2>Current Progress</h2>
                <?php
                $completed_count = 0;
                foreach ($this->phases as $key => $label) {
                    if (!empty($progress_data[$key])) $completed_count++;
                }
                $percent = round(($completed_count / count($this->phases)) * 100);
                ?>
                <div style="width: 300px; background: #eee; border-radius: 5px; overflow: hidden;">
                    <div style="width: <?php echo $percent; ?>%; background: #0073aa; color: white; text-align: center; padding: 5px 0;">
                        <?php echo $percent; ?>%
                    </div>
                </div>

            <?php endif; ?>
        </div>
        <?php
    }

    public function save_project_progress() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }

        check_admin_referer('save_project_progress', 'progress_nonce');

        if (empty($_POST['project_id']) || !is_numeric($_POST['project_id'])) {
            wp_redirect(admin_url('admin.php?page=wp-architecture-project-progress'));
            exit;
        }

        $project_id = intval($_POST['project_id']);
        $phases_input = isset($_POST['phases']) && is_array($_POST['phases']) ? $_POST['phases'] : [];

        global $wpdb;

        foreach ($this->phases as $key => $label) {
            $completed = isset($phases_input[$key]) ? 1 : 0;

            $existing = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$this->table_name} WHERE project_id = %d AND phase_key = %s",
                $project_id, $key
            ));

            if ($existing) {
                $wpdb->update(
                    $this->table_name,
                    ['completed' => $completed, 'updated_at' => current_time('mysql')],
                    ['id' => $existing],
                    ['%d', '%s'],
                    ['%d']
                );
            } else {
                $wpdb->insert(
                    $this->table_name,
                    ['project_id' => $project_id, 'phase_key' => $key, 'completed' => $completed, 'updated_at' => current_time('mysql')],
                    ['%d', '%s', '%d', '%s']
                );
            }
        }

        wp_redirect(admin_url('admin.php?page=wp-architecture-project-progress&project_id=' . $project_id . '&updated=1'));
        exit;
    }

    public function render_progress_shortcode($atts) {
        $atts = shortcode_atts([
            'project_id' => 0,
        ], $atts, 'project_progress');

        $project_id = intval($atts['project_id']);
        if (!$project_id) {
            return '<p>No project specified.</p>';
        }

        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT phase_key, completed FROM {$this->table_name} WHERE project_id = %d",
            $project_id
        ), OBJECT_K);

        $output = '<div class="wp-architecture-project-progress">';
        $output .= '<h3>Project Progress</h3>';

        $completed_count = 0;
        $total = count($this->phases);
        foreach ($this->phases as $key => $label) {
            $completed = !empty($rows[$key]) && $rows[$key]->completed ? true : false;
            if ($completed) $completed_count++;
            $output .= '<p>';
            $output .= esc_html($label) . ': ';
            $output .= $completed ? '<strong style="color:green;">Completed</strong>' : '<span style="color:red;">Pending</span>';
            $output .= '</p>';
        }
        $percent = round(($completed_count / $total) * 100);
        $output .= '<div style="width: 100%; background: #eee; border-radius: 5px; overflow: hidden; max-width:400px;">';
        $output .= '<div style="width: ' . $percent . '%; background: #0073aa; color: white; text-align: center; padding: 5px 0;">' . $percent . '%</div>';
        $output .= '</div>';

        $output .= '</div>';

        return $output;
    }

}

// Instantiate the plugin class
$wp_architecture_project_progress = new WP_Architecture_Project_Progress();

// Register activation hook outside the class
register_activation_hook(__FILE__, function() {
    $plugin = new WP_Architecture_Project_Progress();
    $plugin->create_table();
});
