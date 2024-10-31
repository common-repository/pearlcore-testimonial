<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, hooks for enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @link:       http://pearlcore.com/
 * @since      1.0
 * @package    Pc_Testimonial
 * @subpackage Pc_Testimonial/includes
 */
class Pc_Testimonial_backend {

    /**
     * Page hook for the options screen
     *
     * @since 1.0
     * @type string
     */
    protected $pc_screen = null;

    /**
     * The ID of this plugin.
     *
     * @since    1.0
     * @access   private
     * @var      string    $name    The ID of this plugin.
     */
    private $name;

    /**
     * The version of this plugin.
     *
     * @since    1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0
     * @var      string    $name       The name of this plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($name, $version) {

        $this->name = $name;
        $this->version = $version;

        add_action('admin_menu', array($this, 'pc_testimonial_add_menu')); //register the plugin menu in backend

        add_action('init', array($this, 'pc_testimonial_post_type_register'));

        add_action('init', array($this, 'pc_testimonial_category_register'), 1);

        add_action('add_meta_boxes', array($this, 'pc_testimonial_client_info_box'));

        add_action('save_post', array($this, 'pc_testimonial_client_save_info'));
    }

    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    1.0
     */
    public function enqueue_styles($hook) {

        if ($this->pc_screen != $hook):
            return;
        endif;
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pc_Testimonial_Admin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pc_Testimonial_Admin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('pc-testimonial-options-framework', PC_TESTIMONIAL_ASSETS_URL . 'css/pc-testimonial-options-framework.css', array(), PC_TESTIMONIAL_VERSION);
        wp_enqueue_style('wp-color-picker');


        wp_enqueue_style('pc-testimonial-backend', PC_TESTIMONIAL_ASSETS_URL . 'css/pc-testimonial-backend.css', array(), PC_TESTIMONIAL_VERSION);
    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    1.0
     */
    public function enqueue_scripts($hook) {
        if ($this->pc_screen != $hook):
            return;
        endif;
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pc_Testimonial_Admin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pc_Testimonial_Admin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->name . '-pc-testimonial-functions', PC_TESTIMONIAL_ASSETS_URL . 'js/backend/pc-testimonial-functions.js', array('jquery', 'wp-color-picker'), PC_TESTIMONIAL_VERSION, true);

        wp_enqueue_script($this->name . '-pc-testimonial-backend', PC_TESTIMONIAL_ASSETS_URL . 'js/backend/pc-testimonial-backend.js', array('jquery'), PC_TESTIMONIAL_VERSION, true);

        wp_localize_script($this->name . '-pc-testimonial-backend', 'pc_backend', array('pc_ajax' => admin_url('admin-ajax.php')), PC_TESTIMONIAL_VERSION);


        // Inline scripts from options-interface.php
        add_action('admin_head', array($this, 'of_admin_head'));
    }

    function of_admin_head() {
        // Hook to add custom scripts
        do_action('optionsframework_custom_scripts');
    }

    /*
     * Define menu options (still limited to appearance section)
     *
     * Examples usage:
     *
     * add_filter( 'pc_testimonial_backend_menu', function( $menu ) {
     *     $menu['page_title'] = 'The Options';
     * 	   $menu['menu_title'] = 'The Options';
     *     return $menu;
     * });
     *
     * @since 1.0
     *
     */

    static function pc_testimonial_menus() {
        $pc_menu = array(
            // Modes: submenu, menu
            'mode' => 'submenu',
            // Submenu default settings
            'page_title' => __('Pearlcore Testimonial Settings', PC_TESTIMONIAL_TEXT_DOMAIN),
            'menu_title' => __('Setting', PC_TESTIMONIAL_TEXT_DOMAIN),
            'capability' => 'manage_options',
            'menu_slug' => 'pc-testimonial-settings',
            'parent_slug' => 'edit.php?post_type=pc-testimonial',
            'menu_callback' => 'pc_testimonial_main_page',
            // Menu default settings
            'icon_url' => 'dashicons-format-quote',
            'position' => '62'
        );
        return apply_filters('pc_testimonial_backend_menu', $pc_menu);
    }

    public function pc_testimonial_main_page() {
        ?>
        <div id="" class="wrap">
            <?php $menu = $this->pc_testimonial_menus(); ?>
            <h2><?php echo esc_html($menu['page_title']); ?></h2>
            <div class="pc_about_wrapper">
                <span>Feel Free To ask any question or have any problem. <a href="http://pearlcore.com/contact/">Contact Us</a></span>
            </div>

            <h2 class="nav-tab-wrapper">
                <?php echo Pcs_Testimonial_Framework_Interface::pcs_testimonial_framework_tabs(); ?>
            </h2>

            <?php settings_errors('options-framework'); ?>
            <div class="pc_setting_spinner_overlay"></div>
            <div class="pc_setting_spinner_wrapper">
                <div class="pc_setting_spinner">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
                <div class="pc_setting_message"></div>
            </div>
            <div id="optionsframework-metabox" class="metabox-holder">
                <div id="optionsframework" class="postbox">
                    <form action="options.php" method="post">
                        <?php settings_fields('optionsframework'); ?>
                        <?php Pcs_Testimonial_Framework_Interface::pcs_testimonial_framework_fields(); /* Settings */ ?>
                        <div id="optionsframework-submit">
                            <input type="submit" class="button-primary" name="update" value="<?php esc_attr_e('Save Options', 'options-framework'); ?>" />
                            <input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e('Restore Defaults', 'options-framework'); ?>" onclick="return confirm('<?php print esc_js(__('Click OK to reset. Any theme settings will be lost!', 'options-framework')); ?>');" />
                            <div class="clear"></div>
                        </div>
                    </form>
                </div> <!-- / #container -->
            </div>
            <?php do_action('optionsframework_after'); ?>
        </div> <!-- / .wrap -->

        <?php
    }

    /**
     * register the plugin menu for backend.
     */
    public function pc_testimonial_add_menu() {
        $pc_menus = $this->pc_testimonial_menus();
        switch ($pc_menus['mode']) {

            case 'menu':
                // http://codex.wordpress.org/Function_Reference/add_menu_page
                $this->pc_screen = add_menu_page(
                        $pc_menus['page_title'], $pc_menus['menu_title'], $pc_menus['capability'], $pc_menus['menu_slug'], array($this, $pc_menus['menu_callback']), $pc_menus['icon_url'], $pc_menus['position']
                );
                break;

            default:
                // http://codex.wordpress.org/Function_Reference/add_submenu_page
                $this->pc_screen = add_submenu_page(
                        $pc_menus['parent_slug'], $pc_menus['page_title'], $pc_menus['menu_title'], $pc_menus['capability'], $pc_menus['menu_slug'], array($this, $pc_menus['menu_callback']));
                break;
        }
    }

    /**
     * Register this Custom Post Type.
     *
     * @since    1.0.0
     */
    public function pc_testimonial_post_type_register() {

        $labels = array(
            'name' => _x('Testimonial', 'Post Type General Name', PC_TESTIMONIAL_TEXT_DOMAIN),
            'singular_name' => _x('Testimonial', 'Post Type Singular Name', PC_TESTIMONIAL_TEXT_DOMAIN),
            'menu_name' => __('Testimonial', PC_TESTIMONIAL_TEXT_DOMAIN),
            'parent_item_colon' => __('Parent Item:', PC_TESTIMONIAL_TEXT_DOMAIN),
            'all_items' => __('All Testimonial', PC_TESTIMONIAL_TEXT_DOMAIN),
            'view_item' => __('View Item', PC_TESTIMONIAL_TEXT_DOMAIN),
            'add_new_item' => __('Add Testimonial', PC_TESTIMONIAL_TEXT_DOMAIN),
            'add_new' => __('Add Testimonial ', PC_TESTIMONIAL_TEXT_DOMAIN),
            'edit_item' => __('Edit Testimonial', PC_TESTIMONIAL_TEXT_DOMAIN),
            'update_item' => __('Update Testimonial', PC_TESTIMONIAL_TEXT_DOMAIN),
            'search_items' => sprintf(__('Search %s', PC_TESTIMONIAL_TEXT_DOMAIN), 'Testimonial'),
            'not_found' => __('Not found', PC_TESTIMONIAL_TEXT_DOMAIN),
            'not_found_in_trash' => __('Not found in Trash', PC_TESTIMONIAL_TEXT_DOMAIN),
        );
        $args = array(
            'label' => __('pearlcore-testimonial', PC_TESTIMONIAL_TEXT_DOMAIN),
            'description' => __('Pearlcore_Testimonial_Pearlcore_Testimonial_CPT', PC_TESTIMONIAL_TEXT_DOMAIN),
            'labels' => $labels,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 20,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => false,
            'capability_type' => 'page',
            'menu_icon' => 'dashicons-format-quote',
        );
        register_post_type('pc-testimonial', $args);
    }

    /**
     * Resgister Category
     */
    function pc_testimonial_category_register() {

        register_taxonomy('pc_testimonial_category', 'pc-testimonial', array(
            'labels' => array(
                'name' => _x('Category', 'taxonomy general name'),
                'singular_name' => _x('Testimonial Category', 'taxonomy singular name'),
                'search_items' => __('Search Testimonial Categories'),
                'all_items' => __('All Categories'),
                'parent_item' => __('Parent Category'),
                'parent_item_colon' => __('Parent Category:'),
                'edit_item' => __('Edit Category'),
                'update_item' => __('Update Category'),
                'add_new_item' => __('Add New Category'),
                'new_item_name' => __('New Category Name'),
                'menu_name' => __('Categories'),
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'category', // This controls the base slug that will display before each term
                'with_front' => true, // Don't display the category base before "/locations/"
                'hierarchical' => true,
            ),
        ));
    }

    /**
     * Register Post Meta
     */
    public function pc_testimonial_client_info_box() {

        add_meta_box(
                'pc_testimonial_client_info_box', __('Client Information', PC_TESTIMONIAL_TEXT_DOMAIN), array($this, 'pc_testimonial_client_box_content'), 'pc-testimonial', 'normal', 'high'
        );
    }

    function pc_testimonial_client_box_content($post) {
        wp_nonce_field(plugin_basename(__FILE__), 'pc_testimonial_client_box_content');
        ?>
        <p><em>Fields that are left blank, will simply not display any output</em></p>
        <div class="">
            <table>
                <tr>
                    <td><label for="pc_testimonial_client_company_name">Company Name</label></td>
                    <td><input type="text" value="<?php echo get_post_meta($post->ID, 'pc_testimonial_client_company_name', true) ?>" name="pc_testimonial_client_company_name" class="pc_testimonial_client_company_name"></td>
                </tr>

                <tr>
                    <td><label for="pc_testimonial_client_company">Company Website</label></td>
                    <td><input type="text" value="<?php echo get_post_meta($post->ID, 'pc_testimonial_client_company_website', true) ?>" name="pc_testimonial_client_company_website" class="pc_testimonial_client_company_website"></td>
                </tr>

                <tr>
                    <td><label for="pc_testimonial_client_position">Location</label></td>
                    <td><input type="text" value="<?php echo get_post_meta($post->ID, 'pc_testimonial_client_location', true) ?>" name="pc_testimonial_client_location" class="pc_testimonial_client_location"></td>
                </tr>

                <tr>
                    <td><label for="pc_testimonial_client_position">Name</label></td>
                    <td><input type="text" value="<?php echo get_post_meta($post->ID, 'pc_testimonial_client_name', true) ?>" name="pc_testimonial_client_name" class="pc_testimonial_client_name"></td>
                </tr>

                <tr>
                    <td><label for="pc_testimonial_client_position">Job Title</label></td>
                    <td><input type="text" value="<?php echo get_post_meta($post->ID, 'pc_testimonial_client_position', true) ?>" name="pc_testimonial_client_position" class="pc_testimonial_client_position"></td>
                </tr>

            </table>
        </div>
        <?php
    }

    function pc_testimonial_client_save_info($post_id) {
        $slug = 'pc-testimonial';


        if (isset($_POST['post_type']) && $slug != $_POST['post_type']) :
            return;
        endif;

        if (isset($_REQUEST['pc_testimonial_client_company_name'])) :
            $pc_company = $_POST['pc_testimonial_client_company_name'];
            update_post_meta($post_id, 'pc_testimonial_client_company_name', $pc_company);
        endif;
        if (isset($_REQUEST['pc_testimonial_client_company_website'])) :
            $pc_position = $_POST['pc_testimonial_client_company_website'];
            update_post_meta($post_id, 'pc_testimonial_client_company_website', $pc_position);
        endif;
        if (isset($_REQUEST['pc_testimonial_client_location'])) :
            $pc_position = $_POST['pc_testimonial_client_location'];
            update_post_meta($post_id, 'pc_testimonial_client_location', $pc_position);
        endif;
        if (isset($_REQUEST['pc_testimonial_client_name'])) :
            $pc_position = $_POST['pc_testimonial_client_name'];
            update_post_meta($post_id, 'pc_testimonial_client_name', $pc_position);
        endif;
        if (isset($_REQUEST['pc_testimonial_client_position'])) :
            $pc_position = $_POST['pc_testimonial_client_position'];
            update_post_meta($post_id, 'pc_testimonial_client_position', $pc_position);
        endif;
    }

}
