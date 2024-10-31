<?php
if (!defined('ABSPATH')) :
    exit; // Exit if accessed directly
endif;

/**
 * Handle Widget Functionality
 *
 * @class 		Woo_Bag_Widget
 * @version		1.0.0
 * @package		Woo_Bag/Classes/
 * @subpackage          Woo_Bag/includes
 * @category            Class
 * @author 		Creative Gaters
 */
class Pc_Testimonial_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {

        $this->widget_cssclass = 'pc_testimonial_widget';
        $this->widget_description = __("Display the Testimonial in the sidebar with category.", PC_TESTIMONIAL_TEXT_DOMAIN);
        $this->widget_id = 'pc_testimonial_widget';
        $this->widget_name = __('Pearlcore Testimonial', PC_TESTIMONIAL_TEXT_DOMAIN);


        $this->settings = array(
            'pc_title' => array(
                'std' => 'What Client Say ',
                'type' => 'text',
                'label' => __('Testimonial Title', PC_TESTIMONIAL_TEXT_DOMAIN),
            ),
            'pc_category' => array(
                'std' => '',
                'type' => 'select',
                'label' => __('Testimonial Category', PC_TESTIMONIAL_TEXT_DOMAIN),
                'options' => pc_testimonial_get_custom_categories(),
            ),
        );
        $widget_ops = array(
            'classname' => $this->widget_cssclass,
            'description' => $this->widget_description
        );

        parent::__construct($this->widget_id, $this->widget_name, $widget_ops);
    }

    public function widget($args, $instance) {

        $pc_title = (isset($instance['pc_title'])) ? $instance['pc_title'] : '';
        $pc_category = (isset($instance['pc_category'])) ? $instance['pc_category'] : '';
        $type = 'pc-testimonial';
        $query_args = array(
            'post_type' => $type,
            'post_status' => 'publish',
            'category_name' => $pc_category,
            'numberposts' => 10,
            'pc_title' => $pc_title,
        );

        $this->widget_start($args, $instance);
        echo pc_testimonial_plugin_get_template(PC_TESTIMONIAL_PLUGIN_DIR, 'testimonial-a.php', $query_args, true);
        $this->widget_end($args);
    }

    /**
     * Output the html at the start of a widget
     *
     * @param  array $args
     * @return string
     */
    public function widget_start($args, $instance) {

        echo $args['before_widget'];

        if ($title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
    }

    /**
     * Output the html at the end of a widget
     *
     * @param  array $args
     * @return string
     */
    public function widget_end($args) {
        echo $args['after_widget'];
    }

    /**
     * form function.
     *
     * @see WP_Widget->form
     * @param array $instance
     */
    public function form($instance) {

        if (!$this->settings) {
            return;
        }
        foreach ($this->settings as $key => $setting) {
            $std = (isset($setting['std']) ? $setting['std'] : '');
            $value = isset($instance[$key]) ? $instance[$key] : $std;
            ?>
            <div class="<?php echo $key; ?>">
                <?php
                switch ($setting['type']) {

                    case 'text' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_TESTIMONIAL_TEXT_DOMAIN); ?></label>
                            <input class="widefat" id="<?php echo $key; ?>" name="<?php echo $this->get_field_name($key); ?>" type="text" value="<?php echo esc_attr($value); ?>" />
                        </p>
                        <?php
                        break;

                    case 'number' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_TESTIMONIAL_TEXT_DOMAIN); ?></label>
                            <input class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo $this->get_field_name($key); ?>" type="number" step="<?php echo esc_attr($setting['step']); ?>" min="<?php echo esc_attr($setting['min']); ?>" max="<?php echo esc_attr($setting['max']); ?>" value="<?php echo esc_attr($value); ?>" />
                        </p>
                        <?php
                        break;

                    case 'select' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_TESTIMONIAL_TEXT_DOMAIN); ?></label>
                            <select class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo $this->get_field_name($key); ?>">
                                <?php foreach ($setting['options'] as $option_key => $option_value) : ?>
                                    <option value="<?php echo esc_attr($option_key); ?>" <?php selected($option_key, $value); ?>><?php echo esc_html($option_value); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <?php
                        break;
                    case 'radio' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_TESTIMONIAL_TEXT_DOMAIN); ?></label>
                            <br/>
                            <?php foreach ($setting['options'] as $option_key => $option_value) : ?>
                                <input type="radio" value="<?php echo esc_attr($option_key); ?>" <?php checked($option_key, $value); ?> name="<?php echo $this->get_field_name($key); ?>"><?php echo esc_html($option_value); ?><br/>
                            <?php endforeach; ?>
                        </p>
                        <?php
                        break;

                    case 'checkbox' :
                        ?>
                        <p>
                            <input id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>" type="checkbox" value="1" <?php checked($value, 1); ?> />
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_TESTIMONIAL_TEXT_DOMAIN); ?></label>
                        </p>
                        <?php
                        break;
                }
                ?>
            </div>
            <?php
        }
    }

}

/**
 * Register Widgets
 *
 * @since 1.0.0
 */
function pc_testimonial_register_widgets() {
    register_widget('Pc_Testimonial_Widget');
}

add_action('widgets_init', 'pc_testimonial_register_widgets');
