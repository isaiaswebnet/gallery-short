<?php
/**
* Plugin Name: Gallery Short
* Plugin URI: https://github.com/isaiaswebnet/gallery-short
* Description: The Gallery Shortcode on WordPress ([gallery_short id="ID DA PÁGINA OU POST DA GALERIA DE FOTOS"]).
* Author: Isaías Oliveira
* Author URI: http://skynetsites.com.br
* Version: 1.0
* License: GPLv2 or later
* Text Domain: gallery-short
*/

if ( ! defined( 'ABSPATH' ) ) exit;

function gallery_short_scripts() {
    wp_register_script('gallery-short', 'http://malsup.github.io/jquery.cycle2.js', false, false, true);
    
    wp_enqueue_script('gallery-short');
}
function gallery_short_head_styles() {
	echo '<style>#gallery-short{width:75%;max-width:1170px;min-width:928px;margin:15px auto;}#gallery-short ul{width:27%;margin:0px;padding:0px;list-style-type:none;float:left;position:relative;}#gallery-short li{width:47%;float:left;margin:0 3% 3% 0;opacity:0.7;-moz-opacity:0.7;-webkit-opacity:0.7;cursor:pointer;}#gallery-short li:hover,#gallery-short li.cycle-pager-active{opacity:1;-moz-opacity:1;-webkit-opacity:1;}#gallery-short li img{width:100%;}#gallery-short .main-image{width:73%;float:right;}#gallery-short .main-image img{width:100%;}#gallery-short .caption{margin:20px 0 0 0;}#gallery-short .caption h3{width:26%;text-align:right;color:#2c4340;font-size:140%;display:block;float:left;}#gallery-short .caption p{width:73%;float:right;font-size:18px;color:#2c4340;margin:3px 0;}#gallery-short .clear {clear:both;display:block;}</style>';
}
function gallery_short($atts) {
    global $post;
    $atts = extract(shortcode_atts(array(
        'id' => ''
    ), $atts));
    
    $gallery = get_post_gallery($id, false);
    
    $args = array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_status' => 'any',
        'post__in' => explode(',', $gallery['ids']),
        'orderby' => 'menu_order ID',
        'order' => 'ASC'
    );
    
    $images = get_posts($args);
    
    if ($gallery) {
        $gallery_short = '<div id="gallery-short">';
        $gallery_short .= '<ul id="adv-custom-pager"></ul>';
        $gallery_short .= '<div class="cycle-slideshow main-image" data-cycle-fx=fade data-cycle-timeout=6000 data-cycle-pager="#adv-custom-pager" data-cycle-caption="#alt-caption"
data-cycle-caption-template="{{title}}" data-cycle-pager-template="<li><img src=\'{{src}}\'></li>">';
        
        foreach ($images as $image) {
            $image_url     = wp_get_attachment_image_src($image->ID, 'full');
            $image_alt     = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
            $image_title   = $image->post_title;
            $image_content = $image->post_content ? (' - ' . $image->post_content) : '';
            $gallery_short .= '<img src="' . $image_url[0] . '" alt="' . $image_alt . '" title="' . $image_title . $image_content . '" />';
        }
        
        $gallery_short .= '</div>';
        $gallery_short .= '<div class="clear"></div>';
        $gallery_short .= '<div class="caption">';
        $gallery_short .= '<h3>Tipo de Acomodação</h3>';
        $gallery_short .= '<p id="alt-caption" class="subtitle"></p>';
        $gallery_short .= '<div class="clear"></div>';
        $gallery_short .= '</div> ';
        $gallery_short .= '</div>';
        
        return $gallery_short;
    }
}

add_action('wp_enqueue_scripts', 'gallery_short_scripts');
add_action('wp_head', 'gallery_short_head_styles', 999);
add_shortcode('gallery_short', 'gallery_short');
