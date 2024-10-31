<?php

/**
 * WP Social Login Template Hooks
 *
 * Action/filter hooks used for Social Login functions/templates
 *
 * @category 	Core
 * @package 	Social Login/Templates
 * @version     1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * Get Testimonial Template
 *
 * @see pc_testimonial_template()
 */
add_action('pc_testimonial_template', 'pc_testimonial_template', 10);
