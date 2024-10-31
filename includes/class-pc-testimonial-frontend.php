<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @since      1.0
 * @package    Pc_Testimonial
 * @subpackage Pc_Testimonial/admin
 */
class Pc_Testimonial_Frontend {

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
     * @var      string    $name       The name of the plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($name, $version) {

        $this->name = $name;
        $this->version = $version;

        add_shortcode('Pc_Testimonial', array($this, 'pc_testimonial_shortcode'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0
     */
    public function enqueue_styles() {



        wp_enqueue_style($this->name . '-pc-testimonial-frontend', PC_TESTIMONIAL_ASSETS_URL . 'css/pc-testimonial-frontend.css', array(), $this->version, 'all');

        wp_enqueue_style($this->name . '-pc-testimonial-user-style', PC_TESTIMONIAL_ASSETS_URL . 'css/pc-testimonial-user-style.css', array(), $this->version, 'all');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0
     */
    public function enqueue_scripts() {


        wp_enqueue_script($this->name . '-masonry.pkgd.min', PC_TESTIMONIAL_ASSETS_URL . 'js/frontend/masonry.pkgd.min.js', array('jquery'), $this->version, true);

        wp_enqueue_script($this->name . '-jquery.flexslider-min', PC_TESTIMONIAL_ASSETS_URL . 'js/frontend/jquery.flexslider-min.js', array('jquery'), $this->version, true);

        wp_enqueue_script($this->name . '-pc-testimonial-frontend', PC_TESTIMONIAL_ASSETS_URL . 'js/frontend/pc-testimonial-frontend.js', array('jquery'), $this->version, true);

        wp_localize_script($this->name . '-pc-testimonial-frontend', 'pc_frontend', array('pc_ajax' => admin_url('admin-ajax.php')), PC_TESTIMONIAL_VERSION);
    }

    function pc_testimonial_shortcode($atts) {
        $pc_user_attr = shortcode_atts(array(
            'title' => 'What Client Say',
            'category' => ''
                ), $atts);
        $type = 'pc-testimonial';
        $pc_title = (isset($pc_user_attr['title'])) ? $pc_user_attr['title'] : '';
        $pc_category = (isset($pc_user_attr['category'])) ? $pc_user_attr['category'] : '';
        if ($pc_category):
            $args = array(
                'post_type' => $type,
                'post_status' => 'publish',
                'category_name' => $pc_category,
                'numberposts' => 10,
                'pc_title' => $pc_title,
            );
        else:
            $args = array(
                'post_type' => $type,
                'post_status' => 'publish',
                'numberposts' => 10,
                'pc_title' => $pc_title,
            );
        endif;
        return pc_testimonial_plugin_get_template(PC_TESTIMONIAL_PLUGIN_DIR, 'testimonial-a.php', $args, true);
    }

}
