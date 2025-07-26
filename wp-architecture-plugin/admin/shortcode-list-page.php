<?php 
function wp_architecture_shortcode_list_page() {
    add_menu_page(
        'Shortcodes',
        'Shortcodes',
        'manage_options',
        'wp-architecture-shortcodes',
        'wp_architecture_shortcode_list_page_html',
        'dashicons-editor-code',
        100
    );
}
add_action('admin_menu', 'wp_architecture_shortcode_list_page');

function wp_architecture_shortcode_list_page_html() {
    ?>
    <div class="wrap">
        <h1>Available Shortcodes</h1>
        <ul>
            <li>
                <code>[wp_architecture_contact_map]</code>
                <button class="copy-shortcode" data-shortcode="[wp_architecture_contact_map]">Copy</button>
            </li>
            <li>
                <code>[architect_profiles]</code>
                <button class="copy-shortcode" data-shortcode="[architect_profiles]">Copy</button>
            </li>
            <li>
                <code>[wp_architecture_slider]</code>
                <button class="copy-shortcode" data-shortcode="[wp_architecture_slider]">Copy</button>
            </li>
            <li>
                <code>[project_progress project_id="123"]</code>
                <button class="copy-shortcode" data-shortcode='[project_progress project_id="123"]'>Copy</button>
                <p><em>Replace 123 with your Project post ID</em></p>
            </li>
        </ul>
    </div>
    <script>
        document.querySelectorAll('.copy-shortcode').forEach(button => {
            button.addEventListener('click', () => {
                navigator.clipboard.writeText(button.getAttribute('data-shortcode'));
                alert('Shortcode copied to clipboard!');
            });
        });
    </script>
    <?php
}
