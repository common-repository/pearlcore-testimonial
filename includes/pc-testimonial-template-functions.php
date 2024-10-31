<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!function_exists('pc_testimonial_template')) {

    /**
     * Output Before Login Form
     */
    function pc_testimonial_template() {
        wpslw_plugin_get_template(PC_TESTIMONIAL_TEMPLATE_PATH, 'testimonial-a.php');
    }

}