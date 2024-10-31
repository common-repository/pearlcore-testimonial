<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * this starts the plugin.
 *
 * @link:       http://pearlcore.com/
 * @since             1.0
 * @package           Pc_Testimonial
 *
 * @wordpress-plugin
 * Plugin Name:       Pearlcore Testimonial
 * Plugin URI:        http://pearlcore.com/
 * Description:       One of Most Powerful Plugin to show Testimonial on your site with help of Shortcode and Widget
 * Version:           1.0
 * Author:            pearlcore
 * Author URI:        http://pearlcore.com/
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:            pc-testimonial
 * Domain Path:            /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//Declearation of the necessary constants for plugin
if (!defined('PC_TESTIMONIAL_VERSION')) {
    define('PC_TESTIMONIAL_VERSION', '1.0');
}

if (!defined('PC_TESTIMONIAL_FREE')) {
    define('PC_TESTIMONIAL_FREE', 'yes');
}

if (!defined('PC_TESTIMONIAL_LANG_DIR')) {
    define('PC_TESTIMONIAL_LANG_DIR', basename(dirname(__FILE__)) . '/languages/');
}

if (!defined('PC_TESTIMONIAL_TEXT_DOMAIN')) {
    define('PC_TESTIMONIAL_TEXT_DOMAIN', 'pc-testimonial');
}


if (!defined('PC_TESTIMONIAL_PLUGIN_DIR')) {
    define('PC_TESTIMONIAL_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('PC_TESTIMONIAL_PLUGIN_URL')) {
    define('PC_TESTIMONIAL_PLUGIN_URL', plugins_url('/', __FILE__));
}

if (!defined('PC_TESTIMONIAL_ASSETS_URL')) {
    define('PC_TESTIMONIAL_ASSETS_URL', PC_TESTIMONIAL_PLUGIN_URL . 'assets/');
}

if (!defined('PC_TESTIMONIAL_ASSETS_DIR')) {
    define('PC_TESTIMONIAL_ASSETS_DIR', PC_TESTIMONIAL_PLUGIN_DIR . 'assets/');
}

if (!defined('PC_TESTIMONIAL_TEMPLATE_PATH')) {
    define('PC_TESTIMONIAL_TEMPLATE_PATH', PC_TESTIMONIAL_PLUGIN_DIR . 'templates/');
}

if (!defined('PC_TESTIMONIAL_INC')) {
    define('PC_TESTIMONIAL_INC', PC_TESTIMONIAL_PLUGIN_DIR . 'includes/');
}

class Pc_Testimonial {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0
     * @access   protected
     * @var      Pc_Testimonial_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0
     * @access   protected
     * @var      string    $Pc_Testimonial    The string used to uniquely identify this plugin.
     */
    protected $Pc_Testimonial;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Dashboard and
     * the public-facing side of the site.
     *
     * @since    1.0
     */
    public function __construct() {

        $this->plugin_name = 'pc-testimonial';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_backend_hooks();
        $this->define_frontend_hooks();
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters
     * @since  1.0
     */
    private function init_hooks() {

        /** This action is documented in includes/class-pc-testimonial-activator.php */
        register_activation_hook(__FILE__, array('Pc_Testimonial_Activator', 'activate'));

        /** This action is documented in includes/class-pc-testimonial-deactivator.php */
        register_activation_hook(__FILE__, array('Pc_Testimonial_Deactivator', 'deactivate'));

        add_action('after_setup_theme', array($this, 'pc_testimonial_include_template_functions'), 11);
    }

    public function pc_testimonial_include_template_functions() {
        include_once( PC_TESTIMONIAL_INC . 'pc-testimonial-template-functions.php' );
        include_once( PC_TESTIMONIAL_INC . 'pc-testimonial-template-hooks.php' );
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for core functionality of the plugin
         */
        require_once PC_TESTIMONIAL_INC . 'class-pc-testimonial-core.php';


        // Loads the required Options Framework classes.
        require PC_TESTIMONIAL_INC . 'settings/pc-testimonial-settings.php';
        require PC_TESTIMONIAL_INC . 'setting-framework/class-pc-testimonial-framework.php';
        require PC_TESTIMONIAL_INC . 'setting-framework/class-pc-testimonial-framework-admin.php';
        require PC_TESTIMONIAL_INC . 'setting-framework/class-pc-testimonial-interface.php';
        require PC_TESTIMONIAL_INC . 'setting-framework/class-pc-testimonial-sanitization.php';

        /**
         * The code that runs during plugin activation.
         */
        require_once PC_TESTIMONIAL_INC . 'class-pc-testimonial-activator.php';

        /**
         * The code that runs during plugin deactivation.
         */
        require_once PC_TESTIMONIAL_INC . 'class-pc-testimonial-deactivator.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once PC_TESTIMONIAL_INC . 'class-pc-testimonial-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once PC_TESTIMONIAL_INC . 'class-pc-testimonial-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once PC_TESTIMONIAL_INC . 'class-pc-testimonial-frontend.php';

        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        require_once PC_TESTIMONIAL_INC . 'class-pc-testimonial-backend.php';

        /**
         * Backend Function
         */
        require_once PC_TESTIMONIAL_INC . 'pc-testimonial-backend-functions.php';

        /**
         * The class responsible for widget.
         */
        require_once PC_TESTIMONIAL_INC . 'widgets/class-pc-testimonial-widget.php';

        $this->loader = new Pc_Testimonial_Loader();
    }

    /**
     * What type of request is this?
     * string $type ajax, frontend or admin
     * @return bool
     */
    private function is_request($type) {
        switch ($type) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined('DOING_AJAX');
            case 'cron' :
                return defined('DOING_CRON');
            case 'frontend' :
                return (!is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON');
        }
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Pc_Testimonial_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Pc_Testimonial_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the dashboard functionality
     * of the plugin.
     *
     * @since    1.0
     * @access   private
     */
    private function define_backend_hooks() {

        $plugin_admin = new Pc_Testimonial_backend($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles',99);
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts',99);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0
     * @access   private
     */
    private function define_frontend_hooks() {

        $plugin_public = new Pc_Testimonial_Frontend($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles',99);
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts',99);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0
     * @return    Pc_Testimonial_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return PC_TESTIMONIAL_VERSION;
    }

}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function run_Pc_Testimonial() {

    $plugin = new Pc_Testimonial();
    $plugin->run();
}

run_Pc_Testimonial();
