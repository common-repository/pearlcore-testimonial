<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

add_action('wp_ajax_pc_testimonial_save_setting', 'pc_testimonial_save_setting');

function pc_testimonial_save_setting() {
    $pc_data = $_POST['data'];
    $pc_form_data = $pc_data['pc_form_data'];
    $pc_setting_name = pc_testimonial_setting_name();
    $pc_store_data = array();
    if ($pc_form_data):
        foreach ($pc_form_data as $pc_data):
            $pc_field_name = $pc_data['name'];
            $pc_field_value = str_replace('"', "'", trim($pc_data['value']));
            $pc_id = pc_testimonial_string_before(pc_testimonial_string_after($pc_field_name, '['), ']');
            $pc_store_data[$pc_id] = $pc_field_value;
        endforeach;
    endif;
    if (get_option($pc_setting_name)):
        update_option($pc_setting_name, $pc_store_data);
    else:
        add_option($pc_setting_name, $pc_store_data);
    endif;
    pc_testimonial_css();
    $pc_status = array();
    $pc_status['status'] = 'success';
    $pc_status['message'] = 'Setting successfully changed.';
    echo json_encode($pc_status);
    die();
}

/**
 * Create Plugin Styling
 */
function pc_testimonial_css() {
    $pc_user_style = '';
    $pc_setting_name = pc_testimonial_setting_name();
    $pc_all_setting = get_option($pc_setting_name);
    $pc_title_style_size = isset($pc_all_setting['pc_title_style_size']) ? $pc_all_setting['pc_title_style_size'] : '';
    $pc_title_style_color = isset($pc_all_setting['pc_title_style_color']) ? $pc_all_setting['pc_title_style_color'] : '';
    $pc_title_style_style = isset($pc_all_setting['pc_title_style_style']) ? $pc_all_setting['pc_title_style_style'] : '';
    $pc_title_style_face = isset($pc_all_setting['pc_title_style_face']) ? $pc_all_setting['pc_title_style_face'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .pc_testimonial_main_title{'
            . 'font-size:' . $pc_title_style_size . ';'
            . 'font-family:' . $pc_title_style_face . ';'
            . 'color:' . $pc_title_style_color . ';'
            . pc_testimonial_font_face($pc_title_style_style)
            . '}';

    $pc_box_bg = isset($pc_all_setting['pc_box_bg']) ? $pc_all_setting['pc_box_bg'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .cd-testimonials-wrapper{'
            . 'background-color:' . $pc_box_bg . ';'
            . '}';

    $pc_modal = pc_testimonial_hex2rgb($pc_box_bg);
    $pc_modal_bg = 'rgba(' . $pc_modal['red'] . ',' . $pc_modal['green'] . ',' . $pc_modal['blue'] . ',0.7)';
    $pc_user_style .= '.pc_testimonial_wrapper .cd-testimonials-wrapper::after{'
            . 'background-color:' . $pc_modal_bg . ';'
            . '}';

    $pc_testimonial_title_style_size = isset($pc_all_setting['pc_testimonial_title_style_size']) ? $pc_all_setting['pc_testimonial_title_style_size'] : '';
    $pc_testimonial_title_style_color = isset($pc_all_setting['pc_testimonial_title_style_color']) ? $pc_all_setting['pc_testimonial_title_style_color'] : '';
    $pc_testimonial_title_style_style = isset($pc_all_setting['pc_testimonial_title_style_style']) ? $pc_all_setting['pc_testimonial_title_style_style'] : '';
    $pc_testimonial_title_style_face = isset($pc_all_setting['pc_testimonial_title_style_face']) ? $pc_all_setting['pc_testimonial_title_style_face'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .pc_testimonial_title{'
            . 'font-size:' . $pc_testimonial_title_style_size . ';'
            . 'font-family:' . $pc_testimonial_title_style_face . ';'
            . 'color:' . $pc_testimonial_title_style_color . ';'
            . pc_testimonial_font_face($pc_testimonial_title_style_style)
            . '}';

    $pc_short_desc_style_size = isset($pc_all_setting['pc_short_desc_style_size']) ? $pc_all_setting['pc_short_desc_style_size'] : '';
    $pc_short_desc_style_color = isset($pc_all_setting['pc_short_desc_style_color']) ? $pc_all_setting['pc_short_desc_style_color'] : '';
    $pc_short_desc_style_style = isset($pc_all_setting['pc_short_desc_style_style']) ? $pc_all_setting['pc_short_desc_style_style'] : '';
    $pc_short_desc_style_face = isset($pc_all_setting['pc_short_desc_style_face']) ? $pc_all_setting['pc_short_desc_style_face'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .cd-testimonials p,.pc_testimonial_wrapper .cd-testimonials-all p{'
            . 'font-size:' . $pc_short_desc_style_size . ';'
            . 'font-family:' . $pc_short_desc_style_face . ';'
            . 'color:' . $pc_short_desc_style_color . ';'
            . pc_testimonial_font_face($pc_short_desc_style_style)
            . '}';

    $pc_client_name_style_size = isset($pc_all_setting['pc_client_name_style_size']) ? $pc_all_setting['pc_client_name_style_size'] : '';
    $pc_client_name_style_color = isset($pc_all_setting['pc_client_name_style_color']) ? $pc_all_setting['pc_client_name_style_color'] : '';
    $pc_client_name_style_style = isset($pc_all_setting['pc_client_name_style_style']) ? $pc_all_setting['pc_client_name_style_style'] : '';
    $pc_client_name_style_face = isset($pc_all_setting['pc_client_name_style_face']) ? $pc_all_setting['pc_client_name_style_face'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .cd-author .cd-author-info .pc_client_name{'
            . 'font-size:' . $pc_client_name_style_size . ';'
            . 'font-family:' . $pc_client_name_style_face . ';'
            . 'color:' . $pc_client_name_style_color . ';'
            . pc_testimonial_font_face($pc_client_name_style_style)
            . '}';

    $pc_company_detail_style_size = isset($pc_all_setting['pc_company_detail_style_size']) ? $pc_all_setting['pc_company_detail_style_size'] : '';
    $pc_company_detail_style_color = isset($pc_all_setting['pc_company_detail_style_color']) ? $pc_all_setting['pc_company_detail_style_color'] : '';
    $pc_company_detail_style_style = isset($pc_all_setting['pc_company_detail_style_style']) ? $pc_all_setting['pc_company_detail_style_style'] : '';
    $pc_company_detail_style_face = isset($pc_all_setting['pc_company_detail_style_face']) ? $pc_all_setting['pc_company_detail_style_face'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .cd-author .pc_company_detail,.pc_testimonial_wrapper .cd-author .pc_company_detail a{'
            . 'font-size:' . $pc_company_detail_style_size . ';'
            . 'font-family:' . $pc_company_detail_style_face . ';'
            . 'color:' . $pc_company_detail_style_color . ';'
            . pc_testimonial_font_face($pc_company_detail_style_style)
            . '}';

    $pc_next_button_color = isset($pc_all_setting['pc_next_button_color']) ? $pc_all_setting['pc_next_button_color'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .flex-direction-nav a:before,.pc_testimonial_wrapper .flex-direction-nav a:after{'
            . 'background-color:' . $pc_next_button_color . ';'
            . '}';

    $pc_next_button_mouse_over_color = isset($pc_all_setting['pc_next_button_mouse_over_color']) ? $pc_all_setting['pc_next_button_mouse_over_color'] : '';
    $pc_user_style .= '.flex-direction-nav li a:hover::before, .flex-direction-nav li a:hover::after{'
            . 'background-color:' . $pc_next_button_mouse_over_color . ';'
            . '}';

    $pc_enable_see_all = isset($pc_all_setting['pc_enable_see_all']) ? $pc_all_setting['pc_enable_see_all'] : '';
    if ($pc_enable_see_all == 0):
        $pc_user_style .= '.pc_testimonial_wrapper .cd-see-all{'
                . 'display:none !important;'
                . '}';
    endif;

    $pc_see_all_bg = isset($pc_all_setting['pc_see_all_bg']) ? $pc_all_setting['pc_see_all_bg'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .cd-see-all{'
            . 'background-color:' . $pc_see_all_bg . ';'
            . '}';

    $pc_see_all_text_style_size = isset($pc_all_setting['pc_see_all_text_style_size']) ? $pc_all_setting['pc_see_all_text_style_size'] : '';
    $pc_see_all_text_style_color = isset($pc_all_setting['pc_see_all_text_style_color']) ? $pc_all_setting['pc_see_all_text_style_color'] : '';
    $pc_see_all_text_style_style = isset($pc_all_setting['pc_see_all_text_style_style']) ? $pc_all_setting['pc_see_all_text_style_style'] : '';
    $pc_see_all_text_style_face = isset($pc_all_setting['pc_see_all_text_style_face']) ? $pc_all_setting['pc_see_all_text_style_face'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .cd-see-all{'
            . 'font-size:' . $pc_see_all_text_style_size . ';'
            . 'font-family:' . $pc_see_all_text_style_face . ';'
            . 'color:' . $pc_see_all_text_style_color . ';'
            . pc_testimonial_font_face($pc_see_all_text_style_style)
            . '}';
    $pc_see_all_mouse_over_color = isset($pc_all_setting['pc_see_all_mouse_over_color']) ? $pc_all_setting['pc_see_all_mouse_over_color'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .cd-see-all:hover{'
            . 'color:' . $pc_see_all_mouse_over_color . ';'
            . '}';

    $pc_all_testimonial_bg_color = isset($pc_all_setting['pc_all_testimonial_bg_color']) ? $pc_all_setting['pc_all_testimonial_bg_color'] : '';
    $pc_user_style .= '.pc_testimonial_wrapper .cd-testimonials-all{'
            . 'background-color:' . $pc_all_testimonial_bg_color . ';'
            . '}';

    $pc_all_testimonial_close_color = isset($pc_all_setting['pc_all_testimonial_close_color']) ? $pc_all_setting['pc_all_testimonial_close_color'] : '';
    $pc_user_style .= '.close-btn::before, .close-btn::after{'
            . 'background-color:' . $pc_all_testimonial_close_color . ';'
            . '}';

    $pc_file_name = 'pc-testimonial-user-style.css';
    $pc_file_path = PC_TESTIMONIAL_ASSETS_DIR . 'css/';

    pc_testimonial_write_file_content($pc_file_path, $pc_file_name, $pc_user_style);
}

/**
 * Write Content in File
 * 
 * @param type $pc_file_path
 * @param type $pc_file_name
 * @param type $file_content
 */
function pc_testimonial_write_file_content($pc_file_path, $pc_file_name, $file_content) {
    try {


        if (!file_exists($pc_file_path . $pc_file_name)) :
            $fp = fopen($pc_file_path . $pc_file_name, "w");
            if (!$fp) :
                $pc_status['status'] = 'error';
                $pc_status['message'] = 'Filed to open file. Change Permission';
            else:
                $pc_status['status'] = 'success';
                $pc_status['message'] = 'Setting Saved';
                fwrite($fp, $file_content);
                fclose($fp);
            endif;
        else:
            $fp = fopen($pc_file_path . $pc_file_name, "w");
            if (!$fp) :
                $pc_status['status'] = 'error';
                $pc_status['message'] = 'Filed to open file. Change Permission';
            else:
                $pc_status['status'] = 'success';
                $pc_status['message'] = 'Setting Saved';
                fwrite($fp, $file_content);
                fclose($fp);
            endif;
        endif;
    } catch (Exception $e) {
        $pc_status['status'] = 'error';
        $pc_status['message'] = 'Please Try Again';
    }
}
