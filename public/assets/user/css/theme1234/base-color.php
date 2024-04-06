<?php
header("Content-Type:text/css");

// get the color from query parameter
$color = $_GET['color'];

// check, whether color has '#' or not, will return 0 or 1
function checkColor($color)
{
    return preg_match('/^#[a-f0-9]{6}/i', $color);
}

// if, color value does not contain '#', then add '#' before color value
if (isset($color) && (checkColor($color) == 0)) {
    $color = '#' . $color;
}

function rgb($color = null) {
    if (!$color) {
        echo '';
    }
    $hex = htmlspecialchars($color);
    [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');
    echo "$r, $g, $b";
}

// then add color to style
?>
:root {
    --main-color: <?php echo htmlspecialchars($color); ?>;
    --color-primary: <?php echo htmlspecialchars($color); ?>;
    --color-primary-rgb: <?php rgb(htmlspecialchars($color)) ?>;
    --bg-primary-light: rgba(var(--color-primary-rgb), .05);
}


.top_header .top-right .nice-select {
border-color: <?php echo htmlspecialchars($color); ?>;
}

.top_header .top-right .info a:after {
color: <?php echo htmlspecialchars($color); ?>;
}

.hero_post_v1 .arrow:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.cat_btn {
color: <?php echo htmlspecialchars($color); ?>;
}

.post_meta span:before {
color: <?php echo htmlspecialchars($color); ?>;
}

.latest_post_v1 .latest-slider-one .slick-arrow {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box.about_box .about_img {
border: <?php echo '1px dashed ' . htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box .olima_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.vagetarian_v1 .button_box .olima_btn {
color: <?php echo htmlspecialchars($color); ?>;
border: <?php echo '1px solid ' . htmlspecialchars($color); ?>;
}

.vagetarian_v1 .grid_item .post_img .post_overlay .post_content h3:hover,
.trending_v1 .grid_item .post_img .post_overlay .post_content h3:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.footer_v1 .widget_box.useful_link_widget .widget_link li a:hover,
.footer_v1 .widget_box.useful_link_widget .widget_link li a:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.back-top .back-to-top {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_about .grid_item .post_img .post_button .play_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.testimonial-area-v1 .button_box .olima_btn {
border: <?php echo '1px solid ' . htmlspecialchars($color); ?>;
}

.testimonial-area-v1 .button_box .olima_btn:hover,
.testimonial-area-v1 .button_box .olima_btn:focus {
background: <?php echo htmlspecialchars($color); ?>;
border-color: <?php echo htmlspecialchars($color); ?>;
}

.testimonial-area-v1 .testimonial_slide_one .testimonial_box {
border: <?php echo '1px solid ' . htmlspecialchars($color); ?>;
}

.testimonial-area-v1 .testimonial_slide_one .testimonial_box .rating {
border: <?php echo '1px solid ' . htmlspecialchars($color); ?>;
}

.testimonial-area-v1 .testimonial_slide_one .slick-arrow {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.testimonial-area-v1 .testimonial_slide_one .testimonial_box:after {
color: <?php echo htmlspecialchars($color); ?>;
}

.sponsor-area-v1 .sponsor_slide_one .slick-arrow {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.gallery-area-v1 .filter-nav .filter-btn li:hover,
.gallery-area-v1 .filter-nav .filter-btn li:focus,
.gallery-area-v1 .filter-nav .filter-btn li.active {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box.categories_widget ul li a:hover,
.olima_sidebar .widget_box.categories_widget ul li a:focus {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box.categories_widget ul li.active a {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_blog_details .blog_details_wrapper .related_post_slide .slick-arrow,
.olima_blog_details .blog_details_wrapper .post-gallery-slider .slick-arrow {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.contact-form .form_button .olima_btn,
.comment_form .form_button .olima_btn,
.billing_form .form_button .olima_btn,
.review_form .form_button .olima_btn,
#reviews .shop_review_area a.olima_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box.category_widget_2 ul li a:hover,
.olima_sidebar .widget_box.category_widget_2 ul li a:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box.category_widget_2 ul li.active {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box.price_ranger_widget .ui-widget .ui-widget-header {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box.price_ranger_widget .ui-widget .ui-slider-handle {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_shop .product_box .product_img .product_overlay .product_link a {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_shop .product_box .product_info h3:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_shop .product_box .product_info span.price {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_shop .product_box .product_img .product_overlay {
background: <?php echo htmlspecialchars($color) . 'D3'; ?>;
}

.olima_shop_details .shop_big_slide .slick-arrow {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.olima_shop_details .shop_details_box .price {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_shop_details .shop_details_box .button_box .olima_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_shop_details .shop_details_box ul li a:hover,
.olima_shop_details .shop_details_box ul li a:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_shop_details .discription_area .discription_tabs .nav-tabs .nav-link.active {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_shop_details .discription_area .discription_tabs .nav-tabs .nav-link:after {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_cart .olima_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.coupon_box .form_group .olima_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.single_radio .single_input:checked+.single_input_label:before,
.single_checkbox .single_input:checked+.single_input_label:before {
border-color: <?php echo htmlspecialchars($color); ?>;
}

.sigle_input_check:after {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_checkout .order_wrap_box .place_order .olima_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.faq-area-v1 .faq-details-wrapper .card .card-header[aria-expanded=true] {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.user-dashboard .form-content .form_group .btn {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.user-dashboard .form-content .form_group .link:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.user-dashboard .user-profile-details .edit-info-area .btn {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.user-dashboard .main-table .dataTables_wrapper td a.btn {
border: <?php echo '1px solid ' . htmlspecialchars($color); ?>;
color: <?php echo htmlspecialchars($color); ?>;
}

.user-dashboard .main-table .dataTables_wrapper td a.btn:hover {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.user-dashboard .main-table .dataTables_wrapper .dataTables_paginate .paginate_button.current {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.blog_v1 .grid_item.grid_post_big .post_img .ribbon {
background: <?php echo htmlspecialchars($color); ?>;
}

.blog_v1 .latest-slider-two .slick-arrow {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.blog_v1 .grid_item .post_content h3:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.blog_v1 .grid_item .post_content .btn_link:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box.place_widget_box ul li a {
background: <?php echo htmlspecialchars($color) . '0D'; ?>;
border-left: <?php echo '5px solid ' . htmlspecialchars($color) . '4D'; ?>;
color: <?php echo htmlspecialchars($color); ?>;
}

.olima_sidebar .widget_box.place_widget_box ul li a:hover {
background: <?php echo htmlspecialchars($color); ?>;
border-color: <?php echo htmlspecialchars($color); ?>;
}

.video_v2 .video_slide_v2 .grid_item .post_img .post_overlay .post_content h3:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.blog_v2 .grid_item .post_content h3:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.blog_v2 .grid_item .post_content .btn_link:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.newsletter_v1 .newsletter_box .form_group .submit_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.footer_v2 .widget_box.about_box {
background: <?php echo htmlspecialchars($color); ?>;
}

.social_widget ul li a {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.header_v4 .top_header .top-right .nice-select,
.header_v3 .top_header .top-right .nice-select {
border-color: <?php echo htmlspecialchars($color); ?>;
}

.header_v4 .top_header .top-right .info a:after,
.header_v3 .top_header .top-right .info a:after {
color: <?php echo htmlspecialchars($color); ?>;
}

.header_v3 .header_navigation {
background: <?php echo htmlspecialchars($color); ?>;
}

.hero_post_v3 .hero_post_slide_v4 .slick-arrow {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.hero_post_v3 .grid_item .post_img .post_overlay .post_content .post_meta .tag_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.hero_post_v3 .grid_item .post_img .post_overlay .post_content h3:hover,
.hero_post_v3 .grid_item .post_img .post_overlay .post_content h3:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.section_title_2 h3:before {
background: <?php echo htmlspecialchars($color); ?>;
}

.section_title_2 h3:after {
border-top: <?php echo '7px solid ' . htmlspecialchars($color); ?>;
}

.latest_post_v2 .grid_item .post_content .post_meta .tag_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.latest_post_v2 .grid_item .post_content h3:hover,
.latest_post_v2 .grid_item .post_content h3:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.button_box .load-btn:hover,
.button_box .load-btn:focus {
background: <?php echo htmlspecialchars($color); ?>;
border-color: <?php echo htmlspecialchars($color); ?>;
}

.latest_post_v2 .sidebar_v1 .widget_box.about_box .about_content .social_link li a {
background: <?php echo htmlspecialchars($color); ?>;
}

.latest_post_v2 .sidebar_v1 .widget_box h4:before {
background: <?php echo htmlspecialchars($color); ?>;
}

.latest_post_v2 .sidebar_v1 .widget_box h4:after {
border-top: <?php echo '7px solid ' . htmlspecialchars($color); ?>;
}

.latest_post_v2 .sidebar_v1 .widget_box.featured_post .single_post .post_content .date,
.latest_post_v2 .sidebar_v1 .widget_box.featured_post .single_post .post_content .view {
background: <?php echo htmlspecialchars($color) . '1A'; ?>;
color: <?php echo htmlspecialchars($color); ?>;
}

.latest_post_v2 .sidebar_v1 .widget_box.featured_post .single_post .post_content h3:hover,
.latest_post_v2 .sidebar_v1 .widget_box.featured_post .single_post .post_content h3:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.latest_post_v2 .sidebar_v1 .widget_box.newsletter_widget .form_group .olima_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.features_post_v1 .grid_item.grid_post_big .post_overlay .post_content .post_meta .tag_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.features_post_v1 .grid_item.grid_post_big .post_overlay .post_content h3:hover,
.features_post_v1 .grid_item.grid_post_big .post_overlay .post_content h3:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.features_post_v1 .grid_item .post_content h3:hover,
.features_post_v1 .grid_item .post_content h3:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.features_post_v1 .grid_item .post_content .post_meta .date {
background: <?php echo htmlspecialchars($color); ?>;
}

.footer_v1 .widget_box.useful_link_widget .social_link li a:hover,
.footer_v1 .widget_box.useful_link_widget .social_link li a:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.hero_post_v4 .hero_post_slide_v3 .arrow:hover {
background: <?php echo htmlspecialchars($color); ?>;
}

.hero_post_v4 .hero_post_slide_v3 .grid_item .post_content {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_highlights_post .post_filter .filter_btn:after {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_highlights_post .grid_item .post_img .date:before {
background: <?php echo htmlspecialchars($color); ?>;
}

.olima_highlights_post .grid_item .post_content h3:hover,
.olima_highlights_post .grid_item .post_content h3:focus {
color: <?php echo htmlspecialchars($color); ?>;
}

.video_v3 .grid_item .post_img .post_overlay .play_button .play_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.video_v3 .grid_item .post_img .post_overlay .post_content h3:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.video_v3 .video_play_list .grid_item .post_content h3:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.blog_v4 .grid_item .post_img .date {
background: <?php echo htmlspecialchars($color); ?>;
}

.blog_v4 .grid_item .post_content h3:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.footer_v3 .widget_box.about_box p span {
color: <?php echo htmlspecialchars($color); ?>;
}

.footer_v3 .widget_box.useful_link_widget .widget_link li a:hover {
color: <?php echo htmlspecialchars($color); ?>;
}

.footer_v3 .widget_box.featured_post .single_post .post_content span.date {
color: <?php echo htmlspecialchars($color); ?>;
}

.footer_v3 .footer_bottom .newsletter_text h5 i {
color: <?php echo htmlspecialchars($color); ?>;
}

.footer_v3 .footer_bottom .newsletter_box .olima_btn {
background: <?php echo htmlspecialchars($color); ?>;
}

.page-item.active .page-link {
background-color: <?php echo htmlspecialchars($color); ?>;
border-color: <?php echo htmlspecialchars($color); ?>;
}
.page-link {
color: <?php echo htmlspecialchars($color); ?>;
}
.olima_highlights_post .post_filter.nav-tabs .nav-item a:after {
background: <?php echo htmlspecialchars($color); ?>;
}
.olima_shop .related_product_slide .slick-arrow {
background-color: <?php echo htmlspecialchars($color); ?>;
}
.gallery-area-v1 .gallery-item .gallery-img .img-popup i {
background-color: <?php echo htmlspecialchars($color); ?>;
}

.user-dashboard .user-sidebar .links li.active a {
color: <?php echo htmlspecialchars($color); ?>;
}
.vagetarian_v1 .button_box .olima_btn:hover, .vagetarian_v1 .button_box .olima_btn:focus, .olima_blog .button_box .olima_btn:hover, .olima_blog .button_box .olima_btn:focus {
background: <?php echo htmlspecialchars($color); ?>;
border-color: <?php echo htmlspecialchars($color); ?>;
}
.olima_shop_details .shop_thumb_slide .arrow {
background: <?php echo htmlspecialchars($color); ?>;
}
.olima_shop .product-search button {
background: <?php echo htmlspecialchars($color); ?>;
}

a#cartIcon .cart-length {
color: <?php echo htmlspecialchars($color); ?>;
}
.theme-color-1 {
    --color-primary: <?php echo htmlspecialchars($color); ?>;
}
.theme-color-2 {
    --color-primary: <?php echo htmlspecialchars($color); ?>;
}
.theme-color-4 {
    --color-primary: <?php echo htmlspecialchars($color); ?>;
}
.section-title_v2 .title .icons span {
background-color: <?php echo htmlspecialchars($color); ?> !important;
}
