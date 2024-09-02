<?php
/**
 * Class PieAdmin
 *
 * Handles the admin functionality for the "Pies" custom post type.
 *
 * @package CustomPiesPlugin
 */
class PieAdmin {

    /**
     * Constructor to initialize the admin functionality.
     */
    public function __construct() {
        // Hook to manage custom columns in the admin list table
        add_filter('manage_pies_posts_columns', [$this, 'set_custom_columns']);
        add_action('manage_pies_posts_custom_column', [$this, 'render_custom_columns'], 10, 2);
        
        // Hook to modify the admin query for search functionality
        add_filter('pre_get_posts', [$this, 'modify_admin_query']);
        
        // Hook to customize row actions (including Delete) in the list view
        add_filter('post_row_actions', [$this, 'customize_row_actions'], 10, 2);
    }

    /**
     * Set custom columns for the "Pies" admin list table.
     *
     * @param array $columns Existing columns.
     * @return array Modified columns.
     */
    public function set_custom_columns($columns) {
        // Unset unnecessary columns
        unset($columns['date']);

        // Add custom columns
        $columns['pie_type'] = __('Pie Type', 'custom-pies-plugin');
        $columns['ingredients'] = __('Ingredients', 'custom-pies-plugin');
        $columns['excerpt'] = __('Description', 'custom-pies-plugin');

        // Re-add the date column
        $columns['date'] = __('Date', 'custom-pies-plugin');

        return $columns;
    }

    /**
     * Render content for custom columns in the "Pies" admin list table.
     *
     * @param string $column The name of the column.
     * @param int $post_id The ID of the post.
     */
    public function render_custom_columns($column, $post_id) {
        switch ($column) {
            case 'pie_type':
                $pie_type = get_post_meta($post_id, '_pie_type', true);
                echo esc_html($pie_type);
                break;

            case 'ingredients':
                $ingredients = get_post_meta($post_id, '_ingredients', true);
                echo esc_html($ingredients);
                break;

            case 'excerpt':
                echo esc_html(get_the_excerpt($post_id));
                break;
        }
    }

    /**
     * Modify the admin query to enable search by custom meta fields.
     *
     * @param WP_Query $query The current WP_Query instance.
     */
    public function modify_admin_query($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        if ($query->get('post_type') !== 'pies') {
            return;
        }

        $search_term = $query->get('s');
        if ($search_term) {
            $meta_query = [
                'relation' => 'OR',
                [
                    'key' => '_pie_type',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                ],
                [
                    'key' => '_ingredients',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                ]
            ];
            $query->set('meta_query', $meta_query);
        }
    }

    /**
     * Customize the row actions for the "Pies" admin list table.
     *
     * @param array $actions The existing row actions.
     * @param WP_Post $post The current post object.
     * @return array Modified row actions.
     */
    public function customize_row_actions($actions, $post) {
        if ($post->post_type === 'pies') {
            // Replace the "Bin" action with "Delete"
            if (isset($actions['trash'])) {
                $actions['trash'] = '<a href="' . esc_url(get_delete_post_link($post->ID)) . '" onclick="return confirm(\'Are you sure you want to delete this pie?\');" class="submitdelete" aria-label="' . esc_attr__('Delete this pie', 'custom-pies-plugin') . '">' . __('Delete', 'custom-pies-plugin') . '</a>';
            }
        }

        return $actions;
    }
}