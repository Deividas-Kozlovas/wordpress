<?php

/**
 * Add inline css 
 *
 * 
 */
if (!function_exists('shop_kit_inline_css')) :
  function shop_kit_inline_css()
  {

    $style = '';

    // font settings
    $shop_kit_theme_fonts = get_theme_mod('shop_kit_theme_fonts', 'Poppins');
    $shop_kit_font_size = get_theme_mod('shop_kit_font_size', '14');
    $shop_kit_font_line_height = get_theme_mod('shop_kit_font_line_height', '24');
    $shop_kit_theme_font_head = get_theme_mod('shop_kit_theme_font_head', 'Noto Serif');
    $shop_kit_font_weight_head = get_theme_mod('shop_kit_font_weight_head', '700');

    if ($shop_kit_theme_fonts != 'Poppins') {
      $style .= 'body, p{font-family:\'' . esc_attr($shop_kit_theme_fonts) . '\', sans-serif;}';
    }
    if ($shop_kit_font_size != '14') {
      $style .= 'body, p{font-size:' . esc_attr($shop_kit_font_size) . 'px;}';
    }
    if ($shop_kit_font_line_height != '24') {
      $style .= 'body, p{line-height:' . esc_attr($shop_kit_font_line_height) . 'px;}';
    }

    if ($shop_kit_theme_font_head != 'Noto Serif') {
      $style .= 'h1,h1 a, h2,h2 a, h3,h3 a, h4,h4 a, h5, h6{font-family:\'' . esc_attr($shop_kit_theme_font_head) . '\', sans-serif;}';
    }
    if ($shop_kit_font_weight_head != '700') {
      $style .= 'h1, h2, h3, h4, h5, h6{font-weight:' . esc_attr($shop_kit_font_weight_head) . ';}';
    }



    if (get_header_textcolor() != '#000000') {
      $style .= 'h1.site-title a,p.site-description{color:#' . get_header_textcolor() . ';}';
    }

    $shop_kit_titletag_bgcolor = get_theme_mod('shop_kit_titletag_bgcolor');
    if ($shop_kit_titletag_bgcolor) {
      $style .= 'p.site-description:before{background:' . esc_attr($shop_kit_titletag_bgcolor) . ' !important;}';
    }

    $shop_kit_tagline_bgshow = get_theme_mod('shop_kit_tagline_bgshow');
    if (empty($shop_kit_tagline_bgshow)) {
      $style .= 'p.site-description:before{display:none !important;}';
      $style .= '.headerlogo-text.text-left .site-description{padding:0;}';
    }

    //top bar settings  
    $shop_kit_topbar_bg = get_theme_mod('shop_kit_topbar_bg', '#ededed');
    $shop_kit_topbar_color = get_theme_mod('shop_kit_topbar_color', '#000');
    $shop_kit_topbar_hcolor = get_theme_mod('shop_kit_topbar_hcolor', '#6b14fa');

    if ($shop_kit_topbar_bg != '#ededed') {
      $style .= '.shop-kit-tophead.bg-light,#bessearch .search-submit{background-color:' . $shop_kit_topbar_bg . '!important;}';
    }
    if ($shop_kit_topbar_color != '#000') {
      $style .= '.shop-kit-tophead, .shop-kit-tophead a, .shop-kit-tophead span, .shop-kit-tophead input,.besearch-icon i, .besearch-icon a,#bessearch .search-submit{color:' . $shop_kit_topbar_color . ';}';
    }
    if ($shop_kit_topbar_hcolor != '#6b14fa') {
      $style .= '.shop-kit-tophead a:hover,.besearch-icon a:hover i{color:' . $shop_kit_topbar_hcolor . ';}';
    }

    // image logo width height
    $shop_kit_logo_width = get_theme_mod('shop_kit_logo_width');
    $shop_kit_logo_height = get_theme_mod('shop_kit_logo_height');
    if ($shop_kit_logo_width) {
      $style .= '.site-branding.has-himg a img{width:' . $shop_kit_logo_width . 'px;}';
    }
    if ($shop_kit_logo_height) {
      $style .= '.site-branding.has-himg a img{height:' . $shop_kit_logo_height . 'px;}';
    }

    // title and tagline font size
    $shop_kit_hmiddle_height = get_theme_mod('shop_kit_hmiddle_height');
    $shop_kit_logo_fontsize = get_theme_mod('shop_kit_logo_fontsize');
    $shop_kit_desc_fontsize = get_theme_mod('shop_kit_desc_fontsize');
    if ($shop_kit_hmiddle_height) {
      $style .= '.site-branding, .shop-kit-header-img img{height:' . $shop_kit_hmiddle_height . 'px !important;}';
    }
    if ($shop_kit_logo_fontsize) {
      $style .= 'h1.site-title a{font-size:' . $shop_kit_logo_fontsize . 'px;}';
    }
    if ($shop_kit_desc_fontsize) {
      $style .= 'p.site-description{font-size:' . $shop_kit_desc_fontsize . 'px;}';
    }
    $shop_kit_menu_position = get_theme_mod('shop_kit_menu_position', 'center');
    if ($shop_kit_menu_position) {
      $style .= '.main-navigation ul{justify-content:' . $shop_kit_menu_position . ';}';
    }
    $shop_kit_menu_tbpadding = get_theme_mod('shop_kit_menu_tbpadding');
    if ($shop_kit_menu_tbpadding) {
      $style .= '.shop-kit-main-nav{padding:' . $shop_kit_menu_tbpadding . 'px 0;}';
    }
    $shop_kit_menu_fontsize = get_theme_mod('shop_kit_menu_fontsize');
    if ($shop_kit_menu_fontsize) {
      $style .= '.shop-kit-main-nav ul li a{font-size:' . $shop_kit_menu_fontsize . 'px;}';
    }
    $shop_kit_menubg_color = get_theme_mod('shop_kit_menubg_color');
    if ($shop_kit_menubg_color) {
      $style .= '.shop-kit-main-nav.bg-light,.menu-main-menu-container{background:' . $shop_kit_menubg_color . '  !important;}';
      $style .= '.shop-kit-main-nav ul li a{border-color:' . $shop_kit_menubg_color . '  !important;}';
    }
    $shop_kit_menudropbg_color = get_theme_mod('shop_kit_menudropbg_color');
    if ($shop_kit_menudropbg_color) {
      $style .= '.main-navigation ul ul{background:' . $shop_kit_menudropbg_color . '  !important;}';
    }
    $shop_kit_menu_color = get_theme_mod('shop_kit_menu_color');
    if ($shop_kit_menu_color) {
      $style .= '.shop-kit-main-nav ul li a,.mini-toggle, .main-navigation .page_item_has_children:before, .main-navigation .menu-item-has-children:before{color:' . $shop_kit_menu_color . '  !important;}';
    }

    //color section style
    $shop_kit_header_color = get_theme_mod('shop_kit_header_color');
    if ($shop_kit_header_color) {
      $style .= 'h1,h2,h3,h4,h5,h6, h2.entry-title a, h2.entry-title{color:' . $shop_kit_header_color . ';}';
    }
    $shop_kit_bodyfont_color = get_theme_mod('shop_kit_bodyfont_color');
    if ($shop_kit_bodyfont_color) {
      $style .= 'body,body p{color:' . $shop_kit_bodyfont_color . ';}';
    }
    $shop_kit_contentbg_color = get_theme_mod('shop_kit_contentbg_color');
    if ($shop_kit_contentbg_color) {
      $style .= '.shop-kit-btext, .widget-area .widget, .xskit-blog-grid, .site-footer, .archive-header, .search-header, .shop-kit-page, .site-main .comment-navigation, .site-main .posts-navigation, .site-main .post-navigation, .site-footer, .xskit-blog-list, .xskit-single-list, .comments-area,.xskit-simple-list.hasimg{background-color:' . $shop_kit_contentbg_color . ';}';
    }
    $shop_kit_basic_color = get_theme_mod('shop_kit_basic_color');
    if ($shop_kit_basic_color) {
      $style .= '.entry-footer,h3.widget-title, h2.widget-title,.site-footer, .site-main .comment-navigation, .site-main .posts-navigation, .site-main .post-navigation,span.count.cart-contents,.widget .search-form input.search-submit:hover{background-color:' . $shop_kit_basic_color . ';}';
      $style .= '.entry-meta, .entry-meta a,widget .search-form input.search-submit{color:' . $shop_kit_basic_color . ';}';
      $style .= '.widget .search-form input.search-submit{border-color:' . $shop_kit_basic_color . ';}';
    }
    $shop_kit_link_color = get_theme_mod('shop_kit_link_color');
    if ($shop_kit_link_color) {
      $style .= 'a{color:' . $shop_kit_link_color . ';}';
    }
    $shop_kit_linkhvr_color = get_theme_mod('shop_kit_linkhvr_color');
    if ($shop_kit_linkhvr_color) {
      $style .= 'a:hover,a:visited{color:' . $shop_kit_linkhvr_color . ';}';
    }
    $shop_kit_topfooter_bgcolor = get_theme_mod('shop_kit_topfooter_bgcolor');
    if ($shop_kit_topfooter_bgcolor) {
      $style .= '.footer-top.bg-light{background:' . $shop_kit_topfooter_bgcolor . '  !important;}';
    }
    $shop_kit_tftitle_color = get_theme_mod('shop_kit_tftitle_color');
    if ($shop_kit_tftitle_color) {
      $style .= '.footer-widget .widget-title{color:' . $shop_kit_tftitle_color . '  !important;}';
    }
    $shop_kit_tftext_color = get_theme_mod('shop_kit_tftext_color');
    if ($shop_kit_tftext_color) {
      $style .= '.footer-widget, .footer-widget p, .footer-widget a, .footer-widget #wp-calendar caption, .footer-widget .search-form input.search-submit{color:' . $shop_kit_tftext_color . '  !important;}';
    }
    $shop_kit_tflink_hovercolor = get_theme_mod('shop_kit_tflink_hovercolor');
    if ($shop_kit_tflink_hovercolor) {
      $style .= '.footer-widget a:hover{color:' . $shop_kit_tflink_hovercolor . '  !important;}';
    }
    $shop_kit_footer_bgcolor = get_theme_mod('shop_kit_footer_bgcolor');
    if ($shop_kit_footer_bgcolor) {
      $style .= 'footer.site-footer{background:' . $shop_kit_footer_bgcolor . '  !important;}';
    }
    $shop_kit_footer_color = get_theme_mod('shop_kit_footer_color');
    if ($shop_kit_footer_color) {
      $style .= 'footer.site-footer,footer.site-footer a,footer.site-footer p{color:' . $shop_kit_footer_color . '  !important;}';
    }
    $shop_kit_footerlink_hcolor = get_theme_mod('shop_kit_footerlink_hcolor');
    if ($shop_kit_footerlink_hcolor) {
      $style .= 'footer.site-footer a:hover,footer.site-footer a:focus{color:' . $shop_kit_footerlink_hcolor . '  !important;}';
    }



    wp_add_inline_style('shop-kit-main', $style);
  }
  add_action('wp_enqueue_scripts', 'shop_kit_inline_css');
endif;
