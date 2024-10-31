<?php
$query_args = array(
    'post_type' => isset($post_type) ? $post_type : 'pc-testimonial'
);
if (isset($category_name)):
    $query_args['category_name'] = $category_name;
endif;
$query_args['post_status'] = 'publish';
$pc_testimonial_title = isset($pc_title) ? $pc_title : '';

$pc_posts = new WP_Query($query_args);
$pc_testimonial_list = '';
?>
<?php while ($pc_posts->have_posts()) : $pc_posts->the_post(); ?>
    <?php
    $pc_id = get_the_ID();
    $pc_title = get_the_title();
    $pc_content = get_the_content();
    $pc_excerpt = get_the_excerpt();
    $pc_testimonial_client_company_name = get_post_meta($pc_id, 'pc_testimonial_client_company_name', true);
    $pc_testimonial_client_company_website = get_post_meta($pc_id, 'pc_testimonial_client_company_website', true);
    $pc_testimonial_client_location = get_post_meta($pc_id, 'pc_testimonial_client_location', true);
    $pc_testimonial_client_name = get_post_meta($pc_id, 'pc_testimonial_client_name', true);
    $pc_testimonial_client_position = get_post_meta($pc_id, 'pc_testimonial_client_position', true);

    $url = wp_get_attachment_url(get_post_thumbnail_id($pc_id));
    $pc_testimonial_list .= '<li class="cd-testimonials-item">';
    $pc_testimonial_list .= '<h3 class="pc_testimonial_title">' . $pc_title . '</h3>';
    $pc_testimonial_list .= '<p>' . $pc_excerpt . '</p>';
    $pc_testimonial_list .= '<div class="cd-author">';
    if ($url):
        $pc_testimonial_list .= '<img src="' . $url . '" alt="Author image">';
    endif;
    $pc_testimonial_list .= '<ul class="cd-author-info">';
    $pc_testimonial_list .= '<li class="pc_client_name">' . $pc_testimonial_client_name . '</li>';
    $pc_testimonial_list .= '<li class="pc_company_detail">' . $pc_testimonial_client_position;
    if ($pc_testimonial_client_company_name):
        if ($pc_testimonial_client_company_website):
            $pc_testimonial_list .= ', <a href="' . $pc_testimonial_client_company_website . '">' . $pc_testimonial_client_company_name . '</a>';
        else:
            $pc_testimonial_list .= $pc_testimonial_client_company_name;
        endif;
    endif;
    if ($pc_testimonial_client_location):
        $pc_testimonial_list .= ', ' . $pc_testimonial_client_location;
    endif;
    $pc_testimonial_list .= '</li>';
    $pc_testimonial_list .= '</ul>';
    $pc_testimonial_list .= '</div>';
    $pc_testimonial_list .= '</li>';
    ?>



<?php endwhile; ?>

<div class="pc_testimonial_wrapper">
    <h2 class="pc_testimonial_main_title"><?php echo $pc_testimonial_title; ?></h2>
    <div class="cd-testimonials-wrapper cd-container">
        <ul class="cd-testimonials">
            <?php echo $pc_testimonial_list; ?>
        </ul> <!-- cd-testimonials -->
        <a href="#" class="cd-see-all">See all</a>
    </div> <!-- cd-testimonials-wrapper -->

    <div class="cd-testimonials-all">
        <div class="cd-testimonials-all-wrapper">
            <ul>

                <?php echo $pc_testimonial_list; ?>

            </ul>
        </div>	<!-- cd-testimonials-all-wrapper -->

        <a href="" class="close-btn">Close</a>
    </div> <!-- cd-testimonials-all -->
</div> 