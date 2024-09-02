# Custom Pies Plugin

## Description
The **Custom Pies Plugin** is a WordPress plugin designed to manage and display pies using custom post types. The plugin includes features like custom meta boxes, admin enhancements, and frontend display via shortcodes with pagination and toggleable details.

## Features
- **Custom Post Type for Pies**: 
  - Registers a custom post type named "Pies" to manage different types of pies.
  - Includes support for title and description (using the excerpt).
  
- **Custom Meta Boxes**: 
  - Adds custom meta boxes in the admin area for entering "Pie Type" and "Ingredients".
  - The data is securely saved and displayed in both the admin list and on the front end.

- **Admin Enhancements**: 
  - Custom columns are added to the Pies list table to display "Pie Type", "Ingredients", and "Description".
  - Custom search functionality enables searching pies by "Pie Type" and "Ingredients".
  - Row actions are customized to replace the "Bin" action with "Delete".

- **Shortcode for Frontend Display**: 
  - `[pies]` shortcode allows users to display a list of pies on any post, page, or widget.
  - **Filtering**:
    - `lookup`: 
      - If `lookup` is provided, the shortcode filters pies based on the "Pie Type" value (e.g., `[pies lookup="Fruit Pie"]`).
      - If `lookup` is absent, the shortcode will display all pies.
    - `ingredients`: 
      - If `ingredients` are provided, the shortcode filters pies that contain the specified ingredients (e.g., `[pies ingredients="Apple"]`).
  - **Combination of Filters**: You can combine both `lookup` and `ingredients` to refine the list of pies displayed (e.g., `[pies lookup="Fruit Pie" ingredients="Apple"]`).
  - **Pagination**: The shortcode includes pagination to handle large numbers of pies, ensuring that the list is easy to navigate.
  - **Toggleable Details**: Pies are displayed with a clickable arrow that reveals additional details such as "Pie Type", "Ingredients", and "Description".



## Installation
1. Clone or download the repository to your WordPress plugins directory:
2. Activate the plugin through the WordPress admin dashboard:
- Go to **Plugins** > **Installed Plugins**.
- Locate **Custom Pies Plugin** and click **Activate**.

## Usage
- **Adding Pies**:
- Go to the WordPress admin dashboard.
- Navigate to **Pies** > **Add New**.
- Enter the title, select the "Pie Type", add "Ingredients", and a short description ( excerpt ).
- Click **Publish** to save the pie.

- **Displaying Pies**:
- Use the `[pies]` shortcode in any post, page, or widget.
- Example: `[pies lookup="Fruit Pie" ingredients="Apple"]`
- The shortcode supports pagination and toggleable details.

## Shortcode Attributes
- `lookup` (optional): Filter pies by "Pie Type".
- `ingredients` (optional): Filter pies by "Ingredients".
- `posts_per_page` (optional): Set the number of pies to display per page (default is 3).

## Customization
### CSS and JS
- Styles for the shortcode output are located in `assets/css/pie-shortcode.css`.
- JavaScript for toggle functionality is located in `assets/js/pie-shortcode.js`.

## Changelog
- **1.0**: Initial release with custom post type, admin enhancements, shortcode, and toggleable details.

## Author
- **Alex Turcan**
