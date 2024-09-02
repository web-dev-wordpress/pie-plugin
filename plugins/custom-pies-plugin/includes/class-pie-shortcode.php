<?php

/**
 * Class PieShortcode
 *
 * Handles the rendering of the "Pies" custom post type using a shortcode.
 *
 * @package CustomPiesPlugin
 */
class PieShortcode {

    /**
     * PieShortcode constructor.
     *
     * Hooks into WordPress to register the shortcode and enqueue the necessary styles and scripts.
     */
    public function __construct() {
        // Register the [pies] shortcode
        add_shortcode('pies', [$this, 'render_shortcode']);
        
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Enqueue the necessary styles and scripts for the shortcode.
     *
     * This method ensures that the required CSS and JavaScript files are loaded when the shortcode is used.
     */
    public function enqueue_assets() {
        // Enqueue the styles
        wp_enqueue_style('pies-shortcode-style', plugin_dir_url(__FILE__) . '../assets/css/pie-shortcode.css');

        // Enqueue the scripts
        wp_enqueue_script('pies-shortcode-script', plugin_dir_url(__FILE__) . '../assets/js/pie-shortcode.js', ['jquery'], null, true);
    }

    /**
     * Renders the [pies] shortcode output.
     *
     * This method generates the HTML output for the shortcode, including the list of pies and pagination.
     *
     * @param array $atts The shortcode attributes.
     * @return string The HTML output of the shortcode.
     */
    public function render_shortcode($atts) {
        // Parse shortcode attributes and set defaults
        $atts = shortcode_atts([
            'lookup' => '',
            'ingredients' => '',
            'posts_per_page' => 3 // Default number of pies to display
        ], $atts, 'pies');

        // Set up the query arguments
        $query_args = [
            'post_type' => 'pies',
            'posts_per_page' => $atts['posts_per_page'],
            'paged' => max(1, get_query_var('paged', 1)) // Handle pagination
        ];

        // Add lookup (pie type) to the query if specified
        if (!empty($atts['lookup'])) {
            $query_args['meta_query'][] = [
                'key' => '_pie_type',
                'value' => $atts['lookup'],
                'compare' => 'LIKE'
            ];
        }

        // Add ingredients to the query if specified
        if (!empty($atts['ingredients'])) {
            $query_args['meta_query'][] = [
                'key' => '_ingredients',
                'value' => $atts['ingredients'],
                'compare' => 'LIKE'
            ];
        }

        // Run the custom query
        $query = new WP_Query($query_args);

        // Start building the output
        $output = '<div class="pie-list">';
        
        // Check if there are pies to display
        if ($query->have_posts()) {
            $output .= '<ul>';
            while ($query->have_posts()) {
                $query->the_post();

                // Append each pie to the output
                $output .= '<li>';
                $output .= '<h3 class="pie-title">';
                $output .= '<span class="toggle-arrow">▶</span> '; // Arrow icon
                $output .= get_the_title() . '</h3>';
                $output .= '<div class="pie-details" style="display: none;">';
                $output .= '<p class="pie-type"><strong>Pie Type:</strong> ' . esc_html(get_post_meta(get_the_ID(), '_pie_type', true)) . '</p>';
                $output .= '<p class="pie-ingredients"><strong>Ingredients:</strong> ' . esc_html(get_post_meta(get_the_ID(), '_ingredients', true)) . '</p>';
                $output .= '<p class="pie-description"><strong>Description:</strong> ' . get_the_excerpt() . '</p>';
                $output .= '</div>';
                $output .= '</li>';
            }
            $output .= '</ul>';

            // Generate and append pagination
            $output .= $this->get_pagination($query);
        } else {
            // No pies found message
            $output .= '<p>' . __('No pies found.', 'custom-pies-plugin') . '</p>';
        }

        $output .= '</div>';

        // Reset post data
        wp_reset_postdata();

        return $output;
    }

    /**
     * Generates pagination links for the shortcode.
     *
     * @param WP_Query $query The current WP_Query instance.
     * @return string The HTML output for pagination.
     */
    protected function get_pagination($query) {
        $pages = paginate_links([
            'format' => '?paged=%#%',  // Pagination format
            'current' => max(1, get_query_var('paged')),  // Current page number
            'total' => $query->max_num_pages,  // Total number of pages
            'type' => 'array',  // Return pagination links as an array
            'prev_text' => __('« Prev', 'custom-pies-plugin'),  // Previous page text
            'next_text' => __('Next »', 'custom-pies-plugin'),  // Next page text
        ]);
    
        if (is_array($pages)) {
            $pagination = '<nav class="pagination"><ul>';
            foreach ($pages as $page) {
                $pagination .= '<li>' . $page . '</li>';
            }
            $pagination .= '</ul></nav>';
            return $pagination;
        }
    
        return '';
    }
}