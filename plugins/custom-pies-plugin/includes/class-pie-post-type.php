<?php

/**
 * Class PiePostType
 *
 * This class handles the registration and management of the "Pies" custom post type.
 *
 * @package CustomPiesPlugin
 */
class PiePostType {

    /**
     * Constructor to initialize the custom post type.
     *
     * Hooks into WordPress actions to register the custom post type, add meta boxes,
     * and save the meta box data.
     */
    public function __construct() {
        // Register the custom post type
        add_action('init', [$this, 'register_post_type']);

        // Add custom meta boxes
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);

        // Save custom meta box data
        add_action('save_post', [$this, 'save_meta_boxes']);
    }

    /**
     * Registers the 'pies' custom post type with necessary arguments.
     *
     * The custom post type supports titles and excerpts (used as descriptions)
     * and has a custom icon in the admin menu.
     */
    public function register_post_type() {
        $labels = [
            'name'               => __('Pies', 'custom-pies-plugin'),
            'singular_name'      => __('Pie', 'custom-pies-plugin'),
            'add_new'            => __('Add New Pie', 'custom-pies-plugin'),
            'add_new_item'       => __('Add New Pie', 'custom-pies-plugin'),
            'edit_item'          => __('Edit Pie', 'custom-pies-plugin'),
            'new_item'           => __('New Pie', 'custom-pies-plugin'),
            'all_items'          => __('All Pies', 'custom-pies-plugin'),
            'view_item'          => __('View Pie', 'custom-pies-plugin'),
            'search_items'       => __('Search Pies', 'custom-pies-plugin'),
            'not_found'          => __('No pies found', 'custom-pies-plugin'),
            'not_found_in_trash' => __('No pies found in Trash', 'custom-pies-plugin'),
            'menu_name'          => __('Pies', 'custom-pies-plugin')
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'supports'           => ['title', 'excerpt'], // Supports title and excerpt (used as description)
            'show_in_rest'       => true, // Enable Gutenberg editor support
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-carrot', // Custom Dashicon for the menu
        ];

        // Register the custom post type
        register_post_type('pies', $args);
    }

    /**
     * Adds custom meta boxes for the Pie Type and Ingredients fields.
     *
     * The meta boxes allow users to input additional information about each pie.
     */
    public function add_meta_boxes() {
        add_meta_box(
            'pie_details',                  // Unique ID
            __('Pie Details', 'custom-pies-plugin'), // Title
            [$this, 'render_pie_details_meta_box'], // Callback function to render the meta box
            'pies',                         // Post type
            'normal',                       // Context (normal, side, advanced)
            'high'                          // Priority (default, core, high, low)
        );
    }

    /**
     * Renders the content of the Pie Details meta box.
     *
     * Outputs fields for Pie Type and Ingredients.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_pie_details_meta_box($post) {
        // Add a nonce field for security
        wp_nonce_field('save_pie_details', 'pie_nonce');

        // Retrieve existing values from the database
        $pie_type = get_post_meta($post->ID, '_pie_type', true);
        $ingredients = get_post_meta($post->ID, '_ingredients', true);

        // Display the form fields
        ?>
        <p>
            <label for="pie_type"><?php _e('Pie Type', 'custom-pies-plugin'); ?></label>
            <input type="text" name="pie_type" id="pie_type" value="<?php echo esc_attr($pie_type); ?>" class="widefat" />
        </p>
        <p>
            <label for="ingredients"><?php _e('Ingredients', 'custom-pies-plugin'); ?></label>
            <textarea name="ingredients" id="ingredients" class="widefat"><?php echo esc_textarea($ingredients); ?></textarea>
        </p>
        <?php
    }

    /**
     * Saves the custom meta box data when a post is saved.
     *
     * Checks if the nonce is set, verifies it, and ensures the user has permission
     * to save the data. The data is then sanitized and saved to the database.
     *
     * @param int $post_id The ID of the current post being saved.
     */
    public function save_meta_boxes($post_id) {
        // Check if nonce is set and valid
        if (!isset($_POST['pie_nonce']) || !wp_verify_nonce($_POST['pie_nonce'], 'save_pie_details')) {
            return;
        }

        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save or update the Pie Type
        if (isset($_POST['pie_type'])) {
            update_post_meta($post_id, '_pie_type', sanitize_text_field($_POST['pie_type']));
        }

        // Save or update the Ingredients
        if (isset($_POST['ingredients'])) {
            update_post_meta($post_id, '_ingredients', sanitize_textarea_field($_POST['ingredients']));
        }
    }
}
