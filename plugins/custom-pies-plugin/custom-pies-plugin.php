<?php
/*
Plugin Name: Custom Pies Plugin
Description: A custom plugin to manage pies (Lawgistics - Task).
Version: 1.0
Author: Alex Turcan
*/

// Exit if accessed directly to prevent unauthorized access.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Pies Plugin Main File
 *
 * This file serves as the main entry point for the Custom Pies Plugin. It includes
 * the necessary files for custom post type registration, admin functionalities,
 * and shortcode handling. The plugin is designed to manage pies, providing full
 * CRUD functionality and display options via shortcodes.
 *
 * @package CustomPiesPlugin
 */

// Include the custom post type class to manage "Pies"
include_once plugin_dir_path(__FILE__) . 'includes/class-pie-post-type.php';

/**
 * Class PiePostType
 *
 * This class handles the registration and management of the "Pies" custom post type.
 *
 * @package CustomPiesPlugin
 */

// Include the admin functionality class for managing "Pies" in the admin dashboard
include_once plugin_dir_path(__FILE__) . 'includes/class-pie-admin.php';

/**
 * Class PieAdmin
 *
 * This class handles the customization of the admin interface for the "Pies" custom post type.
 *
 * @package CustomPiesPlugin
 */

// Include the shortcode functionality class for rendering "Pies" via shortcodes
include_once plugin_dir_path(__FILE__) . 'includes/class-pie-shortcode.php';

/**
 * Class PieShortcode
 *
 * This class handles the rendering of "Pies" using shortcodes on the front-end.
 *
 * @package CustomPiesPlugin
 */

// Initialize the custom post type
new PiePostType();

/**
 * Initializes the PiePostType class to register and manage the "Pies" custom post type.
 */

// Initialize the admin functionality
new PieAdmin();

/**
 * Initializes the PieAdmin class to customize the admin interface for "Pies".
 */

// Initialize the shortcode functionality
new PieShortcode();

/**
 * Initializes the PieShortcode class to enable shortcode functionality for "Pies".
 */

