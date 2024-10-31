<?php

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */
function pcs_testimonial_option_name() {

    // This gets the theme name from the stylesheet (lowercase and without spaces)
    $themename = get_option('stylesheet');
    $themename = preg_replace("/\W/", "_", strtolower($themename));

    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);

    // echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */
function pcs_testimonial_options() {

    $login_label_defaults = array(
        'size' => '15px',
        'face' => 'georgia',
        'style' => 'bold',
        'color' => '#bada55'
    );

    // If using image radio buttons, define a directory path
    $imagepath = PC_TESTIMONIAL_ASSETS_URL . 'images/';

    $options = array();

    $options[] = array(
        'name' => __('Settings', PC_TESTIMONIAL_TEXT_DOMAIN),
        'type' => 'heading'
    );

    $options[] = array(
        'name' => __('Testimonial Main Title Style', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_title_style",
        'std' => array(
            'size' => '24px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#000'
        ),
        'type' => 'typography',
        'pc_form_id' => 'inline_setting'
    );

    $options[] = array(
        'name' => __('Box Background Color', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_box_bg",
        'std' => '#39393c',
        'type' => 'color',
        'pc_form_id' => 'inline_setting'
    );

    $options[] = array(
        'name' => __('Testimonial Title Style', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_testimonial_title_style",
        'std' => array(
            'size' => '18px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#ccc'
        ),
        'type' => 'typography',
        'pc_form_id' => 'inline_setting'
    );


    $options[] = array(
        'name' => __('Description Style', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_short_desc_style",
        'std' => array(
            'size' => '17px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#fff'
        ),
        'type' => 'typography',
        'pc_form_id' => 'inline_setting'
    );
    
    $options[] = array(
        'name' => __('Client Name Style', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_client_name_style",
        'std' => array(
            'size' => '14px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#fff'
        ),
        'type' => 'typography',
        'pc_form_id' => 'inline_setting'
    );
    
    $options[] = array(
        'name' => __('Company Detail Style', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_company_detail_style",
        'std' => array(
            'size' => '14px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#fff'
        ),
        'type' => 'typography',
        'pc_form_id' => 'inline_setting'
    );

    $options[] = array(
        'name' => __('Next/Back Icon Color', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_next_button_color",
        'std' => '#5e5e63',
        'type' => 'color',
        'pc_form_id' => 'inline_setting'
    );

    $options[] = array(
        'name' => __('Next/Back Icon Mouse Over Color', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_next_button_mouse_over_color",
        'std' => '#fff',
        'type' => 'color',
        'pc_form_id' => 'inline_setting'
    );

    $options[] = array(
        'name' => __('All Testimonial Settings', PC_TESTIMONIAL_TEXT_DOMAIN),
        'type' => 'heading'
    );
    
    $options[] = array(
        'name' => __('Enable See All Feature', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => 'pc_enable_see_all',
        'std' => '1',
        'type' => 'checkbox'
    );
    
    $options[] = array(
        'name' => __('See All Background Color', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_see_all_bg",
        'std' => '#252527',
        'type' => 'color',
        'pc_form_id' => 'inline_setting'
    );
    
    $options[] = array(
        'name' => __('See All Text Style', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_see_all_text_style",
        'std' => array(
            'size' => '12px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#6b6b70'
        ),
        'type' => 'typography',
        'pc_form_id' => 'inline_setting'
    );
    
    $options[] = array(
        'name' => __('See All Mouse Over Color', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_see_all_mouse_over_color",
        'std' => '#fff',
        'type' => 'color',
        'pc_form_id' => 'inline_setting'
    );
    
    $options[] = array(
        'name' => __('All Testimonial Background Color', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_all_testimonial_bg_color",
        'std' => '#39393c',
        'type' => 'color',
        'pc_form_id' => 'inline_setting'
    );
    
    $options[] = array(
        'name' => __('All Testimonial Close Color', PC_TESTIMONIAL_TEXT_DOMAIN),
        'id' => "pc_all_testimonial_close_color",
        'std' => '#C2C2CE',
        'type' => 'color',
        'pc_form_id' => 'inline_setting'
    );
    
    return $options;
}
