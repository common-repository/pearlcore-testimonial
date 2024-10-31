<?php

/**
 * The core plugin class.
 *
 * @since      1.0
 * @package    Pc_Testimonial
 * @subpackage Pc_Testimonial/includes
 */

if (!function_exists('pc_testimonial_plugin_locate_template')) {

    /**
     * Locate the templates and return the path of the file found
     *
     * @param string $plugin_basename
     * @param string $path
     * @param array  $var
     *
     * @return string
     * @since 2.0.0
     */
    function pc_testimonial_plugin_locate_template($plugin_basename, $path, $var = NULL) {

        $template_path = '/theme/templates/' . $path;

        $located = locate_template(array(
            $template_path
        ));

        if (!$located) {
            $located = $plugin_basename . '/templates/' . $path;
        }

        return $located;
    }

}

if (!function_exists('pc_testimonial_plugin_get_template')) {

    /**
     * Retrieve a template file.
     *
     * @param string $plugin_basename
     * @param string $path
     * @param mixed  $var
     * @param bool   $return
     *
     * @return string
     * @since 2.0.0
     */
    function pc_testimonial_plugin_get_template($plugin_basename, $path, $var = null, $return = false) {

        $located = pc_testimonial_plugin_locate_template($plugin_basename, $path, $var);

        if ($var && is_array($var)) {
            extract($var);
        }

        if ($return) {
            ob_start();
        }

        // include file located
        if (file_exists($located)) {
            include( $located );
        }

        if ($return) {
            return ob_get_clean();
        }
    }

}

/**
 * Extract after some string
 * 
 * @param string $string
 * @param string $substring
 * @return String
 */
function pc_testimonial_string_after($string, $substring) {
    $pos = strpos($string, $substring);
    if ($pos === false):
        return $string;
    else:
        return(substr($string, $pos + strlen($substring)));
    endif;
}

/**
 * Extract Before some string
 * 
 * @param string $string
 * @param string $substring
 * @return String
 */
function pc_testimonial_string_before($string, $substring) {
    $pos = strpos($string, $substring);
    if ($pos === false):
        return $string;
    else:
        return(substr($string, 0, $pos));
    endif;
}



/**
 * Get Custom Categories
 * 
 * @global type $wpdb
 * @return type
 */
function pc_testimonial_get_custom_categories() {
    global $wpdb;
    $term_table = $wpdb->prefix . 'terms';
    $taxonomy_table = $wpdb->prefix . 'term_taxonomy';
    $pc_query = "SELECT * FROM $term_table WHERE term_id in(SELECT term_id FROM $taxonomy_table WHERE taxonomy = 'pc_testimonial_category')";
    $pc_categories = $wpdb->get_results($pc_query);
    $category_list = array();
    $category_list[''] = 'Select Category:';
    foreach ($pc_categories as $pc_category):
        $pc_slug = $pc_category->slug;
        $pc_name = $pc_category->name;
        $category_list[$pc_slug] = $pc_name;
    endforeach;
    return $category_list;
}

/**
 * Store Likes
 */
add_action('wp_ajax_pc_testimonial_store_likes', 'pc_testimonial_store_likes');

function pc_testimonial_store_likes() {
    global $wpdb;
    $pc_table_name = $wpdb->prefix . 'pc_like_counts';
    $pc_data = $_POST['data'];
    $pc_type = $pc_data['pc_type'];
    $pc_post_id = $pc_data['pc_post_id'];
    $pc_user_ip = get_testimonial_client_ip();
    $pc_like_meta = 'pc_testimonial_like';
    $prev_value = get_post_meta($pc_post_id, $pc_like_meta, true);
    if ($pc_type == 'pc_testimonial_dislike'):
        $pc_new_value = $prev_value - 1;
        update_post_meta($pc_post_id, $pc_like_meta, $pc_new_value, $prev_value);
        $pc_query = 'DELETE FROM ' . $pc_table_name . ' WHERE post_id = "' . $pc_post_id . '" AND user_ip = "' . $pc_user_ip . '"';
    else:
        $pc_new_value = $prev_value + 1;
        update_post_meta($pc_post_id, $pc_like_meta, $pc_new_value, $prev_value);
        $pc_query = 'INSERT INTO ' . $pc_table_name . ' (user_ip,post_id) VALUES ("' . $pc_user_ip . '","' . $pc_post_id . '") ';
    endif;
    $wpdb->query($pc_query);
    $pc_status = array();
    $pc_status['status'] = 'success';
    $pc_status['count'] = $pc_new_value;
    $pc_status['message'] = 'Setting successfully changed.';
    echo json_encode($pc_status);
    die();
}

function pc_testimonial_setting_name($call_option = NULL) {
    return 'pc_testimonial_setting';
}

/**
 * Get Client IP
 * 
 * @return string
 */
function get_testimonial_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 * Return Font Style/Weight
 * 
 * @param type $pc_style
 * @return string
 */
function pc_testimonial_font_face($pc_style) {
    if ($pc_style == 'italic'):
        $pc_font = 'font-style:italic;';
    elseif ($pc_style == 'bold_italic'):
        $pc_font = 'font-style:italic;font-weight:bold;';
    else:
        $pc_font = 'font-weight:' . $pc_style . ';';
    endif;
    return $pc_font;
}

/**
 * Convert hex Color rgb
 * 
 * @param type $colour
 * @return boolean
 */
function pc_testimonial_hex2rgb($colour) {
    if ($colour[0] == '#') {
        $colour = substr($colour, 1);
    }
    if (strlen($colour) == 6) {
        list( $r, $g, $b ) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    } elseif (strlen($colour) == 3) {
        list( $r, $g, $b ) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    } else {
        return false;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return array('red' => $r, 'green' => $g, 'blue' => $b);
}